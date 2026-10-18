<?php
/**
 * "Modern" safari animation (Settings → Homepage → Safari animation style): the painted Safari Journey
 * scene, with the jeep driving over the hill, dust behind the wheels, birds and a glowing sun. Pure SVG
 * (SMIL); the page script pauses it while off screen and keeps it still for reduced motion.
 */
$track = 'M940 346 C943 345.0 950 343.2 960 340 C970 336.8 987 330.5 1000 327 C1013 323.5 1027 321.8 1040 319 C1053 316.2 1067 312.8 1080 310 C1093 307.2 1107 304.5 1120 302 C1133 299.5 1143 296.8 1160 295 C1177 293.2 1203 291.7 1220 291 C1237 290.3 1248 290.5 1260 291 C1272 291.5 1280 292.3 1290 294 C1300 295.7 1305 297.8 1320 301 C1335 304.2 1361 308.5 1380 313 C1399 317.5 1413 322.3 1433 328 C1453 333.7 1472 338.7 1500 347 C1528 355.3 1570 368.0 1600 378 C1630 388.0 1653 397.5 1680 407 C1707 416.5 1733 425.8 1760 435 C1787 444.2 1817 454.5 1840 462 C1863 469.5 1890 477.0 1900 480';
?>
<div class="jeep-track jeep-modern" data-jeep-track>
  <svg class="jm-scene" viewBox="0 0 2172 564" preserveAspectRatio="xMidYMid slice" role="img" aria-label="A safari jeep driving over a sandy hill in Jawai, a leopard resting on a granite rock, the lake and the hills at sunset">
    <defs>
      <path id="jm-track" d="<?= $track ?>"/>
      <radialGradient id="jm-glow" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="#FFC56E" stop-opacity=".75"/><stop offset="1" stop-color="#FFC56E" stop-opacity="0"/></radialGradient>
    </defs>
    <g>
      <animateTransform attributeName="transform" type="scale" values="1;1.025;1" dur="24s" repeatCount="indefinite" additive="sum"/>
      <image href="<?= asset('img/jawai-journey.webp') ?>" width="2172" height="564"/>

      <!-- warm glow breathing around the sun -->
      <circle cx="1830" cy="182" r="150" fill="url(#jm-glow)"><animate attributeName="opacity" values=".35;.8;.35" dur="6s" repeatCount="indefinite"/><animate attributeName="r" values="140;165;140" dur="6s" repeatCount="indefinite"/></circle>

      <!-- birds drifting across the sky -->
      <?php foreach ([[0, 120, 30, 1], [-11, 80, 36, .8], [-21, 150, 33, .9]] as [$b, $y, $dur, $s]): ?>
      <g>
        <animateMotion path="M-80 <?= $y ?> C 600 <?= $y - 30 ?> 1400 <?= $y + 24 ?> 2260 <?= $y - 12 ?>" dur="<?= $dur ?>s" begin="<?= $b ?>s" repeatCount="indefinite"/>
        <path class="jm-bird" transform="scale(<?= $s ?>)" d="M-14 0 Q-7 -8 0 0 Q7 -8 14 0"><animate attributeName="d" values="M-14 0 Q-7 -8 0 0 Q7 -8 14 0;M-14 -4 Q-7 2 0 0 Q7 2 14 -4;M-14 0 Q-7 -8 0 0 Q7 -8 14 0" dur=".8s" repeatCount="indefinite"/></path>
      </g>
      <?php endforeach; ?>

      <!-- the jeep: follows the curve of the hill, bounces a little, kicks up dust; fades in and out at the ends of the loop -->
      <g opacity="0">
        <animate attributeName="opacity" values="0;1;1;0" keyTimes="0;.07;.9;1" dur="11s" repeatCount="indefinite"/>
        <animateMotion dur="11s" repeatCount="indefinite" rotate="auto" keyPoints="0;1" keyTimes="0;1" calcMode="spline" keySplines=".35 0 .65 1"><mpath href="#jm-track"/></animateMotion>
        <g class="jm-dust">
          <?php foreach ([[0, -96, -6], [.35, -110, -12], [.7, -88, -16]] as [$d, $dx, $dy]): ?>
          <circle cx="<?= $dx ?>" cy="<?= $dy ?>" r="8"><animate attributeName="r" values="6;22" dur="1.1s" begin="<?= $d ?>s" repeatCount="indefinite"/><animate attributeName="opacity" values=".7;0" dur="1.1s" begin="<?= $d ?>s" repeatCount="indefinite"/><animate attributeName="cx" values="<?= $dx ?>;<?= $dx - 46 ?>" dur="1.1s" begin="<?= $d ?>s" repeatCount="indefinite"/><animate attributeName="cy" values="<?= $dy ?>;<?= $dy - 16 ?>" dur="1.1s" begin="<?= $d ?>s" repeatCount="indefinite"/></circle>
          <?php endforeach; ?>
        </g>
        <g>
          <animateTransform attributeName="transform" type="translate" values="0 0;0 -2;0 0;0 -1;0 0" dur=".7s" repeatCount="indefinite"/>
          <image href="<?= asset('img/jawai-journey-jeep.webp') ?>" width="215" height="138" x="-106.5" y="-117" transform="rotate(-13.4)"/>
        </g>
      </g>
    </g>
  </svg>
</div>
