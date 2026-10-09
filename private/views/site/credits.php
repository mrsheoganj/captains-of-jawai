<?= \App\Core\View::partial('site/partials/page-hero', ['title' => 'Photo credits', 'kicker' => 'Thank you', 'intro' => 'Photographs shared by their creators under Creative Commons or public-domain licences. We are grateful to every photographer listed below.', 'image' => (int) setting('img_404') ?: null, 'fallback' => 'granite-boulders', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="credits-grid">
      <?php foreach ($items as $m): ?>
      <figure class="credit-item">
        <?= picture($m, $m['alt_text'], ['sizes' => '200px']) ?>
        <figcaption>
          <strong><?= e($m['alt_text'] ?: $m['filename']) ?></strong>
          <span><?= e($m['credit']) ?></span>
          <span>
            <?php if ($m['license_url']): ?><a href="<?= e($m['license_url']) ?>" target="_blank" rel="noopener license"><?= e($m['license']) ?></a><?php else: ?><?= e($m['license']) ?><?php endif; ?>
            <?php if ($m['source_url']): ?> · <a href="<?= e($m['source_url']) ?>" target="_blank" rel="noopener">Source</a><?php endif; ?>
          </span>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
    <?php else: ?><p class="empty">All photographs on this website are our own.</p><?php endif; ?>
  </div>
</section>
