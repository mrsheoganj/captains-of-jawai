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
    public static function run(bool $demoEnquiries = true): void
    {
        $now = now();
        $img = Demo::registerImages();
        $i = fn (string $n) => $img[$n] ?? null;
        $fresh = !(int) DB::val('SELECT COUNT(*) FROM safaris');

        if (!(int) DB::val('SELECT COUNT(*) FROM safaris')) {
            foreach (self::safaris($i) as $n => $s) {
                DB::insert('safaris', $s + ['sort_order' => $n + 1, 'status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
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
        JawaiGuide::ensure(); // the /jawai/ pillar page, guide pages and their menu links
        // Every image slot gets a photo, so the site is presentation-ready straight after install.
        Demo::assignImages($fresh); // on a fresh install, apply the full curated image map
        if ($demoEnquiries) {
            Demo::loadEnquiries();
        }
    }

    private static function safaris(callable $i): array
    {
        return [
            [
                'title' => 'Classic Leopard Tracking Safari', 'slug' => 'leopard-safari', 'category' => 'Apex Predator',
                'tagline' => 'Dawn and dusk among the granite caves of the leopard hills.',
                'excerpt' => 'Follow alarm calls, pugmarks and the warmth of sun-struck granite with a master tracker, as resident leopards surface on the rocks at first and last light.',
                'duration' => 'About 3 hours', 'timing' => 'Winter 6:00–9:00 and 15:30–18:30 · Summer 5:00–8:00 and 16:30–19:30', 'best_season' => 'October – March',
                'group_size' => 'Private vehicle, up to 6 guests', 'image_id' => $i('leopard1'), 'is_featured' => 1,
                'highlights' => "Private open 4x4 with an experienced local tracker\nTracking by langur and peafowl alarm calls\nEngine-off observation at ethical distances\nBinoculars on board, beanbags for photographers\nHot tea or coffee served in the field",
                'body' => '<h2>The anatomy of a tracking drive</h2><p>Long before sunrise, your Captain is already listening. In Jawai the leopards do not hide in dense forest. They rest on and inside the great granite domes, and the land itself tells you where they are. A langur barking from a ridge, a peacock\'s sharp alarm, fresh pugmarks in a sandy riverbed: each sign is read and weighed.</p><p>As the sun warms the rock, leopards often emerge onto ledges and cave mouths to bask. We position the vehicle quietly, switch off the engine and simply wait. Patience is the whole art.</p><h2>Ethical distance standards</h2><p>We never block an animal\'s path, never crowd a sighting and never use calls or bait. Engines are switched off during observation and we leave before the animal is disturbed. A leopard sighting is never guaranteed (any operator who promises one is misleading you), but the open granite terrain gives Jawai one of the highest daytime observation probabilities anywhere.</p><h2>What to bring</h2><ul><li>Neutral clothing: khaki, olive, stone. Warm layers for winter mornings (it can be 8–12°C at dawn).</li><li>A 100–400mm or 70–200mm lens for photographers; we carry beanbags.</li><li>Hat, sunscreen, and a scarf for dusty tracks.</li></ul>',
                'meta_title' => 'Jawai Leopard Safari in Bera | Private 4x4 Tracking',
                'meta_description' => 'Experience wild leopards on ancient granite kopjes. Private 4x4 jeeps, dawn and dusk drives, expert local trackers in Jawai and Bera.',
            ],
            [
                'title' => 'Jawai Bandh Wetland & Raptor Drive', 'slug' => 'dam-birding-safari', 'category' => 'Avian Wetland',
                'tagline' => 'Flamingos, cranes and basking mugger crocodiles on the great reservoir.',
                'excerpt' => 'A slower, scope-led exploration of the reservoir shoreline, winter flamingo flocks, demoiselle cranes, raptors and the resident marsh crocodiles.',
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
                'excerpt' => 'A fully private expedition designed around you: flexible timings, longer sightings, boulder-top sundowners and a senior naturalist dedicated to your party.',
                'duration' => 'Half day or full day', 'timing' => 'Fully flexible', 'best_season' => 'All year (October – March is prime)',
                'group_size' => 'Exclusive to your party', 'image_id' => $i('safari1'), 'is_featured' => 1,
                'highlights' => "Exclusive vehicle and senior Captain for your party\nFlexible start times and unhurried sightings\nOptional private sundowner on a granite crest\nIdeal for families, honeymooners and photographers",
                'body' => '<h2>Exclusivity changes everything</h2><p>On a shared drive you follow the group. On a private expedition, the day bends around you: linger with a basking leopard for an hour, chase the best light for photographs, or pause for a picnic breakfast on the rocks.</p><h2>Who it is for</h2><p>Families with young children who need a gentler pace, couples celebrating something special, and serious photographers who want angles and patience a standard drive cannot offer.</p><h2>Add-ons</h2><ul><li>Private sundowner on a secluded granite summit</li><li>Jawai Bandh wetland drive on the same day</li></ul><p>We run safaris only. Hotels and transfers are not part of the service: tell us where you are staying and we will meet you there or at an agreed point.</p>',
                'meta_title' => 'Private 4x4 Safari in Jawai | Bespoke Naturalist Expedition',
                'meta_description' => 'A fully private Jawai safari with your own vehicle and senior naturalist. Flexible timings, sundowners and unhurried leopard sightings.',
            ],
            [
                'title' => 'Wildlife Photography Expedition', 'slug' => 'photography-safari', 'category' => 'Photography',
                'tagline' => 'Rim-lit granite, golden hour and patient positioning.',
                'excerpt' => 'Planned around light: low-angle positioning, beanbag mounts and a Captain who understands composition as well as tracking.',
                'duration' => 'Multi-drive programmes', 'timing' => 'Golden hours, morning and evening', 'best_season' => 'November – April',
                'group_size' => 'Max 3 photographers per vehicle', 'image_id' => $i('leopard2'), 'is_featured' => 0,
                'highlights' => "Vehicle limited to 3 photographers for clear angles\nBeanbags and charging on board\nPositioning for backlight and rim light on granite\nEthics first: no baiting, no calls, no crowding",
                'body' => '<h2>Light, patience and position</h2><p>The granite domes of Jawai create extraordinary photographic stages: a leopard silhouetted against a dawn sky, warm rim light on spotted fur, a cub peering from a cave mouth. Your Captain plans each drive around the sun as much as the animals.</p><h2>Recommended kit</h2><ul><li>Primary: 100–400mm, 150–600mm or a 500mm prime</li><li>Secondary: 24–70mm for environmental and landscape frames</li><li>Fast cards and spare batteries (charging available on board)</li></ul><p>Drone flying is not permitted around wildlife.</p>',
                'meta_title' => 'Jawai Wildlife Photography Safari | Leopards on Granite',
                'meta_description' => 'Photography-focused leopard expeditions in Jawai: small groups, beanbag mounts and golden-hour positioning by expert trackers.',
            ],
        ];
    }

    private static function posts(callable $i): array
    {
        return [
            [
                'title' => 'The Complete Jawai Travel Guide: Best Time, Safari Timings and Access', 'slug' => 'jawai-travel-guide', 'category' => 'Planning',
                'image_id' => $i('landscape1'), 'reading_time' => 7,
                'excerpt' => 'Everything you need to plan a first visit to Jawai: seasons, safari timings, how to get there and how many drives to book.',
                'body' => '<p>Jawai sits in the Pali district of southern Rajasthan, roughly midway between Udaipur and Jodhpur. Its signature is the granite: smooth, ancient domes rising from scrub plains, riddled with caves that leopards use as dens and lookouts.</p><h2>When to go</h2><p><strong>October to March</strong> is prime season. Mornings are crisp (often 8–12°C in mid-winter), leopards bask on the warm rock well into the morning, and tens of thousands of migratory birds arrive at Jawai Bandh. <strong>April to June</strong> is hot but wildlife concentrates around water. The <strong>monsoon (July–September)</strong> turns the hills green and dramatic, wonderful for landscape photography, though tracks can be challenging.</p><h2>Safari timings</h2><p>Drives follow the light and last about three hours. In winter they run roughly 6:00 to 9:00 and 15:30 to 18:30; in summer about 5:00 to 8:00 and 16:30 to 19:30.</p><h2>Getting there</h2><ul><li><strong>Udaipur Airport (UDR)</strong>, about 155 km, three and a half hours by road (Udaipur city is about 141 km via Ranakpur).</li><li><strong>Jodhpur Airport (JDH)</strong>, about 175 km, around four hours.</li><li><strong>Sumerpur Jawai Bandh (JWB)</strong> railway station, about 15 km, and <strong>Falna (FA)</strong>, about 34 km, with trains from Delhi, Jaipur and Ahmedabad.</li></ul><h2>How many drives?</h2><p>Three or four drives (a morning and an evening on two days) give you a fair chance of good leopard sightings. Photographers often book more.</p>',
                'meta_title' => 'Jawai Travel Guide: Best Time, Safari Timings & How to Reach', 'meta_description' => 'Plan your Jawai leopard safari: best season, drive timings, routes from Udaipur, Jodhpur and Falna, and how many drives to book.',
            ],
            [
                'title' => 'The Granite Cave Dwellers: How Jawai\'s Leopards Adapted to Rock', 'slug' => 'jawai-leopards-granite-caves', 'category' => 'Wildlife',
                'image_id' => $i('leopard1'), 'reading_time' => 6,
                'excerpt' => 'Why the leopards of Jawai live in caves rather than trees, and why that makes them some of the most observable in the world.',
                'body' => '<p>Most leopards are creatures of shadow and foliage. In Jawai they live in a world of stone. The granite inselbergs here have weathered over hundreds of millions of years into rounded domes and deep crevices, natural fortresses that shelter leopards from heat, keep cubs safe, and give a commanding view of the plains below.</p><h2>Warm rock, open views</h2><p>On winter mornings the rock warms quickly in the sun, and leopards often lie out on ledges to soak up the heat. With little dense vegetation to hide them, they can be observed calmly from a respectful distance, the reason Jawai has become so special for naturalists.</p><h2>A life among people</h2><p>These leopards share their hills with villages, temples and herds. Over generations a remarkable tolerance has developed on both sides, which we explore in our article on Rabari coexistence.</p><h2>How we watch</h2><p>We read alarm calls, scan ridgelines, and wait. Engines off, voices low, never blocking a path. A sighting is never guaranteed, but in Jawai patience is very often rewarded.</p>',
                'meta_title' => 'Jawai Leopards: How They Adapted to Granite Caves', 'meta_description' => 'Discover why Jawai\'s leopards live among granite caves and boulders, and why that makes ethical daytime observation possible.',
            ],
            [
                'title' => 'The Sacred Pact: Why the Rabari Live Alongside Leopards', 'slug' => 'rabari-leopard-coexistence', 'category' => 'Culture',
                'image_id' => $i('landscape1'), 'reading_time' => 6,
                'excerpt' => 'Inside one of the world\'s most remarkable stories of human–carnivore coexistence.',
                'body' => '<p>The Rabari are pastoralists whose red turbans and white garments are part of the Jawai landscape as much as the granite itself. They have herded goats, sheep, cattle and camels beneath these hills for generations.</p><h2>Reverence rather than retaliation</h2><p>Many hill shrines in the area are associated with the goddess, and the leopard is regarded with reverence. Occasional livestock losses are seen through this lens of faith and tolerance rather than revenge, a big reason leopards here are so relaxed around people.</p><h2>Visiting respectfully</h2><p>Our pastoral heritage walks are arranged with local families. We ask guests to dress modestly, to always ask before photographing anyone, and to approach the experience as guests rather than spectators. Coexistence is not a show. It is a way of life.</p>',
                'meta_title' => 'Rabari & Leopards: The Coexistence of Jawai', 'meta_description' => 'How the Rabari pastoralists of Jawai came to live peacefully alongside wild leopards, and how to visit their world respectfully.',
            ],
            [
                'title' => 'Birdwatching at Jawai Bandh: Flamingos, Cranes and Crocodiles', 'slug' => 'birdwatching-jawai-bandh', 'category' => 'Wildlife',
                'image_id' => $i('bird1'), 'reading_time' => 5,
                'excerpt' => 'A winter guide to the wetland side of Jawai, and why it is more than a side trip.',
                'body' => '<p>Jawai Bandh is the largest reservoir in western Rajasthan, and in winter it becomes one of the region\'s finest wetlands. Flocks of greater flamingos sift the shallows, demoiselle cranes and bar-headed geese arrive from Central Asia, and raptors patrol the shoreline.</p><h2>Species to look for</h2><ul><li>Greater and lesser flamingo</li><li>Demoiselle crane, bar-headed goose, greylag goose</li><li>Painted stork, spoonbill, river tern, Indian skimmer</li><li>Osprey, steppe eagle, peregrine falcon</li></ul><h2>The crocodiles</h2><p>The reservoir is home to a healthy population of mugger crocodiles, often seen basking on sandy banks on cool mornings.</p><h2>When to go</h2><p>November to February for the migrants; bring binoculars or borrow ours.</p>',
                'meta_title' => 'Birdwatching at Jawai Dam: Flamingos & Crocodiles', 'meta_description' => 'A guide to winter birding at Jawai Bandh: flamingos, cranes, raptors and mugger crocodiles, with the best times to visit.',
            ],
            [
                'title' => 'What to Pack for a Jawai Safari', 'slug' => 'what-to-pack-jawai-safari', 'category' => 'Planning',
                'image_id' => $i('safari1'), 'reading_time' => 4,
                'excerpt' => 'Clothing, footwear and kit for comfortable, respectful days in the leopard hills.',
                'body' => '<h2>Clothing</h2><ul><li>Neutral colours: khaki, olive, stone, brown. Avoid bright white and loud colours.</li><li>Warm layers for winter mornings: a fleece, a light down jacket, a beanie and gloves from December to February.</li><li>Breathable long sleeves and a wide-brimmed hat for sun.</li><li>A scarf or buff for dusty tracks.</li></ul><h2>Footwear</h2><p>Comfortable closed shoes with good grip for walking on rock.</p><h2>Kit</h2><ul><li>Binoculars (8x42 or 10x42 are ideal)</li><li>Camera with a telephoto lens</li><li>Sunscreen, lip balm, sunglasses</li><li>A reusable water bottle</li></ul><h2>For village visits</h2><p>Modest clothing covering shoulders and knees is appreciated.</p>',
                'meta_title' => 'What to Pack for a Jawai Leopard Safari', 'meta_description' => 'Packing list for a Jawai safari: what to wear in each season, footwear, binoculars and camera kit, and village visit etiquette.',
            ],
            [
                'title' => 'Jawai vs Ranthambhore: Which Rajasthan Safari Is Right for You?', 'slug' => 'jawai-vs-ranthambhore', 'category' => 'Planning',
                'image_id' => $i('leopard2'), 'reading_time' => 5,
                'excerpt' => 'Leopards on granite or tigers in dry forest? An honest comparison to help you decide.',
                'body' => '<p>Both are extraordinary, and very different.</p><h2>Ranthambhore</h2><p>A national park famous for tigers, with zoned routes, fixed permits and shared canters or gypsies. Busy in peak season.</p><h2>Jawai</h2><p>An open, lived-in landscape of granite hills, villages and a great reservoir. There are no rigid park zones; drives are in private 4x4s guided by local trackers, and the star is the leopard, often seen resting on open rock. Add the wetland birding and Rabari culture, and Jawai feels intimate and unhurried.</p><h2>Can you do both?</h2><p>Yes. Many guests combine them on a longer Rajasthan journey. Speak to us and we will help you plan the Jawai portion in depth.</p>',
                'meta_title' => 'Jawai vs Ranthambhore: Choosing Your Rajasthan Safari', 'meta_description' => 'An honest comparison of Jawai and Ranthambhore: leopards vs tigers, private 4x4 vs park zones, crowds, culture and how to combine them.',
            ],
            [
                'title' => 'Monsoon in Jawai: The Hidden Season for Photographers', 'slug' => 'monsoon-in-jawai', 'category' => 'Seasons',
                'image_id' => $i('granite-boulders'), 'reading_time' => 4,
                'excerpt' => 'From July to September the granite hills turn emerald, waterfalls spill off the rocks and the skies put on a show.',
                'body' => '<p>Most visitors come in winter, but the monsoon transforms Jawai. Fresh green scrub softens the granite, temporary waterfalls appear on the larger domes, and towering clouds make dramatic backdrops.</p><h2>What to expect</h2><ul><li>Lush landscapes and very few other vehicles.</li><li>Leopards are still active, though vegetation can make them harder to spot.</li><li>Some tracks become muddy or impassable after heavy rain, so plans stay flexible.</li></ul><h2>Who it suits</h2><p>Landscape photographers, return visitors and anyone who wants Jawai at its quietest. Bring a rain shell and protection for your camera.</p>',
                'meta_title' => 'Jawai in the Monsoon: A Photographer\'s Season', 'meta_description' => 'Why July to September is a hidden season in Jawai: green granite hills, waterfalls, dramatic skies and quiet safaris for photographers.',
            ],
            [
                'title' => 'How to Reach Jawai from Udaipur', 'slug' => 'udaipur-to-jawai', 'category' => 'Planning',
                'image_id' => $i('bandh-shoreline'), 'reading_time' => 4,
                'excerpt' => 'The 141 km drive from Udaipur, about three hours, with a stop at the marble temples of Ranakpur.',
                'body' => '<p>Udaipur is the most popular gateway to Jawai. The drive is about 141 km and takes three to three and a half hours through the Aravalli hills. From Udaipur Airport it is about 155 km.</p><h2>The route</h2><p>Head north-west via the hill roads towards Ranakpur, then continue through Sadri and Desuri towards Bera and the Jawai Bandh area.</p><h2>Stop at Ranakpur</h2><p>The 15th-century Jain temple at Ranakpur, about an hour and a quarter from Jawai, is one of the great marble monuments of India. Allow an hour, and dress modestly.</p><h2>Timing your arrival</h2><p>Leave Udaipur by late morning and you will be in Jawai in time for an evening drive. We do not run transfers ourselves; your hotel or a local taxi can bring you, and we meet you for the safari.</p>',
                'meta_title' => 'Udaipur to Jawai: Route, Time & Ranakpur Stop', 'meta_description' => 'How to reach Jawai from Udaipur: the 141 km, 3-hour drive, a stop at Ranakpur temple, and timing your arrival for an evening safari.',
            ],
            [
                'title' => 'The Code of the Captains: How We Watch Wildlife', 'slug' => 'code-of-the-captains', 'category' => 'Conservation',
                'image_id' => $i('leopard-stalking'), 'reading_time' => 3,
                'excerpt' => 'Engines off, respectful distances, no baiting: the simple rules that keep Jawai\'s leopards relaxed and wild.',
                'body' => '<p>The leopards of Jawai are remarkably tolerant of people. Keeping it that way is everyone\'s responsibility.</p><h2>Our rules in the field</h2><ol><li>We never promise a sighting and never chase an animal.</li><li>Engines are switched off while we observe.</li><li>We keep our distance and never block a path or a den.</li><li>No baiting, calls, spotlights or drones.</li><li>When too many vehicles gather, we move on.</li></ol><p>These rules make for calmer animals and better, longer sightings for our guests.</p>',
                'meta_title' => 'Ethical Leopard Safaris in Jawai: Our Code', 'meta_description' => 'How Captains of Jawai watches wildlife ethically: engines off, respectful distances, no baiting, no chasing and never a guaranteed sighting.',
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
                'body' => '<p>At Captains of Jawai, an expedition is a masterclass in reading nature: interpreting the sudden alarm call of a peacock echoing off the boulders, waiting patiently as the afternoon light turns granite into molten bronze, and watching a wild leopard emerge with regal stillness from her den.</p><h2>What we believe</h2><ul><li><strong>Mastery in the field.</strong> True tracking is an art of patience, acoustics, wind and animal behaviour.</li><li><strong>Reverent coexistence.</strong> We honour the bond between the leopards and the Rabari community.</li><li><strong>Uncompromising exclusivity.</strong> Small private groups, dedicated vehicles, no convoys.</li></ul><h2>How booking works</h2><ol><li>Send a safari request with your date, drive time and number of guests.</li><li>We reply personally, usually within 12 hours, with availability and the price.</li><li>You confirm directly with us. No online payments, no pressure.</li><li>We meet you at your stay or an agreed point in Jawai for the drive.</li></ol><p>We run safaris only. We do not book hotels or arrange transfers.</p>',
                'meta_title' => 'About Captains of Jawai | Masters of the Granite Sanctuary', 'meta_description' => 'Learn about Captains of Jawai: our master naturalists, Rabari heritage roots and strict commitment to ethical, low-impact wildlife exploration.',
            ],
            [
                'title' => 'Rabari Pastoral Heritage', 'slug' => 'experiences/rabari-culture', 'kicker' => 'The Living Pact', 'image_id' => $i('landscape1'), 'show_cta' => 1,
                'intro' => 'The red-turbaned herders whose tolerance of the leopard shaped this land.',
                'body' => '<p>The Rabari are semi-nomadic pastoralists who have grazed their herds across these hills for generations. Their faith, centred on hill shrines and the goddess, extends a remarkable tolerance to the leopards that share their land.</p><h2>What you will see on safari</h2><p>On morning and evening drives you pass Rabari herds moving between the hills and the water, red turbans in the fields, and small shrines on the rocks where leopards often rest. Your tracker will explain how herders and leopards share the same ground.</p><h2>Be a good guest</h2><ul><li>Keep a respectful distance from homes, herds and shrines.</li><li>Always ask before photographing people.</li><li>Stay on the tracks agreed with the village.</li></ul>',
                'meta_title' => 'Rabari Pastoral Heritage | Human–Leopard Coexistence', 'meta_description' => 'Discover the bond between wild leopards and Rabari herders and what you will see of Rabari life on a Jawai safari.',
            ],
            [
                'title' => 'Granite Boulder Sundowners', 'slug' => 'experiences/boulder-sundowners', 'kicker' => 'Curated Moments', 'image_id' => $i('leopard2'), 'show_cta' => 1,
                'intro' => 'Watch the granite turn to bronze from a private summit as the day ends.',
                'body' => '<p>After an evening drive we can set up a private sundowner on a secluded rock crest: your choice of drinks, warm snacks, lanterns and an uninterrupted view over the hills and reservoir. Perfect for celebrations and honeymoons.</p>',
                'meta_title' => 'Private Boulder Sundowners in Jawai', 'meta_description' => 'Private sundowners on secluded granite summits in Jawai, after an evening leopard drive. Ideal for celebrations and honeymoons.',
            ],
            [
                'title' => 'Conservation & Ethics', 'slug' => 'about/conservation-ethics', 'kicker' => 'The Code of the Captains', 'image_id' => $i('leopard1'), 'show_cta' => 0,
                'intro' => 'How we watch wildlife, and why it matters.',
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
                'body' => $legal . '<h2>Enquiries are not bookings</h2><p>Submitting a form on this website is a request for information. A safari is confirmed only after you receive and accept our written confirmation from us.</p><h2>Wildlife</h2><p>Wildlife sightings cannot be guaranteed. Itineraries may change due to weather, animal welfare or safety.</p><h2>Cancellations</h2><p>Cancellation and refund terms are provided with each proposal.</p>',
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
            ['Planning', 'How many drives should we book?', 'Three or four drives (morning and evening on two days) give a fair chance of good leopard sightings. Photographers often book more.'],
            ['Planning', 'How do we reach Jawai?', 'Udaipur Airport is about 155 km (3.5 hours by road) and Jodhpur Airport about 175 km (4 hours). The nearest railway station, Sumerpur Jawai Bandh, is about 15 km away, and Falna about 34 km. We run safaris only and do not arrange transfers, but we are happy to suggest options.'],
            ['Booking', 'Do you book hotels or transfers?', 'No. Captains of Jawai runs safaris only. You book your own stay; tell us where you are staying and we will arrange where to meet for each drive.'],
            ['Booking', 'Can I book and pay online?', 'No. Send a safari request with your date and drive time and we will confirm availability and the price personally, usually within 12 hours. Nothing is charged until you confirm.'],
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
        $wild = $add('header', 'Jawai', '/jawai/', 2);
        $add('header', 'About Jawai', '/jawai/', 1, $wild);
        $add('header', 'Rabari Heritage', '/experiences/rabari-culture/', 2, $wild);
        $add('header', 'Boulder Sundowners', '/experiences/boulder-sundowners/', 3, $wild);
        $add('header', 'Gallery', '/gallery/', 4, $wild);
        $add('header', 'Field Journal', '/journal/', 4);
        $about = $add('header', 'About', '/about/', 5);
        $add('header', 'Our Story & Captains', '/about/', 1, $about);
        $add('header', 'Conservation & Ethics', '/about/conservation-ethics/', 2, $about);
        $add('header', 'FAQ', '/faq/', 3, $about);
        $add('header', 'Contact', '/contact/', 4, $about);

        foreach ([['Expeditions', '/safaris/'], ['Jawai guide', '/jawai/'], ['Rabari Heritage', '/experiences/rabari-culture/'], ['Gallery', '/gallery/']] as $n => [$l, $u]) {
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
