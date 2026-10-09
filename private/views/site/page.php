<?php use App\Core\View; ?>
<?php if ($item['image_id']): ?>
<?= View::partial('site/partials/page-hero', ['title' => $item['title'], 'kicker' => $item['kicker'], 'intro' => $item['intro'], 'image' => (int) $item['image_id'], 'crumbs' => $seo['breadcrumbs']]) ?>
<?php else: ?>
<section class="plain-hero">
  <div class="container narrow">
    <nav class="crumbs crumbs-dark" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span><span aria-current="page"><?= e($item['title']) ?></span></nav>
    <?php if ($item['kicker']): ?><span class="kicker"><?= e($item['kicker']) ?></span><?php endif; ?>
    <h1 class="display-1"><?= e($item['title']) ?></h1>
    <?php if ($item['intro']): ?><p class="lead-muted"><?= e($item['intro']) ?></p><?php endif; ?>
  </div>
</section>
<?php endif; ?>
<section class="section<?= $item['image_id'] ? '' : ' section-tight' ?>">
  <div class="container narrow prose"><?= $item['body'] ?></div>
</section>
<?php if ($item['show_cta']): ?><?= View::partial('site/partials/cta') ?><?php endif; ?>
