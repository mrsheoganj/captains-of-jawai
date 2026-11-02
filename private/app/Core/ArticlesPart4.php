<?php
declare(strict_types=1);

namespace App\Core;

/** Articles 29–38: Jawai wildlife. Part of {@see Articles}. No sighting is ever guaranteed. */
final class ArticlesPart4
{
    public static function rows(): array
    {
        return [
            [
                'slug' => 'jawai-animals', 'title' => 'Animals of Jawai: what you can see', 'img' => 'leopard1',
                'intro' => 'Leopards are the headline, but Jawai\'s wildlife list is long: sloth bears, Indian wolves, chinkara, crocodiles, flamingos and more.',
                'mt' => 'Jawai Animals: Leopards, Sloth Bear, Wolf, Crocodile & Birds',
                'md' => 'Animals of Jawai: leopards, sloth bear, Indian wolf, chinkara, desert fox, porcupine, crocodiles, flamingos, sarus crane and pelicans.',
                'body' => <<<'HTML'
<p>Jawai is best known for its leopards, but the hills, scrub and lake support a much wider range of wildlife.</p>
<h2>Mammals</h2>
<ul>
<li><strong>Leopard:</strong> the star, seen on rocks, ridges and cave mouths.</li>
<li><strong>Sloth bear:</strong> rarely seen, usually at dusk.</li>
<li><strong>Indian wolf:</strong> an elusive predator of the open ground.</li>
<li><strong>Chinkara:</strong> the Indian gazelle, graceful and shy.</li>
<li><strong>Desert fox and porcupine:</strong> mostly seen at dawn and dusk.</li>
</ul>
<h2>Reptiles and birds</h2>
<ul>
<li><strong>Marsh crocodiles</strong> in the Jawai Dam.</li>
<li><strong>Flamingos, pelicans and sarus cranes</strong> and many other wetland birds.</li>
<li>Raptors, owls and desert birds in the hills.</li>
</ul>
<h2>Seeing them</h2>
<p>The morning safari (6:00 to 9:00 AM) and the evening safari (4:00 to 7:00 PM) are the best windows. Sightings can never be guaranteed, because these are wild animals.</p>
<p>Read more: <a href="/jawai/wildlife/">wildlife of Jawai</a>, <a href="/birds-of-jawai/">birds of Jawai</a> and <a href="/jawai-leopard-facts/">leopard facts</a>.</p>
HTML,
                'faq' => [
                    'What animals live in Jawai? | Leopards, sloth bears, Indian wolves, chinkara, desert foxes, porcupines, crocodiles and many birds.',
                    'Are tigers found in Jawai? | No. Jawai is known for leopards.',
                    'Can I be sure of seeing a leopard? | No. Sightings are never guaranteed.',
                ],
            ],
            [
                'slug' => 'birds-of-jawai', 'title' => 'Birds of Jawai and the dam', 'img' => 'bird1',
                'intro' => 'A birdwatcher\'s guide to Jawai: flamingos, pelicans, sarus cranes, raptors and the best months and times to look.',
                'mt' => 'Birds of Jawai: Flamingo, Pelican, Sarus Crane & Birding Tips',
                'md' => 'Birds of Jawai: flamingos, pelicans, sarus cranes, raptors and more at the dam and in the hills. Best months and times for birdwatching.',
                'body' => <<<'HTML'
<p>The Jawai Dam and the surrounding scrub make a good birding area, with water birds on the lake and raptors over the hills.</p>
<h2>Birds to look for</h2>
<ul>
<li><strong>Flamingos:</strong> large flocks arrive in winter.</li>
<li><strong>Pelicans:</strong> seen on the reservoir.</li>
<li><strong>Sarus crane:</strong> the tall, grey crane of the wetlands.</li>
<li><strong>Raptors and owls:</strong> in the rocks and open ground.</li>
<li><strong>Ducks, storks and waders:</strong> along the shoreline in the cooler months.</li>
</ul>
<h2>When to go</h2>
<p>October to March is the best period, with winter migrants joining the resident birds. Early morning has the best light and the most activity.</p>
<h2>How to go</h2>
<p>Our <a href="/safaris/dam-birding-safari/">dam birding safari</a> concentrates on the water and shoreline. Bring binoculars and a field guide, and keep quiet near the water.</p>
<p>Also read <a href="/flamingos-at-jawai/">flamingos at Jawai</a>, <a href="/jawai-animals/">animals of Jawai</a> and <a href="/jawai/jawai-dam/">the Jawai Dam</a>.</p>
HTML,
                'faq' => [
                    'When are flamingos at Jawai? | Mainly in winter, from about October to March.',
                    'Do I need binoculars? | Yes, they help a lot for water birds.',
                    'Is there a dedicated birding drive? | Yes, the Jawai Dam birding safari.',
                ],
            ],
            [
                'slug' => 'flamingos-at-jawai', 'title' => 'Flamingos at Jawai Dam', 'img' => 'flamingo-flock',
                'intro' => 'When flamingos visit the Jawai Dam, where to see them and how to watch without disturbing the flock.',
                'mt' => 'Flamingos at Jawai Dam: Season, Best Time & Viewing Tips',
                'md' => 'Flamingos at Jawai Dam are a winter highlight. Find out when they arrive, the best time of day to watch and how to avoid disturbing them.',
                'body' => <<<'HTML'
<p>Pink flocks on the Jawai Dam are one of the prettiest sights of winter, set against granite hills.</p>
<h2>When to see them</h2>
<p>Flamingos are mainly winter visitors. The cooler months, October to March, are the time to look. Numbers and timing vary from year to year with water levels and weather.</p>
<h2>Where and when</h2>
<p>Look along the shallow edges of the reservoir. Early morning is best, when the water is still and the light is soft. Our <a href="/safaris/dam-birding-safari/">dam birding safari</a> covers these spots.</p>
<h2>Watching responsibly</h2>
<ul>
<li>Keep a distance. Flocks fly if approached too close.</li>
<li>Stay quiet and avoid sudden moves.</li>
<li>Never use drones near the flock.</li>
</ul>
<h2>Photography</h2>
<p>A long lens and a steady hand are needed. Reflections in still water make wonderful shots at dawn.</p>
<p>See <a href="/birds-of-jawai/">birds of Jawai</a>, <a href="/jawai-in-winter/">Jawai in winter</a> and <a href="/crocodiles-at-jawai-dam/">crocodiles at Jawai Dam</a>.</p>
HTML,
                'faq' => [
                    'Are flamingos at Jawai all year? | No. They are mainly winter visitors, roughly October to March.',
                    'What is the best time of day? | Early morning, with soft light and calm water.',
                    'Can I use a drone? | No. Drones disturb birds and are not welcome.',
                ],
            ],
            [
                'slug' => 'crocodiles-at-jawai-dam', 'title' => 'Crocodiles at Jawai Dam', 'img' => 'bandh-shoreline',
                'intro' => 'Marsh crocodiles live in the Jawai Dam. Where they bask, when to see them and how to stay safe near the water.',
                'mt' => 'Crocodiles at Jawai Dam: Marsh Crocodile Spotting & Safety',
                'md' => 'Marsh crocodiles live in Jawai Dam. Learn where they bask, the best season to see them and how to stay safe near the water.',
                'body' => <<<'HTML'
<p>Besides leopards and birds, the Jawai Dam is home to marsh crocodiles (mugger). You can sometimes see them basking on the banks.</p>
<h2>Where and when</h2>
<p>Crocodiles bask on sunny banks and rocks, especially in the cooler months when the sun warms them. Winter mornings are a good time. In the hot season they often stay in the water.</p>
<h2>Staying safe</h2>
<ul>
<li>Keep well away from the water's edge.</li>
<li>Do not swim, paddle or let children go near the bank.</li>
<li>Never feed or tease wildlife.</li>
<li>Follow your guide's instructions at all times.</li>
</ul>
<h2>On a safari</h2>
<p>Crocodile spotting comes with the <a href="/safaris/dam-birding-safari/">dam birding safari</a>. You will see them from a safe distance in the vehicle or on the shore, with your guide.</p>
<p>Also read <a href="/birds-of-jawai/">birds of Jawai</a>, <a href="/jawai-animals/">animals of Jawai</a> and <a href="/jawai/jawai-dam/">the Jawai Dam</a>.</p>
HTML,
                'faq' => [
                    'Are there crocodiles in Jawai Dam? | Yes, marsh crocodiles live in the dam.',
                    'Is it safe to swim? | No. Do not swim or wade in the dam.',
                    'When are crocodiles easiest to see? | On sunny winter mornings, when they bask on the banks.',
                ],
            ],
            [
                'slug' => 'sloth-bear-in-jawai', 'title' => 'Sloth bears in Jawai', 'img' => 'ridge-sighting',
                'intro' => 'Sloth bears live in the hills around Jawai. What they are like, how rarely they are seen and how to give them space.',
                'mt' => 'Sloth Bear in Jawai: Habitat, Sightings & Safety Tips',
                'md' => 'Sloth bears live in the Jawai hills but are rarely seen. Learn about their habits, when sightings happen and how to keep safe.',
                'body' => <<<'HTML'
<p>The Indian sloth bear is a shaggy, shy forager that lives in the rocky hills of Jawai alongside the leopards.</p>
<h2>About the sloth bear</h2>
<p>Sloth bears feed mostly on termites, ants, fruit and honey. They are mainly active at night and at dusk, and they can be protective if surprised, so they should always be treated with respect.</p>
<h2>How often are they seen?</h2>
<p>Rarely. Most visitors do not see one. Sightings are most likely in the last light on the evening safari (4:00 to 7:00 PM) or in the first light of morning. No sighting is guaranteed.</p>
<h2>Safety</h2>
<ul>
<li>Stay in the vehicle and follow your guide.</li>
<li>Keep voices low and do not make sudden noise.</li>
<li>Never get out to approach any animal.</li>
</ul>
<p>Related: <a href="/jawai-animals/">animals of Jawai</a>, <a href="/jawai/wildlife/">wildlife of Jawai</a> and <a href="/jawai-safari-etiquette/">safari etiquette</a>.</p>
HTML,
                'faq' => [
                    'Are there sloth bears in Jawai? | Yes, but they are rarely seen.',
                    'When are they most likely to be seen? | Around dusk or at first light.',
                    'Are sloth bears dangerous? | They can be if surprised. Always stay in the vehicle and follow your guide.',
                ],
            ],
            [
                'slug' => 'indian-wolf-in-jawai', 'title' => 'Indian wolf, chinkara and desert fox in Jawai', 'img' => 'landscape1',
                'intro' => 'Beyond the leopard: the Indian wolf, chinkara gazelle and desert fox that live on the open ground around Jawai.',
                'mt' => 'Indian Wolf, Chinkara & Desert Fox in Jawai | Jawai Wildlife',
                'md' => 'Besides leopards, Jawai has Indian wolves, chinkara gazelles and desert foxes on the open ground. Where and when you might see them.',
                'body' => <<<'HTML'
<p>The open scrub and grassland around the granite hills support animals that are very different from the leopard.</p>
<h2>Indian wolf</h2>
<p>A lean, long-legged wolf of dry open country. It is elusive and seldom seen, and sightings are a real bonus.</p>
<h2>Chinkara</h2>
<p>The Indian gazelle, small and fast, with long legs and delicate horns. Chinkara are shy but are the most likely of these three to be spotted in the open.</p>
<h2>Desert fox</h2>
<p>A small fox of arid areas, mostly active at dusk and dawn.</p>
<h2>When to look</h2>
<p>All three are best sought in the cool of the morning (6:00 to 9:00 AM) and in the evening (4:00 to 7:00 PM). A patient, quiet group has the best chance. No sighting can be promised.</p>
<p>Read more: <a href="/jawai-animals/">animals of Jawai</a>, <a href="/sloth-bear-in-jawai/">sloth bears</a> and <a href="/jawai-leopard-sighting-chances/">leopard sighting chances</a>.</p>
HTML,
                'faq' => [
                    'Are there wolves in Jawai? | Yes, Indian wolves live in the open country, though they are seldom seen.',
                    'What is a chinkara? | The Indian gazelle, a small, fast antelope of dry country.',
                    'When is best to look? | Early morning and evening.',
                ],
            ],
            [
                'slug' => 'jawai-leopard-facts', 'title' => 'Jawai leopard facts', 'img' => 'leopard-portrait',
                'intro' => 'How many leopards live in Jawai, why they live so close to people, and what makes this population unusual.',
                'mt' => 'Jawai Leopard Facts: How Many, Why Here & How They Live',
                'md' => 'Jawai leopard facts: an estimated 50 to 70 leopards, no official count, living in granite hills beside villages. What makes them special.',
                'body' => <<<'HTML'
<p>Jawai has become known as one of the places in India where leopards are seen with unusual regularity.</p>
<h2>Numbers</h2>
<p>The population is commonly estimated at <strong>50 to 70 leopards</strong>. There is no official count, so treat any exact figure with caution. The Jawai Bandh Leopard Conservation Reserve covers roughly 19.8 square kilometres.</p>
<h2>Why here?</h2>
<ul>
<li>Granite caves and ledges for shelter and cubs.</li>
<li>Plenty of prey on and near the hills.</li>
<li>Water from the dam, built between 1946 and 1957.</li>
<li>A local community, the Rabari, who tolerate the animals.</li>
</ul>
<h2>Behaviour</h2>
<p>Leopards are mostly solitary and most active around dawn and dusk. In Jawai they often rest on rocks in daylight, which is why safari sightings are better than in many forests.</p>
<h2>An honest note</h2>
<p>Sightings are likely but not guaranteed. Learn more in <a href="/jawai/leopards/">leopards of Jawai</a>, <a href="/jawai-leopard-sighting-chances/">sighting chances</a> and <a href="/is-jawai-a-national-park/">is Jawai a national park?</a></p>
HTML,
                'faq' => [
                    'How many leopards are in Jawai? | An estimated 50 to 70, though there is no official count.',
                    'Why are leopards easy to see in Jawai? | They rest on open granite rocks and are used to vehicles and local people.',
                    'Are sightings guaranteed? | No. They are wild animals.',
                ],
            ],
            [
                'slug' => 'jawai-leopard-sighting-chances', 'title' => 'Jawai leopard sighting chances', 'img' => 'leopard-stalking',
                'intro' => 'How likely are you to see a leopard in Jawai, which season and time improve your odds, and how to set realistic expectations.',
                'mt' => 'Jawai Leopard Sighting Chances: Best Time & How to Improve Odds',
                'md' => 'How likely is it to see a leopard in Jawai? Sightings are common but never guaranteed. Best season, time of day and tips to improve your odds.',
                'body' => <<<'HTML'
<p>It is the question everyone asks. The honest answer: your chances are good, but it can never be promised.</p>
<h2>Why Jawai is good</h2>
<p>The leopards rest in open granite hills and are used to jeeps. Many guests see at least one animal over two safaris, and often more.</p>
<h2>What improves your chances</h2>
<ul>
<li><strong>Do at least two safaris.</strong> One morning (6:00 to 9:00 AM) and one evening (4:00 to 7:00 PM).</li>
<li><strong>Visit October to March</strong>, when the weather is comfortable and the animals are easy to track. See <a href="/jawai-in-winter/">Jawai in winter</a>.</li>
<li><strong>Be quiet and patient.</strong> Tracks, alarm calls and local knowledge guide the search.</li>
<li><strong>Use a local guide and tracker.</strong></li>
</ul>
<h2>Manage expectations</h2>
<p>Some drives are quiet. Others end with a long, close view. Enjoy the landscape and other wildlife too, and treat a leopard as a bonus on a great drive.</p>
<p>See <a href="/jawai-leopard-safari-what-to-expect/">what to expect on a safari</a> and <a href="/book-a-safari/">book a safari</a>.</p>
HTML,
                'faq' => [
                    'Will I definitely see a leopard? | No one can promise that. Chances are good, especially over two safaris.',
                    'Which season is best? | October to March.',
                    'Does it help to do more than one safari? | Yes, it improves your chances.',
                ],
            ],
            [
                'slug' => 'jawai-conservation-and-community', 'title' => 'Jawai conservation and the local community', 'img' => 'safari-guests',
                'intro' => 'How people and leopards share Jawai, why the Rabari matter, and how responsible tourism helps conservation.',
                'mt' => 'Jawai Conservation: Leopards, Rabari Community & Responsible Tourism',
                'md' => 'Jawai conservation depends on the local Rabari community living alongside leopards. How responsible tourism supports both.',
                'body' => <<<'HTML'
<p>Jawai is unusual because leopards live so close to people. That coexistence is the reason the area matters for conservation.</p>
<h2>The role of the community</h2>
<p>Rabari herders have lived in these hills for generations. Their tolerance of the leopards, even when livestock is lost, is a major factor in the animals' survival.</p>
<h2>Reserve status</h2>
<p>The Jawai Bandh Leopard Conservation Reserve covers about 19.8 square kilometres. It is a conservation reserve, not a national park. See <a href="/is-jawai-a-national-park/">is Jawai a national park?</a></p>
<h2>How tourism can help</h2>
<ul>
<li>Choose operators who employ local guides and trackers.</li>
<li>Keep the number of vehicles low at any sighting.</li>
<li>Respect distance, silence and the guide's instructions.</li>
<li>Buy from local artisans.</li>
</ul>
<h2>Our approach</h2>
<p>We run small private safaris, never promise sightings and encourage guests to respect the animals and the villages. More in <a href="/jawai-safari-etiquette/">safari etiquette</a>, <a href="/rabari-village-jawai/">Rabari village life</a> and <a href="/jawai/culture/">culture</a>.</p>
HTML,
                'faq' => [
                    'Is Jawai a national park? | No. It is a conservation reserve.',
                    'Why is the Rabari community important? | Their tolerance of leopards helps the animals survive.',
                    'How can I travel responsibly? | Use local guides, respect distance and silence, and support local artisans.',
                ],
            ],
        ];
    }
}
