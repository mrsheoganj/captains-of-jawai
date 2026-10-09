<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $item['title'], 'kicker' => $item['category'], 'intro' => $item['tagline'], 'image' => $item['image_id'] ? (int) $item['image_id'] : null, 'fallback' => 'leopard1', 'crumbs' => $seo['breadcrumbs']]) ?>
<div class="fact-strip">
  <div class="container fact-grid">
    <?php foreach ([['clock', 'Duration', $item['duration']], ['sun', 'Timings', $item['timing']], ['calendar', 'Best season', $item['best_season']], ['users', 'Group', $item['group_size']]] as [$ic, $label, $val]): if (!$val) continue; ?>
    <div class="fact"><?= icon($ic, 20) ?><div><span class="mono"><?= e($label) ?></span><strong><?= e($val) ?></strong></div></div>
    <?php endforeach; ?>
  </div>
</div>
<section class="section">
  <div class="container detail-grid">
    <article class="prose">
      <p class="lead"><?= e($item['excerpt']) ?></p>
      <?= $item['body'] ?>
    </article>
    <aside class="detail-aside">
      <?php if ($hl = lines($item['highlights'])): ?>
      <div class="aside-card">
        <h3>Highlights</h3>
        <ul class="checks"><?php foreach ($hl as $h): ?><li><?= icon('check', 16) ?><?= e($h) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>
      <div class="aside-card aside-cta">
        <h3>Inquire about this expedition</h3>
        <p>Share your dates and a Captain will reply within 12 hours. No payment is taken online.</p>
        <a class="btn btn-primary btn-block" href="/plan-your-journey/?safari=<?= e(rawurlencode($item['title'])) ?>">Plan this journey <?= icon('arrow-right', 16) ?></a>
        <?php if (whatsapp_link()): ?><a class="btn btn-outline btn-block" href="<?= e(whatsapp_link('Hello, I am interested in the ' . $item['title'] . '.')) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 16) ?> Ask on WhatsApp</a><?php endif; ?>
      </div>
    </aside>
  </div>
</section>
<?php if ($others): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal"><h2 class="display-3">Pair it with</h2></div>
    <div class="grid grid-3"><?php foreach ($others as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?></div>
  </div>
</section>
<?php endif; ?>
<?= View::partial('site/partials/cta') ?>
