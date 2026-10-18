<?php
/**
 * Animated Jawai scene: a convoy of open-top gypsies climbs over a granite hill on a village track,
 * past the dam, a hill shrine and a leopard on the rocks. Pure SVG (SMIL); the page script pauses it
 * while off screen and keeps it still for visitors who prefer reduced motion.
 */
$name = e((string) setting('site_name'));
$track = 'M-160 214 C 60 214 170 212 300 202 C 400 194 460 150 540 128 C 600 112 640 112 690 126 C 760 146 800 186 880 204 C 960 220 1080 216 1360 216';
?>
<div class="jeep-track jeep-scene" data-jeep-track>
  <svg class="js-scene" viewBox="0 0 1200 250" preserveAspectRatio="xMidYMax slice" role="img" aria-label="Safari jeeps climbing a granite hill in Jawai, past the dam, a hill shrine and a leopard on the rocks">
    <defs>
      <!-- the open-top gypsy (origin at the middle of the ground line, facing right) -->
      <g id="js-jeep">
        <g transform="translate(-110 -104)">
          <ellipse cx="110" cy="105" rx="92" ry="4" class="jt-shadow"/>
          <g>
            <animateTransform attributeName="transform" type="translate" values="0 0;0 -1.6;0 0" dur=".5s" repeatCount="indefinite"/>
            <path class="jt-cage" d="M28 52 V18 H100 V52 M64 52 V18 M28 30 H100"/>
            <rect x="32" y="34" width="26" height="16" rx="4" class="jt-seat"/><rect x="66" y="34" width="26" height="16" rx="4" class="jt-seat"/><rect x="100" y="38" width="15" height="14" rx="3" class="jt-seat"/>
            <circle cx="15" cy="62" r="11" class="jt-tyre"/><circle cx="15" cy="62" r="5" class="jt-rim"/>
            <path class="jt-panel" d="M20 82 V50 H118 L125 57 H186 Q197 57 199 64 V82 H188 A21 21 0 0 0 148 82 H72 A21 21 0 0 0 32 82 Z"/>
            <path class="jt-line" d="M20 62 H199 M125 57 V80 M150 57 L152 64"/>
            <text x="64" y="74" class="jt-name"><?= $name ?></text>
            <path class="jt-glass" d="M124 57 L117 30 H130 L137 57 Z"/><path class="jt-cage jt-thin" d="M124 57 L117 30 H130 L137 57"/>
            <circle cx="194" cy="66" r="3.2" class="jt-lamp"/><rect x="196" y="76" width="11" height="4" rx="1.5" class="jt-bumper"/>
            <g class="jt-tracker"><circle cx="46" cy="10" r="5"/><path d="M41 6 Q46 1 52 6" class="jt-hat"/><path class="jt-shirt" d="M40 35 L41 19 Q46 14.5 51 19 L52 35 Z"/><path d="M48 21 L55 15 M55 15 l3 -1"/></g>
          </g>
          <?php foreach ([52, 168] as $wx): ?>
          <g transform="translate(<?= $wx ?> 86)"><circle r="18" class="jt-tyre"/><circle r="17" class="jt-tread"/>
            <g><animateTransform attributeName="transform" type="rotate" from="0" to="360" dur=".6s" repeatCount="indefinite"/><circle r="9" class="jt-rim"/><path class="jt-spokes" d="M0 -8V8M-8 0H8M-5.7 -5.7L5.7 5.7M-5.7 5.7L5.7 -5.7"/><circle r="2.6" class="jt-hub"/></g>
          </g>
          <?php endforeach; ?>
          <g class="js-dust">
            <?php foreach ([[0, 18, 96], [.4, 6, 92], [.8, -6, 95]] as [$d, $dx, $dy]): ?>
            <circle cx="<?= $dx ?>" cy="<?= $dy ?>" r="5"><animate attributeName="r" values="3;9" dur="1.2s" begin="<?= $d ?>s" repeatCount="indefinite"/><animate attributeName="opacity" values=".7;0" dur="1.2s" begin="<?= $d ?>s" repeatCount="indefinite"/><animate attributeName="cx" values="<?= $dx ?>;<?= $dx - 26 ?>" dur="1.2s" begin="<?= $d ?>s" repeatCount="indefinite"/></circle>
            <?php endforeach; ?>
          </g>
        </g>
      </g>
      <path id="js-track" d="<?= $track ?>"/>
      <!-- acacia / khejri tree -->
      <g id="js-tree"><path d="M0 0 V-16 M0 -11 L-6 -18 M0 -12 L6 -19" class="js-ink"/><path d="M-17 -18 C-15 -25 -8 -27 -4 -25 C-2 -30 6 -30 8 -25 C13 -27 19 -24 18 -19 C12 -15 -11 -15 -17 -18 Z" class="js-canopy"/></g>
      <radialGradient id="js-sun" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="#F6C46A"/><stop offset="1" stop-color="#F6C46A" stop-opacity="0"/></radialGradient>
    </defs>

    <!-- sky and far hills -->
    <circle cx="930" cy="70" r="70" fill="url(#js-sun)" opacity=".55"/>
    <circle cx="930" cy="70" r="22" class="js-sunball"/>
    <path class="js-far" d="M0 170 C80 140 140 120 210 138 C270 104 320 96 390 128 C450 112 500 126 560 140 C640 98 720 86 790 124 C860 110 900 128 950 140 C1020 108 1090 100 1200 132 V250 H0 Z"/>

    <!-- birds -->
    <?php foreach ([[0, 70, 26], [-9, 52, 32], [-17, 84, 28]] as [$b, $y, $dur]): ?>
    <path class="js-bird" d="M0 0 q6 -6 12 0 q6 -6 12 0">
      <animateMotion path="M-60 <?= $y ?> C 300 <?= $y - 20 ?> 700 <?= $y + 16 ?> 1260 <?= $y - 10 ?>" dur="<?= $dur ?>s" begin="<?= $b ?>s" repeatCount="indefinite"/>
      <animateTransform attributeName="transform" type="scale" values="1 1;1 .4;1 1" dur=".7s" repeatCount="indefinite" additive="sum"/>
    </path>
    <?php endforeach; ?>

    <!-- Jawai Dam backwater: crocodile on the bank, flamingos wading -->
    <path class="js-water" d="M0 190 C50 180 120 178 190 184 C228 188 232 198 200 204 C150 210 60 210 0 208 Z"/>
    <path class="js-ripple" d="M24 192 q8 -4 16 0 t16 0 M96 188 q8 -4 16 0 t16 0 M150 198 q6 -3 12 0">
      <animate attributeName="opacity" values=".3;.9;.3" dur="3s" repeatCount="indefinite"/>
    </path>
    <g class="js-croc" transform="translate(18 186)">
      <path class="js-croc-body" d="M8 0 C20 -5 44 -7 66 -6 C80 -5 92 -3 104 -1 L118 1 L104 3 C88 4 60 5 36 4 C22 4 12 3 8 0 Z"/>
      <path class="js-croc-ridge" d="M40 -6 l3 -3 l3 3 M50 -6.5 l3 -3 l3 3 M60 -6.5 l3 -3 l3 3 M70 -6 l3 -3 l3 3 M80 -5 l2.5 -2.5 l2.5 2.5"/>
      <path class="js-croc-leg" d="M30 3 l-4 5 M56 4 l-3 5 M84 3 l3 5"/>
      <circle cx="21" cy="-3.6" r="1.5" class="js-croc-eye"/>
      <path class="js-croc-jaw" d="M8 0 L-10 -1 C-12 -1 -13 1 -10 2 L10 3 Z"><animateTransform attributeName="transform" type="rotate" values="0 9 1;0 9 1;-14 9 1;-14 9 1;0 9 1" keyTimes="0;.55;.62;.8;1" dur="6s" repeatCount="indefinite"/></path>
      <path class="js-croc-jaw" d="M8 2 L-10 2.5 C-11 3 -10 4 -8 4 L10 4 Z"/>
    </g>
    <?php foreach ([[118, 186, 1, 0], [146, 190, .9, -1.6]] as [$fx, $fy, $fs, $fb]): ?>
    <g class="js-flamingo" transform="translate(<?= $fx ?> <?= $fy ?>) scale(<?= $fs ?>)">
      <path class="js-fl-leg" d="M0 0 V-16 M3 0 L2 -8 L0 -16"/>
      <ellipse cx="2" cy="-21" rx="8" ry="5" class="js-fl-body"/>
      <g><animateTransform attributeName="transform" type="rotate" values="0 6 -22;0 6 -22;70 6 -22;70 6 -22;0 6 -22" keyTimes="0;.5;.6;.85;1" dur="5s" begin="<?= $fb ?>s" repeatCount="indefinite"/>
        <path class="js-fl-neck" d="M6 -23 C10 -30 4 -34 6 -40 C7 -44 12 -44 13 -41"/>
        <path class="js-fl-beak" d="M13 -41 l4 2 l-2 1.5"/>
      </g>
    </g>
    <?php endforeach; ?>

    <!-- hill shrine with a fluttering flag -->
    <g transform="translate(1000 148)">
      <path class="js-rock" d="M-70 70 C-60 30 -30 10 0 8 C30 10 60 30 74 70 Z"/>
      <rect x="-12" y="-14" width="24" height="22" class="js-shrine"/><path d="M-15 -14 L0 -30 L15 -14 Z" class="js-shrine"/>
      <path d="M0 -30 V-52" class="js-ink"/>
      <path class="js-flag" d="M0 -52 L18 -47 L0 -42 Z"><animate attributeName="d" values="M0 -52 L18 -47 L0 -42 Z;M0 -52 L16 -45 L0 -42 Z;M0 -52 L18 -48 L0 -42 Z" dur="1.2s" repeatCount="indefinite"/></path>
    </g>

    <!-- the granite hill the track climbs, with boulders -->
    <path class="js-granite" d="M260 250 C360 214 440 150 540 126 C600 110 650 112 700 128 C790 156 840 210 940 250 Z"/>
    <path class="js-granite-line" d="M470 170 C520 150 560 140 600 140 M650 150 C700 160 730 178 760 200"/>
    <!-- granite outcrop on the hills behind the track, with a leopard resting on top -->
    <g transform="translate(-292 4)">
    <g class="js-boulders">
      <path d="M560 128 C556 100 580 84 604 88 C626 92 636 112 630 128 Z"/>
      <path d="M616 126 C618 106 640 98 656 106 C668 112 668 124 664 130 Z"/>
    </g>
    <!-- leopard resting on the top boulder, tail swinging -->
    <g class="js-leopard" transform="translate(588 89)">
      <path class="js-tail" d="M-32 -7 C-44 -7 -48 4 -45 18 C-44 22 -40 23 -40 19"><animateTransform attributeName="transform" type="rotate" values="-6 -32 -7;9 -32 -7;-6 -32 -7" dur="2.8s" repeatCount="indefinite"/></path>
      <ellipse cx="-22" cy="-8" rx="12" ry="8.5" class="js-leo"/>
      <path class="js-leo" d="M-33 -1 C-35 -11 -26 -18 -12 -19 C2 -20 16 -18 24 -14 C29 -12 31 -8 30 -3 L30 0 L-30 0 Z"/>
      <path class="js-leo" d="M16 -3 L42 -3 C45 -3 46 0 44 1.5 L16 1.5 Z"/>
      <path class="js-leo" d="M10 0 L36 0 C39 0 40 2.4 38 3.5 L10 3.5 Z"/>
      <path class="js-leo" d="M22 -14 C22 -22 28 -28 36 -28 C43 -28 48 -24 48 -19 C48 -15 44 -12 38 -12 C32 -12 25 -11 22 -14 Z"/>
      <path class="js-leo" d="M30 -26 L31 -32 L35 -27 Z M37 -28 L40 -33 L42 -27 Z"/>
      <path class="js-leo-muzzle" d="M44 -19 C46 -19 48 -18 48 -17 C47 -15 44 -15 43 -16"/>
      <circle cx="41" cy="-22" r="1.1" class="js-leo-eye"><animate attributeName="r" values="1.1;1.1;.2;1.1" keyTimes="0;.93;.96;1" dur="4s" repeatCount="indefinite"/></circle>
      <circle cx="48" cy="-19" r="1" class="js-leo-eye"/>
      <g class="js-rosettes">
        <?php foreach ([[-24, -11], [-18, -5], [-12, -14], [-6, -8], [0, -15], [4, -6], [10, -12], [16, -7], [-28, -4], [20, -13], [30, -22], [34, -17]] as [$rx, $ry]): ?><circle cx="<?= $rx ?>" cy="<?= $ry ?>" r="1.7"/><?php endforeach; ?>
      </g>
    </g>
    </g>
    </g>

    <!-- trees -->
    <?php foreach ([[330, 214, 1.1], [860, 222, 1], [1110, 216, 1.3], [1160, 220, .9], [420, 196, .8]] as [$tx, $ty, $sc]): ?>
    <use href="#js-tree" transform="translate(<?= $tx ?> <?= $ty ?>) scale(<?= $sc ?>)"/>
    <?php endforeach; ?>

    <!-- the track -->
    <use href="#js-track" class="js-road"/>
    <use href="#js-track" class="js-ruts" transform="translate(0 7)"/>

    <!-- shallow water crossing on the track -->
    <path class="js-water js-ford" d="M138 196 C170 192 230 190 300 194 C312 196 314 206 302 210 C260 220 180 222 140 218 C126 214 126 200 138 196 Z"/>
    <path class="js-ripple" d="M160 206 q8 -4 16 0 t16 0 M226 200 q8 -4 16 0 t16 0 M250 212 q6 -3 12 0">
      <animate attributeName="opacity" values=".4;1;.4" dur="2.4s" repeatCount="indefinite"/>
    </path>

    <!-- convoy: same jeep, three liveries, following the track and tilting with the slope -->
    <?php foreach ([['#5E6B47', '#F3E8CF', 0], ['#C9A96E', '#3A2C1C', -8], ['#2F3B2C', '#F3E8CF', -16]] as [$body, $label, $begin]): ?>
    <g style="--jt-body:<?= $body ?>;--jt-label:<?= $label ?>">
      <use href="#js-jeep" transform="scale(.62)"/>
      <g class="js-splash" opacity="0">
        <animate attributeName="opacity" values="0;0;1;1;0;0" keyTimes="0;.198;.206;.282;.29;1" dur="24s" begin="<?= $begin ?>s" repeatCount="indefinite"/>
        <?php foreach ([-36, 36] as $wx): ?>
        <g transform="translate(<?= $wx ?> 0)">
          <?php foreach ([[-10, -16, 0], [-4, -22, .15], [6, -18, .3], [12, -12, .1]] as [$dx, $dy, $dd]): ?>
          <circle r="2" cx="0" cy="0"><animate attributeName="cx" values="0;<?= $dx ?>" dur=".55s" begin="<?= $dd ?>s" repeatCount="indefinite"/><animate attributeName="cy" values="-2;<?= $dy ?>;-2" dur=".55s" begin="<?= $dd ?>s" repeatCount="indefinite"/><animate attributeName="opacity" values="1;0" dur=".55s" begin="<?= $dd ?>s" repeatCount="indefinite"/></circle>
          <?php endforeach; ?>
        </g>
        <?php endforeach; ?>
        <path class="js-wake" d="M-60 2 q10 -4 20 0 t20 0 t20 0 t20 0 t20 0 t20 0"/>
      </g>
      <animateMotion dur="24s" begin="<?= $begin ?>s" repeatCount="indefinite" rotate="auto"><mpath href="#js-track"/></animateMotion>
    </g>
    <?php endforeach; ?>

    <!-- foreground ground -->
    <path class="js-ground" d="M0 236 C200 230 400 238 600 234 C800 230 1000 238 1200 234 V250 H0 Z"/>
  </svg>
</div>
