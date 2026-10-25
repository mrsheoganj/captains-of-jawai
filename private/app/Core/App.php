<?php
declare(strict_types=1);

namespace App\Core;

use App\Admin;
use App\Controllers\ApiController;
use App\Controllers\InsightsController;
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
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains'); // browsers stay on HTTPS
        }
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

        // One-time deploy hook (FTP deploys without shell access); closed unless a fresh token file exists.
        if ($method === 'POST' && $path === '/_deploy') {
            (new \App\Controllers\DeployController())->run();
        }

        // Private website insights (own password, separate from the admin login).
        if ($path === '/pk-insights' || str_starts_with($path, '/pk-insights/')) {
            header('X-Frame-Options: DENY');
            header('Cache-Control: no-store, private');
            header('X-Robots-Tag: noindex, nofollow');
            $router->get('pk-insights', [InsightsController::class, 'index']);
            $router->post('pk-insights', [InsightsController::class, 'login']);
            $router->post('pk-insights/logout', [InsightsController::class, 'logout']);
            if (!$router->dispatch($method, $path)) {
                redirect('/pk-insights/');
            }
            return;
        }

        // Public site ------------------------------------------------------
        self::applyRedirects($path);
        Insights::track($path);

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
        // The Field Journal was removed; old links go to the Jawai guide.
        $router->get('journal', fn () => redirect('/jawai/', 301));
        $router->get('journal/{slug}', fn (string $slug) => redirect('/jawai/', 301));
        $router->get('gallery', [SiteController::class, 'gallery']);
        $router->get('faq', [SiteController::class, 'faq']);
        $router->get('about', [SiteController::class, 'about']);
        $router->get('contact', [SiteController::class, 'contact']);
        $router->get('photo-credits', [SiteController::class, 'credits']);
        $router->get('book-a-safari', [SiteController::class, 'plan']);
        $router->get('experiences', fn () => redirect('/safaris/', 301)); // no index page for /experiences/
        $router->get('plan-your-journey', fn () => redirect('/book-a-safari/', 301));
        $router->get('sitemap.xml', [SiteController::class, 'sitemap']);
        $router->get('robots.txt', [SiteController::class, 'robots']);
        $router->get('llms.txt', [SiteController::class, 'llms']);
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
        $r->post("$a/media/find/recommended", [Admin\MediaController::class, 'recommended']);
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
        $r->post("$a/email-log/clear", [Admin\SettingsController::class, 'clearEmailLog']);

        $r->get("$a/users", [Admin\UserController::class, 'index']);
        $r->get("$a/users/new", [Admin\UserController::class, 'edit']);
        $r->post("$a/users/new", [Admin\UserController::class, 'save']);
        $r->get("$a/users/{id:\d+}", [Admin\UserController::class, 'edit']);
        $r->post("$a/users/{id:\d+}", [Admin\UserController::class, 'save']);
        $r->post("$a/users/{id:\d+}/delete", [Admin\UserController::class, 'delete']);

        $r->get("$a/audit", [Admin\DashboardController::class, 'audit']);
        $r->get("$a/system", [Admin\DashboardController::class, 'system']);
        $r->post("$a/demo/{action}", [Admin\DashboardController::class, 'demo']);
        $r->post("$a/system/reset", [Admin\DashboardController::class, 'resetSite']);
        $r->post("$a/system/refresh-content", [Admin\DashboardController::class, 'refreshContent']);
    }

    /** After uploading a new version, create any new tables/columns automatically (once). */
    /** 2.11.8: search titles aimed at "Jawai safari" (titles and descriptions only; page text is unchanged). */
    public const SEO_FIXES = [
        ["Join expert local trackers for morning (6–9 AM) and evening (4–7 PM) Jawai leopard safaris in private open 4x4 gypsies across Bera's granite hills.", "Jawai leopard safari from {price} per jeep: morning (6–9 AM) and evening (4–7 PM) drives with expert local trackers in private open 4x4 gypsies in Bera."],
        ["Bera Leopard Safari Booking | Private & Shared 4x4", "Jawai Leopard Safari | Morning & Evening 4x4 in Bera"],
        ["Bera Leopard Safari Booking | Private 4x4 Gypsy", "Jawai Leopard Safari | Morning & Evening 4x4 in Bera"],
        ["Rajasthan Wildlife Safari | Leopards, Bears & Birds", "Jawai Wildlife Safari | Leopards, Sloth Bears & Birds"],
    ];

    /** 2.11.4: private gypsies only by default (shared can be switched on in Settings → Enquiry Form). */
    public const PRIVATE_FIXES = [
        ["Private or shared 4x4 gypsy, up to 6 guests", "Private open 4x4 gypsy, up to 6 guests"],
        ["Open 4x4 gypsy, private or shared, with an experienced local tracker", "Private open 4x4 gypsy with an experienced local tracker"],
        ["Bera Leopard Safari Booking | Private & Shared 4x4", "Bera Leopard Safari Booking | Private 4x4 Gypsy"],
        ["in private or shared open 4x4 gypsies across Bera", "in private open 4x4 gypsies across Bera"],
    ];

    /** 2.11.2: audit fixes (leopard estimates with honest wording, private or shared jeeps, one season for photography). */
    public const CONTENT_FIXES = [
        ["Published estimates put the population at around 50 to 60 leopards in and around the area.", "Local estimates put the population at around 50 to 70 leopards in the wider Jawai and Bera area; there is no official count for Jawai alone."],
        ["<tr><th>Leopards</th><td>Estimated 50 to 60 in the wider area</td></tr>", "<tr><th>Leopards</th><td>Estimated 50 to 70 in the wider area (no official count)</td></tr>"],
        ["Published estimates usually put the number at around 50 to 60 leopards in the wider Jawai area. Numbers are estimates and change over time.", "Local estimates usually put the number at around 50 to 70 leopards in the wider Jawai and Bera area. There is no official census figure for Jawai alone, and numbers change over time."],
        ["Figures quoted by the forest department and researchers are usually in the range of 50 to 60 leopards for the wider Jawai area.", "Local estimates are usually in the range of 50 to 70 leopards for the wider Jawai and Bera area; there is no official census figure for Jawai alone."],
        ["The Jawai Bandh Leopard Conservation Reserve covers about 20 km²,", "The Jawai Bandh Leopard Conservation Reserve covers about 19.8 km²,"],
        ["Private open 4x4 with an experienced local tracker", "Open 4x4 gypsy, private or shared, with an experienced local tracker"],
        ["November – April", "October – March (best light November – February)"],
    ];

    /** 2.11.1: road distances measured from Bera village (OpenStreetMap routing), replacing older estimates. */
    public const DISTANCE_FIXES = [
        ["about 155 km, or three and a half hours by road. Jodhpur airport is about 175 km to the north. By train, Sumerpur Jawai Bandh station is about 15 km from Bera and Falna about 34 km,", "about 130 km, or two and a half to three hours by road. Jodhpur airport is about 175 km to the north. By train, Sumerpur Jawai Bandh station is about 18 km from Bera and Falna about 37 km,"],
        ["Udaipur (about 155 km, 3.5 hours by road)", "Udaipur (about 130 km, 2.5 to 3 hours by road)"],
        ["Sumerpur Jawai Bandh, JWB (about 15 km)", "Sumerpur Jawai Bandh, JWB (about 18 km)"],
        ["Udaipur Airport is about 155 km away and Sumerpur Jawai Bandh railway station about 15 km.", "Udaipur Airport is about 130 km away and Sumerpur Jawai Bandh railway station about 18 km."],
        ["It is about 155 km from Bera, around three and a half hours by road.", "It is about 130 km from Bera, around two and a half to three hours by road."],
        ["is about 175 km, around four hours.", "is about 175 km, around three and a half hours."],
        ["sits right by the dam, about 15 km from Bera. <strong>Falna</strong> (FA) is about 34 km away.", "sits right by the dam, about 18 km from Bera. <strong>Falna</strong> (FA) is about 37 km away."],
        ["Sumerpur Jawai Bandh station (15 km)</td><td>25 minutes", "Sumerpur Jawai Bandh station (18 km)</td><td>30 minutes"],
        ["Falna station (34 km)</td><td>45 minutes", "Falna station (37 km)</td><td>50 minutes"],
        ["Udaipur city (141 km via Ranakpur)</td><td>3 to 3.5 hours", "Udaipur city (115 km)</td><td>2.5 hours"],
        ["Udaipur Airport (155 km)</td><td>3.5 hours", "Udaipur Airport (130 km)</td><td>2.5 to 3 hours"],
        ["Jodhpur Airport (175 km)</td><td>4 hours", "Jodhpur Airport (175 km)</td><td>3.5 hours"],
        ["about 155 km or three and a half hours by road. Jodhpur Airport is about 175 km away.", "about 130 km or two and a half to three hours by road. Jodhpur Airport is about 175 km away."],
        ["about 15 km from Bera. Falna, about 34 km away, has more express trains.", "about 18 km from Bera. Falna, about 37 km away, has more express trains."],
        ["About 141 km via Ranakpur, three to three and a half hours by car. Udaipur airport is about 155 km away.", "About 115 km, around two and a half hours by car. Udaipur airport is about 130 km away."],
        ["Jodhpur airport is about 175 km away, around four hours by car.", "Jodhpur airport is about 175 km away, around three and a half hours by car."],
        ["Udaipur Airport is about 155 km (3.5 hours by road) and Jodhpur Airport about 175 km (4 hours). The nearest railway station, Sumerpur Jawai Bandh, is about 15 km away, and Falna about 34 km.", "Udaipur Airport is about 130 km (2.5 to 3 hours by road) and Jodhpur Airport about 175 km (3.5 hours). The nearest railway station, Sumerpur Jawai Bandh, is about 18 km away, and Falna about 37 km."],
    ];

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
            'hero_subtitle' => ["we'll plan the rest", 'Private and shared open 4x4'],
            'ticker_items' => ['Rabari village walks'],
            'coexist_body' => ['We arrange visits'],
            'coexist_link' => ['/experiences/rabari-culture/'],
            'booking_intro' => ['a date and a drive time'],
            'coexist_kicker' => ['The Rabari'],
            'cta_body' => ['with options and prices'],
            'tpl_admin_subject' => ['journey enquiry'],
            'tpl_admin_body' => ['journey enquiry'],
            'tpl_guest_body' => ['tailored proposal'],
            'announcement_link' => ['/plan-your-journey/'],
            'color_accent' => ['#B8822B'],
            'color_accent_hover' => ['#9E6B1D'],
            'color_dark' => ['#121416'],
            // 2.11.1: map distances and the map's centre now measured from Bera village
            'distances_items' => ['155 km | 3.5 hrs | 24.6177'],
            'latitude' => ['25.1055'],
            // 2.11.2: one set of drive times everywhere; honest leopard estimate
            'booking_details' => ['15:30 to 18:30'],
            'stats' => ['50–60 | Leopards estimated in the reserve'],
            // 2.11.4: wording for private gypsies only
            'longitude' => ['73.1722'],
            // 2.10.1: report's homepage title and description replace the earlier defaults
            'seo_home_title' => ['Private Safaris in Jawai, Rajasthan', 'Private Expeditions | Captains of Jawai', 'Jawai Leopard Safari | Captains of Jawai, Bera'],
            'seo_home_description' => ['Private leopard safaris in Jawai', 'Private 4x4 leopard tracking across', 'Book private 4x4 Jawai leopard safaris in Bera', 'Book private & shared 4x4', 'Jawai safari with local trackers: private open 4x4'],
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
        foreach (['form_interests', 'form_drive_times', 'form_drives', 'journal_enabled', 'journal_heading', 'img_journal', 'team_enabled', 'team_kicker', 'team_heading', 'team_body', 'values_items', 'values_images', 'form_accommodation', 'form_transfers', 'form_nights', 'journeys_enabled', 'journeys_heading', 'img_journeys'] as $key) {
            DB::delete('settings', 'skey = ?', [$key]);
        }
        // 2.11.4: drop the "Shared gypsy safari" booking type from a saved list (other types are kept).
        $types = (string) DB::val('SELECT svalue FROM settings WHERE skey = ?', ['booking_types']);
        if ($types !== '' && stripos($types, 'Shared gypsy safari') !== false) {
            $keep = array_filter(preg_split('/\R/', $types), fn ($l) => stripos($l, 'Shared gypsy safari') !== 0);
            Settings::set('booking_types', implode("\n", $keep));
        }
        // 2.11.5: clear the old email (SMTP) sending log once; email settings are not touched.
        if (!is_file(STORAGE_PATH . '/.email-log-cleared-v1')) {
            DB::q('DELETE FROM email_log');
            @file_put_contents(STORAGE_PATH . '/.email-log-cleared-v1', date('c'));
        }
        // 2.11.3: the new square favicon pack replaces any earlier uploaded (round) favicon, once.
        if (!is_file(STORAGE_PATH . '/.favicon-pack-v2')) {
            DB::delete('settings', 'skey = ?', ['favicon_image']);
            @file_put_contents(STORAGE_PATH . '/.favicon-pack-v2', date('c'));
        }
        foreach ([...self::PRIVATE_FIXES, ...self::SEO_FIXES] as [$from, $to]) {
            foreach (['meta_title', 'meta_description'] as $col) {
                DB::q("UPDATE pages SET $col = REPLACE($col, ?, ?)", [$from, $to]);
                DB::q("UPDATE safaris SET $col = REPLACE($col, ?, ?)", [$from, $to]);
            }
        }
        foreach ([...self::DISTANCE_FIXES, ...self::CONTENT_FIXES, ...self::PRIVATE_FIXES] as [$from, $to]) {
            foreach (['intro', 'body', 'faq', 'meta_description'] as $col) {
                DB::q("UPDATE pages SET $col = REPLACE($col, ?, ?)", [$from, $to]);
            }
            foreach (['excerpt', 'body', 'highlights', 'best_season', 'timing', 'meta_description'] as $col) {
                DB::q("UPDATE safaris SET $col = REPLACE($col, ?, ?)", [$from, $to]);
            }
            DB::q('UPDATE faqs SET answer = REPLACE(answer, ?, ?)', [$from, $to]);
        }
        DB::delete('menu_items', "url LIKE '/journeys/%'");
        DB::q("UPDATE menu_items SET url = '/book-a-safari/' WHERE url = '/plan-your-journey/'");
        DB::q("UPDATE menu_items SET label = 'Our story' WHERE label = 'Our Story & Captains'");
        DB::delete('menu_items', "url = '/journal/' OR url LIKE '/journal/%'"); // journal removed
        // 2.9: no "starting template" note on the public legal pages.
        DB::q("UPDATE pages SET body = REPLACE(body, ?, '')", ['<p><em>This page is a starting template. Please review it with your legal adviser and update it in Admin → Pages before launch.</em></p>']);
        // 2.10: photo credits live on their own page, linked from the footer (CC BY / BY-SA attribution).
        if (!DB::val("SELECT COUNT(*) FROM menu_items WHERE url = '/photo-credits/'")) {
            DB::insert('menu_items', ['location' => 'legal', 'label' => 'Photo credits', 'url' => '/photo-credits/', 'parent_id' => null, 'sort_order' => 4, 'is_active' => 1, 'new_tab' => 0]);
        }
        // 2.9: tidy navigation. If the menus are still the old defaults (they link to /experiences/), rebuild them.
        if ((int) DB::val("SELECT COUNT(*) FROM menu_items WHERE url LIKE '/experiences/%' AND location IN ('header', 'footer')")) {
            DB::q("DELETE FROM menu_items");
            Seeder::menus();
            JawaiGuide::ensure();
            Articles::ensure();
        }
        Settings::flush();
    }

    private static function migrateIfUpdated(): void
    {
        if (Settings::get('schema_version') === APP_VERSION) {
            return;
        }
        try {
            self::upgradeNow();
        } catch (\Throwable $e) {
            error_log('Auto-migration failed: ' . $e->getMessage());
        }
    }

    /** Run every upgrade step now (also used by private/cli/apply-update.php). */
    public static function upgradeNow(): void
    {
        Schema::create(DB::pdo());
        JawaiGuide::ensure(); // add new guide pages to existing sites
        Articles::ensure(); // travel articles (routes, booking, price…)
        self::safariOnlyUpgrade();
        Demo::assignImages(); // register new built-in photos and fill any empty image slots
        Settings::set('schema_version', APP_VERSION);
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
