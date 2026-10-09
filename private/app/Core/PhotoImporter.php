<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Finds freely-licensed photos on Wikimedia Commons and imports them into the media library
 * with photographer credit and licence filled in. Runs on the live server (needs outbound HTTPS).
 * Only Creative Commons BY / BY-SA / CC0 / public-domain images are offered (no NC / ND).
 */
final class PhotoImporter
{
    /** Preset searches used by the "Starter photo pack": [label, query, gallery category] */
    public const PRESETS = [
        ['Jawai leopards', 'Jawai leopard', 'Leopards'],
        ['Bera leopards', 'Bera leopard Rajasthan', 'Leopards'],
        ['Leopards on rock', 'Indian leopard rock', 'Leopards'],
        ['Rajasthan leopards', 'leopard Rajasthan', 'Leopards'],
        ['Jawai Dam', 'Jawai dam', 'Landscape'],
        ['Jawai Bandh', 'Jawai Bandh', 'Landscape'],
        ['Aravalli granite hills', 'Aravalli hills Rajasthan', 'Landscape'],
        ['Pali district', 'Pali district Rajasthan', 'Landscape'],
        ['Flamingos', 'Greater flamingo Rajasthan', 'Birds & Wetland'],
        ['Demoiselle cranes', 'Demoiselle crane Rajasthan', 'Birds & Wetland'],
        ['Bar-headed geese', 'Bar-headed goose India', 'Birds & Wetland'],
        ['Painted storks', 'Painted stork Rajasthan', 'Birds & Wetland'],
        ['Mugger crocodile', 'Mugger crocodile', 'Wildlife'],
        ['Striped hyena', 'Striped hyena India', 'Wildlife'],
        ['Nilgai', 'Nilgai Rajasthan', 'Wildlife'],
        ['Rabari herders', 'Rabari Rajasthan', 'Culture'],
        ['Rabari camels', 'Rabari camel', 'Culture'],
        ['Ranakpur temple', 'Ranakpur temple', 'Culture'],
        ['Safari jeep', 'safari jeep India', 'Safari Life'],
    ];

    private const UA = 'CaptainsOfJawaiWebsite/2.1 (+https://captainsofjawai.com; media importer)';

    private static function api(): string
    {
        return (string) Config::get('photo_api', 'https://commons.wikimedia.org/w/api.php');
    }

    /** Hosts we are willing to download from (prevents fetching arbitrary URLs). */
    private static function allowedHost(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $hosts = (array) Config::get('photo_hosts', ['upload.wikimedia.org', 'commons.wikimedia.org']);
        return in_array($host, $hosts, true) && in_array(parse_url($url, PHP_URL_SCHEME), ['https', 'http'], true);
    }

    private static function http(string $url, int $maxBytes = 2_000_000, int $timeout = 25): array
    {
        if (!function_exists('curl_init')) {
            return [0, '', 'The cURL PHP extension is not enabled on this server.'];
        }
        $ch = curl_init($url);
        $buf = '';
        $tooBig = false;
        curl_setopt_array($ch, [
            CURLOPT_USERAGENT => self::UA,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_WRITEFUNCTION => function ($ch, string $chunk) use (&$buf, &$tooBig, $maxBytes): int {
                $buf .= $chunk;
                if (strlen($buf) > $maxBytes) {
                    $tooBig = true;
                    return 0;
                }
                return strlen($chunk);
            },
        ]);
        curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err = $tooBig ? 'File too large' : curl_error($ch);
        curl_close($ch);
        return [$code, $buf, $err];
    }

    /**
     * Search Commons. Returns ['items' => [...], 'error' => ?string]
     * Each item: title, thumb, download, page, width, height, license, license_url, artist, description, imported (bool)
     */
    public static function search(string $query, int $limit = 40): array
    {
        $params = [
            'action' => 'query', 'format' => 'json', 'formatversion' => '2',
            'generator' => 'search', 'gsrsearch' => trim($query) . ' filetype:bitmap', 'gsrnamespace' => '6', 'gsrlimit' => (string) min(50, $limit),
            'prop' => 'imageinfo', 'iiprop' => 'url|size|mime|extmetadata', 'iiurlwidth' => '480',
            'iiextmetadatafilter' => 'LicenseShortName|LicenseUrl|Artist|ImageDescription|ObjectName',
        ];
        [$code, $body, $err] = self::http(self::api() . '?' . http_build_query($params));
        if ($code !== 200) {
            return ['items' => [], 'error' => 'Could not reach Wikimedia Commons' . ($err ? " ($err)" : " (HTTP $code)") . '.'];
        }
        $data = json_decode($body, true);
        $pages = $data['query']['pages'] ?? [];
        usort($pages, fn ($a, $b) => ($a['index'] ?? 0) <=> ($b['index'] ?? 0));
        $existing = array_flip(array_filter(array_column(DB::all("SELECT source_url FROM media WHERE source_url <> ''"), 'source_url')));
        $items = [];
        foreach ($pages as $p) {
            $ii = $p['imageinfo'][0] ?? null;
            if (!$ii || !in_array($ii['mime'] ?? '', ['image/jpeg', 'image/png', 'image/webp'], true)) {
                continue;
            }
            if (($ii['width'] ?? 0) < 1000 || ($ii['height'] ?? 0) < 600) {
                continue;
            }
            $meta = $ii['extmetadata'] ?? [];
            $license = trim(strip_tags((string) ($meta['LicenseShortName']['value'] ?? '')));
            if (!self::licenseOk($license)) {
                continue;
            }
            $thumb = (string) ($ii['thumburl'] ?? '');
            $download = $ii['width'] > 1920 && str_contains($thumb, '/480px-') ? str_replace('/480px-', '/1920px-', $thumb) : (string) $ii['url'];
            $title = preg_replace('/^File:/', '', (string) $p['title']);
            $items[] = [
                'title' => $title,
                'name' => self::niceName($title, (string) ($meta['ObjectName']['value'] ?? '')),
                'thumb' => $thumb,
                'download' => $download,
                'original' => (string) $ii['url'],
                'page' => (string) ($ii['descriptionurl'] ?? ''),
                'width' => (int) $ii['width'],
                'height' => (int) $ii['height'],
                'license' => $license,
                'license_url' => (string) strip_tags((string) ($meta['LicenseUrl']['value'] ?? '')),
                'artist' => self::clean((string) ($meta['Artist']['value'] ?? 'Unknown')),
                'description' => mb_substr(self::clean((string) ($meta['ImageDescription']['value'] ?? '')), 0, 300),
                'imported' => isset($existing[(string) ($ii['descriptionurl'] ?? '')]),
            ];
        }
        return ['items' => $items, 'error' => null];
    }

    public static function licenseOk(string $license): bool
    {
        if ($license === '' || preg_match('/\b(NC|ND)\b|non-?commercial|no ?deriv/i', $license)) {
            return false;
        }
        return (bool) preg_match('/^(CC0|CC[ -]BY(-SA)?\b|Public domain|PD\b|Attribution)/i', $license);
    }

    private static function clean(string $html): string
    {
        $t = html_entity_decode(strip_tags(str_replace(['<br>', '<br />', '<br/>'], ' ', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/\s+/', ' ', $t));
    }

    private static function niceName(string $title, string $objectName): string
    {
        $n = self::clean($objectName) ?: pathinfo($title, PATHINFO_FILENAME);
        $n = preg_replace('/[_]+/', ' ', $n);
        $n = preg_replace('/\b(IMG|DSC|DSCN|P)\s?\d+\b/i', '', $n);
        return trim(preg_replace('/\s+/', ' ', $n)) ?: 'Jawai photograph';
    }

    /** Download one search item into the media library. Returns media id or error string. */
    public static function import(array $item, string $category = '', bool $inGallery = true): int|string
    {
        $page = (string) ($item['page'] ?? '');
        if ($page !== '' && ($id = DB::val('SELECT id FROM media WHERE source_url = ?', [$page]))) {
            return (int) $id;
        }
        if (!self::licenseOk((string) ($item['license'] ?? ''))) {
            return 'Licence not allowed for commercial use: ' . ($item['license'] ?? 'unknown');
        }
        $urls = array_values(array_unique(array_filter([(string) ($item['download'] ?? ''), (string) ($item['original'] ?? '')])));
        $body = '';
        $lastErr = 'No download URL';
        foreach ($urls as $url) {
            if (!self::allowedHost($url)) {
                $lastErr = 'Download host not allowed';
                continue;
            }
            [$code, $body, $err] = self::http($url, 25_000_000, 60);
            if ($code === 200 && strlen($body) > 1000) {
                break;
            }
            $lastErr = $err ?: "HTTP $code";
            $body = '';
        }
        if ($body === '') {
            return 'Download failed: ' . $lastErr;
        }
        $tmp = tempnam(sys_get_temp_dir(), 'coj');
        file_put_contents($tmp, $body);
        unset($body);
        $artist = mb_substr((string) ($item['artist'] ?? ''), 0, 200);
        $result = Media::upload(
            ['name' => (string) ($item['title'] ?? 'photo.jpg'), 'tmp_name' => $tmp, 'error' => UPLOAD_ERR_OK, 'size' => filesize($tmp)],
            [
                '_local' => true,
                'alt_text' => mb_substr((string) ($item['name'] ?? ''), 0, 240),
                'caption' => (string) ($item['description'] ?? ''),
                'credit' => $artist . ' / Wikimedia Commons',
                'license' => (string) $item['license'],
                'license_url' => (string) ($item['license_url'] ?? ''),
                'source_url' => $page,
                'in_gallery' => $inGallery ? 1 : 0,
                'gallery_category' => $category,
            ]
        );
        @unlink($tmp);
        return $result;
    }

    /** Import the top $n new photos for one preset. Returns [imported ids, messages]. */
    public static function importPreset(int $index, int $n = 3): array
    {
        $preset = self::PRESETS[$index] ?? null;
        if (!$preset) {
            return [[], ['Unknown preset']];
        }
        [$label, $query, $cat] = $preset;
        $res = self::search($query, 30);
        if ($res['error']) {
            return [[], [$res['error']]];
        }
        $ids = [];
        $msgs = [];
        foreach ($res['items'] as $item) {
            if (count($ids) >= $n) {
                break;
            }
            if ($item['imported']) {
                continue;
            }
            $r = self::import($item, $cat, true);
            if (is_int($r)) {
                $ids[] = $r;
            } else {
                $msgs[] = $item['title'] . ': ' . $r;
            }
        }
        if (!$res['items']) {
            $msgs[] = "No freely-licensed results for “{$label}”.";
        }
        return [$ids, $msgs];
    }

    /**
     * Put imported photos to work: replace placeholder/empty images in homepage settings, safaris,
     * journeys, journal posts and pages. Never overwrites an image the team chose themselves.
     * Returns number of slots filled.
     */
    public static function useAcrossSite(): int
    {
        $pool = [];
        foreach (DB::all("SELECT id, gallery_category FROM media WHERE source_url <> '' ORDER BY id") as $m) {
            $pool[$m['gallery_category'] ?: 'Other'][] = (int) $m['id'];
        }
        if (!$pool) {
            return 0;
        }
        $cursor = [];
        $pick = function (array $cats) use (&$pool, &$cursor): ?int {
            foreach ($cats as $c) {
                if (!empty($pool[$c])) {
                    $i = $cursor[$c] ?? 0;
                    $cursor[$c] = $i + 1;
                    return $pool[$c][$i % count($pool[$c])];
                }
            }
            foreach ($pool as $c => $ids) {
                return $ids[0];
            }
            return null;
        };
        $placeholderIds = array_map('intval', array_column(DB::all("SELECT id FROM media WHERE path LIKE 'assets/photos/%'"), 'id'));
        $replaceable = fn ($v) => !$v || in_array((int) $v, $placeholderIds, true);
        $filled = 0;

        // Homepage settings
        $heroIds = array_filter(array_map('intval', explode(',', (string) Settings::get('hero_images'))));
        if (!$heroIds || !array_diff($heroIds, $placeholderIds)) {
            $hero = array_values(array_unique(array_filter([$pick(['Leopards']), $pick(['Landscape']), $pick(['Leopards']), $pick(['Birds & Wetland', 'Landscape'])])));
            if ($hero) {
                Settings::set('hero_images', implode(',', $hero));
                $filled++;
            }
        }
        foreach (['intro_image' => ['Leopards'], 'coexist_image' => ['Culture', 'Landscape'], 'cta_image' => ['Safari Life', 'Landscape'], 'seo_og_image' => ['Leopards']] as $key => $cats) {
            if ($replaceable(Settings::get($key))) {
                Settings::set($key, (string) $pick($cats));
                $filled++;
            }
        }

        $assign = function (string $table, array $map, string $default) use ($pick, $replaceable, &$filled): void {
            foreach (DB::all("SELECT id, image_id, slug, " . ($table === 'posts' ? 'category' : "'' AS category") . " FROM $table") as $r) {
                if (!$replaceable($r['image_id'])) {
                    continue;
                }
                $key = $table === 'posts' ? (string) $r['category'] : (string) $r['slug'];
                if (!isset($map[$key]) && $default === '') {
                    continue; // e.g. legal pages stay without a hero image
                }
                $cats = $map[$key] ?? [$default];
                if ($table === 'posts' && preg_match('/flamingo|bird|bandh|crane/i', (string) $r['slug'])) {
                    $cats = ['Birds & Wetland', 'Landscape'];
                }
                if ($id = $pick($cats)) {
                    DB::update($table, ['image_id' => $id], 'id = ?', [$r['id']]);
                    $filled++;
                }
            }
        };
        $assign('safaris', [
            'leopard-safari' => ['Leopards'], 'dam-birding-safari' => ['Birds & Wetland', 'Wildlife'],
            'private-safari' => ['Safari Life', 'Landscape'], 'photography-safari' => ['Leopards'],
        ], 'Leopards');
        $assign('journeys', ['3-day-classic' => ['Leopards'], '4-day-photography' => ['Birds & Wetland', 'Leopards'], 'rajasthan-circuit' => ['Culture', 'Landscape']], 'Landscape');
        $assign('posts', ['Wildlife' => ['Leopards', 'Wildlife'], 'Culture' => ['Culture'], 'Planning' => ['Landscape', 'Safari Life']], 'Landscape');
        $assign('pages', [
            'jawai' => ['Landscape'], 'experiences/rabari-culture' => ['Culture'], 'experiences/boulder-sundowners' => ['Landscape'],
            'about/conservation-ethics' => ['Leopards', 'Wildlife'], 'about' => ['Safari Life', 'Leopards'],
        ], '');

        // Keep placeholders out of the public gallery once real photos exist.
        if ($placeholderIds) {
            DB::q("UPDATE media SET in_gallery = 0 WHERE path LIKE 'assets/photos/%'");
        }
        return $filled;
    }
}
