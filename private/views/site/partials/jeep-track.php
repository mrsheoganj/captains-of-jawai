<?php
/** Decorative strip: an open-top Gypsy driving across the granite country (moves only while visible; parked for reduced motion). */
$jeep = static function (string $name): string {
    $spokes = '<path d="M0 -8V8M-8 0H8M-5.7 -5.7L5.7 5.7M-5.7 5.7L5.7 -5.7"/>';
    $wheel = fn ($x) => '<g transform="translate(' . $x . ' 86)"><circle r="18" class="jt-tyre"/><circle r="17" class="jt-tread"/><g class="jt-wheel"><circle r="9" class="jt-rim"/>' . $spokes . '<circle r="2.6" class="jt-hub"/></g></g>';
    return '<svg class="jt-jeep" viewBox="0 0 220 110" role="img" aria-label="' . e($name) . ' open 4x4 safari gypsy">'
        . '<g class="jt-body">'
        . '<ellipse cx="110" cy="106" rx="96" ry="4" class="jt-shadow"/>'
        // roll cage and raised safari seats
        . '<path class="jt-cage" d="M28 52 V18 H100 V52 M64 52 V18 M28 30 H100"/>'
        . '<rect x="32" y="34" width="26" height="16" rx="4" class="jt-seat"/><rect x="66" y="34" width="26" height="16" rx="4" class="jt-seat"/><rect x="100" y="38" width="15" height="14" rx="3" class="jt-seat"/>'
        // spare wheel on the tailgate
        . '<circle cx="15" cy="62" r="11" class="jt-tyre"/><circle cx="15" cy="62" r="5" class="jt-rim"/>'
        // body with wheel arches, hood and grille
        . '<path class="jt-panel" d="M20 82 V50 H118 L125 57 H186 Q197 57 199 64 V82 H188 A21 21 0 0 0 148 82 H72 A21 21 0 0 0 32 82 Z"/>'
        . '<path class="jt-line" d="M20 62 H199 M125 57 V80 M150 57 L152 64"/>'
        . '<text x="64" y="74" class="jt-name">' . e($name) . '</text>'
        // folded-up windscreen
        . '<path class="jt-glass" d="M124 57 L117 30 H130 L137 57 Z"/><path class="jt-cage jt-thin" d="M124 57 L117 30 H130 L137 57"/>'
        . '<circle cx="194" cy="66" r="3.2" class="jt-lamp"/><rect x="196" y="76" width="11" height="4" rx="1.5" class="jt-bumper"/>'
        // tracker standing at the back with binoculars
        . '<g class="jt-tracker"><circle cx="46" cy="10" r="5"/><path d="M41 6 Q46 1 52 6" class="jt-hat"/><path class="jt-shirt" d="M40 35 L41 19 Q46 14.5 51 19 L52 35 Z"/><path d="M48 21 L55 15 M55 15 l3 -1"/></g>'
        . $wheel(52) . $wheel(168)
        . '</g>'
        . '<g class="jt-dust"><circle cx="18" cy="96" r="5"/><circle cx="8" cy="92" r="7"/><circle cx="-4" cy="95" r="5"/></g>'
        . '</svg>';
};
?>
<div class="jeep-track" aria-hidden="false" data-jeep-track>
  <svg class="jt-scene" viewBox="0 0 1200 120" preserveAspectRatio="none" aria-hidden="true">
    <path class="jt-hills" d="M0 92 C60 70 110 58 170 74 C220 50 260 38 320 66 C380 52 430 70 470 80 C540 46 600 34 660 64 C720 56 760 72 800 82 C860 52 930 40 990 70 C1040 58 1100 64 1140 78 L1200 84 V120 H0 Z"/>
    <path class="jt-road" d="M0 108 H1200"/>
    <path class="jt-ruts" d="M0 114 H1200"/>
  </svg>
  <div class="jt-mover"><?= $jeep((string) setting('site_name')) ?></div>
</div>
