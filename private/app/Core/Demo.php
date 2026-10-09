<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Demo-ready content: a built-in photo set (public_html/assets/photos) wired into every image slot,
 * plus optional sample CRM enquiries so the dashboard looks alive during a presentation.
 * Everything here is editable or removable in the admin.
 */
final class Demo
{
    /** Built-in photos: name => [alt text, gallery category, caption] */
    public const PHOTOS = [
        'leopard1' => ['Leopard resting on a granite boulder at golden hour', 'Leopards', 'Morning basking on warm granite'],
        'leopard-portrait' => ['Close portrait of a Jawai leopard on the rocks', 'Leopards', 'Calm, unhurried — the Jawai temperament'],
        'leopard-basking' => ['Leopard stretched out on a sun-warmed boulder', 'Leopards', 'Winter sun on the kopjes'],
        'leopard2' => ['Leopard walking a rocky ridge at sunset', 'Leopards', 'Last light on the ridge'],
        'leopard-stalking' => ['Leopard moving through granite and scrub', 'Leopards', 'On patrol through the boulders'],
        'ridge-sighting' => ['Safari vehicle among the granite hills of Jawai', 'Safari Life', 'Scanning the ridgelines'],
        'landscape1' => ['Granite hills reflected in the waters of Jawai Bandh', 'Landscape', 'Jawai Bandh on a clear morning'],
        'lake-reflection' => ['Still water mirroring the granite domes of Jawai', 'Landscape', 'Reflections on the reservoir'],
        'bandh-shoreline' => ['Boulder-strewn shoreline of Jawai Bandh', 'Landscape', 'The reservoir shoreline'],
        'granite-boulders' => ['Weathered granite boulders beside the reservoir', 'Landscape', 'Ancient granite, smoothed by time'],
        'sunset-valley' => ['Sunset over the valley and granite hills', 'Landscape', 'Dusk over the leopard hills'],
        'granite-dome-dusk' => ['Granite dome glowing at dusk above the wetland', 'Landscape', 'Granite domes at dusk'],
        'bird1' => ['Flamingos wading in the shallows of Jawai Bandh', 'Birds & Wetland', 'Winter flamingos on the Bandh'],
        'flamingo-flock' => ['Flock of flamingos reflected in the wetland', 'Birds & Wetland', 'Flamingo flock in golden light'],
        'safari1' => ['Guests on an open 4x4 safari among the granite kopjes', 'Safari Life', 'A private expedition in the hills'],
        'safari-jeep' => ['Open safari jeep with guests and a naturalist', 'Safari Life', 'Your vehicle, your Captain'],
        'safari-guests' => ['Guests watching wildlife through binoculars', 'Safari Life', 'Binoculars up — a sighting on the rocks'],
    ];

    /** Register the built-in photos in the media library (idempotent). Returns name => id. */
    public static function registerImages(): array
    {
        $ids = [];
        foreach (DB::all("SELECT id, path FROM media WHERE path LIKE 'assets/photos/%'") as $m) {
            $ids[basename($m['path'])] = (int) $m['id'];
        }
        $n = 0;
        foreach (self::PHOTOS as $name => [$alt, $cat, $caption]) {
            $n++;
            $data = [
                'alt_text' => $alt, 'caption' => $caption, 'credit' => '', 'license' => 'Demo photo — replace with your own photography',
                'in_gallery' => 1, 'gallery_category' => $cat, 'sort_order' => $n,
            ];
            if (isset($ids[$name])) {
                // Upgrade rows created by older versions (labelled "Placeholder").
                DB::update('media', $data, 'id = ? AND (credit = ? OR license LIKE ?)', [$ids[$name], 'Placeholder', 'Placeholder%']);
                continue;
            }
            if (!is_file(PUBLIC_PATH . "/assets/photos/$name-1600.jpg")) {
                continue;
            }
            $size = @getimagesize(PUBLIC_PATH . "/assets/photos/$name-1600.jpg") ?: [1600, 900];
            $ids[$name] = DB::insert('media', $data + [
                'path' => 'assets/photos/' . $name, 'filename' => $name . '.jpg', 'mime' => 'image/jpeg',
                'width' => $size[0], 'height' => $size[1], 'filesize' => (int) filesize(PUBLIC_PATH . "/assets/photos/$name-1600.jpg"),
                'source_url' => '', 'license_url' => '', 'created_at' => now(),
            ]);
        }
        return $ids;
    }

    /**
     * Give every image slot on the site a photo. Only fills slots that are empty
     * (or point to a deleted image) unless $force is true; never touches photos the team chose
     * unless forced.
     * @return int number of slots filled
     */
    public static function assignImages(bool $force = false): int
    {
        $ids = self::registerImages();
        if (!$ids) {
            return 0;
        }
        $id = fn (string $name) => $ids[$name] ?? reset($ids);
        $exists = fn ($v) => $v && Media::find((int) $v);
        $filled = 0;

        $settings = [
            'intro_image' => 'leopard-portrait', 'coexist_image' => 'granite-dome-dusk', 'cta_image' => 'safari1',
            'seo_og_image' => 'leopard1', 'img_safaris' => 'leopard2', 'img_journeys' => 'ridge-sighting',
            'img_journal' => 'lake-reflection', 'img_gallery' => 'leopard-basking', 'img_faq' => 'safari-jeep',
            'img_contact' => 'bandh-shoreline', 'img_plan' => 'leopard-portrait', 'img_about' => 'safari-guests',
            'img_404' => 'granite-boulders', 'img_login' => 'leopard2',
        ];
        foreach ($settings as $key => $name) {
            if ($force || !$exists(Settings::get($key))) {
                Settings::set($key, (string) $id($name));
                $filled++;
            }
        }
        foreach (['hero_images' => ['leopard1', 'landscape1', 'leopard2', 'bird1'], 'values_images' => ['leopard-stalking', 'granite-boulders', 'safari-guests', 'leopard-basking']] as $key => $names) {
            $current = array_filter(array_map('intval', explode(',', (string) Settings::get($key))), fn ($v) => $exists($v));
            if ($force || !$current) {
                Settings::set($key, implode(',', array_map($id, $names)));
                $filled++;
            }
        }

        $map = [
            'safaris' => ['leopard-safari' => 'leopard-basking', 'dam-birding-safari' => 'flamingo-flock', 'private-safari' => 'safari-jeep', 'photography-safari' => 'leopard2'],
            'journeys' => ['3-day-classic' => 'ridge-sighting', '4-day-photography' => 'leopard-stalking', 'rajasthan-circuit' => 'lake-reflection'],
            'posts' => [
                'jawai-travel-guide' => 'landscape1', 'jawai-leopards-granite-caves' => 'leopard-portrait', 'rabari-leopard-coexistence' => 'granite-dome-dusk',
                'birdwatching-jawai-bandh' => 'bird1', 'what-to-pack-jawai-safari' => 'safari-guests', 'jawai-vs-ranthambhore' => 'sunset-valley',
                'monsoon-in-jawai' => 'granite-boulders', 'udaipur-to-jawai' => 'bandh-shoreline', 'code-of-the-captains' => 'leopard-stalking',
            ],
            'pages' => [
                'about' => 'safari-guests', 'jawai' => 'bandh-shoreline', 'experiences/rabari-culture' => 'granite-dome-dusk',
                'experiences/boulder-sundowners' => 'sunset-valley', 'about/conservation-ethics' => 'leopard-portrait',
                'privacy-policy' => 'lake-reflection', 'terms' => 'granite-boulders', 'cookie-policy' => 'landscape1',
            ],
        ];
        $rotation = array_keys(self::PHOTOS);
        $i = 0;
        foreach ($map as $table => $bySlug) {
            foreach (DB::all("SELECT id, slug, image_id FROM $table ORDER BY id") as $r) {
                if (!$force && $exists($r['image_id'])) {
                    continue;
                }
                $name = $bySlug[$r['slug']] ?? $rotation[$i++ % count($rotation)];
                DB::update($table, ['image_id' => $id($name)], 'id = ?', [$r['id']]);
                $filled++;
            }
        }
        foreach (DB::all('SELECT id, image_id FROM team') as $r) {
            if ($force || !$exists($r['image_id'])) {
                DB::update('team', ['image_id' => $id($rotation[$i++ % count($rotation)])], 'id = ?', [$r['id']]);
                $filled++;
            }
        }
        return $filled;
    }

    /** Sample enquiries across the CRM pipeline (codes start with DEMO-). */
    public static function loadEnquiries(?int $userId = null): int
    {
        if ((int) DB::val("SELECT COUNT(*) FROM enquiries WHERE code LIKE 'DEMO-%'")) {
            return 0;
        }
        $userId ??= (int) DB::val("SELECT id FROM users WHERE role = 'super_admin' ORDER BY id LIMIT 1") ?: null;
        $rows = [
            // [hours ago, name, email, phone, country, interests, window, nights, adults, children, accommodation, transfer, status, message, follow-up days, utm]
            [2, 'Ananya Mehta', 'ananya.mehta@example.com', '+91 98200 11223', 'India', 'Leopard Tracking, Boulder Sundowner', 'December 2026 (flexible)', '2 nights', 2, 0, 'Luxury Wilderness Tented Camp', 'Udaipur airport / city', 'new', 'Anniversary trip — would love a private sundowner on the rocks.', null, 'instagram'],
            [5, 'James Whitfield', 'j.whitfield@example.co.uk', '+44 7700 900123', 'United Kingdom', 'Leopard Tracking, Wildlife Photography', 'February 2027', '3 nights', 2, 0, 'Boutique Stone Villa', 'Jodhpur airport / city', 'new', 'Keen photographers with 600mm lenses. Is a vehicle for just the two of us possible?', null, 'google'],
            [9, 'Rohan & Kavya Iyer', 'rohan.iyer@example.com', '+91 99860 44551', 'India', 'Family Journey, Leopard Tracking, Wetland & Birding', 'Diwali week, November 2026', '2 nights', 2, 2, 'Heritage Haveli / Fort', 'Falna railway station', 'contacted', 'Kids are 7 and 10 — shorter drives please.', 2, ''],
            [26, 'Sophie Laurent', 'sophie.laurent@example.fr', '+33 6 12 34 56 78', 'France', 'Leopard Tracking, Rabari Cultural Walk', 'January 2027 (flexible)', '3 nights', 2, 0, 'Please advise me', 'Udaipur airport / city', 'qualified', 'Part of a Rajasthan circuit (Udaipur → Jawai → Jodhpur).', 1, 'google'],
            [50, 'Vikram Singhania', 'vikram.s@example.com', '+91 98200 12345', 'India', 'Leopard Tracking, Wildlife Photography', '15–18 November 2026', '3 nights', 2, 1, 'Luxury Wilderness Tented Camp', 'Udaipur airport / city', 'proposal_sent', 'Interested in a dedicated private 4x4 with beanbag mounts.', 0, 'google'],
            [76, 'Emma & Lukas Becker', 'becker.travel@example.de', '+49 151 23456789', 'Germany', 'Wetland & Birding, Leopard Tracking', 'Late December 2026', '4+ nights', 2, 0, 'Boutique Stone Villa', 'Ahmedabad', 'follow_up', 'Birders — hoping for demoiselle cranes and flamingos.', -1, ''],
            [120, 'Priya Nair', 'priya.nair@example.com', '+91 90040 77889', 'India', 'Leopard Tracking', '10–12 October 2026', '2 nights', 4, 0, 'Already booked my own stay', 'No transfer needed', 'confirmed', 'Group of friends from Mumbai.', null, 'instagram'],
            [190, 'Michael Chen', 'm.chen@example.com', '+1 415 555 0142', 'United States', 'Leopard Tracking, Wildlife Photography, Boulder Sundowner', 'Early October 2026', '3 nights', 2, 0, 'Luxury Wilderness Tented Camp', 'Jodhpur airport / city', 'completed', 'Celebrating a milestone birthday.', null, ''],
            [260, 'Arjun Kapoor', 'arjun.k@example.com', '+91 98110 22334', 'India', 'Family Journey', 'September 2026', '1 night', 2, 1, 'Please advise me', 'Udaipur airport / city', 'lost', 'Dates did not work out this year.', null, ''],
            [330, 'Hannah Okafor', 'hannah.okafor@example.com', '+234 803 555 0101', 'Nigeria', 'Leopard Tracking, Rabari Cultural Walk', 'March 2027', '2 nights', 1, 0, 'Boutique Stone Villa', 'Udaipur airport / city', 'qualified', 'Solo traveller — happy to share a vehicle if needed.', 4, 'google'],
            [400, 'Nisha & Sameer Shah', 'shahfamily@example.com', '+91 98250 66778', 'India', 'Family Journey, Wetland & Birding', 'Christmas 2026', '3 nights', 2, 2, 'Luxury Wilderness Tented Camp', 'Ahmedabad', 'proposal_sent', 'Grandparents travelling too — need easy access rooms.', 3, ''],
            [500, 'Daniel Rossi', 'd.rossi@example.it', '+39 347 123 4567', 'Italy', 'Wildlife Photography', 'April 2027', '4+ nights', 1, 0, 'Please advise me', 'Jodhpur airport / city', 'contacted', 'Professional photographer working on a big cat book.', 5, ''],
            [610, 'Meera Pillai', 'meera.p@example.com', '+91 94470 33221', 'India', 'Leopard Tracking', 'August 2026', '2 nights', 2, 0, 'Heritage Haveli / Fort', 'Falna railway station', 'completed', '', null, 'instagram'],
            [700, 'Website visitor', 'question@example.com', '', 'India', '', '', '', 0, 0, '', '', 'contacted', 'Do you offer safaris during the monsoon?', null, ''],
        ];
        $notes = [
            'contacted' => 'Called on WhatsApp — sending options for stays and drive timings.',
            'qualified' => 'Dates and party confirmed. Preparing a tailored itinerary.',
            'proposal_sent' => 'Proposal sent with two accommodation options.',
            'follow_up' => 'Guest asked about birding at Jawai Bandh — follow up with crane sightings update.',
            'confirmed' => 'Deposit received. Vehicle and Captain allocated.',
            'completed' => 'Wonderful trip — two leopard sightings and a sundowner. Thank-you email sent.',
            'lost' => 'Guest postponed to next season.',
        ];
        $n = 0;
        foreach (array_reverse($rows) as [$h, $name, $email, $phone, $country, $interests, $window, $nights, $adults, $children, $acc, $transfer, $status, $msg, $follow, $utm]) {
            $n++;
            $created = date('Y-m-d H:i:s', time() - $h * 3600);
            $type = $interests === '' ? 'contact' : 'journey';
            $eid = DB::insert('enquiries', [
                'code' => 'DEMO-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT), 'type' => $type, 'full_name' => $name, 'email' => $email,
                'phone' => $phone, 'country' => $country, 'interests' => $interests, 'travel_window' => $window, 'nights' => $nights,
                'adults' => $adults, 'children' => $children, 'private_vehicle' => $type === 'journey' ? 'Yes' : '', 'accommodation' => $acc,
                'transfer' => $transfer, 'message' => $msg, 'source_page' => $type === 'journey' ? '/plan-your-journey/' : '/contact/',
                'utm_source' => $utm, 'utm_medium' => $utm ? ($utm === 'google' ? 'cpc' : 'social') : '', 'utm_campaign' => $utm ? 'season-2026' : '',
                'ip' => '127.0.0.1', 'user_agent' => 'demo', 'status' => $status, 'assigned_user_id' => $status === 'new' ? null : $userId,
                'follow_up_date' => $follow === null ? null : date('Y-m-d', strtotime(($follow >= 0 ? '+' : '') . $follow . ' days')),
                'first_contacted_at' => $status === 'new' ? null : date('Y-m-d H:i:s', strtotime($created) + (2 + $n % 7) * 3600),
                'created_at' => $created, 'updated_at' => $created,
            ]);
            if (isset($notes[$status]) && $userId) {
                DB::insert('enquiry_notes', ['enquiry_id' => $eid, 'user_id' => $userId, 'note' => $notes[$status], 'created_at' => date('Y-m-d H:i:s', strtotime($created) + 5 * 3600)]);
            }
        }
        return $n;
    }

    public static function hasEnquiries(): int
    {
        return (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE code LIKE 'DEMO-%'");
    }

    public static function clearEnquiries(): int
    {
        $ids = array_map('intval', array_column(DB::all("SELECT id FROM enquiries WHERE code LIKE 'DEMO-%'"), 'id'));
        if ($ids) {
            $in = implode(',', $ids);
            DB::q("DELETE FROM enquiry_notes WHERE enquiry_id IN ($in)");
            DB::q("DELETE FROM enquiries WHERE id IN ($in)");
        }
        return count($ids);
    }
}
