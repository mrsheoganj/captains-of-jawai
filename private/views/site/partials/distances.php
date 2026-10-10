<?php
/**
 * "How far is Jawai?" — a drawn map with animated routes from Jawai (Bera) to the
 * airport, city and stations listed in Admin → Settings → Homepage → Places.
 * Positions come from real latitude/longitude, so the layout stays true when places change.
 */
use App\Core\Settings;

$home = [(float) (setting('latitude') ?: 25.1055), (float) (setting('longitude') ?: 73.1722)];
$places = [];
foreach (Settings::lines('distances_items') as $line) {
    $p = array_pad(array_map('trim', explode('|', $line)), 6, '');
    if ($p[0] === '') {
        continue;
    }
    $places[] = [
        'name' => $p[0], 'type' => in_array($p[1], ['airport', 'rail', 'city'], true) ? $p[1] : 'city',
        'km' => $p[2], 'time' => $p[3],
        'lat' => is_numeric($p[4]) ? (float) $p[4] : null, 'lng' => is_numeric($p[5]) ? (float) $p[5] : null,
    ];
}
if (!$places) {
    return;
}

// Compass-style map: real direction (bearing) from Jawai, distance on a log scale so a
// station 15 km away and an airport 155 km away both fit, with range rings to read it.
$W = 720; $H = 580; $cx = 320; $cy = 300;
$rFor = fn (float $km) => 70 + (log(max(8, $km)) - log(8)) / (log(220) - log(8)) * 185;
$pts = [];
foreach ($places as $n => $p) {
    if ($p['lat'] === null || $p['lng'] === null) {
        continue;
    }
    $dy = $p['lat'] - $home[0];
    $dx = ($p['lng'] - $home[1]) * cos(deg2rad($home[0]));
    $p['ang'] = atan2($dx, $dy); // radians, 0 = north, clockwise
    $p['r'] = $rFor((float) preg_replace('/[^\d.]/', '', $p['km']) ?: 20);
    $p['i'] = $n;
    $pts[] = $p;
}
// Keep labels apart: at least 26 degrees between neighbouring places.
usort($pts, fn ($a, $b) => $a['ang'] <=> $b['ang']);
$gap = deg2rad(26);
for ($pass = 0; $pass < 6; $pass++) {
    for ($j = 1; $j < count($pts); $j++) {
        $d = $pts[$j]['ang'] - $pts[$j - 1]['ang'];
        if ($d < $gap) {
            $push = ($gap - $d) / 2;
            $pts[$j - 1]['ang'] -= $push;
            $pts[$j]['ang'] += $push;
        }
    }
}
foreach ($pts as &$p) {
    $p['x'] = round($cx + sin($p['ang']) * $p['r'], 1);
    $p['y'] = round($cy - cos($p['ang']) * $p['r'], 1);
}
unset($p);
[$hx, $hy] = [$cx, $cy];
$rings = [[25, '25 km'], [50, '50 km'], [100, '100 km'], [200, '200 km']];

$label = ['airport' => 'Airport', 'rail' => 'Railway station', 'city' => 'City'];
?>
<section class="section distances" id="distances">
  <div class="container distances-grid">
    <div class="distances-text">
      <span class="kicker reveal"><?= e(setting('distances_kicker')) ?></span>
      <h2 class="display-2 reveal"><?= e(setting('distances_heading')) ?></h2>
      <p class="lead-muted reveal"><?= e(setting('distances_intro')) ?></p>
      <ol class="distance-list" data-stagger>
        <?php foreach ($places as $n => $p): ?>
        <li class="reveal dist-<?= e($p['type']) ?>" data-dist="<?= $n ?>">
          <span class="dist-type"><?= e($label[$p['type']]) ?></span>
          <strong class="dist-name"><?= e($p['name']) ?></strong>
          <span class="dist-km" data-count><?= e($p['km']) ?></span>
          <?php if ($p['time'] !== ''): ?><span class="dist-time"><?= e($p['time']) ?> by road</span><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <figure class="map-card reveal" data-map>
      <svg class="route-map" viewBox="0 0 <?= $W ?> <?= $H ?>" role="img" aria-label="Map of Jawai with routes to nearby airports, stations and cities">
        <defs>
          <pattern id="mapgrid" width="32" height="32" patternUnits="userSpaceOnUse"><path d="M32 0H0V32" fill="none" stroke="currentColor" stroke-width=".5" opacity=".12"/></pattern>
          <radialGradient id="mapglow" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="var(--accent)" stop-opacity=".22"/><stop offset="1" stop-color="var(--accent)" stop-opacity="0"/></radialGradient>
        </defs>
        <rect width="<?= $W ?>" height="<?= $H ?>" fill="url(#mapgrid)"/>
        <!-- range rings -->
        <g class="map-rings">
          <?php foreach ($rings as [$km, $txt]): $r = round($rFor($km), 1); ?>
          <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="<?= $r ?>"/>
          <text x="<?= $hx - 6 ?>" y="<?= round($hy + $r - 6, 1) ?>" text-anchor="end"><?= $txt ?></text>
          <?php endforeach; ?>
        </g>
        <g class="map-axes"><path d="M<?= $hx ?> <?= $hy - 268 ?>V<?= $hy + 268 ?>M<?= $hx - 330 ?> <?= $hy ?>H<?= $hx + 400 ?>"/></g>
        <!-- Aravalli hills: an arc to the east, between Jawai and Udaipur -->
        <path class="map-ridge" d="M<?= $hx + 150 ?> <?= $hy - 170 ?> Q <?= $hx + 205 ?> <?= $hy - 10 ?> <?= $hx + 120 ?> <?= $hy + 175 ?>" fill="none"/>
        <text class="map-note" x="<?= $hx + 196 ?>" y="<?= $hy - 70 ?>" transform="rotate(80 <?= $hx + 196 ?> <?= $hy - 70 ?>)">ARAVALLI HILLS</text>
        <!-- Jawai Dam reservoir, just west of Bera -->
        <path class="map-water" d="M<?= $hx - 96 ?> <?= $hy + 14 ?> c 10 -16 34 -20 46 -10 c 12 10 26 4 28 16 c 2 14 -18 20 -34 16 c -16 -4 -26 6 -38 0 c -10 -6 -8 -14 -2 -22 z"/>
        <text class="map-note" x="<?= $hx - 100 ?>" y="<?= $hy + 56 ?>">JAWAI DAM</text>
        <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="90" fill="url(#mapglow)"/>
        <?php foreach ($pts as $p): $x = $p['x']; $y = $p['y'];
            $mx = ($hx + $x) / 2 + ($y - $hy) * .14; $my = ($hy + $y) / 2 - ($x - $hx) * .14; ?>
        <g class="map-route route-<?= e($p['type']) ?>" style="--i:<?= $p['i'] ?>">
          <path class="route-line" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
          <path class="route-flow" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
          <g class="map-pin" transform="translate(<?= $x ?> <?= $y ?>)">
            <circle r="16" class="pin-halo"/>
            <circle r="7" class="pin-dot"/>
            <?php $right = sin($p['ang']) >= -0.2 && $x < $W - 170; $up = cos($p['ang']) > 0.5; ?>
            <g transform="translate(<?= $right ? 16 : -16 ?> <?= $up ? -14 : 4 ?>)">
              <text class="pin-name" text-anchor="<?= $right ? 'start' : 'end' ?>"><?= e(preg_replace('/\s*\(.*\)$/', '', $p['name'])) ?></text>
              <text class="pin-km" y="20" text-anchor="<?= $right ? 'start' : 'end' ?>"><?= e($p['km']) ?><?= $p['time'] !== '' ? ' · ' . e($p['time']) : '' ?></text>
            </g>
          </g>
        </g>
        <?php endforeach; ?>

        <g class="map-home" transform="translate(<?= $hx ?> <?= $hy ?>)">
          <circle r="26" class="home-ring"/>
          <circle r="26" class="home-ring home-ring-2"/>
          <circle r="10" class="home-dot"/>
          <text class="home-label" y="-24" text-anchor="middle">JAWAI</text>
          <text class="home-sub" y="34" text-anchor="middle">Bera</text>
        </g>

        <g class="map-compass" transform="translate(<?= $W - 44 ?> 44)">
          <circle r="20"/>
          <path d="M0 -15 L5 0 L0 -3 L-5 0 Z"/>
          <text y="-24" text-anchor="middle">N</text>
        </g>
      </svg>
      <figcaption>Directions are true; distances are drawn on a compressed scale. Road distances are approximate, measured from Bera.</figcaption>
    </figure>
  </div>
</section>
