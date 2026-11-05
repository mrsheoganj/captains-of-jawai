<?php
declare(strict_types=1);

namespace App\Core;

/** Articles 73–92: booking, comparisons, itineraries and practical questions. Part of {@see Articles}. */
final class ArticlesPart6
{
    public static function rows(): array
    {
        return [
            [
                'slug' => 'jawai-safari-booking-online', 'title' => 'Jawai safari booking online', 'img' => 'safari-jeep',
                'intro' => 'How to book a Jawai leopard safari online: pick a date, choose morning or evening, send the form or WhatsApp and get a reply.',
                'mt' => 'Jawai Safari Booking Online: Dates, Timings & Price from {price}',
                'md' => 'Book a Jawai leopard safari online: choose your date and morning or evening slot, send the form or WhatsApp. Safaris from {price} per jeep.',
                'body' => <<<'HTML'
<p>Booking a Jawai safari takes a few minutes. There is no online payment on this site: you send your plan and we confirm the details with you.</p>
<h2>Steps</h2>
<ol>
<li>Choose your <strong>date</strong> and whether you want the morning (6:00 to 9:00 AM) or evening (4:00 to 7:00 PM) safari.</li>
<li>Fill in the <a href="/book-a-safari/">booking form</a> with your group size and contact details, or message us on WhatsApp.</li>
<li>We reply to confirm availability, the price and meeting point.</li>
</ol>
<h2>What to have ready</h2>
<ul>
<li>Number of guests (a jeep seats up to six).</li>
<li>Your arrival plan: airport, station or road.</li>
<li>Any wish such as photography or birdwatching.</li>
</ul>
<h2>Price</h2>
<p>Safaris start <strong>from {price} per jeep</strong>. The jeep is for your own group. See <a href="/jawai-safari-price/">Jawai safari price</a>.</p>
<p>Book early for weekends and winter holidays. Read <a href="/how-to-book-jawai-safari/">how to book a Jawai safari</a> and <a href="/jawai-trip-checklist/">the trip checklist</a>.</p>
HTML,
                'faq' => [
                    'Can I pay online? | There is no online payment. We confirm your booking by message.',
                    'How many people fit in a jeep? | Up to six guests.',
                    'How early should I book? | A few days ahead normally; weeks ahead for winter weekends and holidays.',
                ],
            ],
            [
                'slug' => 'private-vs-shared-jawai-safari', 'title' => 'Private or shared Jawai safari?', 'img' => 'safari1',
                'intro' => 'Private and shared 4x4 gypsy safaris both run in Jawai. How they differ and which suits your group.',
                'mt' => 'Private vs Shared Jawai Safari: Which 4x4 Gypsy Option Is Better?',
                'md' => 'Private vs shared Jawai safari: compare privacy, cost and flexibility of a private 4x4 gypsy and a shared jeep, and choose what suits your group.',
                'body' => <<<'HTML'
<p>You can go out in a jeep of your own, or share a jeep with other guests.</p>
<h2>Compared</h2>
<table>
<thead><tr><th></th><th>Private jeep</th><th>Shared jeep</th></tr></thead>
<tbody>
<tr><td>Who is with you</td><td>Only your group</td><td>Other guests</td></tr>
<tr><td>Flexibility</td><td>You can stay longer at a sighting</td><td>Group decides together</td></tr>
<tr><td>Cost</td><td>Per jeep, from {price}</td><td>Shared between guests</td></tr>
<tr><td>Best for</td><td>Families, couples, photographers</td><td>Solo travellers and small budgets</td></tr>
</tbody>
</table>
<h2>Our advice</h2>
<p>A private jeep is worth it if you are photographing or travelling with children, because you can go at your own pace. Solo travellers may prefer a shared jeep to share the cost. Ask for the current options when you enquire.</p>
<p>Related: <a href="/jawai-safari-for-solo-travellers/">Jawai for solo travellers</a>, <a href="/jawai-safari-price/">Jawai safari price</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Do you offer shared safaris? | Yes, ask for the current shared options when you enquire.',
                    'Is a private jeep better for photography? | Yes, because you control the timing and position.',
                    'How much is a private jeep? | From {price} per jeep, for up to six guests.',
                ],
            ],
            [
                'slug' => 'bera-rajasthan-leopard-village', 'title' => 'Bera, Rajasthan: the leopard village', 'img' => 'granite-boulders',
                'intro' => 'Bera is the small village at the heart of the Jawai leopard area. What it is, how far it is from the cities and why people stay here.',
                'mt' => 'Bera, Rajasthan: Jawai\'s Leopard Village Guide & Distances',
                'md' => 'Bera in Rajasthan is the village at the heart of Jawai\'s leopard country. Where it is, distances from Udaipur and Jodhpur, and why it is the base.',
                'body' => <<<'HTML'
<p>Bera is a small village in Pali district, surrounded by granite hills. It has become the base for Jawai leopard safaris.</p>
<h2>Distances to Bera</h2>
<table>
<thead><tr><th>From</th><th>Road distance</th></tr></thead>
<tbody>
<tr><td>Sumerpur Jawai Bandh station</td><td>about 18 km</td></tr>
<tr><td>Falna station</td><td>about 37 km</td></tr>
<tr><td>Udaipur</td><td>about 115 km</td></tr>
<tr><td>Jodhpur</td><td>about 170 km</td></tr>
</tbody>
</table>
<h2>Why Bera?</h2>
<p>The granite hills where leopards rest are close to the village, so safaris reach the wildlife quickly. The area is quiet, with local Rabari families, small temples and farmland.</p>
<h2>Practical</h2>
<p>Facilities are simple. Carry cash, an offline map and a plan for transport. See <a href="/jawai-mobile-network-and-cash/">mobile network and cash in Jawai</a>.</p>
<p>Search "Bera, Rajasthan" in your maps app. Read <a href="/jawai-map-and-location/">Jawai map and location</a> and <a href="/where-to-stay-near-jawai/">where to stay near Jawai</a>.</p>
HTML,
                'faq' => [
                    'Where is Bera? | In Pali district, Rajasthan, close to the Jawai Dam and granite hills.',
                    'How far is Bera from Udaipur? | About 115 km by road.',
                    'Is Bera the same as Jawai? | Bera is the village; Jawai is the wider area and the dam.',
                ],
            ],
            [
                'slug' => 'where-to-stay-near-jawai', 'title' => 'Where to stay near Jawai', 'img' => 'landscape1',
                'intro' => 'Stay options near the Jawai leopard hills: in Bera, in the nearby towns, or in Udaipur and Jodhpur. How to choose.',
                'mt' => 'Where to Stay Near Jawai: Bera, Sumerpur, Falna or Udaipur?',
                'md' => 'Where to stay near Jawai: in or near Bera for easy safaris, or in Sumerpur and Falna on a budget, or in Udaipur and Jodhpur for city trips.',
                'body' => <<<'HTML'
<p>We run safaris only and do not sell rooms, but guests often ask where to stay. Here is how to think about it.</p>
<h2>Options</h2>
<table>
<thead><tr><th>Area</th><th>Good for</th><th>Notes</th></tr></thead>
<tbody>
<tr><td>In or near Bera</td><td>Easy early safari starts</td><td>Few places; book well ahead in winter</td></tr>
<tr><td>Sumerpur and Falna</td><td>Budget and train arrivals</td><td>Falna is about 37 km from Bera</td></tr>
<tr><td>Udaipur</td><td>City sightseeing</td><td>About 115 km, 2.5 hours; a long day trip</td></tr>
<tr><td>Jodhpur</td><td>Forts and the desert</td><td>About 170 km</td></tr>
</tbody>
</table>
<h2>Tips</h2>
<ul>
<li>For the 6:00 AM safari, sleeping near Bera saves an early long drive.</li>
<li>Book accommodation first in winter; it fills quickly.</li>
<li>Tell your host about the safari so they can plan early tea or breakfast.</li>
</ul>
<p>Read <a href="/bera-rajasthan-leopard-village/">Bera, the leopard village</a>, <a href="/jawai-safari-budget/">Jawai trip budget</a> and <a href="/jawai-itinerary/">a 2-day itinerary</a>.</p>
HTML,
                'faq' => [
                    'Do you offer accommodation? | We run safaris only, so please book your stay separately.',
                    'Can I stay in Udaipur and do a safari? | Yes, but it is a long day, so most guests stay closer.',
                    'Should I book early? | Yes, especially from November to February.',
                ],
            ],
            [
                'slug' => 'jawai-safari-for-seniors', 'title' => 'Jawai safari for senior travellers', 'img' => 'safari-guests',
                'intro' => 'A Jawai leopard safari is a gentle outing from a jeep. What to know about comfort, access and pace for older travellers.',
                'mt' => 'Jawai Safari for Senior Citizens: Comfort, Access & Tips',
                'md' => 'Is a Jawai safari suitable for senior citizens? Yes, from a jeep with no walking. Tips on comfort, timing, clothing and pacing the trip.',
                'body' => <<<'HTML'
<p>A safari is done from the vehicle, so there is no walking or climbing. Many older guests enjoy it.</p>
<h2>Comfort</h2>
<ul>
<li>The tracks are rough, so ask for a gentle pace and tell the guide about back or neck concerns.</li>
<li>Choose the <strong>evening safari</strong> (4:00 to 7:00 PM) if early starts are tiring.</li>
<li>Bring a cushion and a light blanket in winter.</li>
</ul>
<h2>Health</h2>
<ul>
<li>Carry your regular medicines and water.</li>
<li>Avoid peak summer afternoons.</li>
<li>Rest between safaris.</li>
</ul>
<h2>Access</h2>
<p>Stepping into the jeep needs a small climb; guides help. Mention mobility needs when you book.</p>
<p>Read <a href="/jawai-safari-etiquette/">safari etiquette</a>, <a href="/jawai-with-kids/">Jawai with kids</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Is walking needed? | No. Safaris are done from the jeep.',
                    'Which safari is easier? | The evening safari avoids the cold early start.',
                    'Can you help with mobility needs? | Tell us when you book, and we will arrange assistance.',
                ],
            ],
            [
                'slug' => 'jawai-safari-for-solo-travellers', 'title' => 'Jawai for solo travellers', 'img' => 'leopard-basking',
                'intro' => 'Travelling alone to Jawai: how to reach it, share a jeep, stay safe and make the most of a leopard safari.',
                'mt' => 'Jawai for Solo Travellers: Getting There, Safety & Shared Safaris',
                'md' => 'Visiting Jawai alone? Tips for solo travellers: getting there, shared safaris to split cost, staying safe and meeting other wildlife lovers.',
                'body' => <<<'HTML'
<p>Jawai is a calm, friendly place for solo travellers, especially in the cooler months.</p>
<h2>Getting there</h2>
<p>Use Udaipur (about 115 km) as your gateway and arrange a car in advance. See <a href="/udaipur-airport-to-jawai/">Udaipur airport to Jawai</a>. Aim to arrive in daylight.</p>
<h2>Sharing the cost</h2>
<p>A private jeep is priced per vehicle, so a shared jeep can be cheaper for one person. Ask about <a href="/private-vs-shared-jawai-safari/">shared options</a>.</p>
<h2>Staying safe</h2>
<ul>
<li>Share your plan with someone at home.</li>
<li>Keep cash and an offline map.</li>
<li>Use a trusted driver and avoid arriving after dark.</li>
</ul>
<h2>Making the most of it</h2>
<p>Do both safaris, 6:00 to 9:00 AM and 4:00 to 7:00 PM, and use the gap for a village walk with a guide.</p>
<p>Also read <a href="/is-jawai-safe-for-tourists/">is Jawai safe for tourists?</a></p>
HTML,
                'faq' => [
                    'Can I book a single seat? | Ask about shared jeep options when you enquire.',
                    'Is Jawai safe alone? | Generally calm, but take normal precautions and avoid night travel.',
                    'How many days should I stay? | Two nights gives three safari chances.',
                ],
            ],
            [
                'slug' => 'jawai-group-safari-booking', 'title' => 'Group safari booking in Jawai', 'img' => 'safari-jeep',
                'intro' => 'Booking a Jawai safari for friends, family or a team: how many jeeps you need, how the cost splits and how to coordinate.',
                'mt' => 'Jawai Group Safari Booking: Jeeps, Cost per Person & Planning',
                'md' => 'Group safari booking in Jawai: a jeep seats six, safaris from {price} per jeep. How to plan for 8, 12 or 20 guests and split the cost.',
                'body' => <<<'HTML'
<p>Each jeep seats up to <strong>six guests</strong>. Larger groups use more than one jeep.</p>
<h2>How many jeeps?</h2>
<table>
<thead><tr><th>Group size</th><th>Jeeps</th></tr></thead>
<tbody>
<tr><td>1 to 6</td><td>1</td></tr>
<tr><td>7 to 12</td><td>2</td></tr>
<tr><td>13 to 18</td><td>3</td></tr>
</tbody>
</table>
<h2>Cost</h2>
<p>From {price} per jeep; a full jeep works out at about {price_each} per person. Costs split evenly across a full group.</p>
<h2>Tips</h2>
<ul>
<li>Book well ahead; several jeeps need to be free for the same slot.</li>
<li>Keep groups at a sighting small so the animals stay calm.</li>
<li>Name one person to coordinate arrivals.</li>
</ul>
<p>See <a href="/jawai-safari-price/">Jawai safari price</a>, <a href="/jawai-safari-budget/">trip budget</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'How many fit in one jeep? | Up to six.',
                    'Can you take a group of 12? | Yes, with two jeeps. Book ahead.',
                    'Is there a group discount? | Ask when you enquire.',
                ],
            ],
            [
                'slug' => 'jawai-in-march', 'title' => 'Jawai in March', 'img' => 'granite-dome-dusk',
                'intro' => 'March is the last good month before the heat: warm days, comfortable mornings and fewer people.',
                'mt' => 'Jawai in March: Weather, Wildlife & Safari Tips',
                'md' => 'Jawai in March: warming days, pleasant mornings, fewer crowds and still good wildlife. Weather and tips for a March leopard safari.',
                'body' => <<<'HTML'
<p>March sits at the end of the winter season. The mornings are still pleasant, and the days begin to warm up.</p>
<h2>What to expect</h2>
<ul>
<li>Pleasant mornings and evenings; warm to hot afternoons.</li>
<li>Fewer visitors than winter.</li>
<li>Drier land and clear views.</li>
<li>Some winter birds still around early in the month.</li>
</ul>
<h2>The safari</h2>
<p>Choose the 6:00 to 9:00 AM safari for cooler air, and the 4:00 to 7:00 PM safari for evening light. Sightings are never guaranteed.</p>
<h2>Pack</h2>
<p>Light cotton, a hat, sunscreen and plenty of water.</p>
<p>See <a href="/jawai-in-january-february/">Jawai in January and February</a>, <a href="/jawai-in-summer/">Jawai in summer</a> and <a href="/jawai-safari-timings/">safari timings</a>.</p>
HTML,
                'faq' => [
                    'Is March too hot? | Afternoons warm up, but mornings and evenings are pleasant.',
                    'Is it quieter than winter? | Yes, usually.',
                    'Which safari is better in March? | Morning, for the coolest air.',
                ],
            ],
            [
                'slug' => 'jawai-in-september', 'title' => 'Jawai in September', 'img' => 'lake-reflection',
                'intro' => 'September is the tail end of the monsoon: green hills, a full dam and a quiet, atmospheric time to visit.',
                'mt' => 'Jawai in September: Monsoon Greens, Dam & Safari Tips',
                'md' => 'Jawai in September: monsoon greens, a full dam and few visitors. What to expect, safari conditions and how to prepare.',
                'body' => <<<'HTML'
<p>By September the monsoon is easing. The hills are green, the dam is full, and the area is quiet.</p>
<h2>What to expect</h2>
<ul>
<li>Lush scenery and dramatic skies.</li>
<li>Humid days and the chance of rain.</li>
<li>Very few visitors.</li>
<li>Muddy tracks that can affect where jeeps go.</li>
</ul>
<h2>The safari</h2>
<p>Safaris may be adjusted for weather. Thick vegetation can make animals harder to spot, so keep expectations flexible. Morning is 6:00 to 9:00 AM and evening 4:00 to 7:00 PM.</p>
<h2>Pack</h2>
<p>A light rain jacket, insect repellent and quick-dry clothes.</p>
<p>Compare with <a href="/jawai-in-monsoon/">Jawai in the monsoon</a> and <a href="/jawai-in-october/">Jawai in October</a>.</p>
HTML,
                'faq' => [
                    'Is September a good time? | It is scenic and quiet, but wildlife is harder to see than in winter.',
                    'Will it rain? | Showers are possible, so carry rain protection.',
                    'Are safaris cancelled in rain? | They can be adjusted. Check with us before you travel.',
                ],
            ],
            [
                'slug' => 'jawai-leopard-safari-vs-tiger-safari', 'title' => 'Leopard safari vs tiger safari', 'img' => 'leopard-stalking',
                'intro' => 'Leopard safaris in Jawai and tiger safaris in national parks are very different experiences. A fair comparison.',
                'mt' => 'Leopard Safari vs Tiger Safari: Jawai or a Tiger Reserve?',
                'md' => 'Leopard safari vs tiger safari: compare Jawai with tiger reserves on landscape, crowds, rules and chances. Which wildlife trip suits you?',
                'body' => <<<'HTML'
<p>Both are wonderful, but they are not the same trip.</p>
<h2>Compared</h2>
<table>
<thead><tr><th></th><th>Jawai leopard safari</th><th>Tiger reserve safari</th></tr></thead>
<tbody>
<tr><td>Animal</td><td>Leopards on open granite</td><td>Tigers in forest and grass</td></tr>
<tr><td>Setting</td><td>Granite hills, lake, villages</td><td>Forests and parks</td></tr>
<tr><td>Crowds</td><td>Small, private jeeps</td><td>Often many vehicles</td></tr>
<tr><td>Status</td><td>Conservation reserve</td><td>National park or tiger reserve</td></tr>
</tbody>
</table>
<h2>Which to choose</h2>
<p>If you want a quieter, scenic safari with good odds of seeing a leopard, choose Jawai. If a tiger is the dream, a tiger reserve is the place. See <a href="/jawai-vs-ranthambore/">Jawai vs Ranthambore</a>.</p>
<p>Neither guarantees sightings. Read <a href="/jawai-leopard-sighting-chances/">Jawai leopard sighting chances</a>.</p>
HTML,
                'faq' => [
                    'Are there tigers in Jawai? | No. Jawai is known for leopards.',
                    'Is Jawai less crowded? | Typically yes, with small private jeeps.',
                    'Can I do both? | Yes. Many travellers combine different parks on one tour.',
                ],
            ],
            [
                'slug' => 'jawai-vs-gir', 'title' => 'Jawai vs Gir: leopards or lions?', 'img' => 'ridge-sighting',
                'intro' => 'Jawai is known for leopards and Gir for Asiatic lions. How the two wildlife trips compare.',
                'mt' => 'Jawai vs Gir: Leopard Safari or Lion Safari in India?',
                'md' => 'Jawai vs Gir: Jawai is known for leopards in Rajasthan, Gir for Asiatic lions in Gujarat. Compare the landscape, travel and experience.',
                'body' => <<<'HTML'
<p>Both are in western India, and some travellers combine them on a long tour.</p>
<h2>The difference</h2>
<table>
<thead><tr><th></th><th>Jawai</th><th>Gir</th></tr></thead>
<tbody>
<tr><td>State</td><td>Rajasthan</td><td>Gujarat</td></tr>
<tr><td>Main animal</td><td>Leopard</td><td>Asiatic lion</td></tr>
<tr><td>Landscape</td><td>Granite hills and a lake</td><td>Dry deciduous forest</td></tr>
<tr><td>Feel</td><td>Small, quiet, private</td><td>Larger national park</td></tr>
</tbody>
</table>
<h2>Travel</h2>
<p>Ahmedabad is a convenient hub for both. Jawai is about 283 km from Ahmedabad; see <a href="/ahmedabad-to-jawai/">Ahmedabad to Jawai</a>.</p>
<p>If you want hillside leopards in a scenic setting, choose Jawai. For lions, Gir is the place. Compare more in <a href="/jawai-leopard-safari-vs-tiger-safari/">leopard vs tiger safari</a>.</p>
HTML,
                'faq' => [
                    'Are there lions in Jawai? | No. Lions live in Gir, Gujarat.',
                    'Which is easier to reach? | Both are accessible by road and air, depending on where you start.',
                    'Can I combine them? | Yes, on a longer western India tour.',
                ],
            ],
            [
                'slug' => 'jawai-dam-facts', 'title' => 'Jawai Dam facts', 'img' => 'bandh-shoreline',
                'intro' => 'Quick facts about the Jawai Dam: when it was built, what it feeds, and why it matters to wildlife and people.',
                'mt' => 'Jawai Dam Facts: History, Purpose, Wildlife & Visiting Tips',
                'md' => 'Jawai Dam facts: built between 1946 and 1957, it supplies water and sustains wildlife and birds. History, wildlife and tips for visiting.',
                'body' => <<<'HTML'
<p>The Jawai Dam, also called Jawai Bandh, is the heart of the area.</p>
<h2>Quick facts</h2>
<ul>
<li><strong>Built:</strong> between 1946 and 1957, across the Jawai river.</li>
<li><strong>Purpose:</strong> water for farming and local supply.</li>
<li><strong>Wildlife:</strong> marsh crocodiles, water birds including flamingos and pelicans in season.</li>
<li><strong>Setting:</strong> surrounded by granite hills and small villages.</li>
</ul>
<h2>Why it matters</h2>
<p>The water supports farming, livestock and wildlife in a dry region. Animals, including leopards, depend on it, especially in the hot months.</p>
<h2>Visiting</h2>
<p>Go early in the morning for birds and calm water, or at sunset for colour. Keep away from the water's edge because of the crocodiles.</p>
<p>See the guide to <a href="/jawai/jawai-dam/">the Jawai Dam</a>, <a href="/birds-of-jawai/">birds of Jawai</a> and <a href="/crocodiles-at-jawai-dam/">crocodiles at Jawai Dam</a>.</p>
HTML,
                'faq' => [
                    'When was Jawai Dam built? | Between 1946 and 1957.',
                    'What lives in the dam? | Marsh crocodiles and many water birds.',
                    'Can I swim? | No. Crocodiles live in the dam.',
                ],
            ],
            [
                'slug' => 'is-jawai-safe-for-tourists', 'title' => 'Is Jawai safe for tourists?', 'img' => 'safari-guests',
                'intro' => 'Safety on a Jawai safari: wildlife rules, road travel, health and everyday precautions for visitors.',
                'mt' => 'Is Jawai Safe for Tourists? Safari, Roads & Health Tips',
                'md' => 'Is Jawai safe for tourists? Yes, with sensible precautions: stay in the jeep, avoid night driving, carry cash and water. Practical safety tips.',
                'body' => <<<'HTML'
<p>Jawai is a quiet rural area, and visitors generally have a calm trip. A few precautions help.</p>
<h2>On safari</h2>
<ul>
<li>Stay in the vehicle and follow your guide.</li>
<li>Leopards are wild but have lived beside people for generations; they avoid contact.</li>
<li>Keep away from the dam's edge (crocodiles).</li>
</ul>
<h2>Travel</h2>
<ul>
<li>Use daylight for the final drive; the roads are small and unlit.</li>
<li>Agree taxi prices in advance.</li>
</ul>
<h2>Health</h2>
<ul>
<li>Drink bottled or filtered water.</li>
<li>Carry sunscreen, insect repellent and personal medicines.</li>
</ul>
<h2>Everyday</h2>
<p>Keep valuables out of sight and carry some cash. Read <a href="/jawai-safari-etiquette/">safari etiquette</a> and <a href="/jawai-trip-checklist/">the trip checklist</a>.</p>
HTML,
                'faq' => [
                    'Are leopards dangerous? | They are wild and avoid people; follow the rules and stay in the jeep.',
                    'Is it safe for women travelling alone? | Generally yes with normal precautions; avoid night travel.',
                    'Is it safe for children? | Yes, with supervision and the same rules. See Jawai with kids.',
                ],
            ],
            [
                'slug' => 'jawai-mobile-network-and-cash', 'title' => 'Mobile network, ATMs and cash in Jawai', 'img' => 'sunset-valley',
                'intro' => 'Signal can be patchy and card machines limited in Jawai\'s villages. What to prepare before you arrive.',
                'mt' => 'Jawai Mobile Network, ATMs & Cash: What to Prepare',
                'md' => 'Mobile signal can be patchy and ATMs limited near Jawai. Carry cash, download offline maps and plan payments before you arrive.',
                'body' => <<<'HTML'
<p>Jawai is rural, so some things you rely on in cities may be limited.</p>
<h2>Mobile signal</h2>
<p>Coverage can be patchy in the hills. Download an offline map and save key phone numbers before you leave Udaipur or Jodhpur.</p>
<h2>Cash and ATMs</h2>
<ul>
<li>Withdraw cash in a larger town before the drive.</li>
<li>Small shops and villages may not take cards.</li>
<li>UPI may work in some places but not everywhere.</li>
</ul>
<h2>Other tips</h2>
<ul>
<li>Charge devices and carry a power bank.</li>
<li>Tell family that signal may drop.</li>
<li>Confirm your pick-up details before you lose signal.</li>
</ul>
<p>See <a href="/jawai-trip-checklist/">the trip checklist</a>, <a href="/bera-rajasthan-leopard-village/">Bera, the leopard village</a> and <a href="/is-jawai-safe-for-tourists/">is Jawai safe?</a></p>
HTML,
                'faq' => [
                    'Is there mobile signal in Jawai? | Often, but it can be patchy in the hills.',
                    'Should I carry cash? | Yes, from a larger town.',
                    'Can I use UPI? | Sometimes. Do not rely on it everywhere.',
                ],
            ],
            [
                'slug' => 'jawai-leopard-safari-cost-per-person', 'title' => 'Jawai leopard safari cost per person', 'img' => 'leopard-portrait',
                'intro' => 'How to work out the per-person cost of a Jawai safari: the jeep price divided by the guests in your group.',
                'mt' => 'Jawai Leopard Safari Cost per Person: Jeep Price Explained',
                'md' => 'Jawai safari cost per person: priced per jeep from {price}, so a full jeep of six works out at about {price_each} each. See how to save.',
                'body' => <<<'HTML'
<p>Safaris in Jawai are priced per <strong>jeep</strong>, not per person. That means the more guests you share with, the less each person pays.</p>
<h2>Working it out</h2>
<table>
<thead><tr><th>Guests in the jeep</th><th>Cost per person</th></tr></thead>
<tbody>
<tr><td>6</td><td>about {price_each}</td></tr>
<tr><td>4</td><td>about one quarter of the jeep price</td></tr>
<tr><td>2</td><td>about half of the jeep price</td></tr>
</tbody>
</table>
<p>The jeep price starts <strong>from {price}</strong>. Longer drives and photography safaris cost more.</p>
<h2>Ways to lower the cost</h2>
<ul>
<li>Fill the jeep with up to six guests.</li>
<li>Ask about shared options; see <a href="/private-vs-shared-jawai-safari/">private or shared?</a></li>
<li>Travel outside holiday weeks.</li>
</ul>
<p>More in <a href="/jawai-safari-price/">Jawai safari price</a> and <a href="/jawai-safari-budget/">trip budget</a>.</p>
HTML,
                'faq' => [
                    'Is the safari priced per person? | No, per jeep.',
                    'What is the starting price? | From {price} per jeep.',
                    'Do longer safaris cost more? | Yes, longer or photography drives are priced higher.',
                ],
            ],
            [
                'slug' => 'jawai-binoculars-and-camera-guide', 'title' => 'Binoculars and camera for a Jawai safari', 'img' => 'leopard1',
                'intro' => 'Which binoculars and camera gear to bring to Jawai, from phone to telephoto, and how to use them from a jeep.',
                'mt' => 'Binoculars & Camera for Jawai Safari: What to Bring',
                'md' => 'What binoculars and camera to bring to a Jawai leopard safari: 8x42 binoculars, a 200mm+ lens, phone tips and how to shoot from a jeep.',
                'body' => <<<'HTML'
<p>You do not need expensive gear, but a few items make the safari more rewarding.</p>
<h2>Binoculars</h2>
<p>An 8x42 or 10x42 pair works well for birds, crocodiles and leopards on distant rocks.</p>
<h2>Camera</h2>
<ul>
<li><strong>Phone:</strong> fine for landscapes and village scenes; zoom limits wildlife shots.</li>
<li><strong>Telephoto:</strong> 200 mm or longer for leopards and birds.</li>
<li><strong>Extras:</strong> a bean bag, spare batteries, memory cards and a dust cover.</li>
</ul>
<h2>Shooting from a jeep</h2>
<ul>
<li>Switch off flash; it is not allowed.</li>
<li>Rest the camera on a bag or the rail, not on your hands.</li>
<li>Use fast shutter speeds in low light.</li>
<li>Keep voices low and wait for the moment.</li>
</ul>
<p>More in <a href="/jawai-photography-tips/">photography tips</a>, <a href="/jawai-photography-spots/">photography spots</a> and <a href="/private-leopard-photo-safari-rajasthan/">private photo safaris</a>.</p>
HTML,
                'faq' => [
                    'What binoculars are best? | 8x42 or 10x42.',
                    'Can I use my phone? | Yes, but a telephoto lens works better for wildlife.',
                    'Is a tripod allowed? | A bean bag or monopod is more practical in a jeep.',
                ],
            ],
            [
                'slug' => 'jawai-day-trip-from-udaipur', 'title' => 'Jawai day trip from Udaipur', 'img' => 'granite-boulders',
                'intro' => 'Is a day trip to Jawai from Udaipur realistic? The timings, the drive and a plan that works.',
                'mt' => 'Jawai Day Trip from Udaipur: Is It Possible? Timings & Plan',
                'md' => 'Jawai day trip from Udaipur: about 115 km each way, 2.5 hours. A realistic one-day plan with the evening safari, and why an overnight is better.',
                'body' => <<<'HTML'
<p>It is possible, but it is a long day: about 115 km each way, or around two and a half hours of driving each direction.</p>
<h2>A day-trip plan</h2>
<table>
<thead><tr><th>Time</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>11:30 AM</td><td>Leave Udaipur</td></tr>
<tr><td>2:00 PM</td><td>Arrive, lunch and rest</td></tr>
<tr><td>4:00 to 7:00 PM</td><td>Evening safari</td></tr>
<tr><td>7:30 PM</td><td>Drive back; arrive about 10:00 PM</td></tr>
</tbody>
</table>
<h2>The catch</h2>
<p>You would drive back in the dark on small roads. Many guests prefer to stay one night, do the evening and 6:00 AM safaris, and return after breakfast.</p>
<p>See <a href="/udaipur-to-jawai-by-car/">Udaipur to Jawai by car</a> and <a href="/jawai-1-day-itinerary/">a one-day itinerary</a>.</p>
HTML,
                'faq' => [
                    'Can I visit Jawai from Udaipur in one day? | Yes, but it is long; an overnight stay is more comfortable.',
                    'How long is the drive? | Around 2.5 hours each way.',
                    'Is driving back at night safe? | It is better to avoid it. Consider staying a night.',
                ],
            ],
            [
                'slug' => 'jawai-1-day-itinerary', 'title' => 'Jawai 1-day itinerary', 'img' => 'safari1',
                'intro' => 'If you have just one day in Jawai, here is how to use it: a morning safari, a village or temple visit and an evening safari.',
                'mt' => 'Jawai 1-Day Itinerary: Two Safaris, Temple & Village Visit',
                'md' => 'Jawai in one day: morning safari 6:00 to 9:00, village or temple visit, rest, then the evening safari 4:00 to 7:00. A full day plan.',
                'body' => <<<'HTML'
<p>One day is enough for two safaris and a taste of the area, if you are already in Jawai.</p>
<h2>The day</h2>
<table>
<thead><tr><th>Time</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>6:00 to 9:00 AM</td><td>Morning safari</td></tr>
<tr><td>9:30 AM</td><td>Breakfast</td></tr>
<tr><td>10:30 AM</td><td>Village visit or a temple such as <a href="/dev-giri-temple-jawai/">Dev Giri</a></td></tr>
<tr><td>1:00 PM</td><td>Lunch and rest</td></tr>
<tr><td>4:00 to 7:00 PM</td><td>Evening safari, ending at sunset</td></tr>
</tbody>
</table>
<h2>Tips</h2>
<ul>
<li>Sleep near Bera the night before.</li>
<li>Rest at midday, which is the hottest part of the day.</li>
<li>Bring layers in winter.</li>
</ul>
<p>For more time, see <a href="/jawai-itinerary/">the 2-day itinerary</a> and <a href="/jawai-3-day-itinerary/">the 3-day itinerary</a>.</p>
HTML,
                'faq' => [
                    'Is one day enough? | For two safaris and a short visit, yes.',
                    'Can I add a temple? | Yes, between the safaris.',
                    'Where should I stay the night before? | Close to Bera to make the 6:00 AM start easy.',
                ],
            ],
            [
                'slug' => 'jawai-3-day-itinerary', 'title' => 'Jawai 3-day itinerary', 'img' => 'granite-dome-dusk',
                'intro' => 'A relaxed three-day plan for Jawai with four or five safaris, a dam birding drive, village life and a day trip to Ranakpur.',
                'mt' => 'Jawai 3-Day Itinerary: Safaris, Dam, Villages & Ranakpur',
                'md' => 'Jawai 3-day itinerary: leopard safaris, a dam birding drive, village life and a day trip to Ranakpur. A relaxed plan for wildlife lovers.',
                'body' => <<<'HTML'
<p>Three days gives you time to see Jawai properly, with several safaris and some culture.</p>
<h2>The plan</h2>
<table>
<thead><tr><th>Day</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>1</td><td>Arrive, rest, evening safari (4:00 to 7:00 PM)</td></tr>
<tr><td>2</td><td>Morning safari (6:00 to 9:00 AM); village visit; dam birding drive in the afternoon</td></tr>
<tr><td>3</td><td>Early safari, or a day trip to <a href="/ranakpur-to-jawai/">Ranakpur</a> (about 48 km)</td></tr>
</tbody>
</table>
<h2>Why three days works</h2>
<ul>
<li>Several safaris improve your chances.</li>
<li>You are not rushed between travel and wildlife.</li>
<li>You can add a temple, village and birding.</li>
</ul>
<p>Best between October and March. Read <a href="/jawai-itinerary/">the 2-day itinerary</a>, <a href="/jawai-road-trip-rajasthan/">the Rajasthan road trip</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'How many safaris in three days? | Four or five are possible.',
                    'Can I add Kumbhalgarh? | Yes, as a day trip about 81 km away.',
                    'What is the best season? | October to March.',
                ],
            ],
            [
                'slug' => 'jawai-safari-faq', 'title' => 'Jawai safari: your questions answered', 'img' => 'leopard2',
                'intro' => 'The questions people ask most about Jawai leopard safaris: timings, price, season, sightings and how to book.',
                'mt' => 'Jawai Safari FAQ: Timings, Price, Best Time & Sightings',
                'md' => 'Jawai safari FAQ: safari timings, price from {price}, best season, leopard sightings, what to wear and how to book. Quick honest answers.',
                'body' => <<<'HTML'
<h2>The quick answers</h2>
<ul>
<li><strong>Timings:</strong> morning 6:00 to 9:00 AM, evening 4:00 to 7:00 PM. See <a href="/jawai-safari-timings/">Jawai safari timings</a>.</li>
<li><strong>Price:</strong> from {price} per jeep, up to six guests. See <a href="/jawai-safari-price/">Jawai safari price</a>.</li>
<li><strong>Best season:</strong> October to March. See <a href="/jawai-in-winter/">Jawai in winter</a>.</li>
<li><strong>Sightings:</strong> likely, never guaranteed. See <a href="/jawai-leopard-sighting-chances/">sighting chances</a>.</li>
<li><strong>Nearest airport:</strong> Udaipur, about 130 km. See <a href="/flights-to-jawai/">flights to Jawai</a>.</li>
<li><strong>Nearest station:</strong> Sumerpur Jawai Bandh (JWB), about 18 km. See <a href="/jawai-by-train/">Jawai by train</a>.</li>
<li><strong>What to wear:</strong> earth tones and layers. See <a href="/jawai-outfit-ideas/">outfit ideas</a>.</li>
<li><strong>How to book:</strong> use the <a href="/book-a-safari/">booking form</a> or WhatsApp.</li>
</ul>
<h2>Still have questions?</h2>
<p>Read the <a href="/faq/">FAQ</a> or the <a href="/jawai-travel-guides/">full list of Jawai guides</a>, or <a href="/contact/">contact us</a>.</p>
HTML,
                'faq' => [
                    'What is the best time of day for a safari? | Both morning (6:00 to 9:00 AM) and evening (4:00 to 7:00 PM) are good.',
                    'Do you guarantee leopard sightings? | No. They are wild animals.',
                    'How do I book? | Use the booking form or message us on WhatsApp.',
                ],
            ],
        ];
    }
}
