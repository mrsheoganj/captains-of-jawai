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
          <span class="dist-km"><?= e($p['km']) ?></span>
          <?php if ($p['time'] !== ''): ?><span class="dist-time"><?= e($p['time']) ?> by road</span><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <figure class="map-card reveal" data-map>
      <div class="map-stage" data-map-stage>
        <div class="map-plane">
          <svg class="route-map" viewBox="0 0 <?= $W ?> <?= $H ?>" role="img" aria-label="Map of Jawai with routes to nearby airports, stations and cities">
            <defs>
              <pattern id="mapgrid" width="32" height="32" patternUnits="userSpaceOnUse"><path d="M32 0H0V32" fill="none" stroke="currentColor" stroke-width=".5" opacity=".12"/></pattern>
              <radialGradient id="mapglow" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="var(--accent)" stop-opacity=".22"/><stop offset="1" stop-color="var(--accent)" stop-opacity="0"/></radialGradient>
              <radialGradient id="hill" cx="40%" cy="35%" r="70%"><stop offset="0" stop-color="#E9D9BF"/><stop offset=".55" stop-color="#C9A97F"/><stop offset="1" stop-color="#9C7A55"/></radialGradient>
              <linearGradient id="ground" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#F6F3EC"/><stop offset="1" stop-color="#EEF2EC"/></linearGradient>
              <filter id="lift" x="-30%" y="-30%" width="160%" height="160%"><feDropShadow dx="3" dy="5" stdDeviation="3" flood-color="#3a2a14" flood-opacity=".28"/></filter>
            </defs>
            <rect width="<?= $W ?>" height="<?= $H ?>" rx="14" fill="url(#ground)"/>
            <rect width="<?= $W ?>" height="<?= $H ?>" fill="url(#mapgrid)"/>
            <g class="map-rings">
              <?php foreach ($rings as [$km, $txt]): $r = round($rFor($km), 1); ?>
              <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="<?= $r ?>"/>
              <text x="<?= $hx - 6 ?>" y="<?= round($hy + $r - 6, 1) ?>" text-anchor="end"><?= $txt ?></text>
              <?php endforeach; ?>
            </g>
            <g class="map-axes"><path d="M<?= $hx ?> <?= $hy - 300 ?>V<?= $hy + 300 ?>M<?= $hx - 330 ?> <?= $hy ?>H<?= $hx + 400 ?>"/></g>
            <!-- Aravalli hills: a chain of raised hills on an arc to the east -->
            <g class="map-hills" filter="url(#lift)">
              <?php
              $p0 = [$hx + 150, $hy - 190]; $p1 = [$hx + 215, $hy - 10]; $p2 = [$hx + 125, $hy + 190];
              for ($t = 0; $t <= 1.0001; $t += 0.09) {
                  $bx = (1 - $t) ** 2 * $p0[0] + 2 * (1 - $t) * $t * $p1[0] + $t * $t * $p2[0];
                  $by = (1 - $t) ** 2 * $p0[1] + 2 * (1 - $t) * $t * $p1[1] + $t * $t * $p2[1];
                  $rw = 13 + 7 * sin($t * 9.3); ?>
              <ellipse cx="<?= round($bx, 1) ?>" cy="<?= round($by, 1) ?>" rx="<?= round($rw, 1) ?>" ry="<?= round($rw * .72, 1) ?>" fill="url(#hill)"/>
              <?php } ?>
            </g>
            <text class="map-note" x="<?= $hx + 228 ?>" y="<?= $hy - 80 ?>" transform="rotate(80 <?= $hx + 228 ?> <?= $hy - 80 ?>)">ARAVALLI HILLS</text>
            <!-- Granite leopard hills around Bera -->
            <g class="map-hills" filter="url(#lift)">
              <?php foreach ([[-34, -34, 15], [30, -40, 12], [44, 18, 14], [-14, 40, 11], [16, 46, 9], [-48, -6, 9]] as [$ox, $oy, $rr]): ?>
              <ellipse cx="<?= $hx + $ox ?>" cy="<?= $hy + $oy ?>" rx="<?= $rr ?>" ry="<?= round($rr * .75, 1) ?>" fill="url(#hill)"/>
              <?php endforeach; ?>
            </g>
            <!-- Jawai Dam reservoir, just west of Bera -->
            <path class="map-water" d="M<?= $hx - 110 ?> <?= $hy + 14 ?> c 10 -16 34 -20 46 -10 c 12 10 26 4 28 16 c 2 14 -18 20 -34 16 c -16 -4 -26 6 -38 0 c -10 -6 -8 -14 -2 -22 z"/>
            <text class="map-note" x="<?= $hx - 116 ?>" y="<?= $hy + 58 ?>">JAWAI DAM</text>
            <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="90" fill="url(#mapglow)"/>
            <?php foreach ($pts as $p): $x = $p['x']; $y = $p['y'];
                $mx = ($hx + $x) / 2 + ($y - $hy) * .14; $my = ($hy + $y) / 2 - ($x - $hx) * .14; ?>
            <g class="map-route route-<?= e($p['type']) ?>" style="--i:<?= $p['i'] ?>" data-route="<?= $p['i'] ?>">
              <path class="route-line" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
              <path class="route-flow" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
              <g class="map-pin" transform="translate(<?= $x ?> <?= $y ?>)"><circle r="16" class="pin-halo"/><circle r="6" class="pin-dot"/></g>
            </g>
            <?php endforeach; ?>
            <g class="map-home" transform="translate(<?= $hx ?> <?= $hy ?>)">
              <circle r="26" class="home-ring"/><circle r="26" class="home-ring home-ring-2"/><circle r="8" class="home-dot"/>
            </g>
            <g class="map-compass" transform="translate(<?= $W - 44 ?> 44)"><circle r="20"/><path d="M0 -15 L5 0 L0 -3 L-5 0 Z"/><text y="-24" text-anchor="middle">N</text></g>
          </svg>

          <!-- Standing 3D pins (HTML, so they stay sharp and clickable) -->
          <div class="pin3d pin-home" style="left:<?= round($hx / $W * 100, 2) ?>%;top:<?= round($hy / $H * 100, 2) ?>%">
            <span class="pin-body"><span class="pin-card"><b class="pin-code">JAWAI</b><small>Bera</small></span><span class="pin-stem"></span></span>
          </div>
          <?php foreach ($pts as $p):
              $code = preg_match('/\(([^)]+)\)/', $p['name'], $m) ? $m[1] : strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $p['name']), 0, 3));
              $short = preg_replace('/\s*\(.*\)$/', '', $p['name']);
              $gmaps = 'https://www.google.com/maps/dir/?api=1&origin=' . $home[0] . ',' . $home[1] . '&destination=' . $p['lat'] . ',' . $p['lng'] . '&travelmode=driving'; ?>
          <?php $side = $p['x'] < $hx - 20 ? ' pin-west' : ($p['x'] > $hx + 20 ? ' pin-east' : ''); ?>
          <button type="button" class="pin3d pin-<?= e($p['type']) ?><?= $side ?>" style="left:<?= round($p['x'] / $W * 100, 2) ?>%;top:<?= round($p['y'] / $H * 100, 2) ?>%;--i:<?= $p['i'] ?>"
            data-place="<?= $p['i'] ?>" data-name="<?= e($p['name']) ?>" data-km="<?= e($p['km']) ?>" data-time="<?= e($p['time']) ?>" data-kind="<?= e($label[$p['type']]) ?>" data-gmaps="<?= e($gmaps) ?>"
            aria-label="<?= e($p['name'] . ', ' . $p['km'] . ($p['time'] !== '' ? ', ' . $p['time'] . ' by road' : '')) ?>">
            <span class="pin-body"><span class="pin-card"><b class="pin-code"><?= e($code) ?></b><span class="pin-text"><strong><?= e($short) ?></strong><small><?= e($p['km']) ?></small></span></span><span class="pin-stem"></span></span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="map-info" data-map-info aria-live="polite">
        <p class="map-hint">Tap a place on the map or in the list to see its route.</p>
      </div>
      <figcaption>Directions are true; distances are drawn on a compressed scale. Road distances are approximate, measured from Bera.</figcaption>
    </figure>
  </div>
</section>
