<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\DB;
use App\Core\Insights;
use App\Core\Session;
use App\Core\View;

/** /pk-insights: private visit statistics behind their own password (not the admin login). */
final class InsightsController
{
    /** bcrypt hash of the insights password (hint: the developer's mobile number). */
    private const PASSWORD_HASH = '$2y$11$50cfr5jVAZO3m5srpobqWusrmtQChc0.0hsi9ogMmc6m4ZsdrgYSC';
    private const IDLE_SECONDS = 7200;
    private const RANGES = ['24h' => ['Last 24 hours', 86400], '7d' => ['Last 7 days', 7 * 86400], '30d' => ['Last 30 days', 30 * 86400]];

    private function signedIn(): bool
    {
        $at = (int) Session::get('insights_ok', 0);
        if ($at && time() - $at < self::IDLE_SECONDS) {
            Session::put('insights_ok', time());
            return true;
        }
        Session::forget('insights_ok');
        return false;
    }

    public function index(): string
    {
        if (!$this->signedIn()) {
            $error = Session::get('insights_error');
            Session::forget('insights_error');
            return View::render('site/insights-login', ['error' => $error]);
        }
        $range = isset(self::RANGES[$_GET['range'] ?? '']) ? $_GET['range'] : '7d';
        [$label, $secs] = self::RANGES[$range];
        $since = date('Y-m-d H:i:s', time() - $secs);
        $prev = date('Y-m-d H:i:s', time() - 2 * $secs);

        $totals = [];
        foreach (self::RANGES as $key => [$name, $s]) {
            $from = date('Y-m-d H:i:s', time() - $s);
            $r = DB::one('SELECT COUNT(*) AS views, COUNT(DISTINCT visitor) AS people FROM visits WHERE created_at >= ?', [$from]);
            $totals[$key] = ['label' => $name, 'views' => (int) $r['views'], 'people' => (int) $r['people']];
        }
        $before = DB::one('SELECT COUNT(*) AS views, COUNT(DISTINCT visitor) AS people FROM visits WHERE created_at >= ? AND created_at < ?', [$prev, $since]);

        // Visits over time: by hour for 24 hours, by day otherwise.
        $hourly = $range === '24h';
        $len = $hourly ? 13 : 10;
        $counts = [];
        foreach (DB::all("SELECT SUBSTR(created_at, 1, $len) AS b, COUNT(*) AS n, COUNT(DISTINCT visitor) AS p FROM visits WHERE created_at >= ? GROUP BY b", [$since]) as $r) {
            $counts[$r['b']] = [(int) $r['n'], (int) $r['p']];
        }
        $series = [];
        $steps = $hourly ? 24 : (int) ($secs / 86400);
        for ($i = $steps - 1; $i >= 0; $i--) {
            $t = $hourly ? time() - $i * 3600 : strtotime("-$i days");
            $key = $hourly ? date('Y-m-d H', $t) : date('Y-m-d', $t);
            $series[] = ['label' => $hourly ? date('g a', $t) : date('j M', $t), 'full' => $hourly ? date('D j M, g a', $t) : date('D j M Y', $t),
                'views' => $counts[$key][0] ?? 0, 'people' => $counts[$key][1] ?? 0];
        }

        // Fill in locations for recent visitors (a few lookups per load), then build the lists.
        $ips = array_column(DB::all('SELECT ip, MAX(created_at) AS last FROM visits WHERE created_at >= ? GROUP BY ip ORDER BY last DESC LIMIT 200', [$since]), 'ip');
        try {
            Insights::locate($ips);
        } catch (\Throwable $e) {
            error_log('Insights geo: ' . $e->getMessage());
        }

        $rows = DB::all('SELECT v.*, g.city, g.region, g.country, g.org FROM visits v LEFT JOIN ip_geo g ON g.ip = v.ip WHERE v.created_at >= ? ORDER BY v.created_at DESC', [$since]);
        $tally = static function (array $rows, callable $key): array {
            $out = [];
            foreach ($rows as $r) {
                $k = $key($r);
                $out[$k] ??= ['views' => 0, 'people' => []];
                $out[$k]['views']++;
                $out[$k]['people'][$r['visitor']] = true;
            }
            $out = array_map(fn ($v) => ['views' => $v['views'], 'people' => count($v['people'])], $out);
            uasort($out, fn ($a, $b) => $b['views'] <=> $a['views']);
            return $out;
        };

        return View::render('site/insights', [
            'range' => $range,
            'rangeLabel' => $label,
            'ranges' => array_map(fn ($r) => $r[0], self::RANGES),
            'totals' => $totals,
            'before' => ['views' => (int) $before['views'], 'people' => (int) $before['people']],
            'series' => $series,
            'hourly' => $hourly,
            'pages' => array_slice($tally($rows, fn ($r) => (string) $r['path']), 0, 12, true),
            'sources' => array_slice($tally($rows, fn ($r) => Insights::source($r['referrer'])), 0, 10, true),
            'places' => array_slice($tally($rows, fn ($r) => self::place($r, false)), 0, 12, true),
            'devices' => $tally($rows, fn ($r) => (string) $r['device']),
            'browsers' => array_slice($tally($rows, fn ($r) => $r['browser'] . ' · ' . $r['os']), 0, 8, true),
            'recent' => array_slice($rows, 0, 300),
            'total' => count($rows),
        ]);
    }

    /** "City, Region, Country" (or the country only), or a placeholder while the lookup is pending. */
    public static function place(array $r, bool $full = true): string
    {
        if (empty($r['country'])) {
            return 'Location not known yet';
        }
        $parts = array_filter([$r['city'] ?? '', $full ? ($r['region'] ?? '') : '', $r['country']]);
        return implode(', ', array_unique($parts));
    }

    public function login(): never
    {
        Csrf::verify();
        $ip = client_ip();
        if (Auth::lockedOut($ip)) {
            Session::put('insights_error', 'Too many wrong attempts. Please wait 15 minutes and try again.');
        } elseif (password_verify((string) ($_POST['password'] ?? ''), self::PASSWORD_HASH)) {
            Session::regenerate();
            Session::put('insights_ok', time());
            Session::forget('insights_error');
            DB::delete('login_attempts', 'ip = ? AND email = ?', [$ip, 'pk-insights']);
        } else {
            DB::insert('login_attempts', ['ip' => $ip, 'email' => 'pk-insights', 'created_at' => now()]);
            Audit::log('login_failed', 'pk-insights', null, ['ip' => $ip]);
            Session::put('insights_error', 'That password is not right.');
        }
        redirect('/pk-insights/');
    }

    public function logout(): never
    {
        Csrf::verify();
        Session::forget('insights_ok');
        redirect('/pk-insights/');
    }
}
