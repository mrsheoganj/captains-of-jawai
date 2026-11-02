<?php
declare(strict_types=1);

namespace App\Core;

/** Articles 11–20: more routes, day trips and a Hindi guide. Part of {@see Articles}. */
final class ArticlesPart2
{
    public static function rows(): array
    {
        return [
            [
                'slug' => 'udaipur-to-jawai-by-car', 'title' => 'Udaipur to Jawai by car: step-by-step', 'img' => 'granite-dome-dusk',
                'intro' => 'A practical guide to driving from Udaipur to Jawai: time, taxi or self-drive, where to stop and how to time the safari.',
                'mt' => 'Udaipur to Jawai by Car: Time, Taxi & Self-Drive Tips',
                'md' => 'Udaipur to Jawai by car is about 115 km, roughly 2.5 hours. Taxi or self-drive tips, stops and how to time the evening leopard safari.',
                'body' => <<<'HTML'
<p>The drive from Udaipur is the most common way to reach Jawai. It is about <strong>115 km</strong> from the city to Bera and takes around <strong>two and a half hours</strong>.</p>
<h2>Taxi or self-drive?</h2>
<ul>
<li><strong>Taxi:</strong> comfortable and stress-free. Book a one-way or a round-trip cab and agree the price first.</li>
<li><strong>Self-drive:</strong> fine for confident drivers. Highways are good; the last stretch is on smaller roads. Do not arrive in the dark.</li>
</ul>
<h2>Good places to stop</h2>
<p>Ranakpur's marble Jain temple (about 48 km from Bera) and Kumbhalgarh Fort (about 81 km) are in the hills on the Udaipur side and make good stops if you leave early.</p>
<h2>Timing</h2>
<p>Depart Udaipur by about 11:30 AM to be at Jawai for the 4:00 PM evening safari. If you leave later, rest on arrival and take the 6:00 AM safari next morning.</p>
<p>See the distance and route overview in <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>, plus <a href="/udaipur-airport-to-jawai/">Udaipur airport to Jawai</a>.</p>
HTML,
                'faq' => [
                    'How long does it take from Udaipur to Jawai? | Around two and a half hours for about 115 km.',
                    'Is it safe to self-drive? | Yes in daylight. Avoid arriving after dark as the final roads are small and unlit.',
                    'When should I leave Udaipur for the evening safari? | By about 11:30 AM.',
                ],
            ],
            [
                'slug' => 'udaipur-airport-to-jawai', 'title' => 'Udaipur airport to Jawai', 'img' => 'sunset-valley',
                'intro' => 'From Maharana Pratap Airport (UDR) to Jawai is about 130 km. How to arrange the transfer and plan your first safari.',
                'mt' => 'Udaipur Airport to Jawai: 130 km Transfer, Time & Taxi',
                'md' => 'Udaipur airport (UDR) to Jawai is about 130 km, 2.5 to 3 hours by road. How to book a transfer and fit in your first leopard safari.',
                'body' => <<<'HTML'
<p>Udaipur's Maharana Pratap Airport (UDR) is the closest airport to Jawai. The road distance to Bera is about <strong>130 km</strong>, which takes <strong>2.5 to 3 hours</strong>.</p>
<h2>Getting a car</h2>
<p>Arrange your transfer before you land. Airport taxis are available but prices vary, and a booked car avoids waiting. Ask us when you enquire and we will help you arrange one.</p>
<h2>Plan by arrival time</h2>
<table>
<thead><tr><th>You land</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>Before about 11:30 AM</td><td>Drive straight to Jawai for the 4:00 PM evening safari</td></tr>
<tr><td>Afternoon</td><td>Drive to Jawai, settle in, safari at 6:00 AM next day</td></tr>
<tr><td>Late evening</td><td>Stay in Udaipur and travel in daylight</td></tr>
</tbody>
</table>
<h2>Leaving Jawai for a flight</h2>
<p>Take the morning safari (6:00 to 9:00 AM), have breakfast and leave by about 10:30 AM. That gets you to the airport comfortably for an afternoon flight. Keep a one-hour buffer.</p>
<p>See <a href="/flights-to-jawai/">flights to Jawai</a> and <a href="/udaipur-to-jawai-by-car/">Udaipur to Jawai by car</a>.</p>
HTML,
                'faq' => [
                    'How far is Udaipur airport from Jawai? | About 130 km by road, around 2.5 to 3 hours.',
                    'Can I do a safari on the day I land? | Yes, if you land before about 11:30 AM you can make the 4:00 PM evening safari.',
                    'How early should I leave for a flight? | After a morning safari, leave Jawai by about 10:30 AM for an afternoon flight.',
                ],
            ],
            [
                'slug' => 'jodhpur-airport-to-jawai', 'title' => 'Jodhpur airport to Jawai', 'img' => 'granite-boulders',
                'intro' => 'Jodhpur airport (JDH) to Jawai is about 175 km. The route via Pali, the drive time and how to plan the safari around it.',
                'mt' => 'Jodhpur Airport to Jawai: 175 km Route, Time & Transfer',
                'md' => 'Jodhpur airport (JDH) to Jawai is about 175 km, around 3.5 hours via Pali. How to arrange a car and time your first safari.',
                'body' => <<<'HTML'
<p>Jodhpur Airport (JDH) is the second airport option for Jawai. The road distance to Bera is about <strong>175 km</strong>, which is around <strong>3.5 hours</strong> by car.</p>
<h2>The route</h2>
<p>The road runs south-east through Pali, then on smaller roads to Jawai. Search for "Bera, Rajasthan" in your maps app.</p>
<h2>When Jodhpur makes sense</h2>
<ul>
<li>You are touring western Rajasthan (Jodhpur, Jaisalmer) and want Jawai as the next stop.</li>
<li>Your flight to Jodhpur is cheaper or more convenient than to Udaipur.</li>
</ul>
<p>If you have the choice, <a href="/udaipur-airport-to-jawai/">Udaipur is about an hour closer</a>.</p>
<h2>Plan the safari</h2>
<p>Land by about 11:00 AM to make the 4:00 PM evening safari. Otherwise rest on arrival and go out at 6:00 AM. Leaving, take the morning safari, then depart after breakfast, allowing the full drive time before your flight.</p>
<p>Also see <a href="/jodhpur-to-jawai/">Jodhpur to Jawai</a> and <a href="/flights-to-jawai/">flights to Jawai</a>.</p>
HTML,
                'faq' => [
                    'How far is Jodhpur airport from Jawai? | About 175 km by road, around 3.5 hours.',
                    'Which is closer to Jawai, Udaipur or Jodhpur airport? | Udaipur, at about 130 km against 175 km.',
                    'Can I arrange a transfer? | Yes. Tell us your flight and we can help you arrange a car.',
                ],
            ],
            [
                'slug' => 'jawai-bandh-station-to-bera', 'title' => 'Jawai Bandh station to Bera', 'img' => 'safari1',
                'intro' => 'From Sumerpur Jawai Bandh station (JWB) to Bera is about 18 km. How to get a lift, what it costs in time and what to know before you arrive.',
                'mt' => 'Jawai Bandh Station to Bera: 18 km Transfer Guide',
                'md' => 'Sumerpur Jawai Bandh station (JWB) to Bera, Jawai is about 18 km. How to arrange a car, when trains arrive and how to reach your safari.',
                'body' => <<<'HTML'
<p>If you arrive by train, the nearest stop is <strong>Sumerpur Jawai Bandh (JWB)</strong>. From there to Bera, the safari village, is about <strong>18 km</strong>, a short drive.</p>
<h2>Before you travel</h2>
<ul>
<li>Arrange a car in advance. Taxis at small stations are scarce and often none at night.</li>
<li>Check that your train really stops at JWB. If it does not, the alternative is <strong>Falna (FA)</strong>, about 37 km away.</li>
<li>Tell your host or driver your arrival time so they can wait if the train is late.</li>
</ul>
<h2>Timing your safari</h2>
<p>A morning arrival can join the evening safari at 4:00 PM. A night arrival should rest and take the morning safari at 6:00 AM.</p>
<h2>Leaving</h2>
<p>If you are catching a train from JWB, leave Bera with plenty of margin. Local roads are small, and a safari should end before you head to the platform.</p>
<p>Read more: <a href="/jawai-by-train/">Jawai by train</a>, <a href="/jawai-district-rajasthan/">which district is Jawai in</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'How far is Jawai Bandh station from Bera? | About 18 km, a short drive of around 30 minutes.',
                    'Will there be a taxi at the station? | Not reliably. Book a car in advance.',
                    'What if my train does not stop at JWB? | Use Falna (FA), about 37 km from Bera.',
                ],
            ],
            [
                'slug' => 'ranakpur-to-jawai', 'title' => 'Ranakpur to Jawai: distance and combined trip', 'img' => 'lake-reflection',
                'intro' => 'Ranakpur is about 48 km from Jawai. How to combine the Jain temple and the leopard hills in one trip.',
                'mt' => 'Ranakpur to Jawai: 48 km Distance, Time & Combined Itinerary',
                'md' => 'Ranakpur to Jawai is about 48 km. See how to combine the marble Jain temple with a Jawai leopard safari in one trip from Udaipur.',
                'body' => <<<'HTML'
<p>Ranakpur's famous Jain temple, carved in marble, lies about <strong>48 km</strong> from Bera, so it pairs naturally with a Jawai safari.</p>
<h2>A simple plan</h2>
<ol>
<li>Leave Udaipur early. Ranakpur is on the way.</li>
<li>Visit the temple in the morning, when it is quieter. Check the visiting hours and dress code before you go.</li>
<li>Drive on to Jawai for the 4:00 PM evening safari (4:00 to 7:00 PM).</li>
</ol>
<h2>Going the other way</h2>
<p>Take the 6:00 AM morning safari, have breakfast and drive to Ranakpur, then continue to Udaipur in the afternoon.</p>
<h2>Good to know</h2>
<p>Ranakpur is a place of worship, so cover shoulders and knees. Bring small change for lockers, and allow around two hours for the visit. More on the area in <a href="/jain-tirth-near-jawai/">Jain tirths near Jawai</a> and <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>.</p>
HTML,
                'faq' => [
                    'How far is Ranakpur from Jawai? | About 48 km by road from Bera.',
                    'Can I visit Ranakpur and do a safari on the same day? | Yes if you start early; temple in the morning, evening safari from 4:00 PM.',
                    'What should I wear at Ranakpur? | Modest clothing covering shoulders and knees.',
                ],
            ],
            [
                'slug' => 'kumbhalgarh-to-jawai', 'title' => 'Kumbhalgarh to Jawai: distance and plan', 'img' => 'landscape1',
                'intro' => 'Kumbhalgarh Fort is about 81 km from Jawai. How to fit the fort and its long wall into a Jawai trip.',
                'mt' => 'Kumbhalgarh to Jawai: 81 km Distance, Time & Trip Plan',
                'md' => 'Kumbhalgarh to Jawai is about 81 km by road. Combine the fort and its great wall with a leopard safari at Jawai in one itinerary.',
                'body' => <<<'HTML'
<p>Kumbhalgarh Fort, known for its very long wall, is about <strong>81 km</strong> from Bera. It makes a good day trip or an on-the-way stop between Udaipur and Jawai.</p>
<h2>Fitting it in</h2>
<ul>
<li><strong>On the way from Udaipur:</strong> visit Kumbhalgarh in the morning, continue past Ranakpur and reach Jawai for the evening safari.</li>
<li><strong>As a day trip from Jawai:</strong> do the 6:00 AM safari, return for breakfast, then drive out.</li>
</ul>
<h2>Plan for time</h2>
<p>Allow around two hours at the fort, which has a lot of walking and steps. Wear sturdy shoes and carry water, especially from March onwards.</p>
<p>Combine it with <a href="/ranakpur-to-jawai/">Ranakpur</a> for a full Aravalli heritage day, then read the <a href="/jawai-road-trip-rajasthan/">Rajasthan road trip with Jawai</a> to see how it fits a longer itinerary.</p>
HTML,
                'faq' => [
                    'How far is Kumbhalgarh from Jawai? | About 81 km by road from Bera.',
                    'Is Kumbhalgarh on the way from Udaipur? | It is in the hills north of Udaipur, so it can be a stop on a longer route to Jawai.',
                    'How long do I need at the fort? | Around two hours is enough for the main sights.',
                ],
            ],
            [
                'slug' => 'mount-abu-to-jawai', 'title' => 'Mount Abu to Jawai: distance and trip', 'img' => 'granite-dome-dusk',
                'intro' => 'Mount Abu is about 99 km from Jawai. How to combine Rajasthan\'s only hill station with a leopard safari.',
                'mt' => 'Mount Abu to Jawai: 99 km Distance, Route & Combined Trip',
                'md' => 'Mount Abu to Jawai is about 99 km by road. Pair Rajasthan\'s hill station and Dilwara temples with a leopard safari in Jawai.',
                'body' => <<<'HTML'
<p>Mount Abu is about <strong>99 km</strong> from Bera. People often combine the two: a cool hill station on one side, leopards and granite on the other.</p>
<h2>A two or three day plan</h2>
<ol>
<li><strong>Day 1:</strong> reach Mount Abu, visit the lake and the Dilwara Jain temples.</li>
<li><strong>Day 2:</strong> drive to Jawai, rest, and take the evening safari (4:00 to 7:00 PM).</li>
<li><strong>Day 3:</strong> morning safari (6:00 to 9:00 AM), then onward travel.</li>
</ol>
<h2>Good to know</h2>
<p>Mount Abu gets crowded in school holidays and on long weekends, so book ahead. The drive between the two is easiest in daylight. Distances here are from OpenStreetMap routing; allow generous time because hill roads are slow.</p>
<p>Plan more in <a href="/jawai-road-trip-rajasthan/">the Rajasthan road trip with Jawai</a> and <a href="/jawai-itinerary/">a 2-day Jawai itinerary</a>.</p>
HTML,
                'faq' => [
                    'How far is Mount Abu from Jawai? | About 99 km by road from Bera.',
                    'Can I visit both in one trip? | Yes. Two to three days works well.',
                    'When is the best time? | October to March is comfortable for both.',
                ],
            ],
            [
                'slug' => 'jawai-road-trip-rajasthan', 'title' => 'Rajasthan road trip with Jawai', 'img' => 'safari-jeep',
                'intro' => 'A route linking Udaipur, Ranakpur, Kumbhalgarh, Jawai and Jodhpur, with distances and an easy pace for each stop.',
                'mt' => 'Rajasthan Road Trip with Jawai: Udaipur, Ranakpur, Jodhpur Route',
                'md' => 'Plan a Rajasthan road trip with Jawai: Udaipur, Ranakpur, Kumbhalgarh, Jawai and Jodhpur. Distances, days needed and where the safari fits.',
                'body' => <<<'HTML'
<p>Jawai sits on a natural route between Udaipur and Jodhpur, so it slots into a classic Rajasthan road trip.</p>
<h2>A five-day outline</h2>
<table>
<thead><tr><th>Day</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>1</td><td>Udaipur: lakes and City Palace</td></tr>
<tr><td>2</td><td>Drive via Kumbhalgarh and Ranakpur (about 81 km and 48 km from Bera); reach Jawai for the evening safari</td></tr>
<tr><td>3</td><td>Morning and evening safari, village visit, dam sunset</td></tr>
<tr><td>4</td><td>Morning safari; drive about 170 km to Jodhpur</td></tr>
<tr><td>5</td><td>Jodhpur fort and old city</td></tr>
</tbody>
</table>
<h2>Why this works</h2>
<p>The safari days sit in the middle of the trip as a break from city sightseeing. Udaipur to Bera is about 115 km and Bera to Jodhpur about 170 km, both comfortable drives.</p>
<h2>Tips</h2>
<ul>
<li>Book your Jawai safari dates first; the rest is flexible.</li>
<li>Winter (October to March) is the best season. See <a href="/jawai-in-winter/">Jawai in winter</a>.</li>
<li>Reverse the order if you are starting from Jodhpur.</li>
</ul>
<p>Also see <a href="/jawai-itinerary/">Jawai itinerary</a> and <a href="/jawai-distance-from-major-cities/">distances from major cities</a>.</p>
HTML,
                'faq' => [
                    'How many days should I allow? | Five days for Udaipur, Jawai and Jodhpur at a relaxed pace.',
                    'How far is Jodhpur from Jawai? | About 170 km, three to three and a half hours.',
                    'Which way round is better? | Either. Udaipur to Jodhpur or the reverse both work.',
                ],
            ],
            [
                'slug' => 'udaipur-se-jawai-ki-duri', 'title' => 'उदयपुर से जवाई की दूरी (Udaipur se Jawai ki duri)', 'img' => 'bandh-shoreline',
                'intro' => 'उदयपुर से जवाई लगभग 115 किमी दूर है, कार से करीब ढाई घंटे। रास्ता, समय और सफारी की जानकारी हिंदी में।',
                'mt' => 'उदयपुर से जवाई की दूरी: 115 किमी, समय और रास्ता | Jawai Safari',
                'md' => 'उदयपुर से जवाई की दूरी लगभग 115 किमी है, कार से ढाई घंटे। जोधपुर, अहमदाबाद, जयपुर से दूरी और जवाई तेंदुआ सफारी की जानकारी हिंदी में।',
                'body' => <<<'HTML'
<p>जवाई राजस्थान के पाली जिले में स्थित है और अपने तेंदुओं, ग्रेनाइट की पहाड़ियों और जवाई बांध के लिए जाना जाता है।</p>
<h2>दूरी और समय</h2>
<table>
<thead><tr><th>कहाँ से</th><th>बेरा (जवाई) तक दूरी</th><th>कार से समय</th></tr></thead>
<tbody>
<tr><td>उदयपुर</td><td>लगभग 115 किमी</td><td>लगभग 2.5 घंटे</td></tr>
<tr><td>जोधपुर</td><td>लगभग 170 किमी</td><td>3 से 3.5 घंटे</td></tr>
<tr><td>अहमदाबाद</td><td>लगभग 283 किमी</td><td>5 से 6 घंटे</td></tr>
<tr><td>जयपुर</td><td>लगभग 387 किमी</td><td>7 से 8 घंटे</td></tr>
</tbody>
</table>
<h2>रेल और हवाई मार्ग</h2>
<p>सबसे नजदीकी रेलवे स्टेशन सुमेरपुर जवाई बांध (JWB) है, जो बेरा से लगभग 18 किमी दूर है। फालना (FA) लगभग 37 किमी दूर है। सबसे नजदीकी हवाई अड्डा उदयपुर है।</p>
<h2>सफारी का समय</h2>
<p>सुबह की सफारी 6:00 से 9:00 बजे तक और शाम की सफारी 4:00 से 7:00 बजे तक होती है। हर जीप में अधिकतम 6 मेहमान बैठ सकते हैं। कीमत {price} प्रति जीप से शुरू होती है।</p>
<p>तेंदुए दिखने की गारंटी कोई नहीं दे सकता, लेकिन जवाई में सर्दियों में मौका अच्छा रहता है। बुकिंग के लिए <a href="/book-a-safari/">यहाँ संपर्क करें</a>। अंग्रेजी में पूरी जानकारी: <a href="/udaipur-to-jawai/">Udaipur to Jawai</a>।</p>
HTML,
                'faq' => [
                    'उदयपुर से जवाई कितनी दूर है? | लगभग 115 किमी, कार से करीब ढाई घंटे।',
                    'जवाई का नजदीकी रेलवे स्टेशन कौन सा है? | सुमेरपुर जवाई बांध (JWB), लगभग 18 किमी। फालना (FA) लगभग 37 किमी है।',
                    'क्या तेंदुआ दिखना पक्का है? | नहीं, जंगली जानवर हैं, इसलिए गारंटी नहीं दी जा सकती।',
                ],
            ],
            [
                'slug' => 'jawai-weekend-trip-from-ahmedabad', 'title' => 'Weekend trip to Jawai from Ahmedabad', 'img' => 'leopard-basking',
                'intro' => 'A two-night plan to reach Jawai from Ahmedabad on Friday, see leopards on Saturday and be home on Sunday.',
                'mt' => 'Weekend Trip to Jawai from Ahmedabad: 2-Night Plan',
                'md' => 'Weekend trip to Jawai from Ahmedabad: leave Friday, safari Saturday morning and evening, drive home Sunday. Distance, timing and tips.',
                'body' => <<<'HTML'
<p>Ahmedabad is about 283 km from Bera, so a weekend works if you plan the drive well.</p>
<h2>The plan</h2>
<table>
<thead><tr><th>Day</th><th>Plan</th></tr></thead>
<tbody>
<tr><td>Friday</td><td>Leave after work or early Friday. Drive five to six hours. Check in and rest.</td></tr>
<tr><td>Saturday</td><td>6:00 AM morning safari. Breakfast, village or dam visit. 4:00 PM evening safari.</td></tr>
<tr><td>Sunday</td><td>Optional short morning outing, or leave after breakfast. Reach Ahmedabad by evening.</td></tr>
</tbody>
</table>
<h2>Tips</h2>
<ul>
<li>Do not drive the last stretch in the dark. If you reach late, stay on Friday and start fresh.</li>
<li>Book safari dates early; weekends in winter are the busiest.</li>
<li>Carry a light jacket for the 6:00 AM safari in winter.</li>
</ul>
<p>Prefer not to drive? See <a href="/ahmedabad-to-jawai-by-train/">Ahmedabad to Jawai by train</a>. More on the route in <a href="/ahmedabad-to-jawai-by-car/">Ahmedabad to Jawai by car</a> and a longer plan in <a href="/jawai-itinerary/">the 2-day itinerary</a>.</p>
HTML,
                'faq' => [
                    'Is a weekend enough for Jawai? | Yes. Two safaris on Saturday plus the drives fit a weekend, though a third night is more relaxed.',
                    'What time should I leave on Friday? | Early enough to arrive before dark, ideally by late afternoon.',
                    'Is it better to take the train? | It can be, if the timetable suits you. Book a car for the last stretch.',
                ],
            ],
        ];
    }
}
