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
        $safaris = $this->published('safaris');
        $testimonials = $this->published('testimonials');
        $heroIds = array_filter(array_map('intval', explode(',', (string) setting('hero_images'))));
        // "Before you come": the Jawai overview plus each guide page, in reading order, with short labels.
        $labels = ['jawai' => 'Jawai at a glance'] + \App\Core\JawaiGuide::MENU;
        $order = array_flip(array_keys($labels));
        $guide = DB::all("SELECT slug, title, intro, image_id FROM pages WHERE status = 'published' AND (slug = 'jawai' OR slug LIKE 'jawai/%')");
        foreach ($guide as &$g) {
            $g['label'] = $labels[$g['slug']] ?? $g['title'];
        }
        unset($g);
        usort($guide, fn ($a, $b) => ($order[$a['slug']] ?? 99) <=> ($order[$b['slug']] ?? 99));
        // Gallery preview: only photos that are not already shown elsewhere on the homepage.
        $used = $heroIds;
        foreach (['jawai_images', 'village_images'] as $k) {
            $used = array_merge($used, array_map('intval', explode(',', (string) setting($k))));
        }
        foreach (['intro_image', 'season_winter_image', 'season_summer_image', 'season_monsoon_image', 'booking_image', 'coexist_image', 'cta_image'] as $k) {
            $used[] = (int) setting($k);
        }
        $used = array_merge($used, array_map('intval', array_column($safaris, 'image_id')), array_map('intval', array_column($guide, 'image_id')));
        $used = array_values(array_filter(array_unique($used)));
        $picked = array_values(array_diff(array_filter(array_map('intval', explode(',', (string) setting('home_gallery_images')))), $used));
        if ($picked) {
            $rows = array_column(DB::all('SELECT * FROM media WHERE id IN (' . implode(',', $picked) . ')'), null, 'id');
            $gallery = array_values(array_filter(array_map(fn ($id) => $rows[$id] ?? null, $picked)));
        } else {
            $gallery = DB::all('SELECT * FROM media WHERE in_gallery = 1' . ($used ? ' AND id NOT IN (' . implode(',', $used) . ')' : '') . ' ORDER BY sort_order, id DESC LIMIT ' . max(4, min(24, (int) setting('home_gallery_count'))));
        }
        $heroMobile = array_values(array_filter(array_map('intval', explode(',', (string) setting('hero_images_mobile')))));
        $slideIds = array_merge($heroIds, $heroMobile);
        $heroSlides = $slideIds ? DB::all('SELECT id, caption, alt_text FROM media WHERE id IN (' . implode(',', $slideIds) . ')') : [];
        $heroSlides = array_column($heroSlides, null, 'id');

        $faqs = DB::all("SELECT question, answer FROM faqs WHERE status = 'published' ORDER BY CASE WHEN category = 'Planning' THEN 0 ELSE 1 END, sort_order, id LIMIT " . max(3, min(10, (int) setting('home_faq_count'))));
        return $this->show('home', compact('safaris', 'testimonials', 'heroIds', 'heroMobile', 'heroSlides', 'guide', 'gallery', 'faqs'), [
            'title' => setting('seo_home_title'),
            'raw_title' => true,
            'description' => setting('seo_home_description'),
            'image' => $heroIds[0] ?? null,
            'path' => '/',
            'schema' => array_values(array_filter([$this->orgSchema(), [
                '@context' => 'https://schema.org', '@type' => 'WebSite', 'url' => abs_url('/'), 'name' => setting('site_name'),
                'publisher' => ['@id' => abs_url('/#organization')],
            ], $this->destinationSchema(), Settings::bool('home_faq_enabled') ? $this->faqSchema($faqs) : null])),
        ]);
    }

    /** Jawai itself as a tourist destination, with the safaris as the way to experience it. */
    private function destinationSchema(): array
    {
        return [
            '@context' => 'https://schema.org', '@type' => 'TouristDestination', 'name' => 'Jawai',
            'alternateName' => ['Jawai Bandh', 'Jawai, Rajasthan'],
            'description' => 'Granite hill country around the Jawai Dam in Pali district, Rajasthan, known for leopards that live alongside Rabari herders, and for winter birds at the reservoir.',
            'url' => abs_url('/jawai/'),
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => (float) setting('latitude'), 'longitude' => (float) setting('longitude')],
            'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Pali district, Rajasthan, India'],
            'touristType' => ['Wildlife enthusiasts', 'Photographers', 'Birdwatchers', 'Families'],
            'includesAttraction' => [
                ['@type' => 'TouristAttraction', 'name' => 'Jawai leopard safari', 'url' => abs_url('/safaris/leopard-safari/')],
                ['@type' => 'TouristAttraction', 'name' => 'Jawai Dam (Jawai Bandh)', 'url' => abs_url('/jawai/jawai-dam/')],
            ],
        ];
    }

    /** FAQPage data for questions that are shown on the page. */
    private function faqSchema(array $faqs): ?array
    {
        $q = [];
        foreach ($faqs as $f) {
            $answer = trim(strip_tags((string) $f['answer']));
            if ($answer !== '' && trim((string) $f['question']) !== '') {
                $q[] = ['@type' => 'Question', 'name' => trim((string) $f['question']), 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer]];
            }
        }
        return $q ? ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $q] : null;
    }

    /** /llms.txt: a plain summary of the business and its key pages for AI assistants and answer engines. */
    public function llms(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $out = '# ' . setting('site_name') . "\n\n> " . trim((string) setting('site_description')) . "\n\n";
        $out .= 'Jawai safari operator based at Jawai Bandh, Bera (Pali district, Rajasthan, India). '
            . 'Private open 4x4 gypsy safaris with local trackers: mornings 6:00–9:00 AM and evenings 4:00–7:00 PM. '
            . 'Booking requests: ' . abs_url('/book-a-safari/')
            . (setting('contact_phone') ? ' · Phone/WhatsApp: ' . setting('contact_phone') : '')
            . (setting('contact_email') ? ' · Email: ' . setting('contact_email') : '') . "\n\n## Safaris\n\n";
        foreach (DB::all("SELECT title, slug, excerpt FROM safaris WHERE status = 'published' ORDER BY sort_order, id") as $r) {
            $out .= '- [' . $r['title'] . '](' . abs_url('/safaris/' . $r['slug'] . '/') . '): ' . excerpt($r['excerpt'], 180) . "\n";
        }
        $out .= "\n## Jawai guide\n\n";
        foreach (DB::all("SELECT title, slug, meta_description FROM pages WHERE status = 'published' AND slug LIKE 'jawai%' ORDER BY slug") as $r) {
            $out .= '- [' . $r['title'] . '](' . abs_url('/' . $r['slug'] . '/') . ')' . ($r['meta_description'] ? ': ' . $r['meta_description'] : '') . "\n";
        }
        $out .= "\n## More\n\n- [All safaris](" . abs_url('/safaris/') . ")\n- [FAQ](" . abs_url('/faq/') . ")\n- [Contact](" . abs_url('/contact/') . ")\n";
        echo $out;
    }

    public function safaris(): string
    {
        $items = $this->published('safaris');
        return $this->show('safaris', ['items' => $items], [
            'title' => 'Jawai Safari Booking | Leopard & Jeep Safaris in Jawai Bandh',
            'raw_title' => true,
            'description' => 'Jawai safari options with local trackers: private open-gypsy leopard safaris at 6 AM and 4 PM in Bera and Jawai Bandh, Jawai Dam birding and photography drives.',
            'schema' => [$this->safariListSchema($items)],
            'breadcrumbs' => [['Safaris', '/safaris/']],
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
            'breadcrumbs' => [['Safaris', '/safaris/'], [$item['title'], '/safaris/' . $item['slug'] . '/']],
            'schema' => [[
                '@context' => 'https://schema.org', '@type' => 'TouristTrip', 'name' => $item['title'],
                'description' => excerpt($item['excerpt'], 300), 'touristType' => ['Wildlife enthusiasts', 'Photographers', 'Luxury travellers'],
                'provider' => ['@id' => abs_url('/#organization')], 'url' => abs_url('/safaris/' . $item['slug'] . '/'),
            ]],
        ]);
    }

    public function gallery(): string
    {
        $items = DB::all('SELECT * FROM media WHERE in_gallery = 1 AND id <> ? ORDER BY sort_order, id DESC', [(int) setting('img_gallery')]); // the banner photo is not repeated below it
        $categories = array_values(array_unique(array_filter(array_column($items, 'gallery_category'))));
        return $this->show('gallery', compact('items', 'categories'), [
            'title' => 'Jawai Photo Gallery: Leopards & Landscapes',
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
            'title' => 'Jawai Safari FAQ: Wildlife, Timings & Booking',
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
        return $this->show('about', compact('page'), [
            'title' => $page['meta_title'] ?? 'About Captains of Jawai — Masters of the Granite Sanctuary',
            'description' => $page['meta_description'] ?? 'Our master naturalists, Rabari heritage roots and strict commitment to ethical, low-impact wildlife exploration in Jawai.',
            'breadcrumbs' => [['About', '/about/']],
            'image' => $page['image_id'] ?? null,
        ]);
    }

    public function credits(): string
    {
        // Only photos that are actually shown: image settings (slider, homepage sections, page banners),
        // published pages and safaris, and the gallery.
        $used = [];
        foreach (DB::all("SELECT skey, svalue FROM settings WHERE skey LIKE 'img\\_%' OR skey LIKE '%\\_image' OR skey LIKE '%\\_images'") as $row) {
            foreach (explode(',', (string) $row['svalue']) as $v) {
                if (ctype_digit(trim($v))) {
                    $used[] = (int) trim($v);
                }
            }
        }
        $used = array_merge(
            $used,
            array_map('intval', array_column(DB::all("SELECT image_id FROM pages WHERE status = 'published' AND image_id IS NOT NULL"), 'image_id')),
            array_map('intval', array_column(DB::all("SELECT image_id FROM safaris WHERE status = 'published' AND image_id IS NOT NULL"), 'image_id')),
            array_map('intval', array_column(DB::all('SELECT id FROM media WHERE in_gallery = 1'), 'id'))
        );
        $used = array_values(array_unique(array_filter($used)));
        $items = $used ? DB::all("SELECT * FROM media WHERE id IN (" . implode(',', $used) . ") AND (source_url <> '' OR (credit <> '' AND credit <> 'Placeholder')) ORDER BY gallery_category, id") : [];
        return $this->show('credits', ['items' => $items], [
            'title' => 'Photo Credits',
            'description' => 'Photographers and Creative Commons licences for the photographs of leopards, birds, the Rabari and Jawai Dam used on the Captains of Jawai website.',
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
        $safaris = DB::all("SELECT title, slug FROM safaris WHERE status = 'published' ORDER BY sort_order, id");
        return $this->show('plan', ['preselect' => $safari, 'safaris' => $safaris], [
            'title' => 'Book a Jawai Safari | Leopard Safari Booking in Bera, Jawai',
            'raw_title' => true,
            'description' => 'Book a Jawai safari: morning (6–9 AM) or evening (4–7 PM) leopard safari in Bera, a Jawai Dam birding drive or a photography jeep. Personal reply within 12 hours.',
            'breadcrumbs' => [['Book a safari', '/book-a-safari/']],
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
        $segs = explode('/', $slug);
        foreach ($segs as $n => $seg) {
            $acc .= ($acc === '' ? '' : '/') . $seg;
            if ($n === count($segs) - 1) {
                $crumbs[] = [$item['title'], '/' . $acc . '/'];
            } elseif ($parent = DB::val("SELECT title FROM pages WHERE slug = ? AND status = 'published'", [$acc])) {
                $crumbs[] = [$parent, '/' . $acc . '/']; // only link parents that exist (no 404 breadcrumbs)
            }
        }

        // FAQ (Question | Answer per line) and sources (url | label per line), editable in Admin → Pages
        $faq = [];
        foreach (lines($item['faq'] ?? '') as $line) {
            [$q, $a] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
            if ($q !== '' && $a !== '') {
                $faq[] = [$q, $a];
            }
        }
        $sources = [];
        foreach (lines($item['sources'] ?? '') as $line) {
            [$u, $l] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
            if (preg_match('~^https?://~', $u)) {
                $sources[] = [$u, $l ?: $u];
            }
        }
        // Related guide pages: siblings/children in the same section (e.g. everything under /jawai/)
        $root = $segs[0];
        $related = DB::all("SELECT slug, title, intro, image_id FROM pages WHERE status = 'published' AND (slug = ? OR slug LIKE ?) AND slug <> ? ORDER BY slug", [$root, $root . '/%', $slug]);

        $schema = [];
        if ($faq) {
            $schema[] = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(
                fn ($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]],
                $faq
            )];
        }
        if ($slug === 'jawai/places-to-visit') {
            foreach ([
                ['dev-giri-temple', 'Dev Giri Temple (Ashapura Mata cave temple)', 'Cave shrine halfway up a granite hill near Perwa and Bera, Jawai, where leopards are often seen on the rocks and steps.'],
                ['kambeshwar-mahadev-temple', 'Kambeshwar Mahadev Temple', 'Old Shiva temple in the Aravalli foothills near Sheoganj, about 11 km from Jawai Dam, with natural springs and granite boulders.'],
                ['khodiyar-mata-tankeshwar-mahadev', 'Khodiyar Mata and Tankeshwar Mahadev temples', 'Shrines on the high ground by Jawai Dam with views over the reservoir and its granite hills.'],
            ] as [$anchor, $name, $desc]) {
                $schema[] = ['@context' => 'https://schema.org', '@type' => 'TouristAttraction', '@id' => abs_url('/jawai/places-to-visit/#' . $anchor), 'name' => $name, 'description' => $desc,
                    'isAccessibleForFree' => true, 'containedInPlace' => ['@type' => 'Place', 'name' => 'Jawai, Pali district, Rajasthan']];
            }
        }
        if ($root === 'jawai') {
            $schema[] = [
                '@context' => 'https://schema.org', '@type' => 'TouristDestination', 'name' => $slug === 'jawai' ? 'Jawai' : $item['title'],
                'description' => excerpt($item['intro'] ?: $item['body'], 300), 'url' => abs_url('/' . $slug . '/'),
                'geo' => ['@type' => 'GeoCoordinates', 'latitude' => (float) setting('latitude'), 'longitude' => (float) setting('longitude')],
                'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => 'Pali district, Rajasthan, India'],
                'touristType' => ['Wildlife enthusiasts', 'Birdwatchers', 'Photographers', 'Families'],
            ];
            $schema[] = [
                '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $item['title'], 'description' => excerpt($item['intro'] ?: $item['body'], 300),
                'dateModified' => date('c', strtotime($item['updated_at'] ?: 'now')), 'author' => ['@type' => 'Organization', 'name' => setting('site_name')],
                'publisher' => ['@id' => abs_url('/#organization')], 'mainEntityOfPage' => abs_url('/' . $slug . '/'),
            ];
        }

        return $this->show('page', compact('item', 'faq', 'sources', 'related'), [
            'title' => $item['meta_title'] ?: $item['title'],
            'raw_title' => (bool) $item['meta_title'],
            'description' => $item['meta_description'] ?: excerpt($item['intro'] ?: $item['body']),
            'image' => $item['image_id'] ? (int) $item['image_id'] : null,
            'type' => $root === 'jawai' ? 'article' : 'website',
            'breadcrumbs' => $crumbs,
            'schema' => $schema,
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
        $latest = (string) (DB::val("SELECT MAX(updated_at) FROM safaris WHERE status = 'published'") ?: date('Y-m-d'));
        $urls = [['/', $latest, '1.0', null]];
        foreach (['/safaris/' => '0.9', '/book-a-safari/' => '0.9', '/gallery/' => '0.6', '/faq/' => '0.7', '/about/' => '0.6', '/contact/' => '0.6'] as $p => $prio) {
            $urls[] = [$p, $latest, $prio, null];
        }
        foreach (DB::all("SELECT slug, title, updated_at, image_id FROM safaris WHERE status = 'published'") as $r) {
            $urls[] = ['/safaris/' . $r['slug'] . '/', $r['updated_at'], '0.9', $r['image_id'] ? [(int) $r['image_id'], $r['title'] . ' in Jawai'] : null];
        }
        foreach (DB::all("SELECT slug, title, updated_at, image_id FROM pages WHERE status = 'published' AND slug <> 'about'") as $r) {
            $prio = str_contains($r['slug'], 'policy') || $r['slug'] === 'terms' ? '0.3' : (str_starts_with($r['slug'], 'jawai') ? '0.8' : '0.7');
            $urls[] = ['/' . $r['slug'] . '/', $r['updated_at'], $prio, $r['image_id'] ? [(int) $r['image_id'], $r['title']] : null];
        }
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        foreach ($urls as [$p, $mod, $prio, $img]) {
            echo '  <url><loc>' . e(abs_url($p)) . '</loc>' . ($mod ? '<lastmod>' . date('Y-m-d', strtotime($mod)) . '</lastmod>' : '') . '<priority>' . $prio . '</priority>';
            if ($img && ($src = Media::url($img[0], 'lg'))) {
                echo '<image:image><image:loc>' . e(abs_url($src)) . '</image:loc></image:image>';
            }
            echo "</url>\n";
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
            // Answer engines (ChatGPT, Claude, Perplexity, Apple, Google AI) may read the public pages.
            foreach (['GPTBot', 'ChatGPT-User', 'OAI-SearchBot', 'ClaudeBot', 'Claude-User', 'PerplexityBot', 'Applebot-Extended', 'Google-Extended'] as $bot) {
                echo "\nUser-agent: $bot\nAllow: /\nDisallow: /api/\n";
            }
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
        if (preg_match('/(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})/u', (string) setting('office_hours'), $h)) {
            $org['openingHoursSpecification'] = [['@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens' => str_pad($h[1], 5, '0', STR_PAD_LEFT), 'closes' => str_pad($h[2], 5, '0', STR_PAD_LEFT)]];
        }
        $org['potentialAction'] = ['@type' => 'ReserveAction', 'name' => 'Book a Jawai safari',
            'target' => ['@type' => 'EntryPoint', 'urlTemplate' => abs_url('/book-a-safari/')]];
        $org['knowsAbout'] = ['Jawai safari', 'Jawai leopard safari', 'Jawai Bandh', 'Jawai Dam bird watching', 'Leopards of Jawai', 'Rabari herders'];
        $org['address']['addressLocality'] = 'Bera';
        $org['address']['postalCode'] ??= null;
        unset($org['address']['postalCode']);
        $offers = [];
        foreach (DB::all("SELECT title, slug, excerpt FROM safaris WHERE status = 'published' ORDER BY sort_order, id") as $s) {
            $offers[] = ['@type' => 'Offer', 'itemOffered' => ['@type' => 'TouristTrip', 'name' => $s['title'], 'description' => excerpt($s['excerpt'], 200), 'url' => abs_url('/safaris/' . $s['slug'] . '/')]];
        }
        if ($offers) {
            $org['makesOffer'] = $offers;
        }
        if ($same) {
            $org['sameAs'] = $same;
        }
        return $org;
    }

    /** ItemList of the safaris on /safaris/, so search engines see the whole range of Jawai safaris. */
    private function safariListSchema(array $items): array
    {
        $list = [];
        foreach (array_values($items) as $i => $s) {
            $list[] = ['@type' => 'ListItem', 'position' => $i + 1, 'url' => abs_url('/safaris/' . $s['slug'] . '/'), 'name' => $s['title']];
        }
        return ['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'Jawai safaris', 'itemListElement' => $list];
    }
}
