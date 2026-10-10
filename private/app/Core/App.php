<?php
declare(strict_types=1);

namespace App\Core;

use App\Admin;
use App\Controllers\ApiController;
use App\Controllers\InstallController;
use App\Controllers\SiteController;

final class App
{
    public static function run(): void
    {
        Session::start();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = '/' . trim(rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'), '/');

        // Missing static files should not boot the whole site.
        if (preg_match('#^/(assets|uploads)/#', $path)) {
            http_response_code(404);
            exit('Not found');
        }

        $router = new Router();

        if (!Config::installed()) {
            $router->get('install', [InstallController::class, 'form']);
            $router->post('install', [InstallController::class, 'install']);
            if (!$router->dispatch($method, $path)) {
                redirect('/install');
            }
            return;
        }

        header('X-Content-Type-Options: nosniff');
        self::migrateIfUpdated();
        $admin = trim(admin_path(), '/');

        if ($path === '/' . $admin || str_starts_with($path, '/' . $admin . '/')) {
            header('X-Frame-Options: SAMEORIGIN');
            header('Cache-Control: no-store, private');
            header('X-Robots-Tag: noindex, nofollow');
            self::adminRoutes($router, $admin);
            if (!$router->dispatch($method, $path)) {
                http_response_code(404);
                echo View::render('admin/forbidden', ['title' => 'Page not found', 'notFound' => true], 'admin/layout');
            }
            return;
        }

        // Public site ------------------------------------------------------
        self::applyRedirects($path);

        // Canonical trailing slash for page URLs (not files like sitemap.xml).
        if ($method === 'GET' && $path !== '/' && !str_contains(basename($path), '.') && !str_ends_with((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') && !str_starts_with($path, '/api/')) {
            $qs = $_SERVER['QUERY_STRING'] ?? '';
            redirect($path . '/' . ($qs !== '' ? '?' . $qs : ''), 301);
        }

        if (Settings::bool('maintenance_mode') && !Auth::check() && !str_starts_with($path, '/api/')) {
            http_response_code(503);
            header('Retry-After: 3600');
            echo View::render('site/maintenance');
            return;
        }

        $router->get('', [SiteController::class, 'home']);
        $router->get('safaris', [SiteController::class, 'safaris']);
        $router->get('safaris/{slug}', [SiteController::class, 'safari']);
        // Multi-day journeys were retired: the business offers safaris only.
        $router->get('journeys', fn () => redirect('/safaris/', 301));
        $router->get('journeys/{slug}', fn (string $slug) => redirect('/safaris/', 301));
        $router->get('journal', [SiteController::class, 'journal']);
        $router->get('journal/{slug}', [SiteController::class, 'post']);
        $router->get('gallery', [SiteController::class, 'gallery']);
        $router->get('faq', [SiteController::class, 'faq']);
        $router->get('about', [SiteController::class, 'about']);
        $router->get('contact', [SiteController::class, 'contact']);
        $router->get('photo-credits', [SiteController::class, 'credits']);
        $router->get('book-a-safari', [SiteController::class, 'plan']);
        $router->get('plan-your-journey', fn () => redirect('/book-a-safari/', 301));
        $router->get('sitemap.xml', [SiteController::class, 'sitemap']);
        $router->get('robots.txt', [SiteController::class, 'robots']);
        $router->post('api/enquiry', [ApiController::class, 'enquiry']);
        $router->post('api/contact', [ApiController::class, 'contact']);
        $router->get('install', fn () => redirect('/'));
        $router->get('{path:.+}', [SiteController::class, 'page']);

        if (!$router->dispatch($method, $path)) {
            (new SiteController())->notFound();
        }
    }

    private static function adminRoutes(Router $r, string $a): void
    {
        $r->get("$a/login", [Admin\AuthController::class, 'loginForm']);
        $r->post("$a/login", [Admin\AuthController::class, 'login']);
        $r->post("$a/logout", [Admin\AuthController::class, 'logout']);
        $r->get("$a/profile", [Admin\AuthController::class, 'profile']);
        $r->post("$a/profile", [Admin\AuthController::class, 'saveProfile']);

        $r->get($a, [Admin\DashboardController::class, 'index']);

        $r->get("$a/enquiries", [Admin\EnquiryController::class, 'index']);
        $r->get("$a/enquiries/export", [Admin\EnquiryController::class, 'export']);
        $r->post("$a/enquiries/bulk", [Admin\EnquiryController::class, 'bulk']);
        $r->get("$a/enquiries/{id:\d+}", [Admin\EnquiryController::class, 'show']);
        $r->post("$a/enquiries/{id:\d+}", [Admin\EnquiryController::class, 'update']);
        $r->post("$a/enquiries/{id:\d+}/note", [Admin\EnquiryController::class, 'note']);
        $r->post("$a/enquiries/{id:\d+}/delete", [Admin\EnquiryController::class, 'delete']);

        $r->get("$a/content/{res}", [Admin\ContentController::class, 'index']);
        $r->get("$a/content/{res}/new", [Admin\ContentController::class, 'create']);
        $r->post("$a/content/{res}/new", [Admin\ContentController::class, 'store']);
        $r->post("$a/content/{res}/reorder", [Admin\ContentController::class, 'reorder']);
        $r->get("$a/content/{res}/{id:\d+}", [Admin\ContentController::class, 'edit']);
        $r->post("$a/content/{res}/{id:\d+}", [Admin\ContentController::class, 'update']);
        $r->post("$a/content/{res}/{id:\d+}/delete", [Admin\ContentController::class, 'delete']);
        $r->post("$a/content/{res}/{id:\d+}/duplicate", [Admin\ContentController::class, 'duplicate']);

        $r->get("$a/media", [Admin\MediaController::class, 'index']);
        $r->get("$a/media/picker", [Admin\MediaController::class, 'picker']);
        $r->get("$a/media/find", [Admin\MediaController::class, 'find']);
        $r->post("$a/media/find/import", [Admin\MediaController::class, 'importSelected']);
        $r->post("$a/media/find/pack", [Admin\MediaController::class, 'pack']);
        $r->post("$a/media/find/apply", [Admin\MediaController::class, 'apply']);
        $r->post("$a/media/upload", [Admin\MediaController::class, 'upload']);
        $r->post("$a/media/{id:\d+}", [Admin\MediaController::class, 'update']);
        $r->post("$a/media/{id:\d+}/delete", [Admin\MediaController::class, 'delete']);

        $r->get("$a/menu", [Admin\MenuController::class, 'index']);
        $r->post("$a/menu", [Admin\MenuController::class, 'save']);

        $r->get("$a/seo", [Admin\SeoController::class, 'index']);
        $r->get("$a/seo/robots", [Admin\SeoController::class, 'robots']);
        $r->post("$a/seo/robots", [Admin\SeoController::class, 'saveRobots']);

        $r->get("$a/settings", fn () => redirect(admin_url('settings/general')));
        $r->post("$a/settings/email/test", [Admin\SettingsController::class, 'testEmail']);
        $r->get("$a/settings/{group}", [Admin\SettingsController::class, 'edit']);
        $r->post("$a/settings/{group}", [Admin\SettingsController::class, 'save']);
        $r->get("$a/email-log", [Admin\SettingsController::class, 'emailLog']);

        $r->get("$a/users", [Admin\UserController::class, 'index']);
        $r->get("$a/users/new", [Admin\UserController::class, 'edit']);
        $r->post("$a/users/new", [Admin\UserController::class, 'save']);
        $r->get("$a/users/{id:\d+}", [Admin\UserController::class, 'edit']);
        $r->post("$a/users/{id:\d+}", [Admin\UserController::class, 'save']);
        $r->post("$a/users/{id:\d+}/delete", [Admin\UserController::class, 'delete']);

        $r->get("$a/audit", [Admin\DashboardController::class, 'audit']);
        $r->get("$a/system", [Admin\DashboardController::class, 'system']);
        $r->post("$a/demo/{action}", [Admin\DashboardController::class, 'demo']);
    }

    /** After uploading a new version, create any new tables/columns automatically (once). */
    /**
     * 2.4: the business offers safaris only (no stays, transfers or multi-day journeys).
     * Saved settings that still hold the old stay/transfer wording go back to the new defaults,
     * and menu links to the retired journeys section are removed.
     */
    private static function safariOnlyUpgrade(): void
    {
        $old = [
            'form_interests' => ['Family Journey', 'Rabari Cultural Walk'],
            'hero_cta_primary' => ['Plan your trip'],
            'hero_subtitle' => ["we'll plan the rest"],
            'ticker_items' => ['Rabari village walks'],
            'coexist_body' => ['We arrange visits'],
            'coexist_link' => ['/experiences/rabari-culture/'],
            'coexist_kicker' => ['The Rabari'],
            'cta_body' => ['with options and prices'],
            'tpl_admin_subject' => ['journey enquiry'],
            'tpl_admin_body' => ['journey enquiry'],
            'tpl_guest_body' => ['tailored proposal'],
            'announcement_link' => ['/plan-your-journey/'],
            'color_accent' => ['#B8822B'],
            'color_accent_hover' => ['#9E6B1D'],
            'color_dark' => ['#121416'],
        ];
        foreach ($old as $key => $needles) {
            $v = (string) DB::val('SELECT svalue FROM settings WHERE skey = ?', [$key]);
            foreach ($needles as $n) {
                if ($v !== '' && str_contains($v, $n)) {
                    DB::delete('settings', 'skey = ?', [$key]);
                    break;
                }
            }
        }
        foreach (['form_accommodation', 'form_transfers', 'form_nights', 'journeys_enabled', 'journeys_heading', 'img_journeys'] as $key) {
            DB::delete('settings', 'skey = ?', [$key]);
        }
        DB::delete('menu_items', "url LIKE '/journeys/%'");
        DB::q("UPDATE menu_items SET url = '/book-a-safari/' WHERE url = '/plan-your-journey/'");
        DB::q("UPDATE menu_items SET label = 'Our story' WHERE label = 'Our Story & Captains'");
        Settings::flush();
    }

    private static function migrateIfUpdated(): void
    {
        if (Settings::get('schema_version') === APP_VERSION) {
            return;
        }
        try {
            Schema::create(DB::pdo());
            JawaiGuide::ensure(); // add new guide pages to existing sites
            self::safariOnlyUpgrade();
            Demo::assignImages(); // register new built-in photos and fill any empty image slots
            Settings::set('schema_version', APP_VERSION);
        } catch (\Throwable $e) {
            error_log('Auto-migration failed: ' . $e->getMessage());
        }
    }

    private static function applyRedirects(string $path): void
    {
        // Legacy URLs from the previous site.
        $legacy = ['/about.php' => '/about/', '/contact.php' => '/contact/', '/index.php' => '/', '/sitemap.php' => '/sitemap.xml'];
        if (isset($legacy[$path])) {
            redirect($legacy[$path], 301);
        }
        try {
            $candidates = [$path, $path . '/', rtrim($path, '/')];
            $row = DB::one('SELECT id, to_url, code FROM redirects WHERE from_path IN (?, ?, ?) LIMIT 1', $candidates);
            if ($row) {
                DB::q('UPDATE redirects SET hits = COALESCE(hits, 0) + 1 WHERE id = ?', [$row['id']]);
                redirect($row['to_url'], in_array((int) $row['code'], [301, 302, 307, 308], true) ? (int) $row['code'] : 301);
            }
        } catch (\Throwable) {
        }
    }
}
