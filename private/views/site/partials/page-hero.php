<?php
/** Light inner-page hero: title block on ivory + framed photo. Vars: $title, $kicker, $intro, $image (media id|null), $fallback, $crumbs */
use App\Core\View;

$fallback ??= 'landscape1';
?>
<section class="page-hero">
  <?= View::partial('site/partials/topo', ['class' => 'topo-page']) ?>
  <div class="container page-hero-inner">
    <?php if (!empty($crumbs)): ?>
    <nav class="crumbs" aria-label="Breadcrumb" data-anim="fade"><a href="/">Home</a><?php foreach ($crumbs as $i => [$l, $u]): ?><span>/</span><?php if ($i === count($crumbs) - 1): ?><span aria-current="page"><?= e($l) ?></span><?php else: ?><a href="<?= e($u) ?>"><?= e($l) ?></a><?php endif; ?><?php endforeach; ?></nav>
    <?php endif; ?>
    <div class="page-hero-text">
      <div>
        <?php if (!empty($kicker)): ?><span class="kicker" data-anim="fade"><?= e($kicker) ?></span><?php endif; ?>
        <h1 data-split><?= e($title) ?></h1>
      </div>
      <?php if (!empty($intro)): ?><p class="lead-muted" data-anim="fade" style="--d:.45s"><?= e($intro) ?></p><?php endif; ?>
    </div>
  </div>
  <div class="container">
    <div class="page-hero-media" data-anim="reveal-img">
      <div class="page-hero-frame" data-parallax="0.12"><?= picture($image ?? null, '', ['sizes' => '(min-width: 1280px) 1240px, 100vw', 'loading' => 'eager', 'fetchpriority' => 'high'], $fallback) ?></div>
    </div>
  </div>
</section>
