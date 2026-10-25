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
                'meta_description' => 'How to book a Jawai leopard safari: choose morning (6–9 AM) or evening (4–7 PM), send your date online or on WhatsApp, and confirm. Prices from ₹3,500 per jeep.',
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
<p>Jawai leopard safaris start <strong>from ₹3,500 per jeep</strong>. The jeep is private to your group and seats up to six guests, so the price per person drops as your group grows. Longer private expeditions and photography drives cost more. See <a href="/jawai-safari-price/">Jawai safari price</a> for the details.</p>

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
                'intro' => 'Jawai leopard safaris start from ₹3,500 per jeep for your group. What is included, what changes the price, and how to get the best value.',
                'meta_title' => 'Jawai Safari Price 2026: Leopard Safari Cost per Jeep',
                'meta_description' => 'Jawai safari price: leopard safaris from ₹3,500 per jeep (up to 6 guests), morning or evening. What is included and what affects the cost.',
                'body' => <<<'HTML'
<p>In Jawai the safari is priced <strong>per jeep</strong>, not per person. You get an open 4x4 gypsy for your own group, a driver and a local tracker.</p>

<h2>Prices at a glance</h2>
<table>
<thead><tr><th>Safari</th><th>Duration</th><th>Price</th></tr></thead>
<tbody>
<tr><td><a href="/safaris/leopard-safari/">Classic Leopard Tracking Safari</a>, morning or evening</td><td>about 3 hours</td><td>from ₹3,500 per jeep</td></tr>
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
<p>Because you pay per jeep, sharing makes it cheaper: with six guests, a ₹3,500 jeep works out at under ₹600 each. Two people pay the same jeep price but have the whole vehicle to themselves.</p>

<h2>Getting the best value</h2>
<ul>
<li><strong>Book two drives.</strong> A morning and an evening safari together give you two very different chances to see leopards.</li>
<li><strong>Avoid the busiest dates</strong> (Diwali, Christmas to New Year, long weekends) if you can.</li>
<li><strong>Compare like for like.</strong> A low price for a crowded or shared vehicle, or a short drive, is not the same as a private three-hour safari with a tracker.</li>
</ul>
<p>Ready? <a href="/book-a-safari/">Send a booking request</a> and we will reply with availability for your date.</p>
HTML,
                'faq' => implode("\n", [
                    'What is the price of a Jawai safari? | Jawai leopard safaris with Captains of Jawai start from ₹3,500 per jeep for a morning or evening drive of about three hours. The jeep is private to your group and seats up to six guests.',
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
<p><a href="/book-a-safari/">Book your Jawai safaris</a>, from ₹3,500 per jeep.</p>
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
<p>Safaris start from ₹3,500 per jeep for up to six guests, which makes Jawai good value for a family or a group of friends. <a href="/book-a-safari/">Book your weekend safaris</a>, or see our <a href="/jawai-itinerary/">2-day Jawai itinerary</a>.</p>
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
<p>Winter is the busiest time, especially from Diwali to New Year. Book your safaris one to two weeks ahead. Drives start from ₹3,500 per jeep. <a href="/book-a-safari/">Send a booking request</a>, or read the <a href="/jawai/best-time-to-visit/">month-by-month guide</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'Is winter a good time to visit Jawai? | Yes, October to March is the best season: pleasant days, active leopards and migratory birds such as flamingos and demoiselle cranes at the Jawai Dam.',
                    'How cold is Jawai in winter? | Days are pleasant, but early mornings from December to February are cold, especially in an open jeep. Bring a warm jacket, cap and gloves.',
                    'When do flamingos come to Jawai? | Flamingos and other migratory birds visit the Jawai Dam reservoir in the cooler months, mainly from about November to February, depending on water levels.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM]),
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
