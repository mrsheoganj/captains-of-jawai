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
            'seo_og_image' => 'leopard1', 'img_safaris' => 'leopard2', 'booking_image' => 'safari-jeep',
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
        foreach (['hero_images' => ['leopard1', 'landscape1', 'leopard2', 'bird1'], 'values_images' => ['leopard-stalking', 'granite-boulders', 'safari-guests', 'leopard-basking'], 'jawai_images' => ['landscape1', 'granite-boulders', 'flamingo-flock', 'sunset-valley', 'lake-reflection']] as $key => $names) {
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
                'about' => 'safari-guests', 'experiences/rabari-culture' => 'granite-dome-dusk',
                'experiences/boulder-sundowners' => 'sunset-valley', 'about/conservation-ethics' => 'leopard-portrait',
                'privacy-policy' => 'lake-reflection', 'terms' => 'granite-boulders', 'cookie-policy' => 'landscape1',
            ] + JawaiGuide::IMAGES,
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
            // [hours ago, name, email, phone, country, safaris, dates, drives, adults, children, drive time, (unused), status, message, follow-up days, utm]
            [2, 'Ananya Mehta', 'ananya.mehta@example.com', '+91 98200 11223', 'India', 'Classic Leopard Tracking Safari, Sundowner on the rocks after an evening drive', 'December 2026 (flexible)', '3 drives', 2, 0, 'Morning (sunrise)', '', 'new', 'Anniversary trip - would love a private sundowner on the rocks.', null, 'instagram'],
            [5, 'James Whitfield', 'j.whitfield@example.co.uk', '+44 7700 900123', 'United Kingdom', 'Classic Leopard Tracking Safari, Wildlife Photography Expedition', 'February 2027', '4 or more drives', 2, 0, 'Evening (sunset)', '', 'new', 'Keen photographers with 600mm lenses. Is a vehicle for just the two of us possible?', null, 'google'],
            [9, 'Rohan & Kavya Iyer', 'rohan.iyer@example.com', '+91 99860 44551', 'India', 'Master Naturalist Private 4x4 Expedition, Classic Leopard Tracking Safari, Jawai Bandh Wetland & Raptor Drive', 'Diwali week, November 2026', '3 drives', 2, 2, 'Morning and evening', '', 'contacted', 'Kids are 7 and 10 - shorter drives please.', 2, ''],
            [26, 'Sophie Laurent', 'sophie.laurent@example.fr', '+33 6 12 34 56 78', 'France', 'Classic Leopard Tracking Safari', 'January 2027 (flexible)', '4 or more drives', 2, 0, 'No preference', '', 'qualified', 'Staying at a hotel near Bera. Two evening drives if possible.', 1, 'google'],
            [50, 'Vikram Singhania', 'vikram.s@example.com', '+91 98200 12345', 'India', 'Classic Leopard Tracking Safari, Wildlife Photography Expedition', '15–18 November 2026', '4 or more drives', 2, 1, 'Morning (sunrise)', '', 'proposal_sent', 'Interested in a dedicated private 4x4 with beanbag mounts.', 0, 'google'],
            [76, 'Emma & Lukas Becker', 'becker.travel@example.de', '+49 151 23456789', 'Germany', 'Jawai Bandh Wetland & Raptor Drive, Classic Leopard Tracking Safari', 'Late December 2026', '4 or more drives', 2, 0, 'Evening (sunset)', '', 'follow_up', 'Birders - hoping for demoiselle cranes and flamingos.', -1, ''],
            [120, 'Priya Nair', 'priya.nair@example.com', '+91 90040 77889', 'India', 'Classic Leopard Tracking Safari', '10–12 October 2026', '3 drives', 4, 0, 'Morning and evening', '', 'confirmed', 'Group of friends from Mumbai.', null, 'instagram'],
            [190, 'Michael Chen', 'm.chen@example.com', '+1 415 555 0142', 'United States', 'Classic Leopard Tracking Safari, Wildlife Photography Expedition, Sundowner on the rocks after an evening drive', 'Early October 2026', '4 or more drives', 2, 0, 'No preference', '', 'completed', 'Celebrating a milestone birthday.', null, ''],
            [260, 'Arjun Kapoor', 'arjun.k@example.com', '+91 98110 22334', 'India', 'Master Naturalist Private 4x4 Expedition', 'September 2026', '2 drives', 2, 1, 'Morning (sunrise)', '', 'lost', 'Dates did not work out this year.', null, ''],
            [330, 'Hannah Okafor', 'hannah.okafor@example.com', '+234 803 555 0101', 'Nigeria', 'Classic Leopard Tracking Safari', 'March 2027', '3 drives', 1, 0, 'Evening (sunset)', '', 'qualified', 'Solo traveller - happy to share a vehicle if needed.', 4, 'google'],
            [400, 'Nisha & Sameer Shah', 'shahfamily@example.com', '+91 98250 66778', 'India', 'Master Naturalist Private 4x4 Expedition, Jawai Bandh Wetland & Raptor Drive', 'Christmas 2026', '4 or more drives', 2, 2, 'Morning and evening', '', 'proposal_sent', 'Grandparents travelling too - need easy access rooms.', 3, ''],
            [500, 'Daniel Rossi', 'd.rossi@example.it', '+39 347 123 4567', 'Italy', 'Wildlife Photography Expedition', 'April 2027', '4 or more drives', 1, 0, 'No preference', '', 'contacted', 'Professional photographer working on a big cat book.', 5, ''],
            [610, 'Meera Pillai', 'meera.p@example.com', '+91 94470 33221', 'India', 'Classic Leopard Tracking Safari', 'August 2026', '3 drives', 2, 0, 'Morning (sunrise)', '', 'completed', '', null, 'instagram'],
            [700, 'Website visitor', 'question@example.com', '', 'India', '', '', '', 0, 0, '', '', 'contacted', 'Do you offer safaris during the monsoon?', null, ''],
        ];
        $notes = [
            'contacted' => 'Called on WhatsApp. Sending drive timings and the meeting point.',
            'qualified' => 'Date and group size confirmed. Checking vehicle availability.',
            'proposal_sent' => 'Availability and price sent for the requested drives.',
            'follow_up' => 'Guest asked about birding at Jawai Bandh. Follow up with a crane sightings update.',
            'confirmed' => 'Deposit received. Vehicle and Captain allocated.',
            'completed' => 'Good drives: two leopard sightings and a sundowner. Thank-you email sent.',
            'lost' => 'Guest postponed to next season.',
        ];
        $n = 0;
        foreach (array_reverse($rows) as [$h, $name, $email, $phone, $country, $interests, $window, $drives, $adults, $children, $driveTime, , $status, $msg, $follow, $utm]) {
            $n++;
            $created = date('Y-m-d H:i:s', time() - $h * 3600);
            $type = $interests === '' ? 'contact' : 'journey';
            $eid = DB::insert('enquiries', [
                'code' => 'DEMO-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT), 'type' => $type, 'full_name' => $name, 'email' => $email,
                'phone' => $phone, 'country' => $country, 'interests' => $interests, 'travel_window' => $window, 'drives' => $drives,
                'adults' => $adults, 'children' => $children, 'private_vehicle' => $type === 'journey' ? 'Yes' : '', 'drive_time' => $driveTime,
                'message' => $msg, 'source_page' => $type === 'journey' ? '/book-a-safari/' : '/contact/',
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
