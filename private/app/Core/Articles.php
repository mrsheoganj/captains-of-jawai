<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Travel articles that answer what people search before a Jawai trip (routes, booking, price, timings,
 * comparisons). Each one is an ordinary page in Admin → Pages at a short top-level address, written as
 * original text. Road distances are from OpenStreetMap routing from Bera (deploy/route_check.py), with
 * realistic driving times. Added once if missing; never overwrites an edited page.
 */
final class Articles
{
    private const WIKI_RESERVE = 'https://en.wikipedia.org/wiki/Jawai_Bandh_Leopard_Conservation_Reserve | Wikipedia: Jawai Bandh Leopard Conservation Reserve';
    private const WIKI_DAM = 'https://en.wikipedia.org/wiki/Jawai_Dam | Wikipedia: Jawai Dam';
    private const WIKI_STATION = 'https://en.wikipedia.org/wiki/Sumerpur_Jawai_Bandh_railway_station | Wikipedia: Sumerpur Jawai Bandh railway station';
    private const WIKI_RANTHAMBORE = 'https://en.wikipedia.org/wiki/Ranthambore_National_Park | Wikipedia: Ranthambore National Park';
    private const OSM = 'https://www.openstreetmap.org/ | OpenStreetMap (road distances)';

    /** slug => built-in photo name */
    private const IMAGES = [
        'udaipur-to-jawai' => 'granite-dome-dusk',
        'how-to-book-jawai-safari' => 'safari-jeep',
        'jawai-safari-price' => 'ridge-sighting',
        'morning-vs-evening-jawai-safari' => 'sunset-valley',
        'jawai-vs-ranthambore' => 'leopard-stalking',
        'is-jawai-a-national-park' => 'leopard-basking',
        'jawai-itinerary' => 'lake-reflection',
        'jodhpur-to-jawai' => 'granite-boulders',
        'ahmedabad-to-jawai' => 'bandh-shoreline',
        'jawai-in-winter' => 'flamingo-flock',
        'where-is-jawai' => 'landscape1',
        'delhi-to-jawai' => 'safari1',
        'mumbai-to-jawai' => 'granite-dome-dusk',
        'jaipur-to-jawai' => 'sunset-valley',
        'jawai-in-monsoon' => 'lake-reflection',
        'jawai-in-summer' => 'leopard1',
        'jawai-leopard-safari-what-to-expect' => 'leopard2',
        'what-to-wear-on-jawai-safari' => 'safari-guests',
        'jawai-with-kids' => 'safari-jeep',
        'jawai-photography-tips' => 'leopard-portrait',
    ];

    public static function pages(callable $img): array
    {
        $i = fn (string $slug) => $img(self::IMAGES[$slug]);
        $base = ['kicker' => 'Jawai travel guide', 'show_cta' => 1];
        return [
            $base + [
                'slug' => 'udaipur-to-jawai', 'title' => 'Udaipur to Jawai: distance, route and travel time', 'image_id' => $i('udaipur-to-jawai'),
                'intro' => 'Jawai is about 115 km from Udaipur city, around two and a half hours by car. How to make the trip, and what to see on the way.',
                'meta_title' => 'Udaipur to Jawai: Distance, Route & Time by Car | Jawai Safari',
                'meta_description' => 'Udaipur to Jawai is about 115 km (2.5 hours by car) and Udaipur airport about 130 km. Best route, taxi tips and stops at Ranakpur and Kumbhalgarh.',
                'body' => <<<'HTML'
<p>Udaipur is the usual gateway to Jawai. Most travellers fly or take the train into Udaipur and drive the rest of the way, arriving in time for an evening leopard safari.</p>

<h2>Distance and time</h2>
<table>
<thead><tr><th>From</th><th>Road distance to Bera, Jawai</th><th>Time by car</th></tr></thead>
<tbody>
<tr><td>Udaipur city</td><td>about 115 km</td><td>around 2.5 hours</td></tr>
<tr><td>Udaipur Airport (Maharana Pratap Airport, UDR)</td><td>about 130 km</td><td>2.5 to 3 hours</td></tr>
</tbody>
</table>
<p>Times are for a car with normal daytime traffic. The last stretch is on smaller country roads, so allow a little extra time and try not to arrive after dark.</p>

<h2>The best way to travel</h2>
<p><strong>By taxi or private car</strong> is the simplest option. Hotels in Udaipur and Jawai can arrange a one-way cab, and app-based or local taxis also make the trip. If you are driving yourself, the route is well signposted and your maps app will take you along the fastest road.</p>
<p><strong>By train</strong>, there is no direct line from Udaipur to Jawai. The nearest station to Jawai is <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km from Bera, with <strong>Falna (FA)</strong> about 37 km away. Most people find a car from Udaipur quicker.</p>

<h2>Make a day of it: Ranakpur and Kumbhalgarh</h2>
<p>With an early start, you can turn the drive into a sightseeing day. The marble Jain temple at <strong>Ranakpur</strong> is about 50 km from Bera, and <strong>Kumbhalgarh Fort</strong>, with its famous long wall, is about 80 km away. Both sit in the hills between Udaipur and Jawai. Visit one or both and reach Jawai by mid-afternoon for the 4:00 PM safari.</p>

<h2>Plan your safari around the drive</h2>
<ul>
<li><strong>Leaving Udaipur in the morning?</strong> You will be in Jawai in time for the evening safari (4:00 to 7:00 PM).</li>
<li><strong>Leaving after lunch?</strong> Rest on arrival and take the morning safari (6:00 to 9:00 AM) the next day.</li>
<li><strong>Going back to Udaipur?</strong> Take the morning safari, have breakfast and leave by late morning to catch an afternoon flight.</li>
</ul>
<p>Read more in <a href="/jawai/how-to-reach/">how to reach Jawai</a> and our <a href="/jawai-itinerary/">2-day Jawai itinerary</a>, or <a href="/book-a-safari/">book your Jawai safari</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Udaipur? | About 115 km by road from Udaipur city to Bera in Jawai, around two and a half hours by car. Udaipur airport is about 130 km away.',
                    'Is there a train from Udaipur to Jawai? | There is no direct train. The nearest stations to Jawai are Sumerpur Jawai Bandh (about 18 km from Bera) and Falna (about 37 km). A car from Udaipur is usually quicker.',
                    'Can I do Jawai as a day trip from Udaipur? | It is possible to drive over for an evening safari and return late, but it makes a long day. One night in Jawai lets you do an evening and a morning safari without rushing.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'how-to-book-jawai-safari', 'title' => 'How to book a Jawai safari', 'image_id' => $i('how-to-book-jawai-safari'),
                'intro' => 'Booking a Jawai leopard safari takes a few minutes: choose a drive, send your date, and confirm the price in writing. Here is how it works.',
                'meta_title' => 'How to Book a Jawai Safari Online: Steps, Price & Timings',
                'meta_description' => 'How to book a Jawai leopard safari: choose morning (6–9 AM) or evening (4–7 PM), send your date online or on WhatsApp, and confirm. Prices from {price} per jeep.',
                'body' => <<<'HTML'
<p>Jawai safaris run every day, in the morning and in the evening, in open 4x4 gypsies with a local tracker. Jeeps are limited, especially from October to March and on holiday weekends, so it is worth booking a few days ahead.</p>

<h2>Step by step</h2>
<ol>
<li><strong>Choose your safari.</strong> The <a href="/safaris/leopard-safari/">Classic Leopard Tracking Safari</a> is the one most people come for. Birders may prefer the <a href="/safaris/dam-birding-safari/">Jawai Dam wetland drive</a>, and photographers the <a href="/safaris/photography-safari/">photography safari</a>.</li>
<li><strong>Pick a time.</strong> Morning (6:00 to 9:00 AM) or evening (4:00 to 7:00 PM). Not sure? Read <a href="/morning-vs-evening-jawai-safari/">morning vs evening safari in Jawai</a>.</li>
<li><strong>Send a booking request.</strong> Use the <a href="/book-a-safari/">Book a safari</a> form or message us on WhatsApp with your date, safari time, number of guests and where you are staying.</li>
<li><strong>Get a reply.</strong> We reply personally, usually within 12 hours, with availability and the price for your group.</li>
<li><strong>Confirm.</strong> Nothing is charged until you confirm with us directly. We then share the pickup point and time.</li>
</ol>

<h2>What it costs</h2>
<p>Jawai leopard safaris start <strong>from {price} per jeep</strong>. The jeep is private to your group and seats up to six guests, so the price per person drops as your group grows. Longer private expeditions and photography drives cost more. See <a href="/jawai-safari-price/">Jawai safari price</a> for the details.</p>

<h2>Tips for booking</h2>
<ul>
<li>Book <strong>both a morning and an evening</strong> drive if you can. Two or three drives give you a much better chance of seeing a leopard.</li>
<li>Tell us your <strong>hotel</strong> when you book, so we can arrange the meeting point.</li>
<li>Travelling with children or older guests? Let us know: we plan a gentler drive.</li>
<li>Beware of sites that promise "guaranteed" sightings. Leopards are wild animals and no honest operator can guarantee one.</li>
</ul>
HTML,
                'faq' => implode("\n", [
                    'How do I book a Jawai safari? | Send your date, safari time and number of guests through the Book a safari form on this website or on WhatsApp. We reply with availability and the price, and you confirm directly with us.',
                    'How far in advance should I book a Jawai safari? | A few days ahead is usually enough. From October to March and on long weekends and holidays, book one to two weeks ahead.',
                    'Do I pay online? | No. Nothing is charged when you send a booking request. You confirm and pay directly with us once the date, time and price are agreed.',
                    'How many people fit in one jeep? | Up to six guests in one open 4x4 gypsy, plus the driver and tracker.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'jawai-safari-price', 'title' => 'Jawai safari price', 'image_id' => $i('jawai-safari-price'),
                'intro' => 'Jawai leopard safaris start from {price} per jeep for your group. What is included, what changes the price, and how to get the best value.',
                'meta_title' => 'Jawai Safari Price 2026: Leopard Safari Cost per Jeep',
                'meta_description' => 'Jawai safari price: leopard safaris from {price} per jeep (up to 6 guests), morning or evening. What is included and what affects the cost.',
                'body' => <<<'HTML'
<p>In Jawai the safari is priced <strong>per jeep</strong>, not per person. You get an open 4x4 gypsy for your own group, a driver and a local tracker.</p>

<h2>Prices at a glance</h2>
<table>
<thead><tr><th>Safari</th><th>Duration</th><th>Price</th></tr></thead>
<tbody>
<tr><td><a href="/safaris/leopard-safari/">Classic Leopard Tracking Safari</a>, morning or evening</td><td>about 3 hours</td><td>from {price} per jeep</td></tr>
<tr><td><a href="/safaris/dam-birding-safari/">Jawai Dam wetland and birding drive</a></td><td>about 3 hours</td><td>on request</td></tr>
<tr><td><a href="/safaris/private-safari/">Private 4x4 expedition</a> (half or full day)</td><td>flexible</td><td>on request</td></tr>
<tr><td><a href="/safaris/photography-safari/">Photography safari</a></td><td>multi-drive</td><td>on request</td></tr>
</tbody>
</table>
<p>Prices can change with the season and on holidays; we always confirm the exact price in writing before you book.</p>

<h2>What is included</h2>
<ul>
<li>A private open 4x4 gypsy for your group (up to six guests)</li>
<li>An experienced driver and local tracker</li>
<li>About three hours in the granite hills at the best light of the day</li>
</ul>

<h2>Price per person</h2>
<p>Because you pay per jeep, sharing makes it cheaper: with six guests, a {price} jeep works out at about {price_each} each. Two people pay the same jeep price but have the whole vehicle to themselves.</p>

<h2>Getting the best value</h2>
<ul>
<li><strong>Book two drives.</strong> A morning and an evening safari together give you two very different chances to see leopards.</li>
<li><strong>Avoid the busiest dates</strong> (Diwali, Christmas to New Year, long weekends) if you can.</li>
<li><strong>Compare like for like.</strong> A low price for a crowded or shared vehicle, or a short drive, is not the same as a private three-hour safari with a tracker.</li>
</ul>
<p>Ready? <a href="/book-a-safari/">Send a booking request</a> and we will reply with availability for your date.</p>
HTML,
                'faq' => implode("\n", [
                    'What is the price of a Jawai safari? | Jawai leopard safaris with Captains of Jawai start from {price} per jeep for a morning or evening drive of about three hours. The jeep is private to your group and seats up to six guests.',
                    'Is the Jawai safari price per person or per jeep? | Per jeep. Up to six guests share one open 4x4 gypsy, so the price per person goes down as your group gets bigger.',
                    'Are there extra charges? | We confirm the full price in writing before you book. Longer private expeditions, photography drives and holiday dates are priced separately.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'morning-vs-evening-jawai-safari', 'title' => 'Morning or evening safari in Jawai?', 'image_id' => $i('morning-vs-evening-jawai-safari'),
                'intro' => 'Both drives can bring a leopard sighting, but they feel very different. How to choose between the 6 AM and 4 PM safari, and why many guests do both.',
                'meta_title' => 'Morning vs Evening Safari in Jawai: Which Is Better?',
                'meta_description' => 'Jawai safari timings compared: the morning drive (6–9 AM) and the evening drive (4–7 PM). Light, temperature, leopard activity and which to choose.',
                'body' => <<<'HTML'
<p>Leopards in Jawai are most active around dawn and dusk, which is why safaris run at the edges of the day: <strong>6:00 to 9:00 AM</strong> and <strong>4:00 to 7:00 PM</strong>.</p>

<h2>The morning safari (6:00 to 9:00 AM)</h2>
<ul>
<li>Leopards are often still out after a night of hunting, moving back to the rocks and caves.</li>
<li>Cool air and soft light, with mist over the dam on winter mornings.</li>
<li>Good birding around the reservoir as the day starts.</li>
<li>Winter mornings are cold in an open jeep: bring a warm jacket, cap and gloves.</li>
</ul>

<h2>The evening safari (4:00 to 7:00 PM)</h2>
<ul>
<li>Leopards come out onto the warm granite as the heat goes out of the day.</li>
<li>Golden light on the rocks makes for the best photographs.</li>
<li>Easier timing if you arrive in Jawai the same day.</li>
<li>Sunset over the hills and the dam is a highlight in itself.</li>
</ul>

<h2>Which should you choose?</h2>
<table>
<thead><tr><th>If you…</th><th>Choose</th></tr></thead>
<tbody>
<tr><td>arrive in Jawai the same day</td><td>Evening</td></tr>
<tr><td>love photography</td><td>Evening (warm light), plus a morning for variety</td></tr>
<tr><td>also want birds at the dam</td><td>Morning</td></tr>
<tr><td>leave Jawai the same day</td><td>Morning</td></tr>
<tr><td>want the best chance of a sighting</td><td>Both</td></tr>
</tbody>
</table>
<p>Every drive is different and sightings are never guaranteed, so two or three drives over a stay give you the best odds. <a href="/book-a-safari/">Book your safari times</a>, or see the <a href="/jawai-safari-price/">Jawai safari price</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'What are the Jawai safari timings? | Morning safaris run from 6:00 to 9:00 AM and evening safaris from 4:00 to 7:00 PM. Exact start times shift slightly with sunrise and sunset through the year.',
                    'Which is better in Jawai, the morning or the evening safari? | Both can bring leopard sightings. The evening has warmer light for photos and suits same-day arrivals; the morning is cooler and better for birds. Many guests do one of each.',
                    'Is it cold on a morning safari in Jawai? | Yes, from December to February the open jeep is cold before sunrise. Bring a warm jacket, a cap and gloves.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'jawai-vs-ranthambore', 'title' => 'Jawai vs Ranthambore: which safari should you choose?', 'image_id' => $i('jawai-vs-ranthambore'),
                'intro' => 'Ranthambore is about tigers in a national park; Jawai is about leopards living among villages on granite hills. How the two safaris compare.',
                'meta_title' => 'Jawai vs Ranthambore: Leopard or Tiger Safari in Rajasthan?',
                'meta_description' => 'Jawai vs Ranthambore compared: leopards on granite hills vs tigers in a national park. Safari style, booking, best season and how to combine both.',
                'body' => <<<'HTML'
<p>Rajasthan has two very different big-cat safaris. <strong>Ranthambore</strong>, in Sawai Madhopur district, is one of India's best-known tiger reserves. <strong>Jawai</strong>, in Pali district, is famous for leopards that live in granite hills alongside Rabari herders and their villages.</p>

<h2>Side by side</h2>
<table>
<thead><tr><th></th><th>Jawai</th><th>Ranthambore</th></tr></thead>
<tbody>
<tr><td>Main animal</td><td>Leopard</td><td>Tiger</td></tr>
<tr><td>Landscape</td><td>Granite hills, caves, the Jawai Dam reservoir and farmland</td><td>Dry deciduous forest, lakes and an old fort</td></tr>
<tr><td>Setting</td><td>Open countryside shared with villages and herders</td><td>National park and tiger reserve</td></tr>
<tr><td>Safari vehicles</td><td>Private open 4x4 gypsy for your group</td><td>Forest department gypsies and larger canters on fixed routes in zones</td></tr>
<tr><td>Booking</td><td>Directly with a local operator</td><td>Through the forest department's permit system</td></tr>
<tr><td>Monsoon</td><td>Drives continue, weather permitting</td><td>Core zones close for the monsoon</td></tr>
</tbody>
</table>

<h2>Why choose Jawai</h2>
<ul>
<li>A private jeep and flexible, unhurried drives instead of shared vehicles on fixed routes.</li>
<li>Leopards seen on open rock, often in full view against the sky.</li>
<li>A rare look at people and big cats living side by side, plus winter birds at the dam.</li>
<li>An easy add-on to Udaipur, Kumbhalgarh and Ranakpur.</li>
</ul>

<h2>Why choose Ranthambore</h2>
<ul>
<li>It is one of the best places in the world to see a wild tiger.</li>
<li>Rich forest, lakes and the historic Ranthambore Fort.</li>
</ul>

<h2>Doing both</h2>
<p>Jawai is about 450 km by road from Sawai Madhopur, a full day's drive, so most travellers combine them by train or plan Jawai with the Udaipur–Jodhpur side of Rajasthan and Ranthambore with Jaipur. See our <a href="/jawai-itinerary/">Jawai itinerary</a> and <a href="/book-a-safari/">book a Jawai safari</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'Is Jawai better than Ranthambore? | They are different. Ranthambore is for tigers in a national park; Jawai is for leopards on open granite hills with a private jeep. If leopards and a less crowded, more personal safari appeal to you, choose Jawai.',
                    'Can you see tigers in Jawai? | No. Jawai is known for leopards, along with hyenas, sloth bears, crocodiles and birds. For tigers, visit Ranthambore.',
                    'How far is Jawai from Ranthambore? | About 450 km by road, a full day of driving.',
                ]),
                'sources' => implode("\n", [self::WIKI_RANTHAMBORE, self::WIKI_RESERVE, self::OSM]),
            ],
            $base + [
                'slug' => 'is-jawai-a-national-park', 'title' => 'Is Jawai a national park?', 'image_id' => $i('is-jawai-a-national-park'),
                'intro' => 'No. Jawai is open countryside with villages, farms and granite hills, and part of it is a leopard conservation reserve. What that means for your safari.',
                'meta_title' => 'Is Jawai a National Park? Jawai Leopard Reserve Explained',
                'meta_description' => 'Is Jawai a national park? No: it is countryside around Jawai Dam with a small leopard conservation reserve. How safaris work, permits and responsible viewing.',
                'body' => <<<'HTML'
<p>Many people expect Jawai to be a fenced national park with gates and zones, like Ranthambore or Kanha. It is not. Jawai is a stretch of granite hill country around the <strong>Jawai Dam</strong> in Pali district, Rajasthan, where villages, farms, temples and wild leopards share the same land.</p>

<h2>The Jawai Bandh Leopard Conservation Reserve</h2>
<p>A small area of the hills, about 19.8 km², is protected as the <strong>Jawai Bandh Leopard Conservation Reserve</strong>. Under Indian law, a conservation reserve sits between a protected area and ordinary land: it protects wildlife habitat while local people keep their traditional rights and ways of life. The leopards themselves range well beyond the reserve, across the wider Jawai and Bera area.</p>

<h2>What this means for your safari</h2>
<ul>
<li><strong>No park gates or fixed zones.</strong> Safaris follow village roads and hill tracks with a local tracker who knows where leopards rest.</li>
<li><strong>Booked with a local operator</strong>, not through a national-park permit portal.</li>
<li><strong>Village life is part of the experience.</strong> You will pass Rabari herders, temples and fields.</li>
<li><strong>Respect matters even more.</strong> Good operators keep their distance, switch engines off at sightings, never bait animals and respect villagers and shrines.</li>
</ul>

<h2>Why the leopards live with people here</h2>
<p>The granite hills are full of caves and crevices, perfect leopard dens. For generations the local Rabari herders have tolerated the cats, and the temples on the hills are often visited by leopards. Read more in <a href="/jawai/leopards/">the leopards of Jawai</a> and <a href="/jawai/culture/">Jawai culture</a>.</p>
<p>Ready to see them? <a href="/book-a-safari/">Book a Jawai safari</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'Is Jawai a national park? | No. Jawai is countryside around the Jawai Dam with villages and farms. A small part of the hills, about 19.8 km², is the Jawai Bandh Leopard Conservation Reserve.',
                    'Do I need a forest permit for a Jawai safari? | Jawai safaris are booked with local operators and run on village and hill tracks, not through a national-park permit system. Your operator handles the arrangements for the drive.',
                    'Is Jawai a tiger reserve? | No. Jawai is known for leopards. Rajasthan\'s best-known tiger reserve is Ranthambore.',
                ]),
                'sources' => implode("\n", [self::WIKI_RESERVE, self::WIKI_DAM]),
            ],
            $base + [
                'slug' => 'jawai-itinerary', 'title' => 'Jawai itinerary: 2 days in Jawai', 'image_id' => $i('jawai-itinerary'),
                'intro' => 'Two days is the sweet spot for Jawai: three leopard safaris, the dam, a hill temple and village life. A simple plan, plus ideas for a third day.',
                'meta_title' => 'Jawai Itinerary: 2-Day Plan with Leopard Safaris',
                'meta_description' => 'A 2-day Jawai itinerary: evening and morning leopard safaris, Jawai Dam, temples and Rabari villages, plus a third day at Ranakpur or Kumbhalgarh.',
                'body' => <<<'HTML'
<p>This plan assumes you arrive from Udaipur, Jodhpur or Ahmedabad by road, and stay two nights near Bera or Jawai Bandh.</p>

<h2>Day 1: arrive and first safari</h2>
<ul>
<li><strong>Morning:</strong> drive to Jawai. From Udaipur, stop at Ranakpur's marble temple on the way.</li>
<li><strong>Afternoon:</strong> check in and rest.</li>
<li><strong>4:00 to 7:00 PM:</strong> evening <a href="/safaris/leopard-safari/">leopard safari</a> as the light turns gold on the granite.</li>
</ul>

<h2>Day 2: the full Jawai day</h2>
<ul>
<li><strong>6:00 to 9:00 AM:</strong> morning leopard safari, or the <a href="/safaris/dam-birding-safari/">Jawai Dam birding drive</a> in winter.</li>
<li><strong>Late morning:</strong> breakfast, then the <a href="/jawai/jawai-dam/">Jawai Dam</a> and one of the <a href="/jawai/places-to-visit/">hill temples</a>.</li>
<li><strong>Afternoon:</strong> a slow look at village life and Rabari herders, or rest.</li>
<li><strong>4:00 to 7:00 PM:</strong> second evening safari.</li>
</ul>

<h2>Day 3: last drive and onward</h2>
<ul>
<li><strong>6:00 to 9:00 AM:</strong> final morning safari.</li>
<li><strong>Late morning:</strong> leave for Udaipur (about 2.5 hours), Jodhpur (about 3 to 3.5 hours) or Ahmedabad (about 5 to 6 hours).</li>
</ul>

<h2>Adding a day</h2>
<p><strong>Kumbhalgarh Fort</strong> (about 80 km) and <strong>Ranakpur</strong> (about 50 km) make an easy day out between safaris, and <strong>Mount Abu</strong> is about 100 km away. A third night also means one more drive, and a better chance of a memorable sighting.</p>

<h2>What to pack</h2>
<p>Neutral-coloured clothes, a warm layer for morning drives from November to February, a cap, sunscreen, binoculars and a camera with a zoom lens.</p>
<p><a href="/book-a-safari/">Book your Jawai safaris</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'How many days are enough for Jawai? | Two nights is ideal: it gives you three safaris (evening, morning, evening or morning) and time for the dam and a temple. One night works if you are short of time.',
                    'How many safaris should I do in Jawai? | At least two, and three if you can. Each drive is different and more drives give you a better chance of seeing leopards.',
                    'What else is there to do in Jawai? | Visit the Jawai Dam, hill temples such as the Dev Giri cave temple, see Rabari village life, and take day trips to Ranakpur and Kumbhalgarh.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM, self::OSM]),
            ],
            $base + [
                'slug' => 'jodhpur-to-jawai', 'title' => 'Jodhpur to Jawai: distance and how to get there', 'image_id' => $i('jodhpur-to-jawai'),
                'intro' => 'Jawai is about 170 km from Jodhpur, around three to three and a half hours by car via Pali. Road, train and timing tips.',
                'meta_title' => 'Jodhpur to Jawai: Distance, Route & Travel Time',
                'meta_description' => 'Jodhpur to Jawai is about 170 km, around 3 to 3.5 hours by car via Pali. Jodhpur airport is about 175 km. Trains to Falna and Jawai Bandh.',
                'body' => <<<'HTML'
<p>Jodhpur is the other main gateway to Jawai, and a natural next stop if you are touring western Rajasthan.</p>

<h2>Distance and time</h2>
<table>
<thead><tr><th>From</th><th>Road distance to Bera, Jawai</th><th>Time by car</th></tr></thead>
<tbody>
<tr><td>Jodhpur city</td><td>about 170 km</td><td>3 to 3.5 hours</td></tr>
<tr><td>Jodhpur Airport (JDH)</td><td>about 175 km</td><td>about 3.5 hours</td></tr>
</tbody>
</table>
<p>The road runs south-east through <strong>Pali</strong>, then on smaller roads to Jawai. Times are for daytime driving with normal traffic.</p>

<h2>By train</h2>
<p>Trains from Jodhpur towards Ahmedabad and Mumbai run via Marwar Junction and stop at <strong>Falna (FA)</strong>, about 37 km from Bera, and some also at <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km away. Check current timetables on the Indian Railways website, and arrange a car from the station.</p>

<h2>Timing your safari</h2>
<p>Leave Jodhpur by late morning to reach Jawai in time for the 4:00 PM evening safari. Leaving Jawai for Jodhpur, take the 6:00 AM morning safari first and set off after breakfast.</p>
<p>See also <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>, <a href="/jawai/how-to-reach/">how to reach Jawai</a>, or <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Jodhpur? | About 170 km by road from Jodhpur city to Bera in Jawai, around three to three and a half hours by car via Pali.',
                    'Which is closer to Jawai, Udaipur or Jodhpur? | Udaipur is closer: about 115 km and two and a half hours, against about 170 km and three hours or more from Jodhpur.',
                    'What is the nearest railway station to Jawai? | Sumerpur Jawai Bandh (JWB), about 18 km from Bera. Falna (FA), about 37 km away, has more express trains.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'ahmedabad-to-jawai', 'title' => 'Ahmedabad to Jawai: distance, route and trains', 'image_id' => $i('ahmedabad-to-jawai'),
                'intro' => 'Jawai is about 285 km from Ahmedabad, five to six hours by car, and on the main railway line. A popular weekend trip from Gujarat.',
                'meta_title' => 'Ahmedabad to Jawai: Distance, Route, Trains & Weekend Plan',
                'meta_description' => 'Ahmedabad to Jawai is about 285 km, 5 to 6 hours by car, with trains to Falna and Sumerpur Jawai Bandh. A simple weekend plan with two leopard safaris.',
                'body' => <<<'HTML'
<p>Jawai is one of the easiest wildlife weekends from Gujarat: a day's drive or an overnight train, and you can be on a leopard safari the same evening.</p>

<h2>By road</h2>
<p>Ahmedabad to Bera in Jawai is <strong>about 285 km</strong>, usually <strong>5 to 6 hours</strong> by car. The route heads north through Palanpur and Abu Road into Rajasthan. Leave early to reach Jawai for the 4:00 PM evening safari.</p>

<h2>By train</h2>
<p>Jawai lies on the main line between Ahmedabad and Jaipur and Delhi. Trains stop at <strong>Falna (FA)</strong>, about 37 km from Bera, and some at <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km away. An overnight train lets you arrive early, rest, and still enjoy a full day. Check current timetables on the Indian Railways website.</p>

<h2>A weekend plan</h2>
<table>
<thead><tr><th>When</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>Saturday morning</td><td>Drive from Ahmedabad (5 to 6 hours)</td></tr>
<tr><td>Saturday 4:00 to 7:00 PM</td><td>Evening leopard safari</td></tr>
<tr><td>Sunday 6:00 to 9:00 AM</td><td>Morning leopard safari</td></tr>
<tr><td>Sunday late morning</td><td>Jawai Dam, then drive home</td></tr>
</tbody>
</table>
<p>Safaris start from {price} per jeep for up to six guests, which makes Jawai good value for a family or a group of friends. <a href="/book-a-safari/">Book your weekend safaris</a>, or see our <a href="/jawai-itinerary/">2-day Jawai itinerary</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Ahmedabad? | About 285 km by road to Bera in Jawai, usually five to six hours by car via Palanpur and Abu Road.',
                    'Is there a train from Ahmedabad to Jawai? | Yes. Trains on the Ahmedabad–Jaipur–Delhi line stop at Falna, about 37 km from Bera, and some at Sumerpur Jawai Bandh, about 18 km away.',
                    'Is Jawai good for a weekend trip from Ahmedabad? | Yes. Leave on Saturday morning for an evening safari, take a morning safari on Sunday and drive back the same day.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'jawai-in-winter', 'title' => 'Jawai in winter', 'image_id' => $i('jawai-in-winter'),
                'intro' => 'October to March is the best time for Jawai: cool days, active leopards and flamingos and cranes at the dam. Weather, wildlife and what to pack.',
                'meta_title' => 'Jawai in Winter: Weather, Leopards, Flamingos & Tips',
                'meta_description' => 'Visiting Jawai in winter (October to March): cool days, cold safari mornings, leopards on the rocks and migratory flamingos and cranes at Jawai Dam.',
                'body' => <<<'HTML'
<p>Winter is Jawai's main season. The heat of summer is gone, the hills are clear, and migratory birds arrive at the reservoir.</p>

<h2>Weather</h2>
<ul>
<li><strong>Days</strong> are pleasant and sunny, ideal for exploring between safaris.</li>
<li><strong>Mornings and nights</strong> are cold from December to February, and an open jeep at 6 AM feels colder still.</li>
<li><strong>October, November and March</strong> are milder at both ends of the day.</li>
</ul>

<h2>Wildlife</h2>
<ul>
<li><strong>Leopards</strong> often bask on the sun-warmed granite in the evening and early morning.</li>
<li><strong>Flamingos, demoiselle cranes, bar-headed geese</strong> and many ducks and waders visit the Jawai Dam reservoir in the cooler months.</li>
<li><strong>Mugger crocodiles</strong> haul out on the banks to warm up in the sun.</li>
</ul>
<p>A winter visit is perfect for combining a <a href="/safaris/leopard-safari/">leopard safari</a> with the <a href="/safaris/dam-birding-safari/">Jawai Dam birding drive</a>.</p>

<h2>What to pack</h2>
<ul>
<li>A warm jacket, cap or beanie and gloves for morning drives</li>
<li>Layers you can remove as the day warms up</li>
<li>Sunscreen and sunglasses</li>
<li>Binoculars for the birds at the dam</li>
</ul>

<h2>Book early</h2>
<p>Winter is the busiest time, especially from Diwali to New Year. Book your safaris one to two weeks ahead. Drives start from {price} per jeep. <a href="/book-a-safari/">Send a booking request</a>, or read the <a href="/jawai/best-time-to-visit/">month-by-month guide</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'Is winter a good time to visit Jawai? | Yes, October to March is the best season: pleasant days, active leopards and migratory birds such as flamingos and demoiselle cranes at the Jawai Dam.',
                    'How cold is Jawai in winter? | Days are pleasant, but early mornings from December to February are cold, especially in an open jeep. Bring a warm jacket, cap and gloves.',
                    'When do flamingos come to Jawai? | Flamingos and other migratory birds visit the Jawai Dam reservoir in the cooler months, mainly from about November to February, depending on water levels.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM]),
            ],
            $base + [
                'slug' => 'where-is-jawai', 'title' => 'Where is Jawai?', 'image_id' => $i('where-is-jawai'),
                'intro' => 'Jawai is in Pali district in southern Rajasthan, between Udaipur and Jodhpur, around the Jawai Dam near Sumerpur and Bera.',
                'meta_title' => 'Where is Jawai? Location, Map & Nearest Cities in Rajasthan',
                'meta_description' => 'Where is Jawai? In Pali district, Rajasthan, about 115 km from Udaipur and 170 km from Jodhpur, around the Jawai Dam near Bera and Sumerpur.',
                'body' => <<<'HTML'
<p>Jawai is not a single town but an area of granite hills, farmland and villages around the <strong>Jawai Dam (Jawai Bandh)</strong> in <strong>Pali district, Rajasthan</strong>. It lies in the foothills of the Aravalli range, roughly halfway between Udaipur and Jodhpur.</p>

<h2>Jawai on the map</h2>
<ul>
<li><strong>District:</strong> Pali, Rajasthan, India</li>
<li><strong>Nearest towns:</strong> Sumerpur and Sheoganj, with the villages of Bera and Jawai Bandh at the heart of leopard country</li>
<li><strong>Landmark:</strong> the Jawai Dam reservoir, built on the Jawai River between 1946 and 1957</li>
</ul>

<h2>How far is Jawai from…</h2>
<table>
<thead><tr><th>From</th><th>Road distance</th><th>Typical drive</th></tr></thead>
<tbody>
<tr><td><a href="/udaipur-to-jawai/">Udaipur</a></td><td>about 115 km</td><td>around 2.5 hours</td></tr>
<tr><td><a href="/jodhpur-to-jawai/">Jodhpur</a></td><td>about 170 km</td><td>3 to 3.5 hours</td></tr>
<tr><td>Mount Abu</td><td>about 100 km</td><td>2 to 2.5 hours</td></tr>
<tr><td><a href="/ahmedabad-to-jawai/">Ahmedabad</a></td><td>about 285 km</td><td>5 to 6 hours</td></tr>
<tr><td><a href="/jaipur-to-jawai/">Jaipur</a></td><td>about 390 km</td><td>6 to 7 hours</td></tr>
<tr><td><a href="/delhi-to-jawai/">Delhi</a></td><td>about 680 km</td><td>11 to 12 hours</td></tr>
<tr><td><a href="/mumbai-to-jawai/">Mumbai</a></td><td>about 800 km</td><td>13 to 14 hours</td></tr>
</tbody>
</table>
<p>The nearest airport is Udaipur (about 130 km) and the nearest railway station is Sumerpur Jawai Bandh, about 18 km from Bera.</p>

<h2>Why Jawai is famous</h2>
<p>Jawai is known across India for its <a href="/jawai/leopards/">leopards</a>, which live in the caves of the granite hills close to villages and temples, and for the Rabari herders who have shared this land with them for generations. In winter, flamingos and cranes gather at the dam. Read the full <a href="/jawai/">Jawai guide</a> or <a href="/book-a-safari/">book a Jawai safari</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'Which district is Jawai in? | Jawai is in Pali district, Rajasthan, around the Jawai Dam near Sumerpur and Bera.',
                    'Is Jawai near Udaipur? | Yes. Jawai is about 115 km from Udaipur city, around two and a half hours by car, and Udaipur airport is the nearest airport.',
                    'What is Jawai famous for? | Leopards living in granite hills alongside villages and Rabari herders, the Jawai Dam, and winter birds such as flamingos and cranes.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM, self::WIKI_RESERVE, self::OSM]),
            ],
            $base + [
                'slug' => 'delhi-to-jawai', 'title' => 'How to reach Jawai from Delhi', 'image_id' => $i('delhi-to-jawai'),
                'intro' => 'The quickest way from Delhi to Jawai is a flight to Udaipur and a 2.5-hour drive. Overnight trains to Falna and Jawai Bandh are a good alternative.',
                'meta_title' => 'Delhi to Jawai: Flights, Trains & Road Distance (680 km)',
                'meta_description' => 'How to reach Jawai from Delhi: fly to Udaipur or Jodhpur and drive, take an overnight train to Falna or Jawai Bandh, or drive about 680 km.',
                'body' => <<<'HTML'
<p>Jawai is about <strong>680 km by road from Delhi</strong>, so most travellers fly or take the train for most of the way.</p>

<h2>By air (fastest)</h2>
<p>Fly from Delhi to <strong>Udaipur (UDR)</strong>, then drive about 130 km, around two and a half to three hours, to Jawai. Flying to <strong>Jodhpur (JDH)</strong> also works, with about three and a half hours by road from there. Take a morning flight and you can be on the evening safari the same day.</p>

<h2>By train</h2>
<p>Jawai lies on the main railway line between Delhi and Ahmedabad. Trains on this route stop at <strong>Falna (FA)</strong>, about 37 km from Bera, and some also at <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km away. An overnight train from Delhi lets you arrive in the morning, rest, and take the evening safari. Check current timetables on the Indian Railways website, and arrange a car from the station.</p>

<h2>By road</h2>
<p>Driving takes <strong>11 to 12 hours</strong>, usually via Jaipur and Ajmer. It is best split over two days, for example with a night in Jaipur, Ajmer or Pushkar.</p>

<h2>Suggested plan from Delhi</h2>
<table>
<thead><tr><th>Day</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>Day 1</td><td>Morning flight to Udaipur, drive to Jawai, evening leopard safari</td></tr>
<tr><td>Day 2</td><td>Morning safari, Jawai Dam and a hill temple, evening safari</td></tr>
<tr><td>Day 3</td><td>Final morning safari, drive to Udaipur for an afternoon flight</td></tr>
</tbody>
</table>
<p>See our <a href="/jawai-itinerary/">2-day Jawai itinerary</a> and <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>, then <a href="/book-a-safari/">book your safaris</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Delhi? | About 680 km by road, 11 to 12 hours of driving. Most people fly to Udaipur and drive the last 130 km.',
                    'Which airport is best for Jawai from Delhi? | Udaipur, about 2.5 to 3 hours by road from Jawai. Jodhpur is the other option, about 3.5 hours away.',
                    'Is there a train from Delhi to Jawai? | Yes. Trains on the Delhi–Ahmedabad line stop at Falna, about 37 km from Bera, and some at Sumerpur Jawai Bandh, about 18 km away.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'mumbai-to-jawai', 'title' => 'How to reach Jawai from Mumbai', 'image_id' => $i('mumbai-to-jawai'),
                'intro' => 'From Mumbai, fly to Udaipur and drive 2.5 hours to Jawai, or take a train towards Rajasthan via Ahmedabad to Falna.',
                'meta_title' => 'Mumbai to Jawai: Flights, Trains & Best Route',
                'meta_description' => 'How to reach Jawai from Mumbai: fly to Udaipur (2.5 hours by road to Jawai), take a train via Ahmedabad to Falna, or drive about 800 km.',
                'body' => <<<'HTML'
<p>Jawai is about <strong>800 km by road from Mumbai</strong>. The easiest way is to fly most of the distance.</p>

<h2>By air (recommended)</h2>
<p>Fly from Mumbai to <strong>Udaipur (UDR)</strong>, about an hour and a half in the air, then drive about 130 km to Jawai (2.5 to 3 hours). Flights to <strong>Jodhpur</strong> are another option, about three and a half hours by road from Jawai. With a morning flight you can reach Jawai for the 4:00 PM evening safari.</p>

<h2>By train</h2>
<p>Several trains from Mumbai to Rajasthan run via Ahmedabad and Abu Road and stop at <strong>Falna (FA)</strong>, about 37 km from Bera; a few also stop at <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km away. An overnight train is relaxed and good value. Check current trains and timings on the Indian Railways website.</p>

<h2>By road</h2>
<p>The drive is <strong>13 to 14 hours</strong> via Ahmedabad. Most people who drive break the journey in Ahmedabad, then continue the next morning (about 5 to 6 hours to Jawai). See <a href="/ahmedabad-to-jawai/">Ahmedabad to Jawai</a>.</p>

<h2>How long to stay</h2>
<p>Two nights is ideal after a long journey: three safaris and time for the dam and a temple. See our <a href="/jawai-itinerary/">Jawai itinerary</a> and <a href="/book-a-safari/">book your safaris</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Mumbai? | About 800 km by road, 13 to 14 hours of driving. Most travellers fly to Udaipur and drive the last 130 km.',
                    'What is the best way to reach Jawai from Mumbai? | A flight to Udaipur followed by a 2.5 to 3 hour drive. Trains via Ahmedabad to Falna are a good-value alternative.',
                    'Which railway station is nearest to Jawai? | Sumerpur Jawai Bandh, about 18 km from Bera. Falna, about 37 km away, has more trains.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'jaipur-to-jawai', 'title' => 'Jaipur to Jawai: distance and how to get there', 'image_id' => $i('jaipur-to-jawai'),
                'intro' => 'Jawai is about 390 km from Jaipur, six to seven hours by road via Ajmer. Trains on the Jaipur–Ahmedabad line stop at Falna and Jawai Bandh.',
                'meta_title' => 'Jaipur to Jawai: Distance (390 km), Train & Road Route',
                'meta_description' => 'Jaipur to Jawai is about 390 km, 6 to 7 hours by car via Ajmer. Trains towards Ahmedabad stop at Falna and Sumerpur Jawai Bandh.',
                'body' => <<<'HTML'
<p>Many Rajasthan trips start in Jaipur. Jawai makes a great wildlife stop on the way south to Udaipur or west to Jodhpur.</p>

<h2>By road</h2>
<p>Jaipur to Bera in Jawai is <strong>about 390 km</strong>, usually <strong>6 to 7 hours</strong> by car. The route runs south-west via Ajmer and Beawar. Leave early to reach Jawai in time for the evening safari, or break the trip with a night in Ajmer or Pushkar.</p>

<h2>By train</h2>
<p>Trains from Jaipur towards Ahmedabad and Mumbai run via Ajmer and Marwar Junction and stop at <strong>Falna (FA)</strong>, about 37 km from Bera, with some stopping at <strong>Sumerpur Jawai Bandh (JWB)</strong>, about 18 km away. Check current timetables on the Indian Railways website.</p>

<h2>Fitting Jawai into a Rajasthan route</h2>
<table>
<thead><tr><th>Route</th><th>Idea</th></tr></thead>
<tbody>
<tr><td>Jaipur → Jawai → Udaipur</td><td>Two nights in Jawai, then about 2.5 hours on to Udaipur</td></tr>
<tr><td>Jaipur → Jodhpur → Jawai → Udaipur</td><td>The classic western loop; Jawai sits neatly between Jodhpur and Udaipur</td></tr>
<tr><td>Jaipur → Ranthambore → … → Jawai</td><td>Tigers and leopards on one trip; see <a href="/jawai-vs-ranthambore/">Jawai vs Ranthambore</a></td></tr>
</tbody>
</table>
<p><a href="/book-a-safari/">Book your Jawai safaris</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'How far is Jawai from Jaipur? | About 390 km by road to Bera in Jawai, six to seven hours by car via Ajmer.',
                    'Can I take a train from Jaipur to Jawai? | Yes. Trains towards Ahmedabad stop at Falna, about 37 km from Bera, and some at Sumerpur Jawai Bandh, about 18 km away.',
                    'Is Jawai on the way from Jaipur to Udaipur? | It is a detour but a popular one: Jawai is about 2.5 hours from Udaipur, so many travellers stop for two nights between Jaipur or Jodhpur and Udaipur.',
                ]),
                'sources' => implode("\n", [self::OSM, self::WIKI_STATION]),
            ],
            $base + [
                'slug' => 'jawai-in-monsoon', 'title' => 'Jawai in the monsoon', 'image_id' => $i('jawai-in-monsoon'),
                'intro' => 'July to September turns Jawai green: the dam fills, the hills soften and the crowds disappear. Is Jawai open in the monsoon, and what to expect.',
                'meta_title' => 'Jawai in Monsoon: Is It Open? Weather, Safaris & Tips',
                'meta_description' => 'Is Jawai open in the monsoon? Yes. July to September brings green hills, a full Jawai Dam and quiet roads. What to expect on a monsoon leopard safari.',
                'body' => <<<'HTML'
<p>Jawai is not a closed national park, so it stays open through the monsoon. From July to September the landscape changes completely: dry granite hills turn green, seasonal streams run and the <a href="/jawai/jawai-dam/">Jawai Dam</a> fills up.</p>

<h2>What the monsoon is like</h2>
<ul>
<li><strong>Green, dramatic scenery</strong> with clouds over the granite and fresh grass on the hills.</li>
<li><strong>Cooler than summer</strong>, but humid, with heavy showers on some days.</li>
<li><strong>Fewer visitors</strong>, so drives feel unhurried.</li>
<li>Peafowl dance and call, and the countryside is full of birdsong.</li>
</ul>

<h2>Safaris in the rain</h2>
<p>Drives run as usual whenever the weather allows. After heavy rain some tracks become muddy or slippery, so routes may change and a drive may occasionally be shortened or moved for safety. Leopards are still around the hills, though thicker green cover can make spotting harder than in the dry months.</p>

<h2>What to bring</h2>
<ul>
<li>A light rain jacket or poncho, and a cover for your camera</li>
<li>Quick-dry clothes and shoes with grip</li>
<li>Insect repellent for the evenings</li>
</ul>

<h2>Is it worth it?</h2>
<p>If you love landscapes and quiet, yes. If seeing a leopard is your main goal, the cooler dry months from October to March usually give better odds. See <a href="/jawai-in-winter/">Jawai in winter</a> and the <a href="/jawai/best-time-to-visit/">month-by-month guide</a>, or <a href="/book-a-safari/">book a monsoon safari</a> from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'Is Jawai open in the monsoon? | Yes. Jawai is not a closed national park, so safaris continue from July to September whenever the weather allows; heavy rain can change routes or shorten a drive.',
                    'Is the monsoon a good time to see leopards in Jawai? | Leopards are still there, but thicker green cover can make them harder to spot. October to March usually gives better odds.',
                    'What is Jawai like in the monsoon? | Green hills, a full Jawai Dam, cooler air than summer, occasional heavy showers and very few visitors.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM]),
            ],
            $base + [
                'slug' => 'jawai-in-summer', 'title' => 'Jawai in summer (April to June)', 'image_id' => $i('jawai-in-summer'),
                'intro' => 'Summer in Jawai is hot, often above 40°C in the afternoon, but early and late drives still bring leopards out on the rocks. How to visit in April, May and June.',
                'meta_title' => 'Jawai in Summer: April–June Weather, Leopards & Tips',
                'meta_description' => 'Visiting Jawai in April, May or June: very hot afternoons, early and late leopard safaris, thin vegetation and quiet roads. Tips for a summer trip.',
                'body' => <<<'HTML'
<p>From April to June, afternoons in Jawai regularly pass 40°C. It is the quietest season for visitors, and with the right timing it can still be rewarding.</p>

<h2>Leopards in the heat</h2>
<ul>
<li>Leopards rest in the cool caves and shade through the day and become active at <strong>dawn and dusk</strong>, exactly when safaris run.</li>
<li>Vegetation is thin and dry, so the open granite can make animals easier to see when they are out.</li>
<li>Animals are often drawn to water near the dam and village ponds.</li>
</ul>

<h2>Plan around the heat</h2>
<ul>
<li>Take the <strong>morning safari (from 6:00 AM)</strong>, when it is coolest, and the evening drive as the sun drops.</li>
<li>Rest indoors between about 11 AM and 4 PM.</li>
<li>Drink plenty of water, wear a hat and sunglasses, and use sunscreen.</li>
<li>Light, breathable, neutral-coloured clothes are best; see <a href="/what-to-wear-on-jawai-safari/">what to wear on a Jawai safari</a>.</li>
</ul>

<h2>Who should visit in summer</h2>
<p>Travellers who don't mind the heat and want quiet roads and unhurried drives. For pleasant weather, choose <a href="/jawai-in-winter/">winter</a>; for green landscapes, the <a href="/jawai-in-monsoon/">monsoon</a>. <a href="/book-a-safari/">Book a summer safari</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'Is Jawai good to visit in summer? | It is hot, often above 40°C in the afternoon, but early morning and evening safaris still bring good chances of leopards, with quiet roads and few visitors.',
                    'What time are summer safaris in Jawai? | The morning safari starts around 6:00 AM, the coolest part of the day, and the evening safari runs as the heat drops, around 4:00 to 7:00 PM.',
                    'How hot is Jawai in May? | May and early June are the hottest months, with afternoon temperatures often above 40°C.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'jawai-leopard-safari-what-to-expect', 'title' => 'Your first Jawai leopard safari: what to expect', 'image_id' => $i('jawai-leopard-safari-what-to-expect'),
                'intro' => 'From the pickup to the moment a leopard appears on the rocks: how a Jawai leopard safari works, how long it takes, and how likely you are to see one.',
                'meta_title' => 'Jawai Leopard Safari: What to Expect on Your First Drive',
                'meta_description' => 'What happens on a Jawai leopard safari: timings, the open jeep, how trackers find leopards, sighting chances, safety and tips for your first drive.',
                'body' => <<<'HTML'
<p>A Jawai safari is different from a national-park drive. There are no gates or zones: your tracker reads the hills, the villages and the animals to find leopards resting on the granite.</p>

<h2>Before the drive</h2>
<ul>
<li><strong>Timing:</strong> mornings 6:00 to 9:00 AM or evenings 4:00 to 7:00 PM, about three hours.</li>
<li><strong>Pickup:</strong> we agree a meeting point near your hotel when you book. Be ready 10 minutes early.</li>
<li><strong>Vehicle:</strong> an open 4x4 gypsy, private for your group (up to six guests), with a driver and a local tracker.</li>
</ul>

<h2>During the drive</h2>
<ol>
<li>The jeep heads into the granite hills on village roads and rough tracks; expect some bumps.</li>
<li>Your tracker scans the rocks and caves, listens for alarm calls from peafowl and langurs, and checks with herders and villagers.</li>
<li>When a leopard is found, the engine is switched off and the jeep stays at a respectful distance. You may watch for a few minutes or much longer.</li>
<li>Between sightings you may see the dam, crocodiles, birds, hyena dens, temples and Rabari herders with their flocks.</li>
</ul>

<h2>Will you see a leopard?</h2>
<p>Jawai offers some of the best leopard viewing in India, but leopards are wild and no honest operator can guarantee a sighting. Your chances rise with every drive, which is why we suggest <strong>two or three safaris</strong>. See <a href="/morning-vs-evening-jawai-safari/">morning vs evening safari</a>.</p>

<h2>Safety and etiquette</h2>
<ul>
<li>Stay seated, keep your hands inside the jeep and follow your tracker's instructions.</li>
<li>Talk quietly at sightings and never call out to animals.</li>
<li>Flash photography and drones are not allowed near wildlife.</li>
<li>Respect villagers and temples; ask before photographing people.</li>
</ul>
<p>Ready? <a href="/book-a-safari/">Book your first Jawai safari</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'How long is a Jawai leopard safari? | About three hours: mornings from 6:00 to 9:00 AM and evenings from 4:00 to 7:00 PM.',
                    'Is a leopard sighting guaranteed in Jawai? | No. Leopards are wild animals. Jawai offers excellent chances, and taking two or three drives improves them a lot.',
                    'Is a Jawai safari safe? | Yes, when you follow the rules: stay seated in the jeep, keep quiet at sightings and follow your tracker. Jeeps keep a respectful distance from animals.',
                    'Can I use a flash or a drone on safari? | No. Flash photography and drones disturb wildlife and are not allowed near animals.',
                ]),
                'sources' => implode("\n", [self::WIKI_RESERVE]),
            ],
            $base + [
                'slug' => 'what-to-wear-on-jawai-safari', 'title' => 'What to wear and pack for a Jawai safari', 'image_id' => $i('what-to-wear-on-jawai-safari'),
                'intro' => 'Neutral colours, layers for cold winter mornings, sun protection in summer and a rain shell in the monsoon. A simple packing list for every season.',
                'meta_title' => 'What to Wear on a Jawai Safari: Packing List by Season',
                'meta_description' => 'What to wear on a Jawai leopard safari: neutral colours, warm layers for winter mornings, sun protection in summer and rain gear in the monsoon.',
                'body' => <<<'HTML'
<p>You'll be in an open jeep for about three hours, often at the coldest or hottest edges of the day. Dress for comfort and blend in with the landscape.</p>

<h2>Every season</h2>
<ul>
<li><strong>Neutral colours:</strong> khaki, olive, brown, beige or grey. Avoid bright colours and white.</li>
<li><strong>Closed, comfortable shoes</strong> with some grip.</li>
<li>A cap or hat, sunglasses and sunscreen.</li>
<li>Binoculars and a camera with a zoom lens.</li>
<li>A small bag and a bottle of water.</li>
</ul>

<h2>By season</h2>
<table>
<thead><tr><th>Season</th><th>Add</th></tr></thead>
<tbody>
<tr><td>Winter (Nov–Feb)</td><td>Warm jacket, beanie or cap, gloves, scarf: morning drives are cold in an open jeep</td></tr>
<tr><td>Spring &amp; autumn (Oct, Mar)</td><td>A light fleece for the early morning, layers to remove later</td></tr>
<tr><td>Summer (Apr–Jun)</td><td>Light, breathable long sleeves, wide-brim hat, extra water</td></tr>
<tr><td>Monsoon (Jul–Sep)</td><td>Rain jacket or poncho, quick-dry clothes, a camera rain cover</td></tr>
</tbody>
</table>

<h2>Village visits</h2>
<p>Jawai's villages and temples are part of the experience. Modest clothing covering shoulders and knees is appreciated, and remove shoes at temples.</p>
<p>More planning help: <a href="/jawai-leopard-safari-what-to-expect/">what to expect on a Jawai safari</a> and <a href="/jawai-photography-tips/">Jawai photography tips</a>. <a href="/book-a-safari/">Book a safari</a>, from {price} per jeep.</p>
HTML,
                'faq' => implode("\n", [
                    'What colour clothes should I wear on a Jawai safari? | Neutral colours such as khaki, olive, brown, beige or grey. Avoid bright colours and white.',
                    'Do I need a jacket for a Jawai safari? | Yes, from November to February. Morning drives in an open jeep are cold, so bring a warm jacket, cap and gloves.',
                    'What should I bring on safari? | Water, a cap, sunscreen, sunglasses, binoculars and a camera with a zoom lens, plus a rain jacket in the monsoon.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'jawai-with-kids', 'title' => 'Jawai with kids: a family leopard safari', 'image_id' => $i('jawai-with-kids'),
                'intro' => 'Jawai is a wonderful first wildlife trip for children: a private jeep, short distances and leopards on open rock. How to plan a family safari.',
                'meta_title' => 'Jawai with Kids: Family Leopard Safari Tips',
                'meta_description' => 'Planning Jawai with children? Private jeeps, a gentle pace and leopards on open rock make Jawai great for families. Tips, timings and what to bring.',
                'body' => <<<'HTML'
<p>Many families choose Jawai for a child's first wildlife adventure. With a <strong>private jeep</strong>, you set the pace, and the open granite hills make animals easier for young eyes to see.</p>

<h2>Why Jawai works for families</h2>
<ul>
<li><strong>Private jeep:</strong> just your family, up to six guests, with no strangers to disturb.</li>
<li><strong>A gentle pace:</strong> we can plan shorter stops and comfortable breaks.</li>
<li><strong>More than leopards:</strong> crocodiles, peacocks, birds at the dam, goats and camels with Rabari herders keep children interested.</li>
<li><strong>Short transfers:</strong> Jawai is about 2.5 hours from Udaipur.</li>
</ul>

<h2>Tips for a happy family safari</h2>
<ul>
<li>Choose the <strong>evening drive</strong> for younger children who find early starts hard.</li>
<li>Bring snacks, water, a warm layer in winter and a small pair of binoculars for each child.</li>
<li>Explain the "quiet rule" at sightings beforehand: whispering is part of the game.</li>
<li>Children must stay seated and hold on when the jeep is moving.</li>
<li>Tell us your children's ages when you book so we can plan the drive.</li>
</ul>

<h2>Between safaris</h2>
<p>The Jawai Dam, a hill temple and village life fill the middle of the day; see <a href="/jawai/places-to-visit/">places to visit in Jawai</a>. A <a href="/jawai-itinerary/">2-day itinerary</a> suits most families.</p>
<p><a href="/book-a-safari/">Book a family safari</a>, from {price} per jeep for up to six guests.</p>
HTML,
                'faq' => implode("\n", [
                    'Is Jawai good for kids? | Yes. A private jeep, a gentle pace and leopards seen on open rock make Jawai a great first wildlife trip. Tell us the children\'s ages when you book.',
                    'Which safari is best for young children? | The evening safari (4:00 to 7:00 PM) avoids the very early start and has warm, golden light.',
                    'How many people fit in one jeep? | Up to six guests in one private open 4x4 gypsy, so most families need just one jeep.',
                ]),
                'sources' => '',
            ],
            $base + [
                'slug' => 'jawai-photography-tips', 'title' => 'Jawai leopard photography tips', 'image_id' => $i('jawai-photography-tips'),
                'intro' => 'Leopards on granite against the sky make Jawai one of India\'s best places for wildlife photography. Lenses, settings, light and etiquette.',
                'meta_title' => 'Jawai Photography Tips: Lenses & Settings for Leopards',
                'meta_description' => 'Wildlife photography in Jawai: best lenses (100–400 mm and longer), camera settings, golden-hour light, beanbags and ethical leopard photography.',
                'body' => <<<'HTML'
<p>In Jawai, leopards often sit on open granite with the sky behind them, a setting photographers dream of. A little preparation makes a big difference.</p>

<h2>Gear</h2>
<ul>
<li><strong>Telephoto lens:</strong> 100–400 mm is the most versatile; 150–600 mm or similar helps for distant rocks.</li>
<li><strong>A second body or a wider lens</strong> for landscapes and leopard-in-habitat shots.</li>
<li><strong>Beanbag:</strong> far more practical than a tripod in a jeep. Ask us for one when you book.</li>
<li>Spare batteries and memory cards, and a lens cloth for the dust.</li>
</ul>

<h2>Camera settings</h2>
<ul>
<li>Shutter speed at least 1/500 s for a resting leopard, faster for movement.</li>
<li>Use Auto ISO; dawn and dusk light is low, so don't be afraid of higher ISO.</li>
<li>Continuous autofocus with animal or eye detection if your camera has it.</li>
<li>Expose for the animal: bright sky behind a dark rock can fool the meter, so use exposure compensation.</li>
</ul>

<h2>Light and timing</h2>
<p>Evening drives give warm, golden light on the granite; mornings give soft light and sometimes mist over the dam. Silhouettes of leopards on ridges at sunset are a Jawai speciality. See <a href="/morning-vs-evening-jawai-safari/">morning vs evening safari</a>.</p>

<h2>Ethics</h2>
<ul>
<li>No flash, drones or calling animals; never ask the driver to get closer than is safe.</li>
<li>Switch engines off at sightings and keep the jeep still while shooting.</li>
<li>Ask before photographing people in the villages.</li>
</ul>
<p>Serious about photography? The <a href="/safaris/photography-safari/">Wildlife Photography Expedition</a> is planned around light and positioning. Drives start from {price} per jeep; <a href="/book-a-safari/">book here</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'What lens do I need for leopards in Jawai? | A 100–400 mm zoom covers most sightings; a 150–600 mm or similar helps when leopards are on distant rocks.',
                    'Is a tripod useful on a Jawai safari? | Not really. A beanbag on the side of the jeep is far more practical and stable.',
                    'Is morning or evening better for photography in Jawai? | Evenings give warm golden light and sunset silhouettes; mornings give soft light and sometimes mist over the dam. Both are good.',
                ]),
                'sources' => '',
            ],
        ];
    }

    /** Add any missing articles (never overwrites an existing or edited page). Returns the number added. */
    public static function ensure(): int
    {
        $img = Demo::registerImages();
        $i = fn (string $n) => $img[$n] ?? null;
        $now = now();
        $added = 0;
        foreach (self::pages($i) as $p) {
            if (!DB::val('SELECT id FROM pages WHERE slug = ?', [$p['slug']])) {
                DB::insert('pages', $p + ['status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
                $added++;
            }
        }
        return $added;
    }
}
