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
      <!-- animals (drawn large, scaled down in the scene) -->
      <pattern id="js-ros" width="9" height="8" patternUnits="userSpaceOnUse" patternTransform="rotate(-8)">
        <path d="M2.2 1.2 a2 1.7 0 1 0 0.1 0 M6.6 5.2 a1.8 1.5 0 1 0 0.1 0" fill="none" stroke="#2F241A" stroke-width="1" stroke-dasharray="2 1.2"/>
        <circle cx="2.3" cy="2.9" r=".55" fill="#9A6A2A"/><circle cx="6.7" cy="6.7" r=".5" fill="#9A6A2A"/>
      </pattern>
      <path id="js-leo-shape" d="M20 52 C20 42 28 34 42 31 C56 29 70 33 86 33 C100 33 112 26 122 21 C128 18 133 15 138 12 C139 7 141 4 145 4 C147 4 149 5 150 7 C154 5 159 5 163 6 C164 3 167 2 169 3 C171 4 171 7 170 9 C174 11 177 14 179 17 C181 18 183 19 183 21 C183 23 181 24 179 24 C177 26 173 27 170 27 C166 29 163 33 162 38 C162 43 164 47 168 49 L194 50 C199 50 201 53 199 55.5 C197 57.5 192 57.5 186 57.5 L156 57.5 C150 57.5 144 56 138 55 L100 56 C86 58 70 60 54 60 C46 60 38 60 32 59 C26 58 22 56 20 54 C16 57 14 63 13 71 C12 81 14 90 19 95 C22 98 25 97 24 94 C20 88 19 80 20 72 C21 64 23 58 24 55 Z"/>
      <path id="js-croc-shape" d="M0 30 C6 27 16 26 30 26 L40 24 C48 22 56 21 62 22 L66 18 L70 22 L76 17 L80 21 L86 16 L90 20 L96 16 L100 20 L106 17 L110 21 L116 18 L120 22 L126 20 L130 23 C150 25 175 27 200 30 C214 31 224 32 236 33 C224 35 210 35 196 35 C180 36 160 36 146 36 L144 42 L136 43 L138 36 C120 37 100 37 84 36 L82 42 L74 43 L76 36 C62 36 50 35 40 34 C30 34 18 34 8 33 C3 33 0 32 0 30 Z"/>
      <g id="js-fl-stand"><path d="M20 92 L21 60 L23 60 L22 92 Z M24 60 L30 74 L22 82 L23 84 L33 75 L26 60 Z" class="js-fl-leg2"/><path d="M12 50 C12 41 22 36 34 38 C42 39 46 44 44 50 C40 58 26 61 18 58 C14 56 12 54 12 50 Z M38 41 C40 34 36 28 32 22 C27 15 28 6 35 4 C41 2 46 6 46 10 L51 14 L49 16 L44 13 C41 10 37 9 35 12 C33 16 37 21 41 28 C45 35 45 41 42 45 Z" class="js-fl-pink"/><path d="M46 10 L51 14 L49 16 L46 14 Z" class="js-fl-tip"/></g>
      <g id="js-fl-feed"><path d="M20 92 L21 60 L23 60 L22 92 Z M28 92 L27 60 L29 60 L30 92 Z" class="js-fl-leg2"/><path d="M12 50 C12 41 22 36 34 38 C42 39 46 44 44 50 C40 58 26 61 18 58 C14 56 12 54 12 50 Z M40 44 C46 46 50 52 52 60 C54 68 56 76 60 82 C62 86 60 90 57 88 L54 84 L57 82 C53 76 50 68 48 60 C46 54 42 50 38 48 Z" class="js-fl-pink"/><path d="M57 88 L54 84 L57 82 C59 84 61 86 60 88 Z" class="js-fl-tip"/></g>
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
    <g class="js-croc" transform="translate(4 172) scale(.5)">
      <use href="#js-croc-shape" class="js-croc-fill"/>
      <circle cx="44" cy="25" r="1.8" class="js-croc-eye"><animate attributeName="r" values="1.8;1.8;.3;1.8" keyTimes="0;.9;.95;1" dur="5s" repeatCount="indefinite"/></circle>
    </g>
    <?php foreach ([[112, 152, .5, 0], [140, 158, .45, -2.2]] as [$fx, $fy, $fs, $fb]): ?>
    <g transform="translate(<?= $fx ?> <?= $fy ?>) scale(<?= $fs ?>)">
      <use href="#js-fl-stand"><animate attributeName="opacity" values="1;1;0;0;1" keyTimes="0;.5;.52;.85;.87" calcMode="discrete" dur="6s" begin="<?= $fb ?>s" repeatCount="indefinite"/></use>
      <use href="#js-fl-feed" opacity="0"><animate attributeName="opacity" values="0;0;1;1;0" keyTimes="0;.5;.52;.85;.87" calcMode="discrete" dur="6s" begin="<?= $fb ?>s" repeatCount="indefinite"/></use>
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
      <path d="M636 142 C638 120 654 108 672 110 C690 112 698 126 698 142 Z"/>
      <path d="M544 142 C544 118 551 99 566 93.5 C590 89 626 89 645 92.5 C662 96 670 116 672 142 Z"/>
      <path class="js-crack" d="M576 112 C588 107 604 107 614 111 M598 126 l16 -3 M640 104 C646 112 648 122 646 134"/>
      <ellipse cx="600" cy="92.5" rx="44" ry="2.6" class="js-rest-shadow"/>
    </g>
    <!-- leopard resting on the top boulder, tail swinging -->
    <g class="js-leopard" transform="translate(546 63) scale(.5)">
      <g><animateTransform attributeName="transform" type="rotate" values="-4 22 55;5 22 55;-4 22 55" dur="2.8s" repeatCount="indefinite"/>
        <path d="M20 54 C16 57 14 63 13 71 C12 81 14 90 19 95 C22 98 25 97 24 94 C20 88 19 80 20 72 C21 64 23 58 24 55 Z" class="js-leo-coat"/>
      </g>
      <use href="#js-leo-shape" class="js-leo-coat"/>
      <use href="#js-leo-shape" fill="url(#js-ros)"/>
      <path d="M60 57 C80 59 110 58 138 55" class="js-leo-belly"/>
      <use href="#js-leo-shape" class="js-leo-line"/>
      <path d="M38 44 C46 34 60 36 62 50 M150 44 C150 51 153 55 158 56" class="js-leo-detail"/>
      <path d="M176 21 C178 22 181 22 183 21 M147 6 L148.5 8 M166 4 L167 6.5" class="js-leo-detail"/>
      <ellipse cx="172.5" cy="14.5" rx="1.6" ry="1.2" class="js-leo-eye2"><animate attributeName="ry" values="1.2;1.2;.15;1.2" keyTimes="0;.92;.95;1" dur="4.5s" repeatCount="indefinite"/></ellipse>
      <circle cx="182" cy="19.5" r="1.4" class="js-leo-eye2"/>
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
