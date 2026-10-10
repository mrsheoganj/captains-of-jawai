<?php
use App\Core\Settings;
use App\Core\View;

View::share('bodyClass', 'page-home');
$heroIds = $heroIds ?: [null];
$fallbacks = ['leopard1', 'landscape1', 'leopard2', 'bird1'];
$stats = [];
foreach (Settings::lines('stats') as $line) {
    $parts = array_map('trim', explode('|', $line, 2));
    $stats[] = [$parts[0], $parts[1] ?? ''];
}
?>
<!-- 1. Full-width slider -->
<section class="slider" data-hero aria-roledescription="carousel" aria-label="Jawai">
  <div class="hero-slides">
    <?php foreach ($heroIds as $n => $id): $cap = $id && isset($heroSlides[$id]) ? ($heroSlides[$id]['caption'] ?: '') : ''; ?>
    <div class="hero-slide<?= $n === 0 ? ' is-active' : '' ?>" aria-roledescription="slide" aria-label="<?= $n + 1 ?> of <?= count($heroIds) ?>">
      <?= picture($id, $id && isset($heroSlides[$id]) ? (string) $heroSlides[$id]['alt_text'] : 'Jawai, Rajasthan', ['sizes' => '100vw', 'loading' => $n === 0 ? 'eager' : 'lazy', 'fetchpriority' => $n === 0 ? 'high' : 'low'], $fallbacks[$n % 4]) ?>
      <?php if ($cap !== ''): ?><p class="slide-caption"><?= e($cap) ?></p><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="container slider-content">
    <span class="kicker" data-anim="fade"><?= e(setting('hero_kicker')) ?></span>
    <h1 class="hero-title" data-anim="fade" style="--d:.15s"><?= e(setting('hero_title')) ?></h1>
    <p class="hero-sub" data-anim="fade" style="--d:.3s"><?= e(setting('hero_subtitle')) ?></p>
    <div class="btn-row" data-anim="fade" style="--d:.45s">
      <a class="btn btn-primary btn-lg" href="#booking"><?= e(setting('hero_cta_primary')) ?></a>
      <a class="btn btn-ghost-light btn-lg" href="#expeditions"><?= e(setting('hero_cta_secondary')) ?></a>
    </div>
  </div>
  <?php if (count($heroIds) > 1): ?>
  <div class="slider-nav">
    <button type="button" class="slider-arrow" data-hero-prev aria-label="Previous photo"><?= icon('chevron-left', 22) ?></button>
    <div class="hero-dots" role="tablist" aria-label="Choose photo">
      <?php foreach ($heroIds as $n => $id): ?><button type="button" class="<?= $n === 0 ? 'is-active' : '' ?>" aria-label="Show photo <?= $n + 1 ?>" data-hero-dot="<?= $n ?>"></button><?php endforeach; ?>
    </div>
    <button type="button" class="slider-arrow" data-hero-next aria-label="Next photo"><?= icon('chevron-right', 22) ?></button>
  </div>
  <?php endif; ?>
</section>

<!-- 2. Distance map -->
<?php if (Settings::bool('distances_enabled')): ?><?= View::partial('site/partials/distances') ?><?php endif; ?>

<!-- Ticker -->
<?php if (Settings::bool('ticker_enabled') && ($ticker = Settings::lines('ticker_items'))): ?>
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php // Two identical halves (each long enough to fill a wide screen), so the loop is seamless.
    $reps = max(2, (int) ceil(12 / count($ticker)));
    for ($half = 0; $half < 2; $half++): ?><div class="ticker-group"><?php for ($r = 0; $r < $reps; $r++): foreach ($ticker as $t): ?><span><?= e($t) ?></span><i>·</i><?php endforeach; endfor; ?></div><?php endfor; ?>
  </div>
</div>
<?php endif; ?>

<!-- 3. Jawai details -->
<section class="section" id="intro">
  <div class="container split split-60">
    <div class="split-text">
      <span class="kicker reveal"><?= e(setting('intro_kicker')) ?></span>
      <h2 class="display-2 reveal"><?= e(setting('intro_heading')) ?></h2>
      <div class="prose-lg reveal"><?= paragraphs(setting('intro_body')) ?></div>
      <a class="link-arrow reveal" href="/jawai/">Read our guide to Jawai</a>
    </div>
    <div class="split-media">
      <div class="framed reveal-img" data-parallax="0.06"><?= picture((int) setting('intro_image') ?: null, 'Leopard on the granite hills of Jawai', ['sizes' => '(min-width: 1024px) 45vw, 100vw'], 'leopard-portrait') ?></div>
      <p class="photo-caption reveal">Granite country near Bera, Pali district</p>
    </div>
  </div>
  <?php if ($stats): ?>
  <div class="container">
    <dl class="stats" data-stagger>
      <?php foreach ($stats as [$v, $l]): ?><div class="reveal"><dt><?= e($v) ?></dt><dd><?= e($l) ?></dd></div><?php endforeach; ?>
    </dl>
  </div>
  <?php endif; ?>
</section>

<!-- 4. Signature expeditions -->
<?php if ($safaris): ?>
<section class="section section-alt" id="expeditions">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal"><?= e(setting('safaris_kicker')) ?></span>
        <h2 class="display-2 reveal"><?= e(setting('safaris_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/safaris/">All safaris</a>
    </div>
    <div class="grid grid-<?= count($safaris) === 4 || count($safaris) === 2 ? 2 : 3 ?>" data-stagger>
      <?php foreach ($safaris as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Jawai in pictures -->
<?php
$stripIds = array_values(array_filter(array_map('intval', explode(',', (string) setting('jawai_images')))));
$stripFallback = ['landscape1', 'granite-boulders', 'flamingo-flock', 'sunset-valley', 'lake-reflection'];
if (Settings::bool('jawai_images_enabled')): ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Jawai</span>
        <h2 class="display-2 reveal"><?= e(setting('jawai_images_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/jawai/">About Jawai</a>
    </div>
    <div class="photo-strip">
      <?php for ($n = 0; $n < 5; $n++): $mid = $stripIds[$n] ?? null; ?>
      <figure class="reveal-img"><?= picture($mid, 'Jawai, Rajasthan', ['sizes' => $n === 0 ? '(min-width: 1024px) 50vw, 100vw' : '(min-width: 1024px) 25vw, 50vw'], $stripFallback[$n]) ?></figure>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Jawai Dam through the seasons -->
<?php if (Settings::bool('seasons_enabled')): ?><?= View::partial('site/partials/seasons') ?><?php endif; ?>

<!-- Booking -->
<?php if (Settings::bool('booking_enabled')):
$details = [];
foreach (Settings::lines('booking_details') as $line) {
    [$k, $v] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
    $details[] = [$k, $v];
}
?>
<section class="section section-alt booking" id="booking">
  <div class="container booking-grid">
    <div class="booking-info">
      <span class="kicker reveal"><?= e(setting('booking_kicker')) ?></span>
      <h2 class="display-2 reveal"><?= e(setting('booking_heading')) ?></h2>
      <p class="lead-muted reveal"><?= e(setting('booking_intro')) ?></p>
      <?php if ($details): ?>
      <dl class="booking-details reveal">
        <?php foreach ($details as [$k, $v]): ?><div><dt><?= e($k) ?></dt><dd><?= e($v) ?></dd></div><?php endforeach; ?>
      </dl>
      <?php endif; ?>
      <div class="booking-photo reveal-img"><?= picture((int) setting('booking_image') ?: null, 'Open safari jeep in Jawai', ['sizes' => '(min-width: 1024px) 40vw, 100vw'], 'safari-jeep') ?></div>
    </div>
    <div class="booking-card reveal">
      <?= View::partial('site/partials/booking-tabs', ['id' => 'home']) ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Gallery -->
<?php if (Settings::bool('home_gallery_enabled') && $gallery): ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Gallery</span>
        <h2 class="display-2 reveal"><?= e(setting('home_gallery_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/gallery/">Full gallery</a>
    </div>
    <div class="home-gallery masonry" data-lightbox>
      <?php foreach ($gallery as $m): ?>
      <figure class="masonry-item reveal">
        <a href="<?= e(media_url($m, 'lg')) ?>" data-caption="<?= e($m['caption'] ?: $m['alt_text']) ?>"><?= picture($m, $m['alt_text'], ['sizes' => '(min-width: 1024px) 25vw, 50vw']) ?></a>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<div class="lightbox" hidden data-lightbox-view>
  <button type="button" class="lb-close" aria-label="Close" data-lb-close><?= icon('close', 28) ?></button>
  <button type="button" class="lb-prev" aria-label="Previous" data-lb-prev><?= icon('chevron-left', 32) ?></button>
  <figure><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt=""><figcaption></figcaption></figure>
  <button type="button" class="lb-next" aria-label="Next" data-lb-next><?= icon('chevron-right', 32) ?></button>
</div>
<?php endif; ?>

<!-- Jawai guide -->
<?php if (!empty($guide)): ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">A guide to Jawai</span>
        <h2 class="display-2 reveal">Before you come</h2>
      </div>
      <a class="link-arrow reveal" href="/jawai/">About Jawai</a>
    </div>
    <div class="guide-cards" data-stagger>
      <?php foreach ($guide as $n => $g): ?>
      <a class="guide-card reveal" href="/<?= e($g['slug']) ?>/">
        <span class="guide-photo"><?= picture($g['image_id'] ? (int) $g['image_id'] : null, $g['label'], ['sizes' => '(min-width: 1024px) 25vw, 50vw'], \App\Core\JawaiGuide::IMAGES[$g['slug']] ?? 'landscape1') ?><span class="guide-num"><?= sprintf('%02d', $n + 1) ?></span></span>
        <span class="guide-body">
          <strong><?= e($g['label']) ?></strong>
          <span class="guide-intro"><?= e(excerpt($g['intro'], 95)) ?></span>
          <span class="guide-more">Read</span>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. Coexistence feature -->
<?php if (Settings::bool('coexist_enabled')): ?>
<section class="section feature">
  <div class="container feature-grid">
    <div class="feature-media reveal-img" data-parallax="0.08"><?= picture((int) setting('coexist_image') ?: null, 'The granite hills of Jawai at dusk', ['sizes' => '(min-width: 1024px) 60vw, 100vw'], 'granite-dome-dusk') ?></div>
    <div class="feature-card reveal" style="--d:.15s">
      <span class="kicker kicker-crimson"><?= e(setting('coexist_kicker')) ?></span>
      <h2 class="display-2"><?= e(setting('coexist_heading')) ?></h2>
      <?= paragraphs(setting('coexist_body')) ?>
      <?php if (setting('coexist_link')): ?><a class="link-arrow" href="<?= e(setting('coexist_link')) ?>">Read about Jawai culture</a><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Guest words (only real testimonials entered in admin) -->
<?php if (Settings::bool('testimonials_enabled') && $testimonials): ?>
<section class="section">
  <div class="container narrow">
    <div class="quotes" data-quotes>
      <?php foreach ($testimonials as $n => $q): ?>
      <figure class="quote<?= $n === 0 ? ' is-active' : '' ?>">
               <blockquote><?= e($q['quote']) ?></blockquote>
        <figcaption><strong><?= e($q['author']) ?></strong><?php if ($q['origin']): ?> · <?= e($q['origin']) ?><?php endif; ?><?php if ($q['source']): ?> <span class="mono">via <?= e($q['source']) ?></span><?php endif; ?></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>



