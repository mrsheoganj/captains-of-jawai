<?php /** @var array $s */ ?>
<a class="exp-card reveal" href="/safaris/<?= e($s['slug']) ?>/" data-tilt>
  <div class="exp-media">
    <?= picture($s['image_id'] ? (int) $s['image_id'] : null, $s['title'], ['sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw'], 'leopard1') ?>
    <?php if ($s['category']): ?><span class="pill"><?= e($s['category']) ?></span><?php endif; ?>
  </div>
  <div class="exp-body">
    <h3><?= e($s['title']) ?></h3>
    <p><?= e(excerpt($s['excerpt'], 130)) ?></p>
    <div class="exp-meta">
      <?php if ($s['duration']): ?><span><?= icon('clock', 15) ?> <?= e($s['duration']) ?></span><?php endif; ?>
      <span class="exp-arrow"><?= icon('arrow-up-right', 18) ?></span>
    </div>
  </div>
</a>
