<?php
/**
 * "How far is Jawai?" — a drawn map with animated routes from Jawai (Bera) to the
 * airport, city and stations listed in Admin → Settings → Homepage → Places.
 * Positions come from real latitude/longitude, so the layout stays true when places change.
 */
use App\Core\Settings;

$home = [(float) (setting('latitude') ?: 25.0012), (float) (setting('longitude') ?: 73.1775)];
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
              <!-- Sketch style: parchment, ink lines with a slight hand-drawn wobble -->
              <radialGradient id="paper" cx="50%" cy="45%" r="75%"><stop offset="0" stop-color="#FBF5E6"/><stop offset=".7" stop-color="#F3E8CF"/><stop offset="1" stop-color="#E6D3AE"/></radialGradient>
              <filter id="grain" x="0" y="0" width="100%" height="100%"><feTurbulence type="fractalNoise" baseFrequency=".85" numOctaves="2" seed="7" result="n"/><feColorMatrix in="n" type="matrix" values="0 0 0 0 .45  0 0 0 0 .33  0 0 0 0 .18  0 0 0 .09 0"/></filter>
              <filter id="rough" x="-5%" y="-5%" width="110%" height="110%"><feTurbulence type="fractalNoise" baseFrequency=".035" numOctaves="2" seed="4" result="t"/><feDisplacementMap in="SourceGraphic" in2="t" scale="3"/></filter>
              <radialGradient id="wash" cx="45%" cy="40%" r="65%"><stop offset="0" stop-color="#A9D3E3"/><stop offset=".75" stop-color="#7FB6CC"/><stop offset="1" stop-color="#6A9FB6"/></radialGradient>
              <radialGradient id="mapglow" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="var(--accent)" stop-opacity=".18"/><stop offset="1" stop-color="var(--accent)" stop-opacity="0"/></radialGradient>
              <!-- Ink mountain: lit face, shaded face with hatching -->
              <symbol id="sk-mtn" viewBox="0 0 64 44" overflow="visible">
                <path d="M2 42 L24 6 L31 15 L37 8 L62 42 Z" fill="#F4EAD3"/>
                <path d="M24 6 L29 20 L26 30 L33 42 L62 42 L37 8 L31 15 Z" fill="#D9C19A"/>
                <path d="M2 42 L24 6 L31 15 L37 8 L62 42" fill="none" stroke="#4A3826" stroke-width="1.6" stroke-linejoin="round"/>
                <path d="M24 6 L29 20 L26 30 L33 42 M37 8 L39 22 L44 30" fill="none" stroke="#4A3826" stroke-width="1"/>
                <path d="M33 22 l6 -3 M35 28 l7 -3 M38 34 l8 -3 M44 38 l7 -3 M41 16 l4 -2 M47 26 l5 -2 M50 33 l5 -2" stroke="#4A3826" stroke-width=".8" stroke-linecap="round"/>
              </symbol>
              <!-- Acacia / khejri: flat-topped scrub tree of the Jawai plains -->
              <symbol id="sk-tree" viewBox="0 0 24 24" overflow="visible">
                <path d="M12 23 V13 M12 16 L8 11 M12 15 L16 11" stroke="#4A3826" stroke-width="1.3" stroke-linecap="round" fill="none"/>
                <path d="M2 11 C3 7 7 6 9 7 C10 4 15 4 16 7 C19 6 23 8 22 11 C19 13 5 13 2 11 Z" fill="#9FA977" stroke="#4A3826" stroke-width="1.1"/>
                <path d="M6 10 l3 -1 M12 9 l3 -1 M17 10 l2 -1" stroke="#4A3826" stroke-width=".7" stroke-linecap="round"/>
              </symbol>
              <!-- Granite boulders (the leopard hills around Bera) -->
              <symbol id="sk-rock" viewBox="0 0 30 20" overflow="visible">
                <path d="M2 19 C1 12 6 6 12 7 C14 3 21 3 23 8 C28 9 30 15 28 19 Z" fill="#E2CBA4" stroke="#4A3826" stroke-width="1.2" stroke-linejoin="round"/>
                <path d="M14 8 C16 11 16 15 14 19 M22 10 l3 2 M23 14 l3 2 M20 16 l3 1.5" stroke="#4A3826" stroke-width=".8" fill="none" stroke-linecap="round"/>
              </symbol>
            </defs>
            <rect width="<?= $W ?>" height="<?= $H ?>" rx="14" fill="url(#paper)"/>
            <rect width="<?= $W ?>" height="<?= $H ?>" rx="14" filter="url(#grain)"/>
            <rect x="8" y="8" width="<?= $W - 16 ?>" height="<?= $H - 16 ?>" rx="10" class="map-border"/>
            <g class="map-rings">
              <?php foreach ($rings as [$km, $txt]): $r = round($rFor($km), 1); ?>
              <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="<?= $r ?>"/>
              <text x="<?= $hx - 6 ?>" y="<?= round($hy + $r - 6, 1) ?>" text-anchor="end"><?= $txt ?></text>
              <?php endforeach; ?>
            </g>
            <g class="map-sketch" filter="url(#rough)">
              <!-- Aravalli hills: a chain of ink mountains on an arc to the east -->
              <?php
              $p0 = [$hx + 150, $hy - 215]; $p1 = [$hx + 240, $hy - 10]; $p2 = [$hx + 140, $hy + 215];
              for ($t = 0, $k = 0; $t <= 1.0001; $t += 0.1, $k++) {
                  $bx = (1 - $t) ** 2 * $p0[0] + 2 * (1 - $t) * $t * $p1[0] + $t * $t * $p2[0];
                  $by = (1 - $t) ** 2 * $p0[1] + 2 * (1 - $t) * $t * $p1[1] + $t * $t * $p2[1];
                  $mw = 64 + 20 * sin($t * 9.3 + 1); $off = $k % 2 ? 26 : -6; ?>
              <use href="#sk-mtn" x="<?= round($bx - $mw / 2 + $off, 1) ?>" y="<?= round($by - $mw * .69, 1) ?>" width="<?= round($mw, 1) ?>" height="<?= round($mw * .69, 1) ?>"/>
              <?php } ?>
              <!-- Distant hills in the corners -->
              <?php foreach ([[20, 26, 96], [92, 56, 64], [24, 450, 90], [104, 486, 58], [540, 456, 76], [610, 494, 54], [168, 22, 52]] as [$mx0, $my0, $mw]): ?>
              <use href="#sk-mtn" x="<?= $mx0 ?>" y="<?= $my0 ?>" width="<?= $mw ?>" height="<?= round($mw * .69, 1) ?>"/>
              <?php endforeach; ?>
              <!-- Scrub trees -->
              <?php foreach ([[40, 150], [62, 168], [30, 250], [110, 400], [132, 418], [210, 470], [250, 505], [420, 520], [470, 50], [500, 30], [240, 70], [60, 330], [640, 300], [660, 330], [650, 160], [380, 440], [196, 196]] as [$tx, $ty]): ?>
              <use href="#sk-tree" x="<?= $tx ?>" y="<?= $ty ?>" width="28" height="28"/><use href="#sk-tree" x="<?= $tx + 18 ?>" y="<?= $ty + 10 ?>" width="20" height="20"/>
              <?php endforeach; ?>
              <!-- Granite leopard hills around Bera -->
              <?php foreach ([[-60, -52, 34], [24, -60, 30], [48, 14, 32], [-24, 30, 26], [14, 40, 22], [-70, -16, 22], [60, -28, 20]] as [$ox, $oy, $rw]): ?>
              <use href="#sk-rock" x="<?= $hx + $ox - $rw / 2 ?>" y="<?= $hy + $oy - $rw * .66 ?>" width="<?= $rw ?>" height="<?= round($rw * .66, 1) ?>"/>
              <?php endforeach; ?>
              <!-- Jawai Dam reservoir, just west of Bera: watercolour wash with ink shore and ripples -->
              <path class="map-water" d="M<?= $hx - 138 ?> <?= $hy + 12 ?> c 12 -22 44 -30 62 -16 c 14 10 32 2 38 18 c 6 18 -22 28 -44 22 c -20 -5 -34 8 -50 0 c -14 -7 -12 -16 -6 -24 z"/>
              <path class="map-ripples" d="M<?= $hx - 116 ?> <?= $hy + 6 ?> q 6 -4 12 0 t 12 0 M<?= $hx - 96 ?> <?= $hy + 18 ?> q 6 -4 12 0 t 12 0 M<?= $hx - 124 ?> <?= $hy + 24 ?> q 5 -3 10 0"/>
            </g>
            <text class="map-note map-note-hills" x="<?= $hx + 262 ?>" y="<?= $hy - 70 ?>" transform="rotate(78 <?= $hx + 262 ?> <?= $hy - 70 ?>)">Aravalli Hills</text>
            <text class="map-note" x="<?= $hx - 150 ?>" y="<?= $hy + 66 ?>">Jawai Dam</text>
            <circle cx="<?= $hx ?>" cy="<?= $hy ?>" r="90" fill="url(#mapglow)"/>
            <?php foreach ($pts as $p): $x = $p['x']; $y = $p['y'];
                $mx = ($hx + $x) / 2 + ($y - $hy) * .14; $my = ($hy + $y) / 2 - ($x - $hx) * .14; ?>
            <g class="map-route route-<?= e($p['type']) ?>" filter="url(#rough)" style="--i:<?= $p['i'] ?>" data-route="<?= $p['i'] ?>">
              <path class="route-line" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
              <path class="route-flow" d="M<?= $hx ?> <?= $hy ?> Q <?= round($mx, 1) ?> <?= round($my, 1) ?> <?= $x ?> <?= $y ?>" pathLength="100"/>
              <g class="map-pin" transform="translate(<?= $x ?> <?= $y ?>)"><circle r="16" class="pin-halo"/><circle r="6" class="pin-dot"/></g>
            </g>
            <?php endforeach; ?>
            <g class="map-home" transform="translate(<?= $hx ?> <?= $hy ?>)">
              <circle r="26" class="home-ring"/><circle r="26" class="home-ring home-ring-2"/><circle r="8" class="home-dot"/>
            </g>
            <g class="map-compass" transform="translate(<?= $W - 48 ?> 58)" filter="url(#rough)"><path class="compass-s" d="M0 22 L6 2 L0 6 L-6 2 Z"/><path d="M0 -24 L7 2 L0 -3 L-7 2 Z"/><text y="-30" text-anchor="middle">N</text></g>
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
