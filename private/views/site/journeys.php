<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Curated Journeys', 'kicker' => 'Multi-day itineraries', 'intro' => 'Starting points for your own journey — every day, drive and stay can be shaped around you.', 'image' => (int) setting('img_journeys') ?: ($items[0]['image_id'] ?? null), 'fallback' => 'ridge-sighting', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container stack-lg">
    <?php foreach ($items as $n => $j): ?>
    <article class="journey-row reveal<?= $n % 2 ? ' is-flipped' : '' ?>">
      <a class="journey-row-media" href="/journeys/<?= e($j['slug']) ?>/"><?= picture($j['image_id'] ? (int) $j['image_id'] : null, $j['title'], ['sizes' => '(min-width: 1024px) 55vw, 100vw'], 'landscape1') ?></a>
      <div class="journey-row-body">
        <span class="mono"><?= e($j['duration_label']) ?></span>
        <h2 class="display-3"><a href="/journeys/<?= e($j['slug']) ?>/"><?= e($j['title']) ?></a></h2>
        <p class="lead-muted"><?= e($j['tagline']) ?></p>
        <p><?= e($j['excerpt']) ?></p>
        <a class="btn btn-outline" href="/journeys/<?= e($j['slug']) ?>/">View day-by-day <?= icon('arrow-right', 16) ?></a>
      </div>
    </article>
    <?php endforeach; ?>
    <?php if (!$items): ?><p class="empty">Journeys will be published here soon.</p><?php endif; ?>
  </div>
</section>
<?= View::partial('site/partials/cta') ?>
