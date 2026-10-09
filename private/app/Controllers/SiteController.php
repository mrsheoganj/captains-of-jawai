<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\DB;
use App\Core\Media;
use App\Core\Settings;
use App\Core\View;

final class SiteController
{
    private function show(string $template, array $data, array $seo = []): string
    {
        $data['seo'] = $this->seo($seo);
        return View::render('site/' . $template, $data, 'site/layout');
    }

    /** Normalise SEO data with sensible defaults. */
    private function seo(array $s): array
    {
        $suffix = (string) setting('seo_title_suffix');
        $title = $s['title'] ?? setting('site_name');
        if (empty($s['raw_title']) && $suffix !== '' && !str_contains($title, trim($suffix, ' |'))) {
            $title .= $suffix;
        }
        $image = $s['image'] ?? null;
        $imageUrl = $image ? abs_url(Media::url($image, 'lg')) : (setting('seo_og_image') ? abs_url(Media::url((int) setting('seo_og_image'), 'lg')) : abs_url('/assets/img/og-image.jpg'));
        return [
            'title' => $title,
            'description' => $s['description'] ?? setting('seo_home_description'),
            'canonical' => abs_url($s['path'] ?? current_path()),
            'image' => $imageUrl,
            'type' => $s['type'] ?? 'website',
            'schema' => $s['schema'] ?? [],
            'noindex' => !empty($s['noindex']) || Settings::bool('seo_noindex'),
            'breadcrumbs' => $s['breadcrumbs'] ?? [],
        ];
    }

    private function published(string $table, string $order = 'sort_order, id'): array
    {
        return DB::all("SELECT * FROM $table WHERE status = 'published' ORDER BY $order");
    }

    public function home(): string
    {
        $safaris = DB::all("SELECT * FROM safaris WHERE status = 'published' ORDER BY is_featured DESC, sort_order, id LIMIT 3");
        $journeys = DB::all("SELECT * FROM journeys WHERE status = 'published' ORDER BY is_featured DESC, sort_order, id LIMIT 3");
        $posts = DB::all("SELECT * FROM posts WHERE status = 'published' AND published_at <= ? ORDER BY published_at DESC LIMIT 3", [now()]);
        $team = $this->published('team');
        $testimonials = $this->published('testimonials');
        $heroIds = array_filter(array_map('intval', explode(',', (string) setting('hero_images'))));

        return $this->show('home', compact('safaris', 'journeys', 'posts', 'team', 'testimonials', 'heroIds'), [
            'title' => setting('seo_home_title'),
            'raw_title' => true,
            'description' => setting('seo_home_description'),
            'image' => $heroIds[0] ?? null,
            'path' => '/',
            'schema' => [$this->orgSchema(), [
                '@context' => 'https://schema.org', '@type' => 'WebSite', 'url' => abs_url('/'), 'name' => setting('site_name'),
                'publisher' => ['@id' => abs_url('/#organization')],
            ]],
        ]);
    }

    public function safaris(): string
    {
        $items = $this->published('safaris');
        return $this->show('safaris', ['items' => $items], [
            'title' => 'Jawai Safari Expeditions — Private 4x4 Wildlife Journeys',
            'description' => 'Explore bespoke safari experiences in Jawai and Bera: private leopard tracking, Jawai Dam wetland drives and photography expeditions.',
            'breadcrumbs' => [['Expeditions', '/safaris/']],
            'image' => $items[0]['image_id'] ?? null,
        ]);
    }

    public function safari(string $slug): string
    {
        $item = DB::one("SELECT * FROM safaris WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$item) {
            $this->notFound();
        }
        $others = DB::all("SELECT * FROM safaris WHERE status = 'published' AND id <> ? ORDER BY sort_order LIMIT 3", [$item['id']]);
        return $this->show('safari', compact('item', 'others'), [
            'title' => $item['meta_title'] ?: $item['title'],
            'raw_title' => (bool) $item['meta_title'],
            'description' => $item['meta_description'] ?: excerpt($item['excerpt']),
            'image' => $item['image_id'] ? (int) $item['image_id'] : null,
            'breadcrumbs' => [['Expeditions', '/safaris/'], [$item['title'], '/safaris/' . $item['slug'] . '/']],
            'schema' => [[
                '@context' => 'https://schema.org', '@type' => 'TouristTrip', 'name' => $item['title'],
                'description' => excerpt($item['excerpt'], 300), 'touristType' => ['Wildlife enthusiasts', 'Photographers', 'Luxury travellers'],
                'provider' => ['@id' => abs_url('/#organization')], 'url' => abs_url('/safaris/' . $item['slug'] . '/'),
            ]],
        ]);
    }

    public function journeys(): string
    {
        $items = $this->published('journeys');
        return $this->show('journeys', ['items' => $items], [
            'title' => 'Curated Jawai Journeys & Itineraries',
            'description' => 'Multi-day Jawai safari itineraries: the essential 2-night expedition, photography immersions and Rajasthan wildlife & heritage circuits.',
            'breadcrumbs' => [['Journeys', '/journeys/']],
            'image' => $items[0]['image_id'] ?? null,
        ]);
    }

    public function journey(string $slug): string
    {
        $item = DB::one("SELECT * FROM journeys WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$item) {
            $this->notFound();
        }
        $days = [];
        foreach (lines($item['itinerary']) as $line) {
            $parts = array_map('trim', explode('|', $line, 3));
            $days[] = ['label' => $parts[0] ?? '', 'title' => $parts[1] ?? '', 'text' => $parts[2] ?? ($parts[1] ?? '')];
        }
        $others = DB::all("SELECT * FROM journeys WHERE status = 'published' AND id <> ? ORDER BY sort_order LIMIT 2", [$item['id']]);
        return $this->show('journey', compact('item', 'days', 'others'), [
            'title' => $item['meta_title'] ?: $item['title'],
            'raw_title' => (bool) $item['meta_title'],
            'description' => $item['meta_description'] ?: excerpt($item['excerpt']),
            'image' => $item['image_id'] ? (int) $item['image_id'] : null,
            'breadcrumbs' => [['Journeys', '/journeys/'], [$item['title'], '/journeys/' . $item['slug'] . '/']],
            'schema' => [[
                '@context' => 'https://schema.org', '@type' => 'TouristTrip', 'name' => $item['title'], 'description' => excerpt($item['excerpt'], 300),
                'provider' => ['@id' => abs_url('/#organization')],
                'itinerary' => ['@type' => 'ItemList', 'numberOfItems' => count($days), 'itemListElement' => array_map(
                    fn ($d, $n) => ['@type' => 'ListItem', 'position' => $n + 1, 'name' => trim($d['label'] . ' — ' . $d['title'], ' —'), 'description' => $d['text']],
                    $days, array_keys($days)
                )],
            ]],
        ]);
    }

    public function journal(): string
    {
        $cat = str_input('category', 100);
        $page = max(1, (int) input('page', 1));
        $per = 9;
        $where = "status = 'published' AND published_at <= ?";
        $params = [now()];
        if ($cat !== '') {
            $where .= ' AND category = ?';
            $params[] = $cat;
        }
        $total = (int) DB::val("SELECT COUNT(*) FROM posts WHERE $where", $params);
        $posts = DB::all("SELECT * FROM posts WHERE $where ORDER BY published_at DESC LIMIT $per OFFSET " . (($page - 1) * $per), $params);
        $categories = DB::all("SELECT category, COUNT(*) AS n FROM posts WHERE status = 'published' AND category <> '' GROUP BY category ORDER BY category");
        return $this->show('journal', compact('posts', 'categories', 'cat', 'page', 'total', 'per'), [
            'title' => 'The Field Journal — Jawai Wildlife Stories & Guides' . ($page > 1 ? " (page $page)" : ''),
            'description' => 'Stories, guides and field notes from Jawai: leopard behaviour, birding at Jawai Bandh, Rabari culture and practical safari planning.',
            'breadcrumbs' => [['Field Journal', '/journal/']],
            'noindex' => $cat !== '',
        ]);
    }

    public function post(string $slug): string
    {
        $item = DB::one("SELECT * FROM posts WHERE slug = ? AND status = 'published' AND published_at <= ?", [$slug, now()]);
        if (!$item) {
            $this->notFound();
        }
        $related = DB::all("SELECT * FROM posts WHERE status = 'published' AND id <> ? AND published_at <= ? ORDER BY CASE WHEN category = ? THEN 0 ELSE 1 END, published_at DESC LIMIT 3", [$item['id'], now(), $item['category']]);
        return $this->show('post', compact('item', 'related'), [
            'title' => $item['meta_title'] ?: $item['title'],
            'raw_title' => (bool) $item['meta_title'],
            'description' => $item['meta_description'] ?: excerpt($item['excerpt']),
            'image' => $item['image_id'] ? (int) $item['image_id'] : null,
            'type' => 'article',
            'breadcrumbs' => [['Field Journal', '/journal/'], [$item['title'], '/journal/' . $item['slug'] . '/']],
            'schema' => [[
                '@context' => 'https://schema.org', '@type' => 'BlogPosting', 'headline' => $item['title'],
                'description' => excerpt($item['excerpt'], 300), 'datePublished' => date('c', strtotime($item['published_at'])),
                'dateModified' => date('c', strtotime($item['updated_at'] ?: $item['published_at'])),
                'author' => ['@type' => 'Organization', 'name' => $item['author_name'] ?: setting('site_name')],
                'publisher' => ['@id' => abs_url('/#organization')],
                'image' => $item['image_id'] ? abs_url(Media::url((int) $item['image_id'])) : abs_url('/assets/img/og-image.jpg'),
                'mainEntityOfPage' => abs_url('/journal/' . $item['slug'] . '/'),
            ]],
        ]);
    }

    public function gallery(): string
    {
        $items = DB::all('SELECT * FROM media WHERE in_gallery = 1 ORDER BY sort_order, id DESC');
        $categories = array_values(array_unique(array_filter(array_column($items, 'gallery_category'))));
        return $this->show('gallery', compact('items', 'categories'), [
            'title' => 'Gallery — Leopards, Landscapes & Life in Jawai',
            'description' => 'Photographs from the Jawai wilderness: leopards on granite, Jawai Bandh wetland birds, landscapes and safari life.',
            'breadcrumbs' => [['Gallery', '/gallery/']],
        ]);
    }

    public function faq(): string
    {
        $faqs = $this->published('faqs');
        $groups = [];
        foreach ($faqs as $f) {
            $groups[$f['category'] ?: 'General'][] = $f;
        }
        return $this->show('faq', compact('groups'), [
            'title' => 'Jawai Safari FAQ — Planning, Wildlife & Booking',
            'description' => 'Answers to common questions about Jawai leopard safaris: sightings, best season, how to get there, safety, children and booking.',
            'breadcrumbs' => [['FAQ', '/faq/']],
            'schema' => [[
                '@context' => 'https://schema.org', '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $f['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['answer'])]], $faqs),
            ]],
        ]);
    }

    public function about(): string
    {
        $page = DB::one("SELECT * FROM pages WHERE slug = 'about' AND status = 'published'");
        $team = $this->published('team');
        return $this->show('about', compact('page', 'team'), [
            'title' => $page['meta_title'] ?? 'About Captains of Jawai — Masters of the Granite Sanctuary',
            'description' => $page['meta_description'] ?? 'Our master naturalists, Rabari heritage roots and strict commitment to ethical, low-impact wildlife exploration in Jawai.',
            'breadcrumbs' => [['About', '/about/']],
            'image' => $page['image_id'] ?? null,
        ]);
    }

    public function credits(): string
    {
        $items = DB::all("SELECT * FROM media WHERE (credit <> '' AND credit <> 'Placeholder') OR source_url <> '' ORDER BY gallery_category, id");
        return $this->show('credits', ['items' => $items], [
            'title' => 'Photo Credits',
            'description' => 'Credits and licences for the photographs used on the Captains of Jawai website.',
            'breadcrumbs' => [['Photo Credits', '/photo-credits/']],
        ]);
    }

    public function contact(): string
    {
        return $this->show('contact', [], [
            'title' => 'Contact Captains of Jawai',
            'description' => 'Contact Captains of Jawai by email, phone or WhatsApp to plan a private leopard safari in Jawai, Rajasthan.',
            'breadcrumbs' => [['Contact', '/contact/']],
            'schema' => [$this->orgSchema()],
        ]);
    }

    public function plan(): string
    {
        $safari = str_input('safari', 120);
        return $this->show('plan', ['preselect' => $safari], [
            'title' => 'Plan Your Jawai Safari — Bespoke Journey Consultation',
            'description' => 'Plan your private wildlife expedition in Jawai, Rajasthan. Share your dates and interests and an Expedition Captain will reply within 12 hours.',
            'breadcrumbs' => [['Plan Your Journey', '/plan-your-journey/']],
        ]);
    }

    public function page(string $path): string
    {
        $slug = trim($path, '/');
        $item = DB::one("SELECT * FROM pages WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$item) {
            // Admin preview of drafts
            if (\App\Core\Auth::check() && isset($_GET['preview'])) {
                $item = DB::one('SELECT * FROM pages WHERE slug = ?', [$slug]);
            }
            if (!$item) {
                $this->notFound();
            }
        }
        $crumbs = [];
        $acc = '';
        foreach (explode('/', $slug) as $n => $seg) {
            $acc .= '/' . $seg;
            $crumbs[] = [$n === count(explode('/', $slug)) - 1 ? $item['title'] : ucwords(str_replace('-', ' ', $seg)), $acc . '/'];
        }
        return $this->show('page', compact('item'), [
            'title' => $item['meta_title'] ?: $item['title'],
            'raw_title' => (bool) $item['meta_title'],
            'description' => $item['meta_description'] ?: excerpt($item['intro'] ?: $item['body']),
            'image' => $item['image_id'] ? (int) $item['image_id'] : null,
            'breadcrumbs' => $crumbs,
            'noindex' => $item['status'] !== 'published',
        ]);
    }

    public function notFound(): never
    {
        http_response_code(404);
        echo $this->show('404', [], ['title' => 'Page not found', 'noindex' => true]);
        exit;
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $urls = [['/', null, '1.0']];
        foreach (['/safaris/', '/journeys/', '/journal/', '/gallery/', '/faq/', '/about/', '/contact/', '/plan-your-journey/'] as $p) {
            $urls[] = [$p, null, '0.8'];
        }
        foreach (DB::all("SELECT slug, updated_at FROM safaris WHERE status = 'published'") as $r) {
            $urls[] = ['/safaris/' . $r['slug'] . '/', $r['updated_at'], '0.9'];
        }
        foreach (DB::all("SELECT slug, updated_at FROM journeys WHERE status = 'published'") as $r) {
            $urls[] = ['/journeys/' . $r['slug'] . '/', $r['updated_at'], '0.8'];
        }
        foreach (DB::all("SELECT slug, updated_at FROM posts WHERE status = 'published' AND published_at <= ?", [now()]) as $r) {
            $urls[] = ['/journal/' . $r['slug'] . '/', $r['updated_at'], '0.7'];
        }
        foreach (DB::all("SELECT slug, updated_at FROM pages WHERE status = 'published' AND slug <> 'about'") as $r) {
            $urls[] = ['/' . $r['slug'] . '/', $r['updated_at'], str_contains($r['slug'], 'policy') || $r['slug'] === 'terms' ? '0.3' : '0.7'];
        }
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as [$p, $mod, $prio]) {
            echo '  <url><loc>' . e(abs_url($p)) . '</loc>' . ($mod ? '<lastmod>' . date('Y-m-d', strtotime($mod)) . '</lastmod>' : '') . '<priority>' . $prio . "</priority></url>\n";
        }
        echo "</urlset>\n";
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        if (Settings::bool('seo_noindex')) {
            echo "User-agent: *\nDisallow: /\n";
            return;
        }
        $custom = trim((string) setting('robots_txt', ''));
        if ($custom !== '') {
            echo $custom . "\n";
        } else {
            // The admin path is deliberately not listed (it would reveal a custom login URL);
            // admin pages send "X-Robots-Tag: noindex" instead.
            echo "User-agent: *\nAllow: /\nDisallow: /api/\n";
        }
        echo "\nSitemap: " . abs_url('/sitemap.xml') . "\n";
    }

    private function orgSchema(): array
    {
        $same = array_values(array_filter([setting('social_instagram'), setting('social_facebook'), setting('social_youtube'), setting('social_tripadvisor'), setting('social_google')]));
        $org = [
            '@context' => 'https://schema.org', '@type' => 'TravelAgency', '@id' => abs_url('/#organization'),
            'name' => setting('site_name'), 'url' => abs_url('/'), 'logo' => abs_url('/assets/img/logo-400.png'),
            'image' => abs_url('/assets/img/og-image.jpg'), 'description' => setting('site_description'),
            'email' => setting('contact_email'),
            'address' => ['@type' => 'PostalAddress', 'streetAddress' => setting('address'), 'addressRegion' => 'Rajasthan', 'addressCountry' => 'IN'],
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => (float) setting('latitude'), 'longitude' => (float) setting('longitude')],
            'areaServed' => [['@type' => 'Place', 'name' => 'Jawai'], ['@type' => 'Place', 'name' => 'Bera'], ['@type' => 'Place', 'name' => 'Jawai Bandh']],
        ];
        if (setting('contact_phone')) {
            $org['telephone'] = setting('contact_phone');
        }
        if ($same) {
            $org['sameAs'] = $same;
        }
        return $org;
    }
}
