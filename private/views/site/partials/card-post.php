<?php /** @var array $p */ ?>
<article class="post-card reveal">
  <a href="/journal/<?= e($p['slug']) ?>/" class="post-media"><?= picture($p['image_id'] ? (int) $p['image_id'] : null, $p['title'], ['sizes' => '(min-width: 1024px) 33vw, 100vw'], 'landscape1') ?></a>
  <div class="post-body">
    <div class="meta-line"><span><?= e($p['category'] ?: 'Journal') ?></span><span><?= e(fmt_date($p['published_at'])) ?></span><?php if ($p['reading_time']): ?><span><?= (int) $p['reading_time'] ?> min read</span><?php endif; ?></div>
    <h3><a href="/journal/<?= e($p['slug']) ?>/"><?= e($p['title']) ?></a></h3>
    <p><?= e(excerpt($p['excerpt'], 130)) ?></p>
  </div>
</article>
