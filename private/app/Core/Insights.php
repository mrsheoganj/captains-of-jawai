<?php
declare(strict_types=1);

namespace App\Core;

/**
 * First-party visit log for the private /pk-insights page: one row per public page view
 * (bots, admins and failed pages are skipped). No cookies: a visitor is a hash of IP + browser per day.
 * Rows older than RETAIN_DAYS are removed automatically.
 */
final class Insights
{
    public const RETAIN_DAYS = 90;
    private const GEO_URL = 'https://ipapi.co/%s/json/';

    /** Call before the page is rendered; the row is written after the response if the page loaded (HTTP 200). */
    public static function track(string $path): void
    {
        $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET' || self::isBot($ua) || str_starts_with($path, '/api/')
            || preg_match('#\.(xml|txt)$#', $path) || isset($_SERVER['HTTP_PURPOSE']) || Auth::check() || Session::get('insights_ok')) {
            return;
        }
        register_shutdown_function(static function () use ($path, $ua): void {
            if (http_response_code() !== 200) {
                return;
            }
            try {
                $ip = client_ip();
                [$device, $browser, $os] = self::parseUa($ua);
                DB::insert('visits', [
                    'created_at' => now(),
                    'ip' => $ip,
                    'visitor' => substr(hash('sha256', $ip . '|' . $ua . '|' . date('Y-m-d') . '|' . (string) Config::get('app_key', '')), 0, 16),
                    'path' => mb_substr($path === '/' ? '/' : rtrim($path, '/') . '/', 0, 250),
                    'referrer' => mb_substr(self::referrer(), 0, 250),
                    'device' => $device,
                    'browser' => $browser,
                    'os' => $os,
                    'user_agent' => mb_substr($ua, 0, 400),
                ]);
                if (random_int(1, 200) === 1) {
                    DB::delete('visits', 'created_at < ?', [date('Y-m-d H:i:s', time() - self::RETAIN_DAYS * 86400)]);
                }
            } catch (\Throwable $e) {
                error_log('Visit log: ' . $e->getMessage());
            }
        });
    }

    public static function isBot(string $ua): bool
    {
        return $ua === '' || (bool) preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview|headless|lighthouse|pingdom|uptime|monitor|curl|wget|python|java\/|go-http|okhttp|axios|scrapy|semrush|ahrefs|mj12|bytespider|petal/i', $ua);
    }

    /** The referring site, or '' for direct visits and clicks within this website. */
    private static function referrer(): string
    {
        $ref = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        $host = strtolower((string) parse_url($ref, PHP_URL_HOST));
        $own = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        if ($host === '' || $host === $own || $host === 'www.' . $own || 'www.' . $host === $own) {
            return '';
        }
        return $ref;
    }

    /** [device, browser, os] from a user-agent string. */
    public static function parseUa(string $ua): array
    {
        $device = preg_match('/iPad|Tablet|(Android(?!.*Mobile))/i', $ua) ? 'Tablet' : (preg_match('/Mobi|iPhone|Android/i', $ua) ? 'Phone' : 'Computer');
        $browser = match (true) {
            (bool) preg_match('/Instagram/i', $ua) => 'Instagram app',
            (bool) preg_match('/FBAN|FBAV/i', $ua) => 'Facebook app',
            (bool) preg_match('/WhatsApp/i', $ua) => 'WhatsApp',
            (bool) preg_match('/Edg\//', $ua) => 'Edge',
            (bool) preg_match('/OPR\/|Opera/', $ua) => 'Opera',
            (bool) preg_match('/SamsungBrowser/', $ua) => 'Samsung Internet',
            (bool) preg_match('/Firefox|FxiOS/', $ua) => 'Firefox',
            (bool) preg_match('/Chrome|CriOS/', $ua) => 'Chrome',
            (bool) preg_match('/Safari/', $ua) => 'Safari',
            default => 'Other',
        };
        $os = match (true) {
            (bool) preg_match('/iPhone|iPad|iPod/', $ua) => 'iOS',
            (bool) preg_match('/Android/', $ua) => 'Android',
            (bool) preg_match('/Windows/', $ua) => 'Windows',
            (bool) preg_match('/Mac OS X|Macintosh/', $ua) => 'macOS',
            (bool) preg_match('/CrOS/', $ua) => 'ChromeOS',
            (bool) preg_match('/Linux/', $ua) => 'Linux',
            default => 'Other',
        };
        return [$device, $browser, $os];
    }

    /** Human label for a referrer URL. */
    public static function source(?string $ref): string
    {
        $host = strtolower(preg_replace('/^www\./', '', (string) parse_url((string) $ref, PHP_URL_HOST)));
        if ($host === '') {
            return 'Direct / typed';
        }
        foreach (['google' => 'Google', 'bing' => 'Bing', 'yahoo' => 'Yahoo', 'duckduckgo' => 'DuckDuckGo', 'instagram' => 'Instagram',
            'facebook' => 'Facebook', 'fb.' => 'Facebook', 'whatsapp' => 'WhatsApp', 'wa.me' => 'WhatsApp', 'youtube' => 'YouTube',
            't.co' => 'X (Twitter)', 'twitter' => 'X (Twitter)', 'linkedin' => 'LinkedIn', 'tripadvisor' => 'TripAdvisor', 'chatgpt' => 'ChatGPT',
            'pinterest' => 'Pinterest'] as $needle => $label) {
            if (str_contains($host, $needle)) {
                return $label;
            }
        }
        return $host;
    }

    /**
     * Look up the location of IPs not seen before (a few per page load, in parallel), cached in ip_geo.
     * Failed lookups are retried after a day.
     */
    public static function locate(array $ips, int $max = 30): void
    {
        if (!function_exists('curl_multi_init') || !$ips) {
            return;
        }
        $known = [];
        $place = implode(',', array_fill(0, count($ips), '?'));
        foreach (DB::all("SELECT ip, country, looked_up_at FROM ip_geo WHERE ip IN ($place)", array_values($ips)) as $r) {
            if ($r['country'] !== null && $r['country'] !== '' || (string) $r['looked_up_at'] > date('Y-m-d H:i:s', time() - 86400)) {
                $known[$r['ip']] = true;
            }
        }
        $todo = array_slice(array_values(array_filter($ips, fn ($ip) => !isset($known[$ip])
            && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))), 0, $max);
        if (!$todo) {
            return;
        }
        $mh = curl_multi_init();
        $handles = [];
        foreach ($todo as $ip) {
            $ch = curl_init(sprintf(self::GEO_URL, rawurlencode($ip)));
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_TIMEOUT => 5,
                CURLOPT_USERAGENT => 'CaptainsOfJawai-Insights/1.0']);
            curl_multi_add_handle($mh, $ch);
            $handles[$ip] = $ch;
        }
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) {
                curl_multi_select($mh, 1.0);
            }
        } while ($running && $status === CURLM_OK);
        foreach ($handles as $ip => $ch) {
            $j = json_decode((string) curl_multi_getcontent($ch), true);
            $ok = is_array($j) && empty($j['error']) && !empty($j['country_name']);
            $row = [
                'country' => $ok ? mb_substr((string) $j['country_name'], 0, 90) : null,
                'region' => $ok ? mb_substr((string) ($j['region'] ?? ''), 0, 90) : null,
                'city' => $ok ? mb_substr((string) ($j['city'] ?? ''), 0, 90) : null,
                'org' => $ok ? mb_substr((string) ($j['org'] ?? ''), 0, 90) : null,
                'looked_up_at' => now(),
            ];
            if (DB::one('SELECT ip FROM ip_geo WHERE ip = ?', [$ip])) {
                DB::update('ip_geo', $row, 'ip = ?', [$ip]);
            } else {
                DB::insert('ip_geo', ['ip' => $ip] + $row);
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        curl_multi_close($mh);
    }
}
