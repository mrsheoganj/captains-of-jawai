<?php
declare(strict_types=1);

namespace App\Core;

/**
 * The site's photo layout: one distinct, real Jawai photograph per spot on the website.
 * Photos come from Wikimedia Commons (CC BY / BY-SA / CC0, credited on /photo-credits/).
 * Similar shots (more frames of the same leopard, more flamingo pictures) go to the Gallery only,
 * never to a second spot on the website.
 *
 * apply() runs once per PLAN_VERSION from a deploy (see Updater). It imports in small batches so each
 * web request stays short on shared hosting; call it again until it reports nothing pending.
 */
final class ImagePlan
{
    public const PLAN_VERSION = 3;

    /** Photos imported per call (each one is downloaded and resized into 3 sizes x 2 formats). */
    private const BATCH = 12;

    /**
     * [Commons file name, alt text, gallery category ('' = not in the gallery), spots]
     * List spots (hero_images, jawai_images, village_images) take the photos in the order listed here.
     */
    public const PHOTOS = [
        // Homepage slider
        ['Breathtaking_Jawai.jpg', 'Jawai Dam reservoir and the granite hills of Jawai, Rajasthan', 'Landscape', ['hero_images']],
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_24.jpg', 'Leopard resting on a granite boulder in Jawai Bandh', 'Leopards', ['hero_images', 'seo_og_image']],
        ['JAWAI_BERA_LEOPARD_KING.jpg', 'Leopard silhouetted on a granite hill at sunset in Bera, Jawai', 'Leopards', ['hero_images']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_28.jpg', 'Dusk over the Jawai reservoir with a safari jeep on the shore', 'Landscape', ['hero_images']],
        // Homepage intro and safaris
        ['JAWAI_BERA_LEOPARD.4.jpg', 'Leopard standing in dry grass on a rocky hillside in Bera, Jawai', 'Leopards', ['intro_image']],
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_13.jpg', 'Leopard walking across a granite slab in Jawai Bandh', 'Leopards', ['safari:leopard-safari']],
        ['Asian_Woolly-necked_Stork_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_01.jpg', 'Woolly-necked stork at the water\'s edge in Jawai Bandh', 'Birds & Wetland', ['safari:dam-birding-safari']],
        ['Padma_a_female_leopard_from_Jawai_giving_close_sighting_to_tourists_during_safari.jpg', 'Padma, a female leopard of Jawai, on a granite rock during a safari', 'Safari Life', ['safari:private-safari']],
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_21.jpg', 'Leopard looking out from a boulder among the bushes in Jawai Bandh', 'Leopards', ['safari:photography-safari']],
        // "Jawai in pictures"
        ['JAWAI_BERA.6.jpg', 'Still water and smooth granite at the edge of the Jawai reservoir', 'Landscape', ['jawai_images']],
        ['The_view_of_Sena_Village_of_Jawai,_Rajasthan.jpg', 'Sena village among the hills of Jawai', 'Village life', ['jawai_images']],
        ['A_Rabari_Shepherd_with_his_herd_of_livestock_at_Jawai,_Rajasthan,_India.jpg', 'A Rabari shepherd with his herd of livestock at Jawai', 'Village life', ['jawai_images']],
        ['Mugger_crocodile_by_Tisha_Mukherjee_01.jpg', 'Mugger crocodile in the shallows of Jawai Bandh', 'Wildlife', ['jawai_images']],
        ['JAWAI_BERA.8.jpg', 'Peacock among the granite rocks of Bera, Jawai', 'Birds & Wetland', ['jawai_images']],
        // Seasons
        ['Greater_Flamingo_by_Tisha_Mukherjee_08.jpg', 'Greater flamingos wading in Jawai Bandh in winter', 'Birds & Wetland', ['season_winter_image']],
        ['Shelter_by_Jawai.jpg', 'Low water and sheep grazing on the Jawai Dam shore in the dry season', 'Landscape', ['season_summer_image']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_37.jpg', 'Monsoon clouds over a full Jawai reservoir and green shores', 'Landscape', ['season_monsoon_image']],
        // Booking, coexistence, closing banner
        ['Maruti_Gypsy,_Pug_Mark,_Kanha.jpg', 'Open 4x4 Gypsy safari vehicle', '', ['booking_image']],
        ['JAWAI_BERA_LEOPARD_AND_THE_PRIEST.jpg', 'A temple priest on a granite hill in Bera, with a leopard resting on the rocks below', 'Village life', ['coexist_image']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_09.jpg', 'Sunset over the Jawai reservoir and its granite islands', 'Landscape', ['cta_image']],
        // Village life
        ['JAWAI_BERA_LOCAL.6.jpg', 'A villager of Bera, Jawai, in a red turban', 'Village life', ['village_images']],
        ['JAWAI_BERA_LOCALS.jpg', 'Villagers gathered in the shade of a tree in Bera', 'Village life', ['village_images']],
        ['MOTHER_AND_DAUGHTER_JAWAI_BERA.jpg', 'Mother and daughter in traditional Marwari dress in Bera, Jawai', 'Village life', ['village_images']],
        ['JAWAI_BERA_BULLOCK_CART.jpg', 'Bullock cart on a village track in Bera, Jawai', 'Village life', ['village_images']],
        ['JAWAI_BERA_TUFF_HUMAN_LIFE.jpg', 'A woman carrying a load along a village path in Bera', 'Village life', ['village_images']],
        ['JAWAI_BERA_LOCAL_HUT.jpg', 'A village home with cattle in Bera, Jawai', 'Village life', ['village_images']],
        ['JAWAI_BERA_AUTO_RIDE.jpg', 'Village women sharing an auto ride in Bera, Jawai', 'Village life', ['village_images']],
        ['JAWAI_BERA.23.jpg', 'A villager with his bicycle on a lane in Bera', 'Village life', ['village_images']],
        // Page banners
        ['JAWAI_BERA_LEOPARD.2.jpg', 'Leopard lying in dry grass in Bera, Jawai', 'Leopards', ['img_safaris']],
        ['JawaiLeopards.jpg', 'Female leopard of Sena resting in a red granite cave', 'Leopards', ['img_gallery']],
        ['JAWAI_BERA.11.jpg', 'Granite cliff and a bare tree in Bera, Jawai', 'Landscape', ['img_faq']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_43.jpg', 'Granite islands in the Jawai reservoir under monsoon clouds', 'Landscape', ['img_contact']],
        ['On_the_rocks,_its_refreshing.jpg', 'Leopard on a granite rock near Bera', 'Leopards', ['img_plan']],
        ['Shepherd_from_Rajasthan_03.jpg', 'Shepherd in a red turban waving at sunset, Jawai Bandh', 'Village life', ['img_about', 'page:about']],
        ['JAWAI_BERA.31.jpg', 'Granite hill and scrub near Bera, Jawai', 'Landscape', ['img_404']],
        ['JAWAI_BERA_LEOPARD.jpg', 'Leopard on a rock at night in Bera, Jawai', 'Leopards', ['img_login']],
        // Jawai guide (also the "Before you come" cards on the homepage)
        ['JAWAI_BERA.9.jpg', 'Granite hills around Bera, Jawai', 'Landscape', ['page:jawai']],
        ['JAWAI_BERA_LEOPARD.6.jpg', 'Leopard sitting in dry golden grass in Bera, Jawai', 'Leopards', ['page:jawai/leopards']],
        ['Jawai_bandh.jpg', 'Jawai Bandh reservoir with the Aravalli hills behind', 'Landscape', ['page:jawai/jawai-dam']],
        ['Golden_jackal_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_02.jpg', 'Golden jackal running across open ground in Jawai Bandh', 'Wildlife', ['page:jawai/wildlife']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_31.jpg', 'Green granite hill with grazing cattle in Jawai during the monsoon', 'Landscape', ['page:jawai/best-time-to-visit']],
        ['JAWAI_BERA.7.jpg', 'Dirt road through the open country of Bera, Jawai', 'Landscape', ['page:jawai/how-to-reach']],
        ['RANAKPUR_JAIN_TEMPLE.JPG', 'Ranakpur Jain temple in Pali district, Rajasthan', 'Places', ['page:jawai/places-to-visit']],
        ['JAWAI_BERA.25.jpg', 'Village women in Bera, Jawai, carrying water pots', 'Village life', ['page:jawai/culture']],
        ['A_Flame_of_the_forest_in_bloom_and_a_Leopard_on_the_background_at_Jawai_Hills_during_early_morning.jpg', 'Flame of the forest in bloom with a leopard on the Jawai hills behind', 'Leopards', ['page:about/conservation-ethics']],
        ['Jawai_more_than_a_dam.jpg', 'Sheep grazing on the shore of Jawai Dam', 'Village life', ['page:experiences/rabari-culture']],
        ['JAWAI_BANDH_SUNSET.jpg', 'Sun setting over Jawai Bandh', 'Landscape', ['page:experiences/boulder-sundowners']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_82.jpg', 'Granite hill above green monsoon fields in Jawai', 'Landscape', ['page:privacy-policy']],
        ['The_Jawai_landscape_during_monsoon,_Pali_District,_Rajasthan,_India_71.jpg', 'Granite boulders and green scrub in Jawai', 'Landscape', ['page:terms']],
        ['JAWAI_BERA.5.jpg', 'Smooth granite slabs under a big sky in Bera, Jawai', 'Landscape', ['page:cookie-policy']],
        // Gallery only: similar shots stay out of the website sections
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_09.jpg', 'Leopard on the move over granite in Jawai Bandh', 'Leopards', []],
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_20.jpg', 'Leopard on a boulder in Jawai Bandh', 'Leopards', []],
        ['Indian_leopard_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_28.jpg', 'Leopard dozing on warm granite in Jawai Bandh', 'Leopards', []],
        ['JAWAI_BERA_LEOPARD.7.jpg', 'Leopard resting at the mouth of a granite cave in Bera', 'Leopards', []],
        ['JAWAI_BERA_LEOPARD.3.jpg', 'Leopard lying in thorn scrub in Bera, Jawai', 'Leopards', []],
        ['Jawai_Dam.jpg', 'Jawai Dam and the granite hills in the dry season', 'Landscape', []],
        ['Greater_Flamingo_by_Tisha_Mukherjee_07.jpg', 'Greater flamingos spreading their wings in Jawai Bandh', 'Birds & Wetland', []],
        ['Greater_Flamingo_by_Tisha_Mukherjee_14.jpg', 'Greater flamingos taking off over Jawai Bandh', 'Birds & Wetland', []],
        ['FLEMINGO_JAWAI_BERA.jpg', 'A flamingo stepping through the shallows at Jawai', 'Birds & Wetland', []],
        ['Asian_green_bee-eater_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_03.jpg', 'Green bee-eater with its catch in Jawai Bandh', 'Birds & Wetland', []],
        ['Crocodile_of_jawai.jpg', 'Mugger crocodile on the shore of Jawai Dam', 'Wildlife', []],
        ['Golden_jackal_in_Jawai_Bandh_April_2025_by_Tisha_Mukherjee_01.jpg', 'Golden jackal running along a track in Jawai Bandh', 'Wildlife', []],
    ];

    /** Earlier imports taken outside Jawai: kept in the media library, removed from the Gallery. */
    private const NOT_JAWAI = [
        'Mugger_Crocodile_(Crocodylus_palustris)_at_National_Chambal', 'Greater_Flamingo,_Sangli', 'Sarus_Crane_by_Tisha_Mukherjee_05',
        'Bar-headed_Goose_by_Dr._Raju_Kasambe', 'Striped_hyena_in_Bhigwan', 'A_fragile_co-existence', 'Chinkara_in_Tal_Chhapar',
        'Nilgai_(Boselaphus_tragocamelus)', 'A_rabari_with_his_cattles', 'Maruti_Gypsy',
    ];

    public static function marker(): string
    {
        return STORAGE_PATH . '/.images-planned-v' . self::PLAN_VERSION;
    }

    public static function done(): bool
    {
        return is_file(self::marker());
    }

    /**
     * Remove exact duplicates, import the next batch, and once everything is in, put each photo in its spot.
     * @return array{0:string[],1:bool} [log lines, finished]
     */
    public static function apply(): array
    {
        $log = [];
        $removed = self::dedupe();
        if ($removed) {
            $log[] = "Removed $removed duplicate photo(s) from the media library (files moved to private/storage/removed-media/).";
        }
        [$ids, $missing, $msgs] = self::importBatch();
        foreach ($msgs as $m) {
            $log[] = '  note: ' . $m;
        }
        if ($missing > 0) {
            $log[] = count($ids) . " photos ready, $missing still to import. PHOTOS-PENDING";
            return [$log, false];
        }
        $log[] = count($ids) . ' of ' . count(self::PHOTOS) . ' planned photos are in the media library.';
        $log[] = 'Placed photos in ' . self::place($ids) . ' spots (one distinct photo per spot).';
        $log[] = self::curateGallery($ids);
        @file_put_contents(self::marker(), date('c'));
        return [$log, true];
    }

    /** @return array{0:array<string,int>,1:int,2:string[]} [file => media id, still missing, messages] */
    private static function importBatch(): array
    {
        $ids = [];
        $todo = [];
        foreach (self::PHOTOS as [$file]) {
            $id = self::findByFile($file);
            if ($id) {
                $ids[$file] = $id;
            } else {
                $todo[] = $file;
            }
        }
        $msgs = [];
        $failed = 0;
        if ($todo) {
            @set_time_limit(600);
            [$got, $msgs] = PhotoImporter::importFiles(array_slice($todo, 0, self::BATCH));
            $ids += $got;
            $failed = count(array_slice($todo, 0, self::BATCH)) - count($got);
        }
        // Photos that could not be fetched (e.g. renamed on Commons) do not block the rest.
        $missing = max(0, count($todo) - min(count($todo), self::BATCH));
        if ($failed && !$missing) {
            $msgs[] = "$failed photo(s) could not be imported; their spots keep their current image.";
        }
        $alts = array_column(self::PHOTOS, 1, 0);
        foreach ($ids as $file => $id) {
            DB::update('media', ['alt_text' => $alts[$file], 'caption' => $alts[$file]], 'id = ?', [$id]);
        }
        return [$ids, $missing, $msgs];
    }

    private static function findByFile(string $file): ?int
    {
        $page = 'https://commons.wikimedia.org/wiki/File:' . str_replace('%2F', '/', rawurlencode($file));
        $id = DB::val('SELECT id FROM media WHERE source_url = ? OR source_url = ? ORDER BY id LIMIT 1', [$page, 'https://commons.wikimedia.org/wiki/File:' . $file]);
        if ($id) {
            return (int) $id;
        }
        // Fallback: decode stored URLs (Commons encodes some characters differently).
        foreach (DB::all("SELECT id, source_url FROM media WHERE source_url LIKE 'https://commons.wikimedia.org/wiki/File:%' ORDER BY id") as $m) {
            if (str_replace(' ', '_', rawurldecode(substr((string) $m['source_url'], 40))) === $file) {
                return (int) $m['id'];
            }
        }
        return null;
    }

    /** @param array<string,int> $ids */
    private static function place(array $ids): int
    {
        $n = 0;
        $lists = [];
        foreach (self::PHOTOS as [$file, , , $slots]) {
            $id = $ids[$file] ?? null;
            if (!$id) {
                continue;
            }
            foreach ($slots as $slot) {
                if (str_ends_with($slot, '_images')) {
                    $lists[$slot][] = $id;
                } elseif (preg_match('/^(page|safari):(.+)$/', $slot, $m)) {
                    DB::update($m[1] === 'page' ? 'pages' : 'safaris', ['image_id' => $id], 'slug = ?', [$m[2]]);
                    $n++;
                } else {
                    Settings::set($slot, (string) $id);
                    $n++;
                }
            }
        }
        foreach ($lists as $key => $list) {
            Settings::set($key, implode(',', array_values(array_unique($list))));
            $n += count($list);
        }
        Settings::flush();
        return $n;
    }

    /** Gallery: the planned Jawai photos (in plan order) plus the team's own uploads; nothing from outside Jawai, no demo photos. */
    private static function curateGallery(array $ids): string
    {
        $cats = array_column(self::PHOTOS, 2, 0);
        $order = 0;
        $shown = 0;
        foreach (self::PHOTOS as [$file]) {
            if (!isset($ids[$file])) {
                continue;
            }
            $in = $cats[$file] !== '';
            DB::update('media', ['in_gallery' => $in ? 1 : 0, 'gallery_category' => $cats[$file], 'sort_order' => 100 + ++$order], 'id = ?', [$ids[$file]]);
            $shown += $in ? 1 : 0;
        }
        DB::q("UPDATE media SET in_gallery = 0 WHERE path LIKE 'assets/photos/%'");
        $hidden = 0;
        foreach (DB::all("SELECT id, source_url FROM media WHERE source_url <> ''") as $m) {
            $u = str_replace(' ', '_', rawurldecode((string) $m['source_url']));
            foreach (self::NOT_JAWAI as $needle) {
                if (str_contains($u, $needle) && !in_array((int) $m['id'], $ids, true)) {
                    DB::update('media', ['in_gallery' => 0], 'id = ?', [$m['id']]);
                    $hidden++;
                    break;
                }
            }
        }
        return "Gallery: $shown Jawai photos in order; $hidden photo(s) taken outside Jawai and the demo photos removed from the gallery (still in the media library).";
    }

    /**
     * Exact duplicates in the media library (same Commons source, or the same image file) are merged into
     * the oldest copy: every page, safari and setting that used a copy now uses the original, then the copy
     * is deleted and its files moved to private/storage/removed-media/. Returns the number removed.
     */
    public static function dedupe(): int
    {
        $keep = [];
        $dupes = [];
        foreach (DB::all("SELECT id, path, mime, source_url FROM media WHERE path NOT LIKE 'assets/photos/%' ORDER BY id") as $m) {
            $keys = [];
            if ($m['source_url'] !== '') {
                $keys[] = 'src:' . str_replace(' ', '_', rawurldecode((string) $m['source_url']));
            }
            $f = PUBLIC_PATH . '/' . $m['path'] . '-400.' . Media::ext($m);
            if (is_file($f)) {
                $keys[] = 'sha:' . sha1_file($f);
            }
            $orig = null;
            foreach ($keys as $k) {
                if (isset($keep[$k])) {
                    $orig = $keep[$k];
                    break;
                }
            }
            if ($orig) {
                $dupes[(int) $m['id']] = $orig;
            } else {
                foreach ($keys as $k) {
                    $keep[$k] = (int) $m['id'];
                }
            }
        }
        if (!$dupes) {
            return 0;
        }
        foreach ($dupes as $dup => $orig) {
            DB::q('UPDATE pages SET image_id = ? WHERE image_id = ?', [$orig, $dup]);
            DB::q('UPDATE safaris SET image_id = ? WHERE image_id = ?', [$orig, $dup]);
        }
        foreach (DB::all("SELECT skey, svalue FROM settings WHERE skey LIKE 'img\\_%' OR skey LIKE '%\\_image' OR skey LIKE '%\\_images'") as $s) {
            $vals = array_map('trim', explode(',', (string) $s['svalue']));
            $new = array_values(array_unique(array_map(fn ($v) => ctype_digit($v) && isset($dupes[(int) $v]) ? (string) $dupes[(int) $v] : $v, $vals)));
            if ($new !== $vals) {
                Settings::set($s['skey'], implode(',', $new));
            }
        }
        $bin = STORAGE_PATH . '/removed-media';
        @mkdir($bin, 0700, true);
        @file_put_contents($bin . '/.htaccess', "Require all denied\n");
        foreach (array_keys($dupes) as $dup) {
            $m = Media::find($dup);
            if ($m) {
                foreach (glob(PUBLIC_PATH . '/' . $m['path'] . '-*') ?: [] as $file) {
                    @rename($file, $bin . '/' . basename($file));
                }
            }
            DB::delete('media', 'id = ?', [$dup]);
        }
        Settings::flush();
        return count($dupes);
    }
}
