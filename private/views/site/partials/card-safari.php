<?php /** @var array $s */ ?>
<article class="exp-card reveal">
  <div class="exp-media">
    <?= picture($s['image_id'] ? (int) $s['image_id'] : null, $s['title'], ['sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw'], 'leopard1') ?>
    <?php if ($s['category']): ?><span class="pill"><?= e($s['category']) ?></span><?php endif; ?>
  </div>
  <div class="exp-body">
    <h3><a class="exp-link" href="/safaris/<?= e($s['slug']) ?>/"><?= e($s['title']) ?></a></h3>
    <p><?= e(excerpt($s['excerpt'], 130)) ?></p>
    <?php if ($s['duration'] || $s['group_size']): ?><p class="exp-facts"><?= e(implode(' · ', array_filter([$s['duration'], $s['group_size']]))) ?></p><?php endif; ?>
    <div class="exp-meta">
      <a class="exp-book" href="/book-a-safari/?safari=<?= e(rawurlencode($s['title'])) ?>">Check availability</a>
      <span class="exp-more">View details</span>
    </div>
  </div>
</article>
