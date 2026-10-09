<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $page['title'] ?? 'About Us', 'kicker' => $page['kicker'] ?? 'Our story', 'intro' => $page['intro'] ?? '', 'image' => ($page['image_id'] ?? null) ?: ((int) setting('img_about') ?: null), 'fallback' => 'safari-guests', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container narrow prose"><?= $page['body'] ?? '' ?></div>
</section>
<?php if ($team): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head section-head-center reveal">
      <span class="kicker"><?= e(setting('team_kicker')) ?></span>
      <h2 class="display-2"><?= e(setting('team_heading')) ?></h2>
    </div>
    <div class="grid grid-3 team-grid">
      <?php foreach ($team as $t): ?>
      <article class="team-card reveal">
        <div class="team-photo"><?= picture($t['image_id'] ? (int) $t['image_id'] : null, $t['name'], ['sizes' => '(min-width: 1024px) 33vw, 100vw'], 'safari1') ?></div>
        <h3><?= e($t['name']) ?></h3>
        <p class="mono"><?= e($t['role']) ?></p>
        <?= paragraphs($t['bio']) ?>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?= View::partial('site/partials/cta') ?>
