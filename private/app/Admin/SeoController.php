<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\DB;
use App\Core\Media;
use App\Core\Settings;

final class SeoController extends BaseController
{
    /** SEO audit: every public URL with its title/description lengths and issues. */
    public function index(): string
    {
        $this->guard('seo');
        $rows = [];
        $add = function (string $type, string $res, array $r, string $url, ?string $title, ?string $desc, ?int $img) use (&$rows): void {
            $title = trim((string) $title);
            $desc = trim((string) $desc);
            $issues = [];
            if ($title === '') {
                $issues[] = 'No custom meta title (page title used)';
            } elseif (mb_strlen($title) > 60) {
                $issues[] = 'Title longer than 60 characters';
            } elseif (mb_strlen($title) < 25) {
                $issues[] = 'Title is short';
            }
            if ($desc === '') {
                $issues[] = 'No meta description';
            } elseif (mb_strlen($desc) > 160) {
                $issues[] = 'Description longer than 160 characters';
            } elseif (mb_strlen($desc) < 70) {
                $issues[] = 'Description is short';
            }
            if ($img === null && $type !== 'Page') {
                $issues[] = 'No image (social previews use the default)';
            }
            if ($img) {
                $m = Media::find($img);
                if ($m && trim((string) $m['alt_text']) === '') {
                    $issues[] = 'Image has no alt text';
                }
            }
            $rows[] = ['type' => $type, 'res' => $res, 'id' => $r['id'], 'name' => $r['title'], 'url' => $url, 'title' => $title, 'desc' => $desc, 'status' => $r['status'], 'issues' => $issues];
        };
        foreach (DB::all('SELECT * FROM safaris ORDER BY sort_order') as $r) {
            $add('Safari', 'safaris', $r, '/safaris/' . $r['slug'] . '/', $r['meta_title'], $r['meta_description'], $r['image_id'] ? (int) $r['image_id'] : null);
        }
        foreach (DB::all('SELECT * FROM journeys ORDER BY sort_order') as $r) {
            $add('Journey', 'journeys', $r, '/journeys/' . $r['slug'] . '/', $r['meta_title'], $r['meta_description'], $r['image_id'] ? (int) $r['image_id'] : null);
        }
        foreach (DB::all('SELECT * FROM posts ORDER BY published_at DESC') as $r) {
            $add('Article', 'posts', $r, '/journal/' . $r['slug'] . '/', $r['meta_title'], $r['meta_description'], $r['image_id'] ? (int) $r['image_id'] : null);
        }
        foreach (DB::all('SELECT * FROM pages ORDER BY slug') as $r) {
            $add('Page', 'pages', $r, '/' . $r['slug'] . '/', $r['meta_title'], $r['meta_description'], $r['image_id'] ? (int) $r['image_id'] : null);
        }
        $noAlt = (int) DB::val("SELECT COUNT(*) FROM media WHERE alt_text IS NULL OR alt_text = ''");
        $checks = [
            ['Search engines allowed to index the site', !Settings::bool('seo_noindex'), admin_url('settings/seo')],
            ['Homepage title & description set', setting('seo_home_title') && setting('seo_home_description'), admin_url('settings/seo')],
            ['Google Search Console verification', (bool) setting('google_verification'), admin_url('settings/seo')],
            ['Google Analytics 4 connected', (bool) setting('ga4_id'), admin_url('settings/seo')],
            ['Site address (base URL) configured', (bool) \App\Core\Config::get('base_url'), admin_url('system')],
            ['All images have alt text', $noAlt === 0, admin_url('media?filter=noalt')],
            ['Phone number for local SEO / schema', (bool) setting('contact_phone'), admin_url('settings/contact')],
            ['Social profiles linked (sameAs)', (bool) (setting('social_instagram') || setting('social_facebook') || setting('social_youtube')), admin_url('settings/contact')],
        ];
        $issueCount = array_sum(array_map(fn ($r) => count($r['issues']), $rows));
        $redirects = (int) DB::val('SELECT COUNT(*) FROM redirects');
        return $this->render('seo', compact('rows', 'checks', 'issueCount', 'noAlt', 'redirects') + ['title' => 'SEO overview']);
    }

    public function robots(): string
    {
        $this->guard('seo');
        $default = "User-agent: *\nAllow: /\nDisallow: /api/";
        return $this->render('robots', ['value' => (string) setting('robots_txt', ''), 'default' => $default, 'title' => 'robots.txt']);
    }

    public function saveRobots(): void
    {
        $this->guard('seo', 'edit');
        Settings::set('robots_txt', trim(str_replace("\r\n", "\n", (string) ($_POST['robots_txt'] ?? ''))));
        Audit::log('update', 'settings', null, ['key' => 'robots_txt']);
        flash('success', 'robots.txt saved.');
        redirect(admin_url('seo/robots'));
    }
}
