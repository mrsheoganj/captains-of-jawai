<?php
declare(strict_types=1);

namespace App\Core;

/** Articles 39–50: safari timings, seasons by month and trip planning. Part of {@see Articles}. */
final class ArticlesPart5
{
    public static function rows(): array
    {
        return [
            [
                'slug' => 'jawai-safari-timings', 'title' => 'Jawai safari timings', 'img' => 'sunset-valley',
                'intro' => 'Jawai safaris run in two windows: 6:00 to 9:00 AM and 4:00 to 7:00 PM. Which to choose and how to plan the day around them.',
                'mt' => 'Jawai Safari Timings: Morning 6–9 AM & Evening 4–7 PM',
                'md' => 'Jawai safari timings: morning safari 6:00 to 9:00 AM and evening safari 4:00 to 7:00 PM. Which is better and how to plan your day.',
                'body' => <<<'HTML'
<p>Leopards are most active around dawn and dusk, so the two safari windows follow that rhythm.</p>
<h2>The two safaris</h2>
<table>
<thead><tr><th>Safari</th><th>Timing</th><th>Feel</th></tr></thead>
<tbody>
<tr><td>Morning</td><td>6:00 to 9:00 AM</td><td>Cool air, soft light, birds and mist on the lake</td></tr>
<tr><td>Evening</td><td>4:00 to 7:00 PM</td><td>Golden light on granite, leopards waking up</td></tr>
</tbody>
</table>
<h2>Which should you pick?</h2>
<p>Both give good chances. Morning suits early risers and photographers who like soft light. Evening suits late arrivals and gives long sunset light. If you have time, do both. Compare them in <a href="/morning-vs-evening-jawai-safari/">morning or evening safari?</a></p>
<h2>Planning your day</h2>
<ul>
<li>Safari one morning, rest, village or dam visit, then safari in the evening.</li>
<li>Winter mornings are cold, so bring layers.</li>
<li>Timings can shift slightly with the season. Confirm when you book.</li>
</ul>
<p>Pricing starts <strong>from {price} per jeep</strong>. See <a href="/jawai-morning-safari/">the morning safari</a>, <a href="/jawai-evening-safari/">the evening safari</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'What are the Jawai safari timings? | Morning 6:00 to 9:00 AM and evening 4:00 to 7:00 PM.',
                    'Which is better, morning or evening? | Both are good. Doing both increases your chances.',
                    'Do timings change? | They can shift slightly with the season. Confirm when booking.',
                ],
            ],
            [
                'slug' => 'jawai-morning-safari', 'title' => 'Jawai morning safari (6:00 to 9:00 AM)', 'img' => 'lake-reflection',
                'intro' => 'What a Jawai morning safari is like: the cool start, the light, the birds and the leopards on their rocks.',
                'mt' => 'Jawai Morning Safari: 6–9 AM Timings, Experience & Tips',
                'md' => 'Jawai morning safari runs 6:00 to 9:00 AM: cool air, soft light, birds and leopards. What to expect, what to wear and how to prepare.',
                'body' => <<<'HTML'
<p>The morning safari starts at <strong>6:00 AM</strong> and runs to <strong>9:00 AM</strong>. It is quiet and often the most atmospheric of the day.</p>
<h2>What it is like</h2>
<ul>
<li>A cool start, sometimes with mist on the lake.</li>
<li>Soft light on the granite, good for photographs.</li>
<li>Many birds at the dam in the first hour.</li>
<li>Leopards returning to their rocks after the night.</li>
</ul>
<h2>Prepare</h2>
<ul>
<li>Layers: winter mornings are cold in an open jeep.</li>
<li>A scarf or buff against dust.</li>
<li>Camera, binoculars and water.</li>
<li>Be ready 15 minutes early.</li>
</ul>
<h2>After the safari</h2>
<p>Return for breakfast, rest, then visit a village, a temple or the dam. See <a href="/jawai-itinerary/">a 2-day itinerary</a>.</p>
<p>Starts from {price} per jeep for up to six guests. Compare with <a href="/jawai-evening-safari/">the evening safari</a> or read <a href="/jawai-safari-timings/">all Jawai safari timings</a>.</p>
HTML,
                'faq' => [
                    'What time is the morning safari? | 6:00 to 9:00 AM.',
                    'Is it cold? | In winter, yes. Bring layers and a scarf.',
                    'Is the morning safari good for photography? | Yes, the light is soft and the air is clear.',
                ],
            ],
            [
                'slug' => 'jawai-evening-safari', 'title' => 'Jawai evening safari (4:00 to 7:00 PM)', 'img' => 'granite-dome-dusk',
                'intro' => 'What to expect on a Jawai evening safari: golden light, active leopards and a sunset over the dam.',
                'mt' => 'Jawai Evening Safari: 4–7 PM Timings, Sunset & Leopard Chances',
                'md' => 'Jawai evening safari runs 4:00 to 7:00 PM: golden light on granite, active leopards and sunset. What to expect and what to bring.',
                'body' => <<<'HTML'
<p>The evening safari runs from <strong>4:00 PM</strong> to <strong>7:00 PM</strong>, as the heat fades and the leopards stir.</p>
<h2>Why people love it</h2>
<ul>
<li>Warm, golden light on the rocks.</li>
<li>Leopards waking from their daytime rest.</li>
<li>A sunset over the hills and the dam.</li>
<li>Easy to fit after a day of travel.</li>
</ul>
<h2>Prepare</h2>
<ul>
<li>A sun hat and sunglasses for the early part.</li>
<li>A warm layer for after sunset in winter.</li>
<li>Insect repellent in monsoon and warm months.</li>
</ul>
<h2>Arrival day</h2>
<p>If you arrive by midday, this is the ideal first safari. See <a href="/udaipur-airport-to-jawai/">Udaipur airport to Jawai</a> for timing.</p>
<p>From {price} per jeep for up to six guests. Compare with <a href="/jawai-morning-safari/">the morning safari</a>, and see <a href="/jawai-sunset-points/">sunset spots</a>.</p>
HTML,
                'faq' => [
                    'What time is the evening safari? | 4:00 to 7:00 PM.',
                    'Is the evening safari good for first-timers? | Yes, it is a gentle start and fits arrival days.',
                    'Do I need warm clothes? | In winter, yes, for after sunset.',
                ],
            ],
            [
                'slug' => 'jawai-outfit-ideas', 'title' => 'Jawai safari outfit ideas for women and men', 'img' => 'safari-guests',
                'intro' => 'What to wear on a Jawai safari for comfort, photos and respect: colours, fabrics, layers and shoes.',
                'mt' => 'Jawai Safari Outfit Ideas for Women & Men: What to Wear',
                'md' => 'Jawai safari outfit ideas: earth tones, layers, comfortable trousers and closed shoes. Practical tips for women and men in every season.',
                'body' => <<<'HTML'
<p>Dress for comfort first, and for the photographs second. A safari is a dusty, open-air morning or evening.</p>
<h2>Colours</h2>
<p>Choose earth tones: khaki, olive, beige, brown, grey. Avoid bright colours, white and strong perfume that can disturb animals.</p>
<h2>What to wear</h2>
<table>
<thead><tr><th>Item</th><th>Suggestion</th></tr></thead>
<tbody>
<tr><td>Top</td><td>Cotton or quick-dry shirt or tee; long sleeves for sun and dust</td></tr>
<tr><td>Bottom</td><td>Comfortable trousers or loose cotton pants</td></tr>
<tr><td>Layers</td><td>Fleece or light jacket in winter</td></tr>
<tr><td>Feet</td><td>Closed shoes or trainers</td></tr>
<tr><td>Extras</td><td>Cap, scarf, sunglasses</td></tr>
</tbody>
</table>
<h2>Temple and village visits</h2>
<p>Carry a shawl or scarf to cover your shoulders, and wear trousers or a long skirt that covers the knees.</p>
<p>Full packing list in <a href="/what-to-wear-on-jawai-safari/">what to wear and pack for a Jawai safari</a>. Season guides: <a href="/jawai-in-winter/">winter</a>, <a href="/jawai-in-summer/">summer</a>.</p>
HTML,
                'faq' => [
                    'What colours should I wear? | Earth tones such as khaki, olive and brown. Avoid bright colours and white.',
                    'Can I wear a dress or skirt? | Trousers are more practical in an open jeep. Carry a scarf for temples.',
                    'Do I need warm clothes? | Yes in winter, especially for morning safaris.',
                ],
            ],
            [
                'slug' => 'private-leopard-photo-safari-rajasthan', 'title' => 'Private leopard photo safari in Rajasthan', 'img' => 'leopard2',
                'intro' => 'A private photography safari in Jawai gives you your own jeep, flexible timing and a guide who understands the light.',
                'mt' => 'Private Leopard Photo Safari in Rajasthan (Jawai) | Photography Tours',
                'md' => 'Private leopard photo safari in Rajasthan: your own jeep in Jawai, flexible stops, expert tracker and golden-hour light. How it works and what to bring.',
                'body' => <<<'HTML'
<p>For photographers, a private jeep makes a big difference. You decide how long to stay at a sighting and where to position for the light.</p>
<h2>What you get</h2>
<ul>
<li>A jeep for your own group, up to six guests.</li>
<li>A driver and a local tracker.</li>
<li>Time to wait and compose, without a crowd.</li>
<li>Planning around the golden hours at 6:00 to 9:00 AM and 4:00 to 7:00 PM.</li>
</ul>
<h2>Gear</h2>
<ul>
<li>A telephoto lens of 200 mm or more.</li>
<li>A bean bag or monopod for stability.</li>
<li>Spare batteries and cards.</li>
<li>A dust cover.</li>
</ul>
<h2>Respect the animals</h2>
<p>Stay in the vehicle, keep a distance and never use flash or drones. See <a href="/jawai-safari-etiquette/">safari etiquette</a>.</p>
<p>Learn more about our <a href="/safaris/photography-safari/">photography safari</a>, <a href="/jawai-photography-tips/">photography tips</a> and <a href="/jawai-photography-spots/">photography spots</a>. Safaris start from {price} per jeep; longer photography drives cost more.</p>
HTML,
                'faq' => [
                    'Is a private jeep worth it for photography? | Yes. You control timing and positioning without sharing the vehicle.',
                    'What lens should I bring? | A telephoto of 200 mm or more.',
                    'Is flash allowed? | No. Flash disturbs animals.',
                ],
            ],
            [
                'slug' => 'jawai-in-october', 'title' => 'Jawai in October', 'img' => 'lake-reflection',
                'intro' => 'October is the start of the Jawai season: post-monsoon green hills, warm days and the first cool mornings.',
                'mt' => 'Jawai in October: Weather, Wildlife & Safari Tips',
                'md' => 'Jawai in October: the monsoon green fades, mornings turn cool and the season begins. Weather, wildlife and tips for an October safari.',
                'body' => <<<'HTML'
<p>October marks the beginning of the Jawai season. The monsoon has just ended, the hills and the dam are still fresh, and the heat has eased.</p>
<h2>What to expect</h2>
<ul>
<li>Warm days and increasingly pleasant mornings.</li>
<li>A fuller dam and greener scrub than in the dry months.</li>
<li>Early winter birds beginning to arrive.</li>
<li>Fewer crowds than December and January.</li>
</ul>
<h2>The safari</h2>
<p>Morning (6:00 to 9:00 AM) and evening (4:00 to 7:00 PM) safaris run as usual. Midday can still be warm, so rest then. Leopard sightings are never guaranteed, but this is a good month to try.</p>
<h2>Pack</h2>
<p>Light cotton layers, a hat, sunscreen and a light jacket for early mornings.</p>
<p>See also <a href="/jawai-in-november/">Jawai in November</a>, <a href="/jawai-in-winter/">Jawai in winter</a> and <a href="/jawai-in-monsoon/">Jawai in the monsoon</a>.</p>
HTML,
                'faq' => [
                    'Is October a good time for Jawai? | Yes, it is the start of the season, with pleasant weather and fewer crowds.',
                    'Is it hot in October? | Days can still be warm, but mornings and evenings are pleasant.',
                    'Do I need to book early? | Weekends and holidays fill up. Book ahead.',
                ],
            ],
            [
                'slug' => 'jawai-in-november', 'title' => 'Jawai in November', 'img' => 'sunset-valley',
                'intro' => 'November is a lovely month in Jawai: clear skies, comfortable days and the arrival of winter birds.',
                'mt' => 'Jawai in November: Weather, Birds & Leopard Safari Guide',
                'md' => 'Jawai in November: comfortable days, cool mornings and winter birds arriving at the dam. Weather, wildlife and safari planning tips.',
                'body' => <<<'HTML'
<p>November is often the sweet spot: comfortable weather, clear light and a good chance of wildlife.</p>
<h2>What to expect</h2>
<ul>
<li>Pleasant days and cool mornings and evenings.</li>
<li>Clear skies and good photography light.</li>
<li>Migratory birds, including flamingos, settling at the dam.</li>
<li>Diwali and holiday periods can be busy.</li>
</ul>
<h2>The safari</h2>
<p>Both safari windows work well: 6:00 to 9:00 AM and 4:00 to 7:00 PM. Doing both increases your chances, though a sighting is never guaranteed.</p>
<h2>Pack</h2>
<p>A light jacket for mornings, a hat, binoculars and a camera.</p>
<p>Related: <a href="/jawai-in-october/">Jawai in October</a>, <a href="/jawai-in-december/">Jawai in December</a>, <a href="/flamingos-at-jawai/">flamingos at Jawai</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Is November busy? | Holiday periods are, so book early.',
                    'Are there flamingos in November? | Winter birds begin arriving, depending on the year.',
                    'What should I pack? | A light jacket, hat, binoculars and a camera.',
                ],
            ],
            [
                'slug' => 'jawai-in-december', 'title' => 'Jawai in December and New Year', 'img' => 'granite-boulders',
                'intro' => 'December in Jawai: cold mornings, clear days and a busy holiday season. How to plan a safari over Christmas and New Year.',
                'mt' => 'Jawai in December & New Year: Weather, Crowds & Safari Planning',
                'md' => 'Jawai in December and New Year: cold mornings, sunny days and busy dates. Weather, wildlife and how to book a leopard safari over the holidays.',
                'body' => <<<'HTML'
<p>December is peak season. The days are sunny and dry, the mornings are cold, and the area is at its busiest.</p>
<h2>What to expect</h2>
<ul>
<li>Cold mornings in an open jeep. Dress warmly.</li>
<li>Clear, sunny afternoons.</li>
<li>Winter birds at the dam.</li>
<li>High demand over Christmas and New Year.</li>
</ul>
<h2>Planning</h2>
<p>Book weeks ahead for the holiday period. Plan two safaris, one morning (6:00 to 9:00 AM) and one evening (4:00 to 7:00 PM). Sightings can never be promised.</p>
<h2>Pack</h2>
<p>A warm jacket, gloves and a hat for the morning, a scarf for dust, and sunglasses for midday.</p>
<p>See <a href="/jawai-in-november/">Jawai in November</a>, <a href="/jawai-in-january-february/">Jawai in January and February</a>, <a href="/jawai-in-winter/">Jawai in winter</a> and <a href="/how-to-book-jawai-safari/">how to book</a>.</p>
HTML,
                'faq' => [
                    'Is December crowded in Jawai? | It is the busiest period, especially around Christmas and New Year.',
                    'How cold are the mornings? | Cold in an open jeep. Bring a warm jacket, scarf and hat.',
                    'How early should I book? | A few weeks ahead for holiday dates.',
                ],
            ],
            [
                'slug' => 'jawai-in-january-february', 'title' => 'Jawai in January and February', 'img' => 'flamingo-flock',
                'intro' => 'January and February bring Jawai\'s coldest mornings, crisp air and some of the best wildlife viewing of the year.',
                'mt' => 'Jawai in January & February: Weather, Wildlife & Safari Tips',
                'md' => 'Jawai in January and February: cold mornings, clear skies and strong wildlife viewing. Weather, birds, and safari planning tips.',
                'body' => <<<'HTML'
<p>The heart of winter is a favourite for many returning guests. Crowds thin after the holidays, and the weather is crisp and dry.</p>
<h2>What to expect</h2>
<ul>
<li>The coldest mornings of the year; dress in layers.</li>
<li>Pleasant, sunny afternoons.</li>
<li>Winter birds at the dam, including flamingos in suitable years.</li>
<li>Good visibility and dry tracks.</li>
</ul>
<h2>The safari</h2>
<p>Morning (6:00 to 9:00 AM) and evening (4:00 to 7:00 PM) safaris both work well. A scarf and jacket make the early start comfortable. As always, leopard sightings cannot be guaranteed.</p>
<h2>Pack</h2>
<p>Jacket, scarf, hat, gloves for early mornings, sunscreen for the day.</p>
<p>Read next: <a href="/jawai-in-december/">Jawai in December</a>, <a href="/jawai-in-winter/">Jawai in winter</a>, <a href="/jawai-in-summer/">Jawai in summer</a> and <a href="/jawai-safari-timings/">safari timings</a>.</p>
HTML,
                'faq' => [
                    'Is February a good month? | Yes. The weather is dry and pleasant, and crowds are lighter than December.',
                    'How cold does it get? | Mornings can feel cold in an open vehicle, so layer up.',
                    'Are birds around in February? | Winter birds are usually still present.',
                ],
            ],
            [
                'slug' => 'jawai-safari-for-couples', 'title' => 'Jawai for couples: a quiet safari getaway', 'img' => 'safari-jeep',
                'intro' => 'Why a private Jawai safari makes a romantic, low-key trip for two, and how to plan it.',
                'mt' => 'Jawai for Couples: Private Safari, Sunsets & Quiet Getaway',
                'md' => 'Jawai for couples: a private jeep, sunsets over granite hills and quiet mornings. How to plan a romantic leopard safari getaway.',
                'body' => <<<'HTML'
<p>Jawai suits couples who prefer wild landscapes and quiet to crowds and noise.</p>
<h2>Why it works</h2>
<ul>
<li>A private jeep means your own space.</li>
<li>Golden sunsets over the granite hills.</li>
<li>Calm lake mornings with birds.</li>
<li>A slow pace, with time for each other.</li>
</ul>
<h2>A simple plan</h2>
<ol>
<li>Arrive, rest and take the 4:00 PM evening safari.</li>
<li>Morning safari at 6:00 AM, then a slow breakfast.</li>
<li>Village visit or temple stop, then a final sunset at the dam.</li>
</ol>
<h2>Good to know</h2>
<p>Winter is the most comfortable season. Because wildlife is wild, sightings cannot be promised, but the landscape is rewarding in itself. Safaris start from {price} per jeep, which suits two as well as a group of six.</p>
<p>See <a href="/jawai-itinerary/">a 2-day itinerary</a>, <a href="/jawai-sunset-points/">sunset spots</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Is a private jeep available for two? | Yes. The jeep is private to your group, whether two or up to six.',
                    'What is the best season for couples? | October to March.',
                    'How many days do we need? | Two nights is ideal.',
                ],
            ],
            [
                'slug' => 'jawai-safari-etiquette', 'title' => 'Jawai safari etiquette: dos and don\'ts', 'img' => 'ridge-sighting',
                'intro' => 'Simple rules that keep you safe, protect the leopards and make the safari better for everyone.',
                'mt' => 'Jawai Safari Etiquette: Dos and Don\'ts for Leopard Safaris',
                'md' => 'Jawai safari etiquette: stay in the jeep, keep quiet, no flash or drones, respect distance. Dos and don\'ts for a safe, ethical leopard safari.',
                'body' => <<<'HTML'
<p>Leopards at Jawai are wild animals living near people. Good behaviour keeps them relaxed, and keeps you safe.</p>
<h2>Do</h2>
<ul>
<li>Stay seated in the vehicle.</li>
<li>Keep voices low and phones on silent.</li>
<li>Follow your guide's instructions.</li>
<li>Dress in muted colours.</li>
<li>Take your litter home.</li>
</ul>
<h2>Don't</h2>
<ul>
<li>Leave the vehicle for any reason except as your guide says.</li>
<li>Use flash, drones or loud music.</li>
<li>Feed, call or tease any animal.</li>
<li>Block an animal's path.</li>
<li>Photograph villagers without asking.</li>
</ul>
<h2>Why it matters</h2>
<p>Quiet behaviour gives you longer sightings, and helps keep the community's goodwill, which the leopards depend on. See <a href="/jawai-conservation-and-community/">Jawai conservation and the community</a>.</p>
<p>Also read <a href="/jawai-leopard-safari-what-to-expect/">what to expect on a safari</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Can I get out of the jeep? | Only if your guide says it is safe. Otherwise stay seated.',
                    'Is flash photography allowed? | No.',
                    'Can I use a drone? | No. Drones disturb wildlife and are not welcome.',
                ],
            ],
            [
                'slug' => 'jawai-safari-budget', 'title' => 'Jawai trip budget: what a safari trip costs', 'img' => 'safari1',
                'intro' => 'A realistic budget for a Jawai trip: the safari from the jeep price, plus travel, stay, food and extras.',
                'mt' => 'Jawai Trip Budget: Safari Cost, Travel, Stay & Extras',
                'md' => 'Jawai trip budget: leopard safaris from {price} per jeep, plus travel from Udaipur or Jodhpur, stay, food and extras. How to plan costs.',
                'body' => <<<'HTML'
<p>Jawai can fit many budgets. The safari itself is priced per jeep, so group size changes the cost per person.</p>
<h2>The main costs</h2>
<table>
<thead><tr><th>Item</th><th>Notes</th></tr></thead>
<tbody>
<tr><td>Safari</td><td>From {price} per jeep, up to six guests; a {price} jeep works out at about {price_each} each when full</td></tr>
<tr><td>Getting there</td><td>Taxi from Udaipur (about 115 km) or Jodhpur (about 170 km), or train to Falna or Sumerpur Jawai Bandh</td></tr>
<tr><td>Stay</td><td>Varies widely, from simple guesthouses to camps</td></tr>
<tr><td>Food</td><td>Often included or inexpensive in local places</td></tr>
<tr><td>Extras</td><td>Village visits, photography drives, day trips</td></tr>
</tbody>
</table>
<h2>Saving money</h2>
<ul>
<li>Travel in a group of up to six and share the jeep.</li>
<li>Visit outside the peak holiday weeks.</li>
<li>Take the train and arrange a car for the last stretch.</li>
</ul>
<p>Details on safari pricing in <a href="/jawai-safari-price/">Jawai safari price</a>. Planning help in <a href="/how-to-book-jawai-safari/">how to book</a> and <a href="/jawai-itinerary/">the 2-day itinerary</a>.</p>
HTML,
                'faq' => [
                    'How much is a Jawai safari? | From {price} per jeep for up to six guests.',
                    'Is the price per person or per jeep? | Per jeep, so a full jeep costs less per person.',
                    'Are meals included? | Check what your stay includes. Safari prices cover the jeep, driver and tracker.',
                ],
            ],
            [
                'slug' => 'jawai-trip-checklist', 'title' => 'Jawai trip checklist: what to book and pack', 'img' => 'leopard-basking',
                'intro' => 'A short checklist for a Jawai leopard safari trip: what to book, what to carry and what to confirm before you leave.',
                'mt' => 'Jawai Trip Checklist: Booking, Packing & Before You Go',
                'md' => 'Jawai trip checklist: book the safari and transfer, pack layers and binoculars, carry cash and ID. A simple list for a smooth leopard safari trip.',
                'body' => <<<'HTML'
<p>A little planning makes the trip smoother, because Jawai is rural and services are limited.</p>
<h2>Book first</h2>
<ul>
<li>Safari dates and timings: morning 6:00 to 9:00 AM, evening 4:00 to 7:00 PM.</li>
<li>A car for the last stretch from the airport or station.</li>
<li>A place to stay near Bera.</li>
</ul>
<h2>Pack</h2>
<ul>
<li>Layers for cold mornings and warm days.</li>
<li>Earth-tone clothes, a hat, sunglasses and sunscreen.</li>
<li>Binoculars and a camera with spare batteries.</li>
<li>A scarf or buff against dust, and a shawl for temples.</li>
<li>A water bottle and any personal medicines.</li>
</ul>
<h2>Carry</h2>
<ul>
<li>Photo ID for hotels and travel.</li>
<li>Some cash: mobile signal and card machines can be unreliable in villages.</li>
<li>An offline map for the area.</li>
</ul>
<p>Detailed lists in <a href="/what-to-wear-on-jawai-safari/">what to wear and pack</a>, <a href="/how-to-book-jawai-safari/">how to book</a> and <a href="/jawai-safari-etiquette/">safari etiquette</a>.</p>
HTML,
                'faq' => [
                    'What should I book first? | Your safari dates, then transport and a stay.',
                    'Should I carry cash? | Yes, as signal and card machines can be unreliable.',
                    'Do I need an offline map? | It helps, because mobile signal can be patchy in the hills.',
                ],
            ],
        ];
    }
}
