<section class="plain-hero">
  <div class="container narrow">
    <nav class="crumbs crumbs-dark" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span><span aria-current="page">Photo Credits</span></nav>
    <span class="kicker">Thank you</span>
    <h1 class="display-1">Photo credits</h1>
    <p class="lead-muted">Some photographs on this website are shared by their creators under Creative Commons or public-domain licences. We are grateful to every photographer listed below.</p>
  </div>
</section>
<section class="section section-tight">
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
