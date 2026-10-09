<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Starter content written from the project research in /Details/docs.
 * Everything here is editable in the admin panel. No prices, phone numbers,
 * reviews or guaranteed-sighting claims are invented (see CLIENT_INPUT_REQUIRED.md).
 */
final class Seeder
{
    public static function run(): void
    {
        $now = now();
        if ((int) DB::val('SELECT COUNT(*) FROM media') === 0) {
            $photos = [
                'leopard1' => 'Leopard resting on a granite boulder at golden hour',
                'leopard2' => 'Leopard walking along a rocky granite ridge at sunset',
                'landscape1' => 'Granite hills and the waters of Jawai Bandh',
                'bird1' => 'Flamingos wading in the shallows of Jawai Dam',
                'safari1' => 'Guests on an open 4x4 safari among the granite kopjes',
            ];
            foreach ($photos as $name => $alt) {
                DB::insert('media', [
                    'path' => 'assets/photos/' . $name, 'filename' => $name . '.jpg', 'mime' => 'image/jpeg',
                    'width' => 1600, 'height' => 893, 'filesize' => 0, 'alt_text' => $alt, 'caption' => '',
                    'credit' => 'Placeholder', 'license' => 'Placeholder — replace with authentic Jawai photography before launch',
                    'in_gallery' => 1, 'gallery_category' => str_starts_with($name, 'leopard') ? 'Leopards' : (str_starts_with($name, 'bird') ? 'Birds & Wetland' : 'Landscape'),
                    'sort_order' => 0, 'created_at' => $now,
                ]);
            }
        }
        $img = [];
        foreach (DB::all('SELECT id, path FROM media') as $m) {
            $img[basename($m['path'])] = (int) $m['id'];
        }
        $i = fn (string $n) => $img[$n] ?? null;

        if (!(int) DB::val('SELECT COUNT(*) FROM settings')) {
            Settings::setMany([
                'hero_images' => implode(',', array_filter([$i('leopard1'), $i('landscape1'), $i('leopard2')])),
                'intro_image' => $i('leopard2'),
                'coexist_image' => $i('landscape1'),
                'cta_image' => $i('safari1'),
            ]);
        }

        if (!(int) DB::val('SELECT COUNT(*) FROM safaris')) {
            foreach (self::safaris($i) as $n => $s) {
                DB::insert('safaris', $s + ['sort_order' => $n + 1, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        if (!(int) DB::val('SELECT COUNT(*) FROM journeys')) {
            foreach (self::journeys($i) as $n => $s) {
                DB::insert('journeys', $s + ['sort_order' => $n + 1, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        if (!(int) DB::val('SELECT COUNT(*) FROM posts')) {
            foreach (self::posts($i) as $n => $s) {
                $date = date('Y-m-d H:i:s', strtotime('-' . ($n * 9 + 2) . ' days'));
                DB::insert('posts', $s + ['author_name' => 'The Captains', 'status' => 'published', 'published_at' => $date, 'created_at' => $date, 'updated_at' => $date]);
            }
        }
        if (!(int) DB::val('SELECT COUNT(*) FROM pages')) {
            foreach (self::pages($i) as $s) {
                DB::insert('pages', $s + ['status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        if (!(int) DB::val('SELECT COUNT(*) FROM faqs')) {
            foreach (self::faqs() as $n => [$cat, $q, $a]) {
                DB::insert('faqs', ['category' => $cat, 'question' => $q, 'answer' => $a, 'sort_order' => $n + 1, 'status' => 'published']);
            }
        }
        if (!(int) DB::val('SELECT COUNT(*) FROM menu_items')) {
            self::menus();
        }
    }

    private static function safaris(callable $i): array
    {
        return [
            [
                'title' => 'Classic Leopard Tracking Safari', 'slug' => 'leopard-safari', 'category' => 'Apex Predator',
                'tagline' => 'Dawn and dusk among the granite caves of the leopard hills.',
                'excerpt' => 'Follow alarm calls, pugmarks and the warmth of sun-struck granite with a master tracker, as resident leopards surface on the rocks at first and last light.',
                'duration' => '3–3.5 hours', 'timing' => 'Morning 05:45–09:15 · Evening 16:15–19:30 (seasonal)', 'best_season' => 'October – March',
                'group_size' => 'Private vehicle, up to 6 guests', 'image_id' => $i('leopard1'), 'is_featured' => 1,
                'highlights' => "Private open 4x4 with an experienced local tracker\nTracking by langur and peafowl alarm calls\nEngine-off observation at ethical distances\nBinoculars on board, beanbags for photographers\nHot tea or coffee served in the field",
                'body' => '<h2>The anatomy of a tracking drive</h2><p>Long before sunrise, your Captain is already listening. In Jawai the leopards do not hide in dense forest — they rest on and inside the great granite domes, and the land itself tells you where they are. A langur barking from a ridge, a peacock\'s sharp alarm, fresh pugmarks in a sandy riverbed: each sign is read and weighed.</p><p>As the sun warms the rock, leopards often emerge onto ledges and cave mouths to bask. We position the vehicle quietly, switch off the engine and simply wait. Patience is the whole art.</p><h2>Ethical distance standards</h2><p>We never block an animal\'s path, never crowd a sighting and never use calls or bait. Engines are switched off during observation and we leave before the animal is disturbed. A leopard sighting is never guaranteed — any operator who promises one is misleading you — but the open granite terrain gives Jawai one of the highest daytime observation probabilities anywhere.</p><h2>What to bring</h2><ul><li>Neutral clothing — khaki, olive, stone. Warm layers for winter mornings (it can be 8–12°C at dawn).</li><li>A 100–400mm or 70–200mm lens for photographers; we carry beanbags.</li><li>Hat, sunscreen, and a scarf for dusty tracks.</li></ul>',
                'meta_title' => 'Jawai Leopard Safari in Bera | Private 4x4 Tracking',
                'meta_description' => 'Experience wild leopards on ancient granite kopjes. Private 4x4 jeeps, dawn and dusk drives, expert local trackers in Jawai and Bera.',
            ],
            [
                'title' => 'Jawai Bandh Wetland & Raptor Drive', 'slug' => 'dam-birding-safari', 'category' => 'Avian Wetland',
                'tagline' => 'Flamingos, cranes and basking mugger crocodiles on the great reservoir.',
                'excerpt' => 'A slower, scope-led exploration of the reservoir shoreline — winter flamingo flocks, demoiselle cranes, raptors and the resident marsh crocodiles.',
                'duration' => '3 hours', 'timing' => 'Mid-morning or late afternoon', 'best_season' => 'November – February for migrants',
                'group_size' => 'Private vehicle, up to 6 guests', 'image_id' => $i('bird1'), 'is_featured' => 1,
                'highlights' => "Greater flamingo, demoiselle crane and bar-headed goose (winter)\nMugger crocodiles basking on sandy shoals\nOsprey, eagles and falcons over the water\nSpotting scope and field guides on board",
                'body' => '<h2>The largest reservoir in western Rajasthan</h2><p>Commissioned by Maharaja Umaid Singh of Jodhpur in 1946 and completed in 1957, Jawai Bandh is a lifeline for people and wildlife alike. Each winter it becomes a staging ground on the Central Asian Flyway.</p><h2>Key species</h2><ul><li>Greater and lesser flamingo</li><li>Demoiselle crane and bar-headed goose</li><li>Painted stork, Eurasian spoonbill, river tern</li><li>Osprey, steppe and tawny eagle, peregrine falcon</li><li>Mugger (marsh) crocodile</li></ul><h2>The experience</h2><p>We work the water\'s edge slowly with binoculars and a spotting scope, stopping where the light and birds are best. It pairs beautifully with an evening leopard drive on the same day.</p>',
                'meta_title' => 'Jawai Dam Safari | Crocodiles & Migratory Bird Watching',
                'meta_description' => 'Mugger crocodiles and winter flamingos at Jawai Bandh. Specialist wetland birding drives with spotting optics and an expert naturalist.',
            ],
            [
                'title' => 'Master Naturalist Private 4x4 Expedition', 'slug' => 'private-safari', 'category' => 'Bespoke',
                'tagline' => 'Your vehicle, your Captain, your pace.',
                'excerpt' => 'A fully private expedition designed around you — flexible timings, longer sightings, boulder-top sundowners and a senior naturalist dedicated to your party.',
                'duration' => 'Half day or full day', 'timing' => 'Fully flexible', 'best_season' => 'All year (October – March is prime)',
                'group_size' => 'Exclusive to your party', 'image_id' => $i('safari1'), 'is_featured' => 1,
                'highlights' => "Exclusive vehicle and senior Captain for your party\nFlexible start times and unhurried sightings\nOptional private sundowner on a granite crest\nIdeal for families, honeymooners and photographers",
                'body' => '<h2>Exclusivity changes everything</h2><p>On a shared drive you follow the group. On a private expedition, the day bends around you: linger with a basking leopard for an hour, chase the best light for photographs, or pause for a picnic breakfast on the rocks.</p><h2>Who it is for</h2><p>Families with young children who need a gentler pace, couples celebrating something special, and serious photographers who want angles and patience a standard drive cannot offer.</p><h2>Add-ons</h2><ul><li>Private sundowner on a secluded granite summit</li><li>Rabari pastoral heritage walk</li><li>Jawai Bandh wetland session</li><li>Transfers from Udaipur, Jodhpur or Falna station</li></ul>',
                'meta_title' => 'Private 4x4 Safari in Jawai | Bespoke Naturalist Expedition',
                'meta_description' => 'A fully private Jawai safari with your own vehicle and senior naturalist. Flexible timings, sundowners and unhurried leopard sightings.',
            ],
            [
                'title' => 'Wildlife Photography Expedition', 'slug' => 'photography-safari', 'category' => 'Photography',
                'tagline' => 'Rim-lit granite, golden hour and patient positioning.',
                'excerpt' => 'Planned around light: low-angle positioning, beanbag mounts and a Captain who understands composition as well as tracking.',
                'duration' => 'Multi-drive programmes', 'timing' => 'Golden hours, morning and evening', 'best_season' => 'November – April',
                'group_size' => 'Max 3 photographers per vehicle', 'image_id' => $i('leopard2'), 'is_featured' => 0,
                'highlights' => "Vehicle limited to 3 photographers for clear angles\nBeanbags and charging on board\nPositioning for backlight and rim light on granite\nEthics first — no baiting, no calls, no crowding",
                'body' => '<h2>Light, patience and position</h2><p>The granite domes of Jawai create extraordinary photographic stages: a leopard silhouetted against a dawn sky, warm rim light on spotted fur, a cub peering from a cave mouth. Your Captain plans each drive around the sun as much as the animals.</p><h2>Recommended kit</h2><ul><li>Primary: 100–400mm, 150–600mm or a 500mm prime</li><li>Secondary: 24–70mm for environmental and landscape frames</li><li>Fast cards and spare batteries (charging available on board)</li></ul><p>Drone flying is not permitted around wildlife.</p>',
                'meta_title' => 'Jawai Wildlife Photography Safari | Leopards on Granite',
                'meta_description' => 'Photography-focused leopard expeditions in Jawai: small groups, beanbag mounts and golden-hour positioning by expert trackers.',
            ],
        ];
    }

    private static function journeys(callable $i): array
    {
        return [
            [
                'title' => 'The Essential Jawai Expedition', 'slug' => '3-day-classic', 'duration_label' => '3 days · 2 nights',
                'tagline' => 'The quintessential introduction to the leopard hills.', 'image_id' => $i('leopard1'), 'is_featured' => 1,
                'excerpt' => 'Two leopard tracking drives, a wetland session at Jawai Bandh and a respectful Rabari village walk — the classic Jawai rhythm.',
                'itinerary' => "Day 1 | Arrival & first light on the rocks | Private transfer from Udaipur or Jodhpur (about 2.5 hours), with an optional stop at the Ranakpur Jain temples. Settle in, then head out for an evening leopard tracking drive and a sundowner.\nDay 2 | Dawn tracking & the great reservoir | Pre-dawn leopard drive. After breakfast, a slow wetland session at Jawai Bandh for crocodiles and winter birds. Late afternoon Rabari pastoral heritage walk.\nDay 3 | Last light and departure | An optional final dawn drive, breakfast, and your onward transfer.",
                'inclusions' => "Private 4x4 drives with a dedicated Captain\nWetland & birding session\nRabari heritage walk\nAccommodation of your choice (on request)\nTransfers on request",
                'body' => '<p>Ideal for first-time visitors and families. Every element can be adjusted — tell us your dates and how you like to travel.</p>',
                'meta_title' => '3-Day Jawai Leopard Safari Itinerary', 'meta_description' => 'A 2-night Jawai itinerary: two leopard drives, Jawai Bandh wetland birding and a Rabari heritage walk, fully tailored by our Captains.',
            ],
            [
                'title' => 'Photographer & Naturalist Immersion', 'slug' => '4-day-photography', 'duration_label' => '4 days · 3 nights',
                'tagline' => 'Unhurried, light-led and deeply rewarding.', 'image_id' => $i('leopard2'), 'is_featured' => 1,
                'excerpt' => 'Five to six drives timed around golden hour, a dedicated birding morning and private boulder-top sundowners.',
                'itinerary' => "Day 1 | Arrival | Transfer and an evening orientation drive among the kopjes.\nDay 2 | Granite & light | Dawn and dusk tracking drives positioned for backlight and rim light.\nDay 3 | Water & wings | Morning on Jawai Bandh for flamingos, cranes and raptors; evening leopard drive and private sundowner.\nDay 4 | Final light | Last dawn session, breakfast and departure.",
                'inclusions' => "5–6 private photography drives (max 3 photographers per vehicle)\nBeanbags and on-board charging\nDedicated birding session\nPrivate sundowner",
                'body' => '<p>Designed for photographers and keen naturalists who want time, space and patience.</p>',
                'meta_title' => '4-Day Jawai Photography Safari Itinerary', 'meta_description' => 'A 3-night Jawai photography immersion: golden-hour leopard drives, wetland birding and private sundowners with expert trackers.',
            ],
            [
                'title' => 'Rajasthan Wildlife & Heritage Circuit', 'slug' => 'rajasthan-circuit', 'duration_label' => '7+ days',
                'tagline' => 'Palaces, temples and the wild heart of Rajasthan.', 'image_id' => $i('landscape1'), 'is_featured' => 0,
                'excerpt' => 'Combine Jawai with Udaipur, Ranakpur and Jodhpur for a journey that balances heritage and wilderness.',
                'itinerary' => "Days 1–2 | Udaipur | Lakes, palaces and the old city.\nDay 3 | Ranakpur to Jawai | The marble Jain temples of Ranakpur en route (about 1 hour 15 minutes from Jawai). Evening drive.\nDays 4–5 | Jawai | Leopard tracking, wetland birding, Rabari culture and sundowners.\nDays 6–7 | Jodhpur | Mehrangarh Fort and the blue city.",
                'inclusions' => "Jawai expedition programme\nRoute planning and private transfers\nHotel recommendations across the circuit",
                'body' => '<p>We plan the Jawai portion in depth and can coordinate the wider route with your preferred hotels.</p>',
                'meta_title' => 'Rajasthan Wildlife & Heritage Circuit with Jawai', 'meta_description' => 'Combine Udaipur, Ranakpur and Jodhpur with leopard safaris in Jawai on a tailored Rajasthan wildlife and heritage journey.',
            ],
        ];
    }

    private static function posts(callable $i): array
    {
        return [
            [
                'title' => 'The Complete Jawai Travel Guide: Best Time, Safari Timings and Access', 'slug' => 'jawai-travel-guide', 'category' => 'Planning',
                'image_id' => $i('landscape1'), 'reading_time' => 7,
                'excerpt' => 'Everything you need to plan a first visit to Jawai — seasons, safari timings, how to get there and how many nights to stay.',
                'body' => '<p>Jawai sits in the Pali district of southern Rajasthan, roughly midway between Udaipur and Jodhpur. Its signature is the granite: smooth, ancient domes rising from scrub plains, riddled with caves that leopards use as dens and lookouts.</p><h2>When to go</h2><p><strong>October to March</strong> is prime season. Mornings are crisp (often 8–12°C in mid-winter), leopards bask on the warm rock well into the morning, and tens of thousands of migratory birds arrive at Jawai Bandh. <strong>April to June</strong> is hot but wildlife concentrates around water. The <strong>monsoon (July–September)</strong> turns the hills green and dramatic — wonderful for landscape photography, though tracks can be challenging.</p><h2>Safari timings</h2><p>Drives follow the light: roughly 05:45–09:15 in the morning and 16:15–19:30 in the evening, shifting with the season.</p><h2>Getting there</h2><ul><li><strong>Udaipur (UDR)</strong> — about 2.5 hours by road, via the Ranakpur temples.</li><li><strong>Jodhpur (JDH)</strong> — about 2.5 hours by road.</li><li><strong>Falna (FA)</strong> railway station — about 35 minutes, with trains from Delhi, Mumbai and Jaipur.</li></ul><h2>How long to stay</h2><p>Two nights gives you three to four drives and a cultural walk; three nights is our recommendation for photographers and anyone who wants an unhurried pace.</p>',
                'meta_title' => 'Jawai Travel Guide: Best Time, Safari Timings & How to Reach', 'meta_description' => 'Plan your Jawai leopard safari: best season, drive timings, routes from Udaipur, Jodhpur and Falna, and how many nights to stay.',
            ],
            [
                'title' => 'The Granite Cave Dwellers: How Jawai\'s Leopards Adapted to Rock', 'slug' => 'jawai-leopards-granite-caves', 'category' => 'Wildlife',
                'image_id' => $i('leopard1'), 'reading_time' => 6,
                'excerpt' => 'Why the leopards of Jawai live in caves rather than trees — and why that makes them some of the most observable in the world.',
                'body' => '<p>Most leopards are creatures of shadow and foliage. In Jawai they live in a world of stone. The granite inselbergs here have weathered over hundreds of millions of years into rounded domes and deep crevices — natural fortresses that shelter leopards from heat, keep cubs safe, and give a commanding view of the plains below.</p><h2>Warm rock, open views</h2><p>On winter mornings the rock warms quickly in the sun, and leopards often lie out on ledges to soak up the heat. With little dense vegetation to hide them, they can be observed calmly from a respectful distance — the reason Jawai has become so special for naturalists.</p><h2>A life among people</h2><p>These leopards share their hills with villages, temples and herds. Over generations a remarkable tolerance has developed on both sides, which we explore in our article on Rabari coexistence.</p><h2>How we watch</h2><p>We read alarm calls, scan ridgelines, and wait. Engines off, voices low, never blocking a path. A sighting is never guaranteed, but in Jawai patience is very often rewarded.</p>',
                'meta_title' => 'Jawai Leopards: How They Adapted to Granite Caves', 'meta_description' => 'Discover why Jawai\'s leopards live among granite caves and boulders, and why that makes ethical daytime observation possible.',
            ],
            [
                'title' => 'The Sacred Pact: Why the Rabari Live Alongside Leopards', 'slug' => 'rabari-leopard-coexistence', 'category' => 'Culture',
                'image_id' => $i('landscape1'), 'reading_time' => 6,
                'excerpt' => 'Inside one of the world\'s most remarkable stories of human–carnivore coexistence.',
                'body' => '<p>The Rabari are pastoralists whose red turbans and white garments are part of the Jawai landscape as much as the granite itself. They have herded goats, sheep, cattle and camels beneath these hills for generations.</p><h2>Reverence rather than retaliation</h2><p>Many hill shrines in the area are associated with the goddess, and the leopard is regarded with reverence. Occasional livestock losses are seen through this lens of faith and tolerance rather than revenge — a big reason leopards here are so relaxed around people.</p><h2>Visiting respectfully</h2><p>Our pastoral heritage walks are arranged with local families. We ask guests to dress modestly, to always ask before photographing anyone, and to approach the experience as guests rather than spectators. Coexistence is not a show — it is a way of life.</p>',
                'meta_title' => 'Rabari & Leopards: The Coexistence of Jawai', 'meta_description' => 'How the Rabari pastoralists of Jawai came to live peacefully alongside wild leopards, and how to visit their world respectfully.',
            ],
            [
                'title' => 'Birdwatching at Jawai Bandh: Flamingos, Cranes and Crocodiles', 'slug' => 'birdwatching-jawai-bandh', 'category' => 'Wildlife',
                'image_id' => $i('bird1'), 'reading_time' => 5,
                'excerpt' => 'A winter guide to the wetland side of Jawai — and why it is more than a side trip.',
                'body' => '<p>Jawai Bandh is the largest reservoir in western Rajasthan, and in winter it becomes one of the region\'s finest wetlands. Flocks of greater flamingos sift the shallows, demoiselle cranes and bar-headed geese arrive from Central Asia, and raptors patrol the shoreline.</p><h2>Species to look for</h2><ul><li>Greater and lesser flamingo</li><li>Demoiselle crane, bar-headed goose, greylag goose</li><li>Painted stork, spoonbill, river tern, Indian skimmer</li><li>Osprey, steppe eagle, peregrine falcon</li></ul><h2>The crocodiles</h2><p>The reservoir is home to a healthy population of mugger crocodiles, often seen basking on sandy banks on cool mornings.</p><h2>When to go</h2><p>November to February for the migrants; bring binoculars or borrow ours.</p>',
                'meta_title' => 'Birdwatching at Jawai Dam: Flamingos & Crocodiles', 'meta_description' => 'A guide to winter birding at Jawai Bandh: flamingos, cranes, raptors and mugger crocodiles, with the best times to visit.',
            ],
            [
                'title' => 'What to Pack for a Jawai Safari', 'slug' => 'what-to-pack-jawai-safari', 'category' => 'Planning',
                'image_id' => $i('safari1'), 'reading_time' => 4,
                'excerpt' => 'Clothing, footwear and kit for comfortable, respectful days in the leopard hills.',
                'body' => '<h2>Clothing</h2><ul><li>Neutral colours: khaki, olive, stone, brown. Avoid bright white and loud colours.</li><li>Warm layers for winter mornings — a fleece, a light down jacket, a beanie and gloves from December to February.</li><li>Breathable long sleeves and a wide-brimmed hat for sun.</li><li>A scarf or buff for dusty tracks.</li></ul><h2>Footwear</h2><p>Comfortable closed shoes with good grip for walking on rock.</p><h2>Kit</h2><ul><li>Binoculars (8x42 or 10x42 are ideal)</li><li>Camera with a telephoto lens</li><li>Sunscreen, lip balm, sunglasses</li><li>A reusable water bottle</li></ul><h2>For village visits</h2><p>Modest clothing covering shoulders and knees is appreciated.</p>',
                'meta_title' => 'What to Pack for a Jawai Leopard Safari', 'meta_description' => 'Packing list for a Jawai safari: what to wear in each season, footwear, binoculars and camera kit, and village visit etiquette.',
            ],
            [
                'title' => 'Jawai vs Ranthambhore: Which Rajasthan Safari Is Right for You?', 'slug' => 'jawai-vs-ranthambhore', 'category' => 'Planning',
                'image_id' => $i('leopard2'), 'reading_time' => 5,
                'excerpt' => 'Leopards on granite or tigers in dry forest? An honest comparison to help you decide.',
                'body' => '<p>Both are extraordinary — and very different.</p><h2>Ranthambhore</h2><p>A national park famous for tigers, with zoned routes, fixed permits and shared canters or gypsies. Busy in peak season.</p><h2>Jawai</h2><p>An open, lived-in landscape of granite hills, villages and a great reservoir. There are no rigid park zones; drives are in private 4x4s guided by local trackers, and the star is the leopard, often seen resting on open rock. Add the wetland birding and Rabari culture, and Jawai feels intimate and unhurried.</p><h2>Can you do both?</h2><p>Yes — many guests combine them on a longer Rajasthan journey. Speak to us and we will help you plan the Jawai portion in depth.</p>',
                'meta_title' => 'Jawai vs Ranthambhore: Choosing Your Rajasthan Safari', 'meta_description' => 'An honest comparison of Jawai and Ranthambhore: leopards vs tigers, private 4x4 vs park zones, crowds, culture and how to combine them.',
            ],
        ];
    }

    private static function pages(callable $i): array
    {
        $legal = '<p><em>This page is a starting template. Please review it with your legal adviser and update it in Admin → Pages before launch.</em></p>';
        return [
            [
                'title' => 'Masters of the Granite Sanctuary', 'slug' => 'about', 'kicker' => 'Our Story', 'image_id' => $i('safari1'), 'show_cta' => 1,
                'intro' => 'We do not believe in mass-market safari convoys, frantic chases or noisy tourism.',
                'body' => '<p>At Captains of Jawai, an expedition is a masterclass in reading nature: interpreting the sudden alarm call of a peacock echoing off the boulders, waiting patiently as the afternoon light turns granite into molten bronze, and watching a wild leopard emerge with regal stillness from her den.</p><h2>What we believe</h2><ul><li><strong>Mastery in the field.</strong> True tracking is an art of patience, acoustics, wind and animal behaviour.</li><li><strong>Reverent coexistence.</strong> We honour the bond between the leopards and the Rabari community.</li><li><strong>Uncompromising exclusivity.</strong> Small private groups, dedicated vehicles, no convoys.</li><li><strong>Quiet luxury.</strong> Sophistication through authenticity, thoughtful details and warmth.</li></ul><h2>How a journey with us works</h2><ol><li>You send an enquiry with your dates and interests.</li><li>A senior Captain replies personally within 12 hours.</li><li>We craft a tailored itinerary and proposal.</li><li>You confirm directly with us — no online payments, no pressure.</li></ol>',
                'meta_title' => 'About Captains of Jawai | Masters of the Granite Sanctuary', 'meta_description' => 'Learn about Captains of Jawai: our master naturalists, Rabari heritage roots and strict commitment to ethical, low-impact wildlife exploration.',
            ],
            [
                'title' => 'The Jawai Wilderness', 'slug' => 'jawai', 'kicker' => 'The Landscape', 'image_id' => $i('landscape1'), 'show_cta' => 1,
                'intro' => 'Ancient granite, a great reservoir and an open, lived-in wilderness unlike any national park.',
                'body' => '<h2>The granite kopjes</h2><p>Jawai\'s signature is its granite: rounded inselbergs and boulder piles, weathered over hundreds of millions of years into domes, ledges and caves. They give leopards shelter, warmth and lookouts — and give us one of the most cinematic safari landscapes in India.</p><h2>Jawai Bandh</h2><p>Built between 1946 and 1957 under Maharaja Umaid Singh of Jodhpur, the dam created the largest reservoir in western Rajasthan, now a winter haven for migratory birds and home to mugger crocodiles.</p><h2>Villages in the wildlife arc</h2><p>Bera, Sena, Perwa, Varawal, Bisalpur, Velar, Kothar and others sit among the hills. People, herds and wildlife share this land.</p><h2>Seasons at a glance</h2><table><thead><tr><th>Season</th><th>Months</th><th>Character</th></tr></thead><tbody><tr><td>Peak winter</td><td>Nov – Feb</td><td>Prime viewing; leopards bask on warm rock; migratory birds</td></tr><tr><td>Spring</td><td>Mar – Apr</td><td>Clear light, active leopards, falling water levels</td></tr><tr><td>Summer</td><td>May – Jun</td><td>Hot; activity at dawn and dusk; wildlife near water</td></tr><tr><td>Monsoon</td><td>Jul – Sep</td><td>Green hills, dramatic skies, rough tracks</td></tr><tr><td>Autumn</td><td>Oct</td><td>Season opens; first birds arrive</td></tr></tbody></table>',
                'meta_title' => 'Jawai Wilderness: Granite Hills, Dam & Seasons', 'meta_description' => 'Discover the Jawai landscape: ancient granite kopjes, the Jawai Bandh reservoir, its villages and the best seasons to visit.',
            ],
            [
                'title' => 'Rabari Pastoral Heritage', 'slug' => 'experiences/rabari-culture', 'kicker' => 'The Living Pact', 'image_id' => $i('landscape1'), 'show_cta' => 1,
                'intro' => 'Meet the red-turbaned herders whose reverence for the leopard shaped this land.',
                'body' => '<p>The Rabari are semi-nomadic pastoralists who have grazed their herds across these hills for generations. Their faith, centred on hill shrines and the goddess, extends a remarkable tolerance to the leopards that share their land.</p><h2>The heritage walk</h2><p>Arranged with local families, our walks introduce you to pastoral life: morning herding, traditional dress and silver jewellery, and the stories that bind people and leopards here.</p><h2>Our principles</h2><ul><li>Small groups and genuine conversation — never a staged show.</li><li>Always ask before photographing people.</li><li>Fair, direct benefit to the families who host us.</li></ul>',
                'meta_title' => 'Rabari Pastoral Heritage | Human–Leopard Coexistence', 'meta_description' => 'Discover the bond between wild leopards and Rabari herders on a respectful guided pastoral heritage walk in Jawai.',
            ],
            [
                'title' => 'Granite Boulder Sundowners', 'slug' => 'experiences/boulder-sundowners', 'kicker' => 'Curated Moments', 'image_id' => $i('leopard2'), 'show_cta' => 1,
                'intro' => 'Watch the granite turn to bronze from a private summit as the day ends.',
                'body' => '<p>After an evening drive we can set up a private sundowner on a secluded rock crest: your choice of drinks, warm snacks, lanterns and an uninterrupted view over the hills and reservoir. Perfect for celebrations and honeymoons.</p>',
                'meta_title' => 'Private Boulder Sundowners in Jawai', 'meta_description' => 'Private sundowners on secluded granite summits in Jawai, after an evening leopard drive. Ideal for celebrations and honeymoons.',
            ],
            [
                'title' => 'Conservation & Ethics', 'slug' => 'about/conservation-ethics', 'kicker' => 'The Code of the Captains', 'image_id' => $i('leopard1'), 'show_cta' => 0,
                'intro' => 'How we watch wildlife — and why it matters.',
                'body' => '<ol><li><strong>No guarantees, no chases.</strong> We never promise sightings and never pursue an animal.</li><li><strong>Engines off.</strong> Vehicles are switched off during observation.</li><li><strong>Distance and escape routes.</strong> We keep a respectful distance and never block an animal\'s path.</li><li><strong>No baiting, calls or lights</strong> to attract wildlife.</li><li><strong>Limited vehicles</strong> at a sighting; we wait our turn or move on.</li><li><strong>Community first.</strong> We work with and pay local people fairly, and respect private land and shrines.</li><li><strong>No drones</strong> around wildlife.</li></ol>',
                'meta_title' => 'Ethical Safari Code | Captains of Jawai', 'meta_description' => 'Our ethical wildlife viewing code: no guaranteed sightings, engines off, respectful distances, no baiting and community-first tourism.',
            ],
            [
                'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'kicker' => 'Legal', 'image_id' => null, 'show_cta' => 0, 'intro' => '',
                'body' => $legal . '<h2>What we collect</h2><p>When you send an enquiry we collect the details you provide (name, email, phone, travel preferences and message) along with basic technical data such as IP address to prevent spam.</p><h2>How we use it</h2><p>Only to respond to your enquiry and plan your journey. We never sell or share your data with third parties for marketing.</p><h2>Retention</h2><p>Enquiry records are kept for as long as needed to serve you and meet legal obligations. Ask us at any time to access or delete your data.</p><h2>Contact</h2><p>Write to us using the details on our contact page.</p>',
                'meta_title' => 'Privacy Policy', 'meta_description' => 'How Captains of Jawai collects, uses and protects your personal information.',
            ],
            [
                'title' => 'Terms & Safari Conditions', 'slug' => 'terms', 'kicker' => 'Legal', 'image_id' => null, 'show_cta' => 0, 'intro' => '',
                'body' => $legal . '<h2>Enquiries are not bookings</h2><p>Submitting a form on this website is a request for information. A journey is confirmed only after you receive and accept a written proposal from us.</p><h2>Wildlife</h2><p>Wildlife sightings cannot be guaranteed. Itineraries may change due to weather, animal welfare or safety.</p><h2>Cancellations</h2><p>Cancellation and refund terms are provided with each proposal.</p>',
                'meta_title' => 'Terms & Safari Conditions', 'meta_description' => 'Terms and conditions for enquiries and safari expeditions with Captains of Jawai.',
            ],
            [
                'title' => 'Cookie Policy', 'slug' => 'cookie-policy', 'kicker' => 'Legal', 'image_id' => null, 'show_cta' => 0, 'intro' => '',
                'body' => $legal . '<p>This website uses a strictly necessary session cookie to protect forms against abuse. If analytics is enabled, Google Analytics may set cookies to help us understand how the site is used.</p>',
                'meta_title' => 'Cookie Policy', 'meta_description' => 'How cookies are used on the Captains of Jawai website.',
            ],
        ];
    }

    private static function faqs(): array
    {
        return [
            ['Safaris & Wildlife', 'Are leopard sightings guaranteed in Jawai?', 'No ethical operator can guarantee a wild animal sighting, and anyone who promises one is misleading you. However, because Jawai\'s leopards live on open granite rather than in dense forest, the probability of observing them over a 2–3 day stay is very high.'],
            ['Safaris & Wildlife', 'How is Jawai different from Ranthambhore or Jim Corbett?', 'Jawai is an open, lived-in landscape rather than a gated national park. There are no rigid zones or shared canters; drives are in private 4x4 vehicles with local trackers, and you can combine leopards with wetland birding and Rabari culture.'],
            ['Safaris & Wildlife', 'Is it safe to be near leopards in an open vehicle?', 'Yes. Jawai\'s leopards are habituated to people and vehicles. Our Captains keep respectful distances, never block an animal\'s path and switch off engines during observation.'],
            ['Safaris & Wildlife', 'What else will I see besides leopards?', 'Striped hyena, nilgai, chinkara, jungle cat, Indian grey wolf and desert fox are possible, along with mugger crocodiles and over 300 bird species, including winter flamingos and cranes.'],
            ['Planning', 'When is the best time to visit?', 'October to March is prime season with cool mornings, basking leopards and migratory birds. April to June is hot but rewarding; the monsoon (July–September) is lush and dramatic.'],
            ['Planning', 'How many days should we stay?', 'Two nights is the classic stay (3–4 drives plus a cultural walk). Three nights suits photographers and anyone who wants a relaxed pace.'],
            ['Planning', 'How do we reach Jawai?', 'Udaipur and Jodhpur airports are each about 2.5 hours by road. Falna railway station is about 35 minutes away. We can arrange private transfers from all of them.'],
            ['Planning', 'Do you arrange accommodation?', 'Yes. We can recommend and coordinate stays from luxury tented camps to boutique villas and heritage properties, or work around a stay you have already booked.'],
            ['Booking', 'Can I book and pay online?', 'No — and that is deliberate. Every journey is tailored. Send an enquiry and a Captain will reply personally within 12 hours with ideas, availability and a proposal.'],
            ['Booking', 'Is Jawai suitable for children and older travellers?', 'Very much so. Private vehicles let us set a gentle pace, shorten drives and plan comfortable breaks.'],
            ['Booking', 'What should I wear?', 'Neutral colours, warm layers for winter mornings, a hat and closed shoes. Modest clothing is appreciated on village visits.'],
        ];
    }

    private static function menus(): void
    {
        $add = function (string $loc, string $label, string $url, int $sort, ?int $parent = null): int {
            return DB::insert('menu_items', ['location' => $loc, 'label' => $label, 'url' => $url, 'parent_id' => $parent, 'sort_order' => $sort, 'is_active' => 1, 'new_tab' => 0]);
        };
        $exp = $add('header', 'Expeditions', '/safaris/', 1);
        $add('header', 'Classic Leopard Safari', '/safaris/leopard-safari/', 1, $exp);
        $add('header', 'Private 4x4 Expedition', '/safaris/private-safari/', 2, $exp);
        $add('header', 'Wetland & Birding', '/safaris/dam-birding-safari/', 3, $exp);
        $add('header', 'Photography Expedition', '/safaris/photography-safari/', 4, $exp);
        $wild = $add('header', 'The Wilderness', '/jawai/', 2);
        $add('header', 'Jawai Landscape & Seasons', '/jawai/', 1, $wild);
        $add('header', 'Rabari Heritage', '/experiences/rabari-culture/', 2, $wild);
        $add('header', 'Boulder Sundowners', '/experiences/boulder-sundowners/', 3, $wild);
        $add('header', 'Gallery', '/gallery/', 4, $wild);
        $add('header', 'Journeys', '/journeys/', 3);
        $add('header', 'Field Journal', '/journal/', 4);
        $about = $add('header', 'About', '/about/', 5);
        $add('header', 'Our Story & Captains', '/about/', 1, $about);
        $add('header', 'Conservation & Ethics', '/about/conservation-ethics/', 2, $about);
        $add('header', 'FAQ', '/faq/', 3, $about);
        $add('header', 'Contact', '/contact/', 4, $about);

        foreach ([['Expeditions', '/safaris/'], ['Curated Journeys', '/journeys/'], ['The Wilderness', '/jawai/'], ['Rabari Heritage', '/experiences/rabari-culture/'], ['Gallery', '/gallery/']] as $n => [$l, $u]) {
            $add('footer', $l, $u, $n + 1);
        }
        foreach ([['About Us', '/about/'], ['Conservation & Ethics', '/about/conservation-ethics/'], ['Field Journal', '/journal/'], ['FAQ', '/faq/'], ['Contact', '/contact/']] as $n => [$l, $u]) {
            $add('footer2', $l, $u, $n + 1);
        }
        foreach ([['Privacy Policy', '/privacy-policy/'], ['Terms', '/terms/'], ['Cookies', '/cookie-policy/'], ['Sitemap', '/sitemap.xml']] as $n => [$l, $u]) {
            $add('legal', $l, $u, $n + 1);
        }
    }
}
