<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Auth;
use App\Core\Config;
use App\Core\DB;
use App\Core\Settings;

final class DashboardController extends BaseController
{
    public function index(): string
    {
        $this->guard('dashboard');
        $d7 = date('Y-m-d H:i:s', strtotime('-7 days'));
        $d14 = date('Y-m-d H:i:s', strtotime('-14 days'));
        $d30 = date('Y-m-d H:i:s', strtotime('-30 days'));
        $new7 = (int) DB::val('SELECT COUNT(*) FROM enquiries WHERE created_at >= ? AND status <> ?', [$d7, 'spam']);
        $prev7 = (int) DB::val('SELECT COUNT(*) FROM enquiries WHERE created_at >= ? AND created_at < ? AND status <> ?', [$d14, $d7, 'spam']);
        $active = (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE status IN ('proposal_sent', 'follow_up', 'qualified')");
        $total30 = (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE created_at >= ? AND status <> 'spam'", [$d30]);
        $won30 = (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE created_at >= ? AND status IN ('confirmed', 'active', 'completed')", [$d30]);
        $resp = DB::all('SELECT created_at, first_contacted_at FROM enquiries WHERE first_contacted_at IS NOT NULL ORDER BY created_at DESC LIMIT 100');
        $avgResp = $resp ? array_sum(array_map(fn ($r) => max(0, strtotime($r['first_contacted_at']) - strtotime($r['created_at'])), $resp)) / count($resp) / 3600 : null;

        $sixHoursAgo = date('Y-m-d H:i:s', strtotime('-6 hours'));
        $priority = DB::all(
            "SELECT * FROM enquiries WHERE (status = 'new' AND created_at <= ?) OR (follow_up_date IS NOT NULL AND follow_up_date <= ? AND status NOT IN ('completed', 'lost', 'spam')) ORDER BY created_at ASC LIMIT 10",
            [$sixHoursAgo, date('Y-m-d')]
        );
        $latest = DB::all("SELECT * FROM enquiries WHERE status <> 'spam' ORDER BY created_at DESC, id DESC LIMIT 8");
        $byStatus = [];
        foreach (DB::all('SELECT status, COUNT(*) AS n FROM enquiries GROUP BY status') as $r) {
            $byStatus[$r['status']] = (int) $r['n'];
        }
        $byCountry = DB::all("SELECT COALESCE(NULLIF(country, ''), 'Unknown') AS country, COUNT(*) AS n FROM enquiries WHERE status <> 'spam' GROUP BY COALESCE(NULLIF(country, ''), 'Unknown') ORDER BY n DESC LIMIT 6");
        // Enquiries per day, last 30 days
        $daily = array_fill_keys(array_map(fn ($i) => date('Y-m-d', strtotime("-$i days")), range(29, 0)), 0);
        foreach (DB::all("SELECT created_at FROM enquiries WHERE created_at >= ? AND status <> 'spam'", [$d30]) as $r) {
            $k = substr($r['created_at'], 0, 10);
            if (isset($daily[$k])) {
                $daily[$k]++;
            }
        }
        $content = [
            'Safaris' => (int) DB::val("SELECT COUNT(*) FROM safaris WHERE status = 'published'"),
            'Journeys' => (int) DB::val("SELECT COUNT(*) FROM journeys WHERE status = 'published'"),
            'Articles' => (int) DB::val("SELECT COUNT(*) FROM posts WHERE status = 'published'"),
            'Pages' => (int) DB::val("SELECT COUNT(*) FROM pages WHERE status = 'published'"),
            'Media' => (int) DB::val('SELECT COUNT(*) FROM media'),
        ];
        $mailFailures = (int) DB::val("SELECT COUNT(*) FROM email_log WHERE status = 'failed' AND created_at >= ?", [$d7]);
        $setup = array_filter([
            !setting('smtp_password') && setting('mail_transport') === 'smtp' ? ['Configure SMTP so enquiry emails are delivered', admin_url('settings/email')] : null,
            !setting('whatsapp_number') ? ['Add your WhatsApp number', admin_url('settings/contact')] : null,
            !setting('contact_phone') ? ['Add your phone number', admin_url('settings/contact')] : null,
            (int) DB::val("SELECT COUNT(*) FROM media WHERE path LIKE 'assets/photos/%'") > 0 ? ['Replace the built-in demo photos with your own Jawai photography', admin_url('media/find')] : null,
            !(int) DB::val('SELECT COUNT(*) FROM team') ? ['Introduce your Captains (team profiles)', admin_url('content/team/new')] : null,
            !setting('ga4_id') ? ['Connect Google Analytics', admin_url('settings/seo')] : null,
            Settings::bool('seo_noindex') ? ['Search engines are blocked (staging mode is on)', admin_url('settings/seo')] : null,
        ]);
        $demoCount = \App\Core\Demo::hasEnquiries();
        return $this->render('dashboard', compact('demoCount', 'new7', 'prev7', 'active', 'total30', 'won30', 'avgResp', 'priority', 'latest', 'byStatus', 'byCountry', 'daily', 'content', 'mailFailures', 'setup') + ['title' => 'Dashboard']);
    }

    public function audit(): string
    {
        $this->guard('audit');
        $page = max(1, (int) input('page', 1));
        $rows = DB::all('SELECT a.*, u.name AS user_name FROM audit_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT 100 OFFSET ' . (($page - 1) * 100));
        $total = (int) DB::val('SELECT COUNT(*) FROM audit_log');
        return $this->render('audit', ['rows' => $rows, 'page' => $page, 'total' => $total, 'title' => 'Activity log']);
    }

    /** Demo content tools (System page + dashboard banner). */
    public function demo(string $action): void
    {
        $this->guard('settings', 'edit');
        $msg = match ($action) {
            'load' => \App\Core\Demo::loadEnquiries(\App\Core\Auth::id()) . ' sample enquiries loaded into the CRM.',
            'clear' => \App\Core\Demo::clearEnquiries() . ' sample enquiries removed.',
            'fill-images' => \App\Core\Demo::assignImages(false) . ' empty image slot(s) filled.',
            'reset-images' => \App\Core\Demo::assignImages(true) . ' image slot(s) reset to the built-in photo set.',
            default => 'Unknown action.',
        };
        \App\Core\Audit::log('demo_' . $action, 'system');
        flash('success', $msg);
        back(admin_url('system'));
    }

    public function system(): string
    {
        $this->guard('settings');
        $info = [
            'App version' => APP_VERSION,
            'PHP version' => PHP_VERSION,
            'Database' => DB::driver() . ' ' . DB::pdo()->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'Server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'Upload limit' => ini_get('upload_max_filesize') . ' (post ' . ini_get('post_max_size') . ')',
            'Memory limit' => ini_get('memory_limit'),
            'GD / WebP' => (extension_loaded('gd') ? 'yes' : 'no') . ' / ' . (function_exists('imagewebp') ? 'yes' : 'no'),
            'OpenSSL' => extension_loaded('openssl') ? 'yes' : 'no',
            'Admin path' => admin_path(),
            'Base URL' => base_url(),
            'Timezone' => date_default_timezone_get() . ' (' . date('Y-m-d H:i') . ')',
            'Uploads writable' => is_writable(PUBLIC_PATH . '/uploads') ? 'yes' : 'NO — fix folder permissions',
            'Storage writable' => is_writable(STORAGE_PATH) ? 'yes' : 'NO — fix folder permissions',
            'Installed at' => (string) Config::get('installed_at'),
        ];
        $log = is_file(STORAGE_PATH . '/logs/php-error.log') ? array_slice(file(STORAGE_PATH . '/logs/php-error.log') ?: [], -40) : [];
        return $this->render('system', ['info' => $info, 'log' => $log, 'title' => 'System']);
    }
}
