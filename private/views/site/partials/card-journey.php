<?php /** @var array $j */ ?>
<a class="journey-card reveal" href="/journeys/<?= e($j['slug']) ?>/" data-tilt>
  <div class="journey-media"><?= picture($j['image_id'] ? (int) $j['image_id'] : null, $j['title'], ['sizes' => '(min-width: 1024px) 33vw, 85vw'], 'landscape1') ?><span class="pill"><?= e($j['duration_label']) ?></span></div>
  <div class="journey-body">
    <h3><?= e($j['title']) ?></h3>
    <p><?= e(excerpt($j['excerpt'], 140)) ?></p>
    <span class="link-arrow">View itinerary <?= icon('arrow-right', 16) ?></span>
  </div>
</a>
