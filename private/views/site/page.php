<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $item['title'], 'kicker' => $item['kicker'], 'intro' => $item['intro'], 'image' => $item['image_id'] ? (int) $item['image_id'] : null, 'fallback' => \App\Core\Media::STOCK[crc32((string) $item['slug']) % count(\App\Core\Media::STOCK)], 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container narrow prose"><?= $item['body'] ?></div>
</section>
<?php if ($item['show_cta']): ?><?= View::partial('site/partials/cta') ?><?php endif; ?>
