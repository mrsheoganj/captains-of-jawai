<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $page['title'] ?? 'About Us', 'kicker' => $page['kicker'] ?? 'Our story', 'intro' => $page['intro'] ?? '', 'image' => ($page['image_id'] ?? null) ?: ((int) setting('img_about') ?: null), 'fallback' => 'safari-guests', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container narrow prose"><?= $page['body'] ?? '' ?></div>
</section>
<?= View::partial('site/partials/cta') ?>
