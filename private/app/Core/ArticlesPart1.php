<?php
declare(strict_types=1);

namespace App\Core;

/** Articles 1–10: where Jawai is and how far it is. Part of {@see Articles}. Road distances are from Bera. */
final class ArticlesPart1
{
    public static function rows(): array
    {
        return [
            [
                'slug' => 'nearest-city-to-jawai', 'title' => 'Nearest city to Jawai', 'img' => 'landscape1',
                'intro' => 'The nearest big city to Jawai is Udaipur, about 115 km away. The nearest towns are Sumerpur and Falna. Here is how they compare.',
                'mt' => 'Nearest City to Jawai, Rajasthan: Udaipur, Jodhpur & Towns',
                'md' => 'The nearest city to Jawai is Udaipur (about 115 km). Jodhpur is about 170 km. Sumerpur and Falna are the nearest towns and railway stops.',
                'body' => <<<'HTML'
<p>Jawai is a rural area of granite hills, villages and a dam in Pali district, Rajasthan. There is no large city beside it, so people choose a gateway city and drive in.</p>
<h2>Cities and towns near Jawai</h2>
<table>
<thead><tr><th>Place</th><th>Type</th><th>Road distance to Bera</th></tr></thead>
<tbody>
<tr><td>Sumerpur Jawai Bandh (JWB) station</td><td>Railway stop</td><td>about 18 km</td></tr>
<tr><td>Falna (FA)</td><td>Town and railway junction</td><td>about 37 km</td></tr>
<tr><td>Udaipur</td><td>City with an airport</td><td>about 115 km</td></tr>
<tr><td>Jodhpur</td><td>City with an airport</td><td>about 170 km</td></tr>
</tbody>
</table>
<h2>Which one should you use?</h2>
<p><strong>Udaipur</strong> is the best gateway: it is the closest city, has an airport and plenty of hotels, and the drive takes around two and a half hours. <strong>Jodhpur</strong> suits you if you are touring Rajasthan from the west. The two railway stops are useful if you arrive by train and arrange a car for the last stretch.</p>
<p>Read the full guides: <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>, <a href="/jodhpur-to-jawai/">Jodhpur to Jawai</a> and <a href="/jawai/how-to-reach/">how to reach Jawai</a>.</p>
HTML,
                'faq' => [
                    'What is the nearest city to Jawai? | Udaipur, about 115 km from Bera by road.',
                    'What is the nearest railway station to Jawai? | Sumerpur Jawai Bandh (JWB), about 18 km from Bera. Falna (FA) is about 37 km away.',
                    'Is there an airport at Jawai? | No. The nearest airports are Udaipur and Jodhpur.',
                ],
            ],
            [
                'slug' => 'jawai-distance-from-major-cities', 'title' => 'Jawai distance from major cities', 'img' => 'granite-dome-dusk',
                'intro' => 'One table of road distances from Jawai to Udaipur, Jodhpur, Ahmedabad, Jaipur, Delhi and Mumbai, with realistic driving times.',
                'mt' => 'Jawai Distance from Delhi, Mumbai, Jaipur, Udaipur & More',
                'md' => 'Jawai distance by road: Udaipur 115 km, Jodhpur 170 km, Ahmedabad 283 km, Jaipur 387 km, Delhi 681 km, Mumbai 794 km. Times and routes explained.',
                'body' => <<<'HTML'
<p>Many people search for the distance to Jawai from their own city. Here are the main ones in one place. All distances are road distances to Bera, the village at the centre of the Jawai safari area.</p>
<h2>Road distance to Jawai (Bera)</h2>
<table>
<thead><tr><th>From</th><th>Distance</th><th>Realistic time by car</th></tr></thead>
<tbody>
<tr><td><a href="/udaipur-to-jawai/">Udaipur</a></td><td>about 115 km</td><td>2.5 hours</td></tr>
<tr><td><a href="/jodhpur-to-jawai/">Jodhpur</a></td><td>about 170 km</td><td>3 to 3.5 hours</td></tr>
<tr><td><a href="/ahmedabad-to-jawai/">Ahmedabad</a></td><td>about 283 km</td><td>5 to 6 hours</td></tr>
<tr><td><a href="/jaipur-to-jawai/">Jaipur</a></td><td>about 387 km</td><td>7 to 8 hours</td></tr>
<tr><td><a href="/delhi-to-jawai/">Delhi</a></td><td>about 681 km</td><td>12 hours or more (most people fly or take a train)</td></tr>
<tr><td><a href="/mumbai-to-jawai/">Mumbai</a></td><td>about 794 km</td><td>14 hours or more (fly to Udaipur instead)</td></tr>
</tbody>
</table>
<h2>Day trips from Jawai</h2>
<p>Ranakpur is about 48 km, Kumbhalgarh about 81 km and Mount Abu about 99 km from Bera.</p>
<h2>Tips for long distances</h2>
<p>For Delhi, Mumbai and Jaipur it is usually faster to fly to Udaipur or Jodhpur, or take an overnight train, and drive the last part. See <a href="/flights-to-jawai/">flights to Jawai</a> and <a href="/jawai-by-train/">Jawai by train</a>. Times are for daytime driving; allow more for festivals, roadworks and night driving.</p>
HTML,
                'faq' => [
                    'How far is Jawai from Delhi? | About 681 km by road, so most travellers fly to Udaipur or Jodhpur or take a train towards Falna.',
                    'How far is Jawai from Mumbai? | About 794 km by road. A flight to Udaipur followed by a drive of about 2.5 hours is easier.',
                    'How far is Jawai from Ahmedabad? | About 283 km, roughly five to six hours by car.',
                ],
            ],
            [
                'slug' => 'jawai-map-and-location', 'title' => 'Jawai map and location', 'img' => 'bandh-shoreline',
                'intro' => 'Where Jawai sits on the map of Rajasthan, which villages and hills surround it, and how to find the safari area from your phone.',
                'mt' => 'Jawai Map & Location in Rajasthan | Jawai Bandh Leopard Area',
                'md' => 'Jawai map and location: Jawai sits in Pali district in southern Rajasthan, near Bera and Sumerpur, between Udaipur and Jodhpur. How to find it.',
                'body' => <<<'HTML'
<p>Jawai is in the south of Rajasthan, in Pali district, in the Aravalli country between Udaipur and Jodhpur. The landscape is built around the <a href="/jawai/jawai-dam/">Jawai Dam</a> and the granite hills that rise beside it.</p>
<h2>How to place it on a map</h2>
<ul>
<li>The safari village is <strong>Bera</strong>, close to the Jawai Bandh (dam).</li>
<li>The nearest town on the railway is <strong>Sumerpur</strong>, with Sumerpur Jawai Bandh (JWB) station about 18 km from Bera.</li>
<li><strong>Falna</strong>, a larger junction, is about 37 km away.</li>
<li>Udaipur lies to the south, about 115 km, and Jodhpur to the north, about 170 km.</li>
</ul>
<h2>Searching on your phone</h2>
<p>Search for "Bera, Rajasthan" or "Jawai Bandh" in your maps app. Search for the village name rather than only "Jawai", because the word is also used for the dam, the region and the railway station. Mobile signal can be patchy in the hills, so download the offline map for the area before you arrive.</p>
<h2>What is around</h2>
<p>The area has granite hills, small temples, Rabari herding villages and the dam lake. Ranakpur (about 48 km), Kumbhalgarh (about 81 km) and Mount Abu (about 99 km) can all be visited as day trips. See <a href="/where-is-jawai/">where is Jawai</a> for a plain-language overview.</p>
HTML,
                'faq' => [
                    'Which district is Jawai in? | Pali district, in southern Rajasthan.',
                    'What should I search for in a maps app? | "Bera, Rajasthan" or "Jawai Bandh". Searching only "Jawai" can show other places.',
                    'Is Jawai near Mount Abu? | Mount Abu is about 99 km from Bera by road, which makes it a possible day trip.',
                ],
            ],
            [
                'slug' => 'jawai-district-rajasthan', 'title' => 'Which district is Jawai in?', 'img' => 'sunset-valley',
                'intro' => 'Jawai is in Pali district, Rajasthan. What that means for travel, trains and the area around Sumerpur and Bera.',
                'mt' => 'Jawai District in Rajasthan: Pali, Sumerpur & Bera Explained',
                'md' => 'Jawai is in Pali district, Rajasthan, near Sumerpur and Bera. Learn how the district, the dam and the safari area fit together.',
                'body' => <<<'HTML'
<p>The name "Jawai" is used in several ways, which can be confusing. It helps to separate them.</p>
<h2>The pieces</h2>
<ul>
<li><strong>Pali district</strong> is the administrative district in which Jawai lies.</li>
<li><strong>Jawai Dam</strong> (also called Jawai Bandh) is the large reservoir, built across the Jawai river between 1946 and 1957.</li>
<li><strong>Jawai Bandh Leopard Conservation Reserve</strong> is the protected area of roughly 19.8 square kilometres around the hills.</li>
<li><strong>Bera</strong> is the village that has become the base for leopard safaris.</li>
<li><strong>Sumerpur</strong> is the nearby town; its railway stop is called Sumerpur Jawai Bandh.</li>
</ul>
<h2>Why it matters when you travel</h2>
<p>If a booking site or ticket says "Jawai", check which one it means. A railway ticket to <strong>JWB</strong> gets you to the station, not to the safari village. A taxi to <strong>Bera</strong> is what you need for the hills. Our <a href="/jawai-bandh-station-to-bera/">station to Bera guide</a> explains the last stretch.</p>
<p>For more background, read <a href="/is-jawai-a-national-park/">is Jawai a national park?</a> and the <a href="/jawai/">Jawai guide</a>.</p>
HTML,
                'faq' => [
                    'Is Jawai in Pali or Sirohi district? | The Jawai Dam and Bera area are in Pali district.',
                    'What is the Jawai river? | The river dammed to form Jawai Dam, also called Jawai Bandh.',
                    'Is Bera the same as Jawai? | Bera is the village used as the safari base. Jawai is the wider area and the dam.',
                ],
            ],
            [
                'slug' => 'jawai-by-train', 'title' => 'Jawai by train: stations and how to finish the journey', 'img' => 'safari1',
                'intro' => 'Which railway stations serve Jawai, how far each is from Bera, and how to plan the last stretch after you step off the train.',
                'mt' => 'Jawai by Train: Nearest Stations (JWB, Falna) & Onward Travel',
                'md' => 'Reach Jawai by train: Sumerpur Jawai Bandh (JWB) is 18 km from Bera and Falna (FA) is 37 km. Tips on which to choose and how to get a car.',
                'body' => <<<'HTML'
<p>There is no station in the safari village itself, but two railway stops are within a short drive.</p>
<h2>Your two station options</h2>
<table>
<thead><tr><th>Station</th><th>Code</th><th>Distance to Bera</th><th>Notes</th></tr></thead>
<tbody>
<tr><td>Sumerpur Jawai Bandh</td><td>JWB</td><td>about 18 km</td><td>Closest, fewer trains stop here</td></tr>
<tr><td>Falna</td><td>FA</td><td>about 37 km</td><td>Junction with more services</td></tr>
</tbody>
</table>
<h2>How to plan</h2>
<ol>
<li>Search the Indian Railways site or app for both stations and choose whichever has a convenient arrival time.</li>
<li>Book a car in advance. Taxis at small stations are limited, especially after dark.</li>
<li>Aim to arrive in daylight so you can make the evening safari from 4:00 PM or settle in for the morning safari at 6:00 AM.</li>
</ol>
<p>Trains on the main line between Rajasthan and Gujarat generally pass this area, so coming from Ahmedabad is often straightforward, and Jodhpur is also connected. Always check current timetables; stops change. If you are coming from far away, see <a href="/flights-to-jawai/">flights to Jawai</a>.</p>
<p>Related: <a href="/ahmedabad-to-jawai-by-train/">Ahmedabad to Jawai by train</a> and <a href="/jawai/how-to-reach/">how to reach Jawai</a>.</p>
HTML,
                'faq' => [
                    'Which station is best for Jawai? | Sumerpur Jawai Bandh (JWB) is closest, about 18 km from Bera. Falna (FA) is about 37 km but has more trains.',
                    'Can I get a taxi from the station? | Sometimes, but arrange one in advance. Small stations have few cars, especially at night.',
                    'Is there a direct train to Jawai from Delhi? | Check the Indian Railways timetable for trains that stop at Falna or Sumerpur. Many travellers fly to Udaipur or Jodhpur instead.',
                ],
            ],
            [
                'slug' => 'flights-to-jawai', 'title' => 'Flights to Jawai: which airport to use', 'img' => 'lake-reflection',
                'intro' => 'There is no airport at Jawai. Udaipur, Jodhpur and Ahmedabad are the airports travellers use, and each leads to a different drive.',
                'mt' => 'Flights to Jawai: Nearest Airport (Udaipur, Jodhpur) & Drive Times',
                'md' => 'Nearest airports to Jawai: Udaipur (about 130 km), Jodhpur (about 175 km). Compare the options, drive times and how to connect to a safari.',
                'body' => <<<'HTML'
<p>Jawai has no airport of its own. You fly to a nearby city and finish the journey by road.</p>
<h2>Airports compared</h2>
<table>
<thead><tr><th>Airport</th><th>Road distance to Bera</th><th>Drive time</th></tr></thead>
<tbody>
<tr><td>Udaipur (UDR)</td><td>about 130 km</td><td>2.5 to 3 hours</td></tr>
<tr><td>Jodhpur (JDH)</td><td>about 175 km</td><td>about 3.5 hours</td></tr>
<tr><td>Ahmedabad (AMD)</td><td>about 283 km</td><td>5 to 6 hours</td></tr>
</tbody>
</table>
<h2>Which to choose</h2>
<p><strong>Udaipur</strong> is the best choice for most travellers. It is the closest, it is well connected with the large Indian cities, and the drive is the shortest. <strong>Jodhpur</strong> works if you are combining Jawai with the Thar desert and its forts. <strong>Ahmedabad</strong> has the most flights, which can help if you are arriving from abroad, but the drive is long.</p>
<h2>Planning around your flight</h2>
<p>If you land in Udaipur before midday you can reach Jawai for the 4:00 PM evening safari. A later flight is better treated as an arrival day, with the 6:00 AM morning safari next day. Flight schedules change by season, so check the airlines directly. See also <a href="/delhi-to-jawai-flight/">Delhi to Jawai by flight</a> and <a href="/mumbai-to-jawai-flight/">Mumbai to Jawai by flight</a>.</p>
HTML,
                'faq' => [
                    'What is the nearest airport to Jawai? | Udaipur (UDR), about 130 km from Bera by road.',
                    'Does Jawai have an airport? | No. You fly to Udaipur or Jodhpur and drive.',
                    'Can I arrange a pick-up from the airport? | Ask us when you book, and we can help you arrange a car.',
                ],
            ],
            [
                'slug' => 'delhi-to-jawai-flight', 'title' => 'Delhi to Jawai by flight and road', 'img' => 'granite-boulders',
                'intro' => 'The practical way from Delhi to Jawai: a flight to Udaipur or Jodhpur, then a road transfer. Timing and tips.',
                'mt' => 'Delhi to Jawai Flight Options: Fly to Udaipur or Jodhpur',
                'md' => 'Delhi to Jawai: there is no direct flight to Jawai, but you can fly Delhi to Udaipur or Jodhpur and drive 115 to 170 km. How to plan it.',
                'body' => <<<'HTML'
<p>Delhi is about 681 km from Jawai by road, a very long day. Most travellers split the trip into a flight and a drive.</p>
<h2>The usual plan</h2>
<ol>
<li><strong>Fly Delhi to Udaipur</strong> (the closest airport, about 130 km from Bera), or Delhi to Jodhpur (about 175 km).</li>
<li><strong>Take a car</strong> from the airport. Allow 2.5 to 3 hours from Udaipur or about 3.5 hours from Jodhpur.</li>
<li><strong>Join a safari</strong> that evening or the next morning.</li>
</ol>
<h2>Other ways</h2>
<p>An overnight train towards Falna is an option if you like rail travel; see <a href="/jawai-by-train/">Jawai by train</a>. Driving all the way is possible but tiring and not recommended in one go.</p>
<h2>Choosing flight times</h2>
<p>Pick a morning or midday flight if you want the evening safari on arrival day (4:00 to 7:00 PM). For the return, an early morning safari (6:00 to 9:00 AM) leaves time for breakfast and a late-morning drive. Flights and routes change, so check airline schedules before you book.</p>
<p>More detail in <a href="/delhi-to-jawai/">how to reach Jawai from Delhi</a> and <a href="/flights-to-jawai/">flights to Jawai</a>.</p>
HTML,
                'faq' => [
                    'Is there a direct flight from Delhi to Jawai? | No, there is no airport at Jawai. Fly to Udaipur or Jodhpur and drive.',
                    'Which is easier from Delhi, Udaipur or Jodhpur? | Udaipur is closer to Jawai (about 130 km versus 175 km), so it usually saves an hour.',
                    'How long is the whole journey? | Roughly five to seven hours door to door including the flight and the drive, depending on schedules.',
                ],
            ],
            [
                'slug' => 'mumbai-to-jawai-flight', 'title' => 'Mumbai to Jawai by flight and road', 'img' => 'ridge-sighting',
                'intro' => 'Mumbai is far from Jawai by road. Fly to Udaipur and drive, or take a train. Here is how to plan each leg.',
                'mt' => 'Mumbai to Jawai Flight: Fly to Udaipur, Then Drive to Jawai',
                'md' => 'Mumbai to Jawai: no direct flight to Jawai, but you can fly to Udaipur or Ahmedabad and drive. Distance, timings and planning tips.',
                'body' => <<<'HTML'
<p>Mumbai is about 794 km from Jawai by road, so driving the whole way is not sensible. Combine a flight with a road transfer.</p>
<h2>Your options</h2>
<table>
<thead><tr><th>Route</th><th>Then</th></tr></thead>
<tbody>
<tr><td>Fly Mumbai to Udaipur</td><td>Drive about 130 km (2.5 to 3 hours) to Bera</td></tr>
<tr><td>Fly Mumbai to Jodhpur</td><td>Drive about 175 km (about 3.5 hours)</td></tr>
<tr><td>Train via Ahmedabad or towards Falna</td><td>Car from Falna (37 km) or Sumerpur Jawai Bandh (18 km)</td></tr>
</tbody>
</table>
<h2>Our suggestion</h2>
<p>Fly to Udaipur. Check current flight schedules, as routes and timings change. Land by midday and you can be at the 4:00 PM evening safari; otherwise treat it as an arrival evening and take the 6:00 AM safari the next morning.</p>
<p>See the overview in <a href="/mumbai-to-jawai/">how to reach Jawai from Mumbai</a>, plus <a href="/flights-to-jawai/">flights to Jawai</a>.</p>
HTML,
                'faq' => [
                    'Is there a direct flight to Jawai from Mumbai? | No, Jawai has no airport. Fly to Udaipur or Jodhpur and take a car.',
                    'How far is Udaipur airport from Jawai? | About 130 km by road, around 2.5 to 3 hours.',
                    'Can I go by train from Mumbai? | Yes, via routes towards Ahmedabad and Rajasthan that stop near Falna or Sumerpur. Check the timetable for the current stops.',
                ],
            ],
            [
                'slug' => 'ahmedabad-to-jawai-by-train', 'title' => 'Ahmedabad to Jawai by train', 'img' => 'flamingo-flock',
                'intro' => 'How to go from Ahmedabad to Jawai by rail: which stations to book, how far they are from Bera and how to finish the journey.',
                'mt' => 'Ahmedabad to Jawai by Train: Stations, Timing & Last Stretch',
                'md' => 'Ahmedabad to Jawai by train: book to Sumerpur Jawai Bandh (JWB, 18 km) or Falna (FA, 37 km), then take a car. Planning tips included.',
                'body' => <<<'HTML'
<p>Trains on the main line between Gujarat and Rajasthan pass through this region, so the train is a reasonable choice from Ahmedabad.</p>
<h2>Which station to book</h2>
<ul>
<li><strong>Sumerpur Jawai Bandh (JWB)</strong>: about 18 km from Bera, the closest.</li>
<li><strong>Falna (FA)</strong>: about 37 km from Bera, with more train choices.</li>
</ul>
<h2>Planning the trip</h2>
<ol>
<li>Search for both stations on the Indian Railways site or app and pick a train that arrives in daylight.</li>
<li>Arrange a car for the last stretch before you travel.</li>
<li>If you reach the area in the afternoon you can join the 4:00 PM safari. A night arrival means a rest and the 6:00 AM morning safari.</li>
</ol>
<h2>Train or car?</h2>
<p>Ahmedabad is about 283 km from Bera. By car it is five to six hours; see <a href="/ahmedabad-to-jawai-by-car/">Ahmedabad to Jawai by car</a>. The train is relaxing but depends on the timetable, and stops can change, so confirm before you book.</p>
<p>Also read: <a href="/ahmedabad-to-jawai/">Ahmedabad to Jawai: distance and route</a> and <a href="/jawai-by-train/">Jawai by train</a>.</p>
HTML,
                'faq' => [
                    'Which station should I choose from Ahmedabad? | Sumerpur Jawai Bandh (JWB) is closest to Bera. Falna (FA) has more trains. Pick the one that fits your schedule.',
                    'How long does it take from Ahmedabad? | The distance is about 283 km. By car it takes five to six hours; train times depend on the service you choose.',
                    'Do I need to book a car in advance? | Yes, it is wise, as stations near Jawai are small.',
                ],
            ],
            [
                'slug' => 'ahmedabad-to-jawai-by-car', 'title' => 'Ahmedabad to Jawai by car: route and time', 'img' => 'safari-jeep',
                'intro' => 'Driving from Ahmedabad to Jawai is about 283 km, roughly five to six hours. Route outline, rest stops and timing for the safari.',
                'mt' => 'Ahmedabad to Jawai by Car: 283 km Route, Time & Tips',
                'md' => 'Ahmedabad to Jawai by car is about 283 km, around 5 to 6 hours. Route outline, when to leave and how to time your leopard safari.',
                'body' => <<<'HTML'
<p>Ahmedabad is the main gateway to Jawai from Gujarat, and the drive makes a popular weekend trip.</p>
<h2>Distance and time</h2>
<p>About <strong>283 km</strong> by road to Bera. Allow <strong>five to six hours</strong> with breaks. The last section is on smaller roads, so keep some buffer.</p>
<h2>The route in outline</h2>
<p>You head north from Ahmedabad on the highway towards Rajasthan, then turn towards Sumerpur and Bera in Pali district. Use your maps app and search for "Bera, Rajasthan" so you are directed to the safari village and not to another place called Jawai.</p>
<h2>Timing tips</h2>
<ul>
<li>Leave Ahmedabad by about 9:30 AM to reach Jawai before the 4:00 PM evening safari.</li>
<li>Avoid driving the final hills after dark if you can.</li>
<li>Fill up fuel before the last section; pumps in small villages are limited.</li>
</ul>
<h2>Hiring a driver</h2>
<p>If you do not want to drive, a taxi from Ahmedabad is possible. Agree the price and the pick-up point in advance.</p>
<p>Related: <a href="/ahmedabad-to-jawai/">Ahmedabad to Jawai</a>, <a href="/ahmedabad-to-jawai-by-train/">by train</a>, and <a href="/jawai-weekend-trip-from-ahmedabad/">a weekend trip from Ahmedabad</a>.</p>
HTML,
                'faq' => [
                    'How far is Ahmedabad from Jawai by car? | About 283 km to Bera, around five to six hours.',
                    'When should I leave for the evening safari? | Around 9:30 AM, to arrive before the 4:00 PM start with time to rest.',
                    'Is the road good? | The highways are fine; the last stretch to Bera is on smaller roads, so take care and avoid night driving.',
                ],
            ],
        ];
    }
}
