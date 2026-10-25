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
    /** @param bool $resetImages on a fresh install, apply the full built-in image map (false: only fill empty slots) */
    public static function run(bool $demoEnquiries = true, bool $resetImages = true): void
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
        Articles::ensure();
        // Every image slot gets a photo, so the site is presentation-ready straight after install.
        Demo::assignImages($fresh && $resetImages); // on a fresh install, apply the full curated image map
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
                'duration' => 'About 3 hours', 'timing' => 'Morning 6:00–9:00 AM · Evening 4:00–7:00 PM', 'best_season' => 'October – March',
                'group_size' => 'Private open 4x4 gypsy, up to 6 guests', 'image_id' => $i('leopard1'), 'is_featured' => 1,
                'highlights' => "Private open 4x4 gypsy with an experienced local tracker\nTracking by langur and peafowl alarm calls\nEngine-off observation at ethical distances\nBinoculars on board, beanbags for photographers\nHot tea or coffee served in the field",
                'body' => '<h2>The anatomy of a tracking drive</h2><p>Long before sunrise, your Captain is already listening. In Jawai the leopards do not hide in dense forest. They rest on and inside the great granite domes, and the land itself tells you where they are. A langur barking from a ridge, a peacock\'s sharp alarm, fresh pugmarks in a sandy riverbed: each sign is read and weighed.</p><p>As the sun warms the rock, leopards often emerge onto ledges and cave mouths to bask. We position the vehicle quietly, switch off the engine and simply wait. Patience is the whole art.</p><h2>Ethical distance standards</h2><p>We never block an animal\'s path, never crowd a sighting and never use calls or bait. Engines are switched off during observation and we leave before the animal is disturbed. A leopard sighting is never guaranteed (any operator who promises one is misleading you), but the open granite terrain gives Jawai one of the highest daytime observation probabilities anywhere.</p><h2>What to bring</h2><ul><li>Neutral clothing: khaki, olive, stone. Warm layers for winter mornings (it can be 8–12°C at dawn).</li><li>A 100–400mm or 70–200mm lens for photographers; we carry beanbags.</li><li>Hat, sunscreen, and a scarf for dusty tracks.</li></ul>',
                'meta_title' => 'Jawai Leopard Safari | Morning & Evening 4x4 in Bera',
                'meta_description' => 'Jawai leopard safari from {price} per jeep: morning (6–9 AM) and evening (4–7 PM) drives with expert local trackers in private open 4x4 gypsies in Bera.',
            ],
            [
                'title' => 'Jawai Bandh Wetland & Raptor Drive', 'slug' => 'dam-birding-safari', 'category' => 'Avian Wetland',
                'tagline' => 'Flamingos, cranes and basking mugger crocodiles on the great reservoir.',
                'excerpt' => 'A slower, scope-led exploration of the reservoir shoreline, winter flamingo flocks, demoiselle cranes, raptors and the resident marsh crocodiles.',
                'duration' => 'About 3 hours', 'timing' => 'Morning 6:00–9:00 AM · Evening 4:00–7:00 PM', 'best_season' => 'November – February for migrants',
                'group_size' => 'Private open 4x4 gypsy, up to 6 guests', 'image_id' => $i('bird1'), 'is_featured' => 1,
                'highlights' => "Greater flamingo, demoiselle crane and bar-headed goose (winter)\nMugger crocodiles basking on sandy shoals\nOsprey, eagles and falcons over the water\nSpotting scope and field guides on board",
                'body' => '<h2>The largest reservoir in western Rajasthan</h2><p>Commissioned by Maharaja Umaid Singh of Jodhpur in 1946 and completed in 1957, Jawai Bandh is a lifeline for people and wildlife alike. Each winter it becomes a staging ground on the Central Asian Flyway.</p><h2>Key species</h2><ul><li>Greater and lesser flamingo</li><li>Demoiselle crane and bar-headed goose</li><li>Painted stork, Eurasian spoonbill, river tern</li><li>Osprey, steppe and tawny eagle, peregrine falcon</li><li>Mugger (marsh) crocodile</li></ul><h2>The experience</h2><p>We work the water\'s edge slowly with binoculars and a spotting scope, stopping where the light and birds are best. It pairs beautifully with an evening leopard drive on the same day.</p>',
                'meta_title' => 'Jawai Dam Bird Watching & Mugger Crocodile Safari',
                'meta_description' => 'See mugger crocodiles and over 100 bird species, from greater flamingos to bar-headed geese and cranes, on a guided 4x4 wetland safari at Jawai Bandh.',
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
                'duration' => 'Multi-drive programmes', 'timing' => 'Golden hours, morning and evening', 'best_season' => 'October – March (best light November – February)',
                'group_size' => 'Max 3 photographers per vehicle', 'image_id' => $i('leopard2'), 'is_featured' => 0,
                'highlights' => "Vehicle limited to 3 photographers for clear angles\nBeanbags and charging on board\nPositioning for backlight and rim light on granite\nEthics first: no baiting, no calls, no crowding",
                'body' => '<h2>Light, patience and position</h2><p>The granite domes of Jawai create extraordinary photographic stages: a leopard silhouetted against a dawn sky, warm rim light on spotted fur, a cub peering from a cave mouth. Your Captain plans each drive around the sun as much as the animals.</p><h2>Recommended kit</h2><ul><li>Primary: 100–400mm, 150–600mm or a 500mm prime</li><li>Secondary: 24–70mm for environmental and landscape frames</li><li>Fast cards and spare batteries (charging available on board)</li></ul><p>Drone flying is not permitted around wildlife.</p>',
                'meta_title' => 'Jawai Wildlife Photography Safari | Leopards on Granite',
                'meta_description' => 'Photography-focused leopard expeditions in Jawai: small groups, beanbag mounts and golden-hour positioning by expert trackers.',
            ],
        ];
    }

    private static function pages(callable $i): array
    {
        $legal = ''; // legal pages: have them reviewed by your adviser and edit them in Admin → Pages
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
                'meta_title' => 'Cookie Policy', 'meta_description' => 'How the Captains of Jawai website uses cookies: one essential session cookie for forms, plus optional analytics if enabled.',
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
            ['Planning', 'How do we reach Jawai?', 'Udaipur Airport is about 130 km (2.5 to 3 hours by road) and Jodhpur Airport about 175 km (3.5 hours). The nearest railway station, Sumerpur Jawai Bandh, is about 18 km away, and Falna about 37 km. We run safaris only and do not arrange transfers, but we are happy to suggest options.'],
            ['Booking', 'Do you book hotels or transfers?', 'No. Captains of Jawai runs safaris only. You book your own stay; tell us where you are staying and we will arrange where to meet for each drive.'],
            ['Booking', 'Can I book and pay online?', 'No. Send a safari request with your date and drive time and we will confirm availability and the price personally, usually within 12 hours. Nothing is charged until you confirm.'],
            ['Booking', 'Is Jawai suitable for children and older travellers?', 'Very much so. Private vehicles let us set a gentle pace, shorten drives and plan comfortable breaks.'],
            ['Booking', 'What should I wear?', 'Neutral colours, warm layers for winter mornings, a hat and closed shoes. Modest clothing is appreciated on village visits.'],
        ];
    }

    /** Default menus for a safari-only site. The Jawai guide pages are added under "Jawai" by JawaiGuide::ensure(). */
    public static function menus(): void
    {
        $add = function (string $loc, string $label, string $url, int $sort, ?int $parent = null): int {
            return DB::insert('menu_items', ['location' => $loc, 'label' => $label, 'url' => $url, 'parent_id' => $parent, 'sort_order' => $sort, 'is_active' => 1, 'new_tab' => 0]);
        };
        $saf = $add('header', 'Safaris', '/safaris/', 1);
        $add('header', 'All safaris', '/safaris/', 1, $saf);
        $add('header', 'Classic Leopard Safari', '/safaris/leopard-safari/', 2, $saf);
        $add('header', 'Private 4x4 Safari', '/safaris/private-safari/', 3, $saf);
        $add('header', 'Wetland & Birding', '/safaris/dam-birding-safari/', 4, $saf);
        $add('header', 'Photography Safari', '/safaris/photography-safari/', 5, $saf);
        $wild = $add('header', 'Jawai', '/jawai/', 2);
        $add('header', 'Jawai at a glance', '/jawai/', 1, $wild);
        $add('header', 'Gallery', '/gallery/', 3);
        $about = $add('header', 'About', '/about/', 4);
        $add('header', 'Our story', '/about/', 1, $about);
        $add('header', 'Conservation & Ethics', '/about/conservation-ethics/', 2, $about);
        $add('header', 'FAQ', '/faq/', 3, $about);
        $add('header', 'Contact', '/contact/', 4, $about);

        foreach ([['Safaris', '/safaris/'], ['Book a safari', '/book-a-safari/'], ['Jawai guide', '/jawai/'], ['Jawai culture', '/jawai/culture/'], ['Gallery', '/gallery/']] as $n => [$l, $u]) {
            $add('footer', $l, $u, $n + 1);
        }
        foreach ([['About us', '/about/'], ['Conservation & Ethics', '/about/conservation-ethics/'], ['FAQ', '/faq/'], ['Contact', '/contact/'], ['Ask a question', '/book-a-safari/#ask']] as $n => [$l, $u]) {
            $add('footer2', $l, $u, $n + 1);
        }
        foreach ([['Privacy Policy', '/privacy-policy/'], ['Terms', '/terms/'], ['Cookies', '/cookie-policy/'], ['Photo credits', '/photo-credits/'], ['Sitemap', '/sitemap.xml']] as $n => [$l, $u]) {
            $add('legal', $l, $u, $n + 1);
        }
    }
}
