<?php
declare(strict_types=1);

namespace App\Core;

/**
 * The Jawai guide: a pillar page (/jawai/) and topic pages under /jawai/...
 *
 * Written as original text. Facts were checked against the Wikipedia articles
 * listed in each page's "sources" field, plus published travel information.
 * Every page is an ordinary record in Admin → Pages, so the owner can edit the
 * wording, FAQ lines and sources at any time.
 */
final class JawaiGuide
{
    private const WIKI_DAM = 'https://en.wikipedia.org/wiki/Jawai_Dam | Wikipedia: Jawai Dam';
    private const WIKI_RESERVE = 'https://en.wikipedia.org/wiki/Jawai_Bandh_Leopard_Conservation_Reserve | Wikipedia: Jawai Bandh Leopard Conservation Reserve';
    private const WIKI_STATION = 'https://en.wikipedia.org/wiki/Sumerpur_Jawai_Bandh_railway_station | Wikipedia: Sumerpur Jawai Bandh railway station';
    private const WIKI_PALI = 'https://en.wikipedia.org/wiki/Pali_district | Wikipedia: Pali district';

    /** Slug => menu label, in the order they appear in the header menu. */
    public const MENU = [
        'jawai/leopards' => 'Leopards of Jawai',
        'jawai/jawai-dam' => 'Jawai Dam',
        'jawai/wildlife' => 'Birds & wildlife',
        'jawai/best-time-to-visit' => 'Best time to visit',
        'jawai/how-to-reach' => 'How to reach Jawai',
        'jawai/places-to-visit' => 'Places to visit',
    ];

    /** Image (built-in photo name) for each guide page. */
    public const IMAGES = [
        'jawai' => 'bandh-shoreline',
        'jawai/leopards' => 'leopard-portrait',
        'jawai/jawai-dam' => 'lake-reflection',
        'jawai/wildlife' => 'flamingo-flock',
        'jawai/best-time-to-visit' => 'sunset-valley',
        'jawai/how-to-reach' => 'ridge-sighting',
        'jawai/places-to-visit' => 'granite-dome-dusk',
    ];

    public static function pages(callable $i): array
    {
        $img = fn (string $slug) => $i(self::IMAGES[$slug]);
        return [
            [
                'slug' => 'jawai', 'title' => 'Jawai, Rajasthan', 'kicker' => 'A guide to the leopard hills', 'image_id' => $img('jawai'), 'show_cta' => 1,
                'intro' => 'Jawai is a stretch of granite hills, farmland and villages around the Jawai Dam in Pali district, Rajasthan. It is best known for leopards that live in the rocks, close to people.',
                'meta_title' => 'Jawai, Rajasthan: Leopards, Jawai Dam & Travel Guide',
                'meta_description' => 'Everything about Jawai in Rajasthan: where it is, the Jawai Dam, the leopards of the granite hills, birds, the Rabari herders, the best time to visit and how to get there.',
                'body' => <<<'HTML'
<p>Most people hear about Jawai because of its leopards. They live in the caves and cracks of the granite hills around the villages of Bera, Sena, Perwa and their neighbours, and on a good morning you can watch one sitting on a rock in the open sun. The rest of Jawai is just as interesting: a large reservoir built in the 1940s and 50s, winter birds on the water, crocodiles on the banks, and the Rabari herders who have shared these hills with leopards for a very long time.</p>

<h2>Where is Jawai?</h2>
<p>Jawai sits in the south of <strong>Pali district</strong> in western Rajasthan, roughly halfway between <strong>Udaipur</strong> and <strong>Jodhpur</strong>. The nearest town of any size is <strong>Sumerpur</strong>. The area takes its name from the Jawai River and the Jawai Dam (locally <em>Jawai Bandh</em>; <em>bandh</em> means dam). On a map, look for the reservoir west of the Aravalli range, near the small town of Falna on the Ahmedabad railway line.</p>

<h2>The Jawai Dam</h2>
<p>The dam was built on the Jawai River, a tributary of the Luni. It was planned by Maharaja Umaid Singh of Jodhpur, work began in 1946 and it was finished in 1957. It is the largest dam in western Rajasthan and still supplies drinking water and irrigation to towns and farms across Pali and beyond. In a dry state, that water is what makes Jawai's wildlife possible. <a href="/jawai/jawai-dam/">Read more about the Jawai Dam</a>.</p>

<h2>Leopards in the granite</h2>
<p>The hills here are made of old granite, weathered into domes, boulder piles and caves. Leopards use the caves to rest and raise cubs, and the warm rock to bask on winter mornings. Published estimates put the population at around 50 to 60 leopards in and around the area. There are no tigers or lions, so the leopard is the largest predator. About 20 square kilometres around the dam is protected as the <strong>Jawai Bandh Leopard Conservation Reserve</strong>, managed by the Rajasthan Forest Department. Jawai is not a national park and it is not fenced: villages, temples and fields sit right among the leopard hills. <a href="/jawai/leopards/">More on the leopards of Jawai</a>.</p>

<h2>People and leopards</h2>
<p>The Rabari are a pastoral community known for their red turbans and herds of sheep, goats and camels. Many Rabari families here treat the leopard with tolerance and respect, linked to their faith and the hill shrines found on many rocks. Leopards do sometimes take livestock, and compensation is paid by the forest department. Conflict with people has been rare compared with many other parts of India, which is a large part of why Jawai's leopards are so relaxed in the open. <a href="/experiences/rabari-culture/">About the Rabari</a>.</p>

<h2>Birds and other wildlife</h2>
<p>The reservoir draws large numbers of birds in winter, including bar-headed geese, demoiselle cranes, flamingos, ruddy shelducks and painted storks. Mugger crocodiles bask on the sandbanks. On land you may see striped hyena, jungle cat, Indian fox, nilgai and grey langur, whose alarm calls often give away a leopard. <a href="/jawai/wildlife/">Birds and wildlife of Jawai</a>.</p>

<h2>When to go</h2>
<p>October to March is the main season, with cool mornings and leopards out on the rocks. December and January are the coldest months and best for winter birds. April to June is very hot, and July to September brings the monsoon, green hills and rough tracks. <a href="/jawai/best-time-to-visit/">Month-by-month guide</a>.</p>

<h2>Getting there</h2>
<p>Udaipur airport is the usual way in, a drive of roughly three hours. Jodhpur is a similar distance to the north. By train, Jawai Bandh and Falna stations are on the line between Ahmedabad and Jaipur. <a href="/jawai/how-to-reach/">How to reach Jawai</a>.</p>

<h2>Quick facts</h2>
<table>
<tbody>
<tr><th>State / district</th><td>Rajasthan / Pali</td></tr>
<tr><th>Nearest towns</th><td>Sumerpur, Falna, Bali</td></tr>
<tr><th>Known for</th><td>Leopards living in granite hills near villages</td></tr>
<tr><th>Protected area</th><td>Jawai Bandh Leopard Conservation Reserve (about 20 km²)</td></tr>
<tr><th>Leopards</th><td>Estimated 50 to 60 in the wider area</td></tr>
<tr><th>Dam</th><td>Jawai Dam, on the Jawai River, completed 1957</td></tr>
<tr><th>Best season</th><td>October to March</td></tr>
<tr><th>Nearest airport</th><td>Udaipur (about 3 hours by road)</td></tr>
</tbody>
</table>
HTML,
                'faq' => implode("\n", [
                    'Where is Jawai? | Jawai is in Pali district in western Rajasthan, India, roughly halfway between Udaipur and Jodhpur, near the towns of Sumerpur and Falna.',
                    'What is Jawai famous for? | Leopards that live among granite hills and caves close to villages, the Jawai Dam reservoir, winter birds and the Rabari herding community.',
                    'Is Jawai a national park? | No. Part of the area is protected as the Jawai Bandh Leopard Conservation Reserve, but there is no fence and no park gate. Villages, farms and temples sit among the leopard hills.',
                    'How many leopards are there in Jawai? | Published estimates usually put the number at around 50 to 60 leopards in the wider Jawai area. Numbers are estimates and change over time.',
                    'Is a leopard sighting guaranteed in Jawai? | No. Sightings are frequent because leopards rest on open rock, but they are wild animals and no honest operator can guarantee one.',
                    'What is the best time to visit Jawai? | October to March. Mornings are cool and leopards often bask on the rocks. December and January are best for migratory birds.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM, self::WIKI_RESERVE, self::WIKI_PALI]),
            ],
            [
                'slug' => 'jawai/leopards', 'title' => 'The leopards of Jawai', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/leopards'), 'show_cta' => 1,
                'intro' => 'Why leopards live so close to people in Jawai, how they use the granite caves, and what a sighting is really like.',
                'meta_title' => 'Jawai Leopards: How & Where to See Leopards in Jawai',
                'meta_description' => 'About the leopards of Jawai, Rajasthan: the granite caves they live in, how many there are, their relationship with the Rabari people and how to watch them responsibly.',
                'body' => <<<'HTML'
<p>In most of India a leopard is a shadow at the edge of the forest. In Jawai it is often a spotted shape on a bare rock, sometimes a few hundred metres from a village temple. That is what makes the place unusual.</p>

<h2>Why the granite matters</h2>
<p>The hills around Jawai are granite outcrops, worn into rounded domes and stacks of boulders. Between the boulders are caves and deep cracks that stay cool in summer and sheltered in winter. A female leopard can raise cubs in a cave like this with little disturbance. In the morning and late afternoon leopards come out onto ledges to warm up and to watch the land below, which is when they are easiest to see.</p>

<h2>How many leopards?</h2>
<p>Figures quoted by the forest department and researchers are usually in the range of 50 to 60 leopards for the wider Jawai area. The Jawai Bandh Leopard Conservation Reserve covers about 20 km², but leopards move freely across the farmland and hills outside it.</p>

<h2>Living next to people</h2>
<p>There are no large forests here, and leopards hunt what they can find: wild prey such as hares, peafowl and langurs, as well as dogs and village livestock. Losses of goats or calves are compensated by the state. What surprises most visitors is how calm the relationship is. The Rabari, who graze herds across these hills, generally accept the leopard as part of the land. Many rocks carry small shrines, and people and leopards use the same paths at different hours.</p>

<h2>What a leopard drive is like</h2>
<p>Drives go out at first light and in the late afternoon, in open 4x4 vehicles on village tracks and dirt roads. Trackers watch the ridgelines, listen for langur and peafowl alarm calls and look for fresh pugmarks in sandy stream beds. When a leopard is found it is usually on a rock some distance away, so binoculars and a long lens help. Some days you watch one leopard for an hour. Some days you see none.</p>

<h2>Watching them responsibly</h2>
<ul>
<li>Keep a good distance and never block a leopard's route back to its cave.</li>
<li>Switch the engine off while watching.</li>
<li>No baiting, calling, spotlights or drones.</li>
<li>Leave quietly if the animal looks uneasy or too many vehicles gather.</li>
</ul>
<p>Our own field rules are on the <a href="/about/conservation-ethics/">conservation and ethics</a> page.</p>
HTML,
                'faq' => implode("\n", [
                    'Why are there so many leopards in Jawai? | The granite hills are full of caves for shelter and cubs, there is water from the Jawai Dam, and local people have largely tolerated leopards for generations.',
                    'Do Jawai leopards attack people? | Attacks on people are very rare in Jawai. Leopards here mostly avoid people and take wild prey, dogs and sometimes livestock.',
                    'What time of day are leopards seen in Jawai? | Early morning and late afternoon, when leopards come out of the caves to bask on the rocks and before they start moving at night.',
                    'Can I see leopards in Jawai in summer? | Yes, but drives are kept to early morning and evening because of the heat, and leopards spend more time inside the caves.',
                ]),
                'sources' => implode("\n", [self::WIKI_RESERVE]),
            ],
            [
                'slug' => 'jawai/jawai-dam', 'title' => 'Jawai Dam (Jawai Bandh)', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/jawai-dam'), 'show_cta' => 1,
                'intro' => 'The largest dam in western Rajasthan, built under Maharaja Umaid Singh of Jodhpur, and the reason Jawai has water, birds and crocodiles.',
                'meta_title' => 'Jawai Dam (Jawai Bandh): History, Birds & Visiting',
                'meta_description' => 'The Jawai Dam in Pali, Rajasthan: when and why it was built, what it supplies, the crocodiles and migratory birds on the reservoir, and how to visit.',
                'body' => <<<'HTML'
<p>The Jawai Dam, called <em>Jawai Bandh</em> locally, holds back the Jawai River near Sumerpur in Pali district. It is the largest dam in western Rajasthan and the reservoir behind it gives the whole area its name.</p>

<h2>History</h2>
<p>The dam was the idea of <strong>Maharaja Umaid Singh</strong> of the princely state of Jodhpur, after repeated droughts and floods in the region. Construction started in 1946 and the dam was completed in 1957, after Indian independence, by which time the project had passed to the new state of Rajasthan.</p>

<h2>What the dam does</h2>
<p>The reservoir provides drinking water to Pali and nearby towns and villages, and irrigation water to farms downstream in the Jawai command area. In years of poor rain the water level drops a long way, which shows how much the area depends on it. The Jawai River is a tributary of the Luni, the main river of western Rajasthan.</p>

<h2>Wildlife on the reservoir</h2>
<p>The shallows and sandbanks are home to <strong>mugger (marsh) crocodiles</strong>, which are easy to see basking on sunny winter days. From November to February the water fills with migratory birds: bar-headed geese, demoiselle cranes, greater flamingos, ruddy shelducks, pintails and many waders. Ospreys and other raptors hunt over the water. See <a href="/jawai/wildlife/">birds and wildlife</a>.</p>

<h2>Visiting</h2>
<p>The dam wall and the shoreline are easy to reach by road from Sumerpur and Bera. Sunset over the reservoir, with granite hills behind, is one of the best views in the area. Stay back from the water's edge: the crocodiles are wild. A wetland drive with a spotting scope is the best way to see the birds; ours is described on the <a href="/safaris/dam-birding-safari/">wetland safari</a> page.</p>
HTML,
                'faq' => implode("\n", [
                    'Who built the Jawai Dam? | It was planned by Maharaja Umaid Singh of Jodhpur. Work started in 1946 and it was completed in 1957.',
                    'Which river is the Jawai Dam on? | The Jawai River, a tributary of the Luni, in Pali district, Rajasthan.',
                    'Are there crocodiles in Jawai Dam? | Yes. Mugger (marsh) crocodiles live in the reservoir and are often seen basking on the banks in winter.',
                    'Is Jawai Dam the largest dam in Rajasthan? | It is usually described as the largest dam in western Rajasthan.',
                ]),
                'sources' => implode("\n", [self::WIKI_DAM, self::WIKI_PALI]),
            ],
            [
                'slug' => 'jawai/wildlife', 'title' => 'Birds and wildlife of Jawai', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/wildlife'), 'show_cta' => 1,
                'intro' => 'Leopards get the attention, but Jawai also has hyenas, crocodiles, cranes, flamingos and a long list of raptors.',
                'meta_title' => 'Jawai Wildlife & Birds: Hyena, Crocodile, Cranes, Flamingos',
                'meta_description' => 'Animals and birds you can see in Jawai, Rajasthan: striped hyena, jungle cat, nilgai, mugger crocodile, demoiselle crane, bar-headed goose, flamingo and more.',
                'body' => <<<'HTML'
<p>Jawai is dry, rocky country with one large body of water in the middle. That mix gives it a wider range of wildlife than its size suggests.</p>

<h2>Mammals</h2>
<ul>
<li><strong>Leopard</strong>: the top predator, living in the granite hills. See <a href="/jawai/leopards/">the leopards of Jawai</a>.</li>
<li><strong>Striped hyena</strong>: mostly nocturnal, occasionally seen at dusk or dawn.</li>
<li><strong>Jungle cat</strong> and <strong>Indian fox</strong>: small hunters of the scrub and fields.</li>
<li><strong>Nilgai</strong> (blue bull): the largest antelope in Asia, common in the farmland.</li>
<li><strong>Grey (Hanuman) langur</strong>: troops on the rocks and temples. Their alarm calls often reveal a leopard.</li>
<li>Indian hare, porcupine, grey mongoose and wild boar.</li>
</ul>

<h2>Reptiles</h2>
<p>The <strong>mugger crocodile</strong> is the one most visitors want to see. It basks on the reservoir banks and sandbars on cool, sunny days. Monitor lizards are also found on the rocks.</p>

<h2>Birds</h2>
<p>Bird lists for the Jawai area run to well over a hundred species. In winter the reservoir is the main attraction:</p>
<ul>
<li><strong>Bar-headed goose</strong>, which migrates over the Himalaya</li>
<li><strong>Demoiselle crane</strong> and <strong>common crane</strong></li>
<li><strong>Greater flamingo</strong></li>
<li>Ruddy shelduck, northern pintail and other ducks</li>
<li>Painted stork, Eurasian spoonbill, black-headed ibis and river tern</li>
</ul>
<p>Around the hills and fields look for Indian peafowl, grey francolin, Indian eagle-owl on the rocks, Indian vulture and a good number of eagles, harriers and falcons, especially in winter.</p>

<h2>When to see what</h2>
<p>Migratory birds are present from about November to February. Mammals are easiest to see in the cool months, early and late in the day. The monsoon months bring breeding residents and green hills, but tracks can be hard going.</p>
HTML,
                'faq' => implode("\n", [
                    'What animals can you see in Jawai besides leopards? | Striped hyena, jungle cat, Indian fox, nilgai, grey langur, wild boar and mugger crocodiles, plus a large number of birds.',
                    'Which migratory birds come to Jawai? | Bar-headed geese, demoiselle and common cranes, greater flamingos, ruddy shelducks and many ducks and waders, mainly from November to February.',
                    'Are there tigers in Jawai? | No. There are no tigers or lions in Jawai. The leopard is the largest predator.',
                ]),
                'sources' => implode("\n", [self::WIKI_RESERVE, self::WIKI_DAM]),
            ],
            [
                'slug' => 'jawai/best-time-to-visit', 'title' => 'Best time to visit Jawai', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/best-time-to-visit'), 'show_cta' => 1,
                'intro' => 'Weather, leopards and birds through the year, so you can pick the right month for your trip.',
                'meta_title' => 'Best Time to Visit Jawai: Month-by-Month Guide',
                'meta_description' => 'When to visit Jawai for leopards and birds. Weather, temperatures and wildlife in winter, summer and the monsoon, month by month.',
                'body' => <<<'HTML'
<p>Short answer: <strong>October to March</strong>. Longer answer below, because each part of the year has something going for it.</p>

<h2>Month by month</h2>
<table>
<thead><tr><th>Months</th><th>Weather</th><th>Wildlife</th></tr></thead>
<tbody>
<tr><td>October</td><td>Warm days, cooler nights after the rains</td><td>Hills still green, first migrant birds arrive, leopards active</td></tr>
<tr><td>November to February</td><td>Cold mornings (can drop below 10°C), pleasant days</td><td>Peak season. Leopards bask on the rocks; cranes, geese and flamingos on the reservoir</td></tr>
<tr><td>March</td><td>Warming up, clear light</td><td>Good leopard viewing, migrants start to leave</td></tr>
<tr><td>April to June</td><td>Hot, often above 40°C</td><td>Drives only at dawn and dusk; wildlife stays close to water</td></tr>
<tr><td>July to September</td><td>Monsoon rain, humid</td><td>Green hills and dramatic skies; tracks can be rough and some days are washed out</td></tr>
</tbody>
</table>

<h2>For leopards</h2>
<p>Winter mornings are hard to beat. After a cold night, leopards come out on the rock to sit in the sun, often in full view. March and October are also good, with fewer visitors than the Christmas and New Year weeks.</p>

<h2>For birds</h2>
<p>Come between late November and mid-February, when the migrant ducks, geese and cranes are on the reservoir.</p>

<h2>For quiet</h2>
<p>The hot months and the monsoon are much quieter. If you can handle the heat, early summer mornings can still be rewarding.</p>

<h2>What to pack</h2>
<ul>
<li>Winter: warm layers, a hat and gloves for early drives</li>
<li>Summer: light, neutral clothing, a scarf and plenty of water</li>
<li>All year: binoculars, sunscreen and a camera with a long lens</li>
</ul>
HTML,
                'faq' => implode("\n", [
                    'Which month is best for Jawai? | December and January are the most popular, with cold mornings and the best chance of leopards basking in the open. October to March is the main season.',
                    'Is Jawai open in the monsoon? | Yes, Jawai is not a closed park, but rain can disrupt drives and tracks can be rough from July to September.',
                    'How hot does Jawai get in summer? | April to June regularly passes 40°C in the afternoon. Drives are kept to early morning and evening.',
                    'How many days do I need in Jawai? | Two nights is the minimum for a fair chance at leopards. Three nights gives you more drives and time for the dam and villages.',
                ]),
                'sources' => implode("\n", [self::WIKI_RESERVE]),
            ],
            [
                'slug' => 'jawai/how-to-reach', 'title' => 'How to reach Jawai', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/how-to-reach'), 'show_cta' => 1,
                'intro' => 'By air, rail and road, from Udaipur, Jodhpur, Ahmedabad, Jaipur and Delhi.',
                'meta_title' => 'How to Reach Jawai: Flights, Trains & Road from Udaipur',
                'meta_description' => 'How to get to Jawai in Rajasthan: nearest airport (Udaipur), nearest railway stations (Jawai Bandh and Falna), and driving times from Udaipur, Jodhpur and Ahmedabad.',
                'body' => <<<'HTML'
<p>Jawai has no airport of its own, but it is well placed between Udaipur and Jodhpur and has a railway station on a main line.</p>

<h2>By air</h2>
<p><strong>Udaipur (Maharana Pratap Airport)</strong> is the usual choice, with flights from Delhi, Mumbai, Bengaluru and other cities. The drive to Jawai takes around three hours. <strong>Jodhpur Airport</strong> is a similar drive from the north. Ahmedabad has more international connections and is about five to six hours by road.</p>

<h2>By train</h2>
<p><strong>Jawai Bandh</strong> station (also called Sumerpur Jawai Bandh) and <strong>Falna</strong> are both on the line between Ahmedabad and Jaipur, with trains to and from Delhi, Jaipur, Ajmer, Abu Road and Ahmedabad. Falna gets more express trains. From either station it is a short drive to the camps and lodges around Bera.</p>

<h2>By road</h2>
<table>
<thead><tr><th>From</th><th>Approx. time by car</th></tr></thead>
<tbody>
<tr><td>Udaipur</td><td>3 hours</td></tr>
<tr><td>Jodhpur</td><td>3 to 3.5 hours</td></tr>
<tr><td>Kumbhalgarh</td><td>2 to 2.5 hours</td></tr>
<tr><td>Ranakpur</td><td>1.5 to 2 hours</td></tr>
<tr><td>Mount Abu</td><td>2.5 to 3 hours</td></tr>
<tr><td>Ahmedabad</td><td>5 to 6 hours</td></tr>
<tr><td>Jaipur</td><td>6 to 7 hours</td></tr>
</tbody>
</table>
<p>Times depend on traffic and the route. The last part of the journey is on smaller country roads, so allow time and avoid arriving late at night if you can.</p>

<h2>Transfers</h2>
<p>We can arrange a car from Udaipur or Jodhpur airport, or from Falna or Jawai Bandh station. Mention it in your <a href="/plan-your-journey/">enquiry</a>.</p>

<h2>Combining Jawai with other places</h2>
<p>Jawai fits neatly into a Rajasthan route: Udaipur, then Kumbhalgarh and Ranakpur, then Jawai, then on to Jodhpur. See <a href="/jawai/places-to-visit/">places to visit near Jawai</a> and our <a href="/journeys/">journeys</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'What is the nearest airport to Jawai? | Udaipur (Maharana Pratap Airport), about three hours away by road. Jodhpur Airport is a similar distance.',
                    'What is the nearest railway station to Jawai? | Jawai Bandh station, also called Sumerpur Jawai Bandh. Falna, a little further away, has more express trains.',
                    'How far is Jawai from Udaipur? | Around three hours by car.',
                    'How far is Jawai from Jodhpur? | Around three to three and a half hours by car.',
                ]),
                'sources' => implode("\n", [self::WIKI_STATION, self::WIKI_PALI]),
            ],
            [
                'slug' => 'jawai/places-to-visit', 'title' => 'Places to visit in and around Jawai', 'kicker' => 'Jawai guide', 'image_id' => $img('jawai/places-to-visit'), 'show_cta' => 1,
                'intro' => 'Between leopard drives: the reservoir, the villages and hill shrines, and the forts and temples within a few hours.',
                'meta_title' => 'Places to Visit in Jawai & Nearby (Ranakpur, Kumbhalgarh)',
                'meta_description' => 'Things to do in and around Jawai, Rajasthan: Jawai Dam, Bera and the village hills, Rabari settlements, Ranakpur Jain temple, Kumbhalgarh Fort and Mount Abu.',
                'body' => <<<'HTML'
<p>Most visitors plan their days around two drives, at dawn and late afternoon. The middle of the day is free, and Jawai has plenty to fill it.</p>

<h2>In Jawai</h2>
<h3>Jawai Dam</h3>
<p>The reservoir is the centre of the area. Go in the late afternoon for crocodiles on the banks and the sunset over the water. In winter it is full of birds. <a href="/jawai/jawai-dam/">About the dam</a>.</p>
<h3>Bera and the village hills</h3>
<p>Bera is the village most associated with leopard sightings, surrounded by granite hills. Walking through the lanes, past fields and small temples, shows you how closely people and leopards live here.</p>
<h3>Hill shrines</h3>
<p>Many hills have a small temple or shrine on top or tucked into a cave, some reached by long flights of steps. Ask locally before climbing and dress respectfully.</p>
<h3>Rabari settlements</h3>
<p>A visit with a Rabari family, arranged properly and with permission, is a chance to learn about herding life, traditional dress and how the community sees the leopard. <a href="/experiences/rabari-culture/">Rabari heritage walk</a>.</p>
<h3>Sundowners on the rocks</h3>
<p>Climbing a granite outcrop for the last light of the day is a Jawai tradition. <a href="/experiences/boulder-sundowners/">Boulder sundowners</a>.</p>

<h2>Day trips</h2>
<h3>Ranakpur Jain temple</h3>
<p>A 15th-century marble temple famous for its hundreds of carved pillars, about two hours away. Combine it with Kumbhalgarh.</p>
<h3>Kumbhalgarh Fort</h3>
<p>A hill fort with one of the longest continuous walls in the world, and part of the UNESCO World Heritage listing for the hill forts of Rajasthan.</p>
<h3>Mount Abu</h3>
<p>Rajasthan's only hill station, known for the Dilwara temples and Nakki Lake, about three hours to the south.</p>
<h3>Udaipur and Jodhpur</h3>
<p>Both cities are around three hours away and make natural start and end points for a trip to Jawai. See <a href="/jawai/how-to-reach/">how to reach Jawai</a>.</p>
HTML,
                'faq' => implode("\n", [
                    'What can you do in Jawai apart from a leopard safari? | Visit the Jawai Dam for crocodiles and birds, walk the village lanes of Bera, meet Rabari herders, climb to a hill shrine and watch sunset from a granite outcrop.',
                    'Can I visit Ranakpur and Kumbhalgarh from Jawai? | Yes. Ranakpur is about two hours away and Kumbhalgarh a little further, so both fit in a long day trip.',
                    'Is Jawai good for families? | Yes. Shorter drives, the dam, birds and village visits work well for children. Tell us their ages when you enquire.',
                ]),
                'sources' => implode("\n", [
                    'https://en.wikipedia.org/wiki/Ranakpur_Jain_temple | Wikipedia: Ranakpur Jain temple',
                    'https://en.wikipedia.org/wiki/Kumbhalgarh | Wikipedia: Kumbhalgarh',
                    'https://en.wikipedia.org/wiki/Hill_Forts_of_Rajasthan | Wikipedia: Hill Forts of Rajasthan',
                ]),
            ],
        ];
    }

    /** Old seeded /jawai/ body, replaced by the guide only if nobody edited it. */
    private const OLD_JAWAI_MARKER = "<h2>The granite kopjes</h2><p>Jawai's signature is its granite";

    /**
     * Add any guide pages missing from an existing site and link them from the header menu.
     * Safe to run repeatedly; never overwrites a page the owner has edited.
     */
    public static function ensure(): int
    {
        $img = Demo::registerImages();
        $i = fn (string $n) => $img[$n] ?? null;
        $now = now();
        $added = 0;
        foreach (self::pages($i) as $p) {
            $row = DB::one('SELECT id, body, faq FROM pages WHERE slug = ?', [$p['slug']]);
            if (!$row) {
                DB::insert('pages', $p + ['status' => 'published', 'created_at' => $now, 'updated_at' => $now]);
                $added++;
            } elseif ($p['slug'] === 'jawai' && trim((string) $row['faq']) === '' && str_starts_with((string) $row['body'], self::OLD_JAWAI_MARKER)) {
                $p['image_id'] ??= null;
                DB::update('pages', $p + ['updated_at' => $now], 'id = ?', [$row['id']]);
                $added++;
            }
        }

        // Menu: hang the guide pages under the header item that points to /jawai/.
        $parent = DB::val("SELECT id FROM menu_items WHERE location = 'header' AND parent_id IS NULL AND url = '/jawai/' ORDER BY id LIMIT 1");
        if ($parent) {
            $sort = (int) DB::val('SELECT MAX(sort_order) FROM menu_items WHERE parent_id = ?', [$parent]);
            foreach (self::MENU as $slug => $label) {
                $url = '/' . $slug . '/';
                if (!DB::val('SELECT id FROM menu_items WHERE url = ? AND location = ?', [$url, 'header'])) {
                    DB::insert('menu_items', ['location' => 'header', 'label' => $label, 'url' => $url, 'parent_id' => $parent, 'sort_order' => ++$sort, 'is_active' => 1, 'new_tab' => 0]);
                }
            }
        }
        return $added;
    }
}
