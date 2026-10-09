<?php /** Animated topographic contour lines (decorative). Var: $class */ ?>
<svg class="topo <?= e($class ?? '') ?>" viewBox="0 0 800 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
  <g fill="none" stroke="currentColor" stroke-width="1.1">
    <path pathLength="100" d="M-20 470 C 120 400, 210 520, 360 450 S 600 360, 820 430"/>
    <path pathLength="100" d="M-20 420 C 110 350, 230 470, 370 400 S 610 310, 820 380"/>
    <path pathLength="100" d="M-20 370 C 100 300, 250 420, 380 350 S 620 260, 820 330"/>
    <path pathLength="100" d="M-20 320 C 90 250, 270 370, 390 300 S 630 210, 820 280"/>
    <path pathLength="100" d="M-20 270 C 80 200, 290 320, 400 250 S 640 160, 820 230"/>
    <path pathLength="100" d="M-20 220 C 70 150, 310 270, 410 200 S 650 110, 820 180"/>
    <ellipse pathLength="100" cx="610" cy="150" rx="120" ry="60"/>
    <ellipse pathLength="100" cx="610" cy="150" rx="80" ry="38"/>
    <ellipse pathLength="100" cx="610" cy="150" rx="40" ry="18"/>
    <ellipse pathLength="100" cx="150" cy="520" rx="110" ry="46"/>
    <ellipse pathLength="100" cx="150" cy="520" rx="62" ry="24"/>
  </g>
</svg>
