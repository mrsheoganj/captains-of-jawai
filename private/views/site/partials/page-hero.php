<?php
/** Inner-page hero. Vars: $title, $kicker, $intro, $image (media id|null), $fallback, $crumbs */
\App\Core\View::share('headerOverlay', true);
$fallback ??= 'landscape1';
?>
<section class="page-hero">
  <div class="page-hero-bg"><?= picture($image ?? null, '', ['sizes' => '100vw', 'loading' => 'eager', 'fetchpriority' => 'high'], $fallback) ?></div>
  <div class="container page-hero-inner">
    <?php if (!empty($crumbs)): ?>
    <nav class="crumbs" aria-label="Breadcrumb"><a href="/">Home</a><?php foreach ($crumbs as $i => [$l, $u]): ?><span>/</span><?php if ($i === count($crumbs) - 1): ?><span aria-current="page"><?= e($l) ?></span><?php else: ?><a href="<?= e($u) ?>"><?= e($l) ?></a><?php endif; ?><?php endforeach; ?></nav>
    <?php endif; ?>
    <?php if (!empty($kicker)): ?><span class="kicker kicker-light"><?= e($kicker) ?></span><?php endif; ?>
    <h1><?= e($title) ?></h1>
    <?php if (!empty($intro)): ?><p class="lead"><?= e($intro) ?></p><?php endif; ?>
  </div>
</section>
