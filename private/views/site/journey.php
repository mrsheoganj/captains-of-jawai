<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $item['title'], 'kicker' => $item['duration_label'], 'intro' => $item['tagline'], 'image' => $item['image_id'] ? (int) $item['image_id'] : null, 'fallback' => 'landscape1', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container detail-grid">
    <div>
      <p class="lead"><?= e($item['excerpt']) ?></p>
      <?php if ($days): ?>
      <ol class="timeline">
        <?php foreach ($days as $d): ?>
        <li class="reveal">
          <span class="mono"><?= e($d['label']) ?></span>
          <?php if ($d['title'] !== $d['text']): ?><h3><?= e($d['title']) ?></h3><?php endif; ?>
          <p><?= e($d['text']) ?></p>
        </li>
        <?php endforeach; ?>
      </ol>
      <?php endif; ?>
      <div class="prose"><?= $item['body'] ?></div>
    </div>
    <aside class="detail-aside">
      <?php if ($inc = lines($item['inclusions'])): ?>
      <div class="aside-card">
        <h3>Typically included</h3>
        <ul class="checks"><?php foreach ($inc as $h): ?><li><?= icon('check', 16) ?><?= e($h) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>
      <div class="aside-card aside-cta">
        <h3>Make it yours</h3>
        <p>Tell us your dates and we will tailor this itinerary — pace, stays and transfers.</p>
        <a class="btn btn-primary btn-block" href="/plan-your-journey/?safari=<?= e(rawurlencode($item['title'])) ?>">Enquire about this journey</a>
      </div>
    </aside>
  </div>
</section>
<?php if ($others): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal"><h2 class="display-3">Other journeys</h2></div>
    <div class="grid grid-2"><?php foreach ($others as $j): ?><?= View::partial('site/partials/card-journey', ['j' => $j]) ?><?php endforeach; ?></div>
  </div>
</section>
<?php endif; ?>
