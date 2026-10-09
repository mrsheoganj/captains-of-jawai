<?php /** @var array $j */ ?>
<a class="journey-card reveal" href="/journeys/<?= e($j['slug']) ?>/">
  <div class="journey-media"><?= picture($j['image_id'] ? (int) $j['image_id'] : null, $j['title'], ['sizes' => '(min-width: 1024px) 50vw, 100vw'], 'landscape1') ?></div>
  <div class="journey-body">
    <span class="mono"><?= e($j['duration_label']) ?></span>
    <h3><?= e($j['title']) ?></h3>
    <p><?= e(excerpt($j['excerpt'], 150)) ?></p>
    <span class="link-arrow">View itinerary <?= icon('arrow-right', 16) ?></span>
  </div>
</a>
