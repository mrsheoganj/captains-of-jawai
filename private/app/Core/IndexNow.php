<?php
declare(strict_types=1);

namespace App\Core;

/**
 * IndexNow: tells Bing, Yandex, Seznam, Naver and other participating search engines straight away
 * that pages were added or changed (Google does not use IndexNow; it reads the sitemap).
 * The key file public_html/{KEY}.txt proves the site owns the key.
 */
final class IndexNow
{
    public const KEY = '6c33d877b846a679941915429973a42a';
    private const ENDPOINT = 'https://api.indexnow.org/indexnow';

    /** Every public URL in the sitemap. */
    public static function allPaths(): array
    {
        $paths = ['/', '/safaris/', '/gallery/', '/faq/', '/about/', '/contact/', '/book-a-safari/'];
        foreach (DB::all("SELECT slug FROM safaris WHERE status = 'published'") as $r) {
            $paths[] = '/safaris/' . $r['slug'] . '/';
        }
        foreach (DB::all("SELECT slug FROM pages WHERE status = 'published' AND slug <> 'about'") as $r) {
            $paths[] = '/' . $r['slug'] . '/';
        }
        return array_values(array_unique($paths));
    }

    /** Submit paths (e.g. '/safaris/'). Returns a short status line; never throws. */
    public static function submit(array $paths): string
    {
        if (!$paths || Settings::bool('seo_noindex') || !function_exists('curl_init')) {
            return 'IndexNow: skipped.';
        }
        $host = (string) parse_url(abs_url('/'), PHP_URL_HOST);
        if ($host === '' || in_array($host, ['localhost', '127.0.0.1'], true)) {
            return 'IndexNow: skipped (not the live site).';
        }
        $body = json_encode([
            'host' => $host,
            'key' => self::KEY,
            'keyLocation' => abs_url('/' . self::KEY . '.txt'),
            'urlList' => array_map('abs_url', array_slice($paths, 0, 10000)),
        ], JSON_UNESCAPED_SLASHES);
        try {
            $ch = curl_init(self::ENDPOINT);
            curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $body, CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf-8'], CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_TIMEOUT => 10]);
            curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);
            return 'IndexNow: ' . count($paths) . ' URL(s) sent to search engines (HTTP ' . $code . ').';
        } catch (\Throwable $e) {
            return 'IndexNow: failed (' . $e->getMessage() . ').';
        }
    }
}
