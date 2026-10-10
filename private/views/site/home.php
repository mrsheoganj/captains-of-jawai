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
<!-- 1. Hero -->
<section class="hero" data-hero>
  <?= View::partial('site/partials/topo', ['class' => 'topo-hero']) ?>
  <div class="container hero-grid">
    <div class="hero-text">
      <span class="kicker hero-kicker" data-anim="fade"><?= e(setting('hero_kicker')) ?></span>
      <h1 class="hero-title" data-anim="fade" style="--d:.2s"><?= e(setting('hero_title')) ?></h1>
      <p class="hero-sub" data-anim="fade" style="--d:.55s"><?= e(setting('hero_subtitle')) ?></p>
      <div class="btn-row" data-anim="fade" style="--d:.7s">
        <a class="btn btn-primary btn-lg" href="/plan-your-journey/"><?= e(setting('hero_cta_primary')) ?></a>
        <a class="btn btn-outline btn-lg" href="#expeditions"><?= e(setting('hero_cta_secondary')) ?></a>
      </div>
    </div>
    <div class="hero-visual" data-anim="arch">
      <div class="arch">
        <div class="hero-slides">
          <?php foreach ($heroIds as $n => $id): ?>
            <div class="hero-slide<?= $n === 0 ? ' is-active' : '' ?>">
              <?= picture($id, '', ['sizes' => '(min-width: 1024px) 45vw, 100vw', 'loading' => $n === 0 ? 'eager' : 'lazy', 'fetchpriority' => $n === 0 ? 'high' : 'low'], $fallbacks[$n % 4]) ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if (count($heroIds) > 1): ?>
      <div class="hero-dots" role="tablist" aria-label="Hero images">
        <?php foreach ($heroIds as $n => $id): ?><button type="button" class="<?= $n === 0 ? 'is-active' : '' ?>" aria-label="Show image <?= $n + 1 ?>" data-hero-dot="<?= $n ?>"></button><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 2. Field ticker -->
<?php if (Settings::bool('ticker_enabled') && ($ticker = Settings::lines('ticker_items'))): ?>
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php for ($r = 0; $r < 2; $r++): foreach ($ticker as $t): ?><span><?= e($t) ?></span><i>·</i><?php endforeach; endfor; ?>
  </div>
</div>
<?php endif; ?>

<!-- 3. Narrative hook -->
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
    <div class="grid grid-3" data-stagger>
      <?php foreach ($safaris as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
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
    <div class="guide-grid reveal">
      <?php foreach ($guide as $g): ?>
      <a href="/<?= e($g['slug']) ?>/">
        <h3><?= e($g['title']) ?></h3>
        <p><?= e(excerpt($g['intro'], 120)) ?></p>
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
      <?php if (setting('coexist_link')): ?><a class="link-arrow" href="<?= e(setting('coexist_link')) ?>">More about the Rabari</a><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 6. The Captains -->
<?php if (Settings::bool('team_enabled')): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head section-head-center">
      <span class="kicker reveal"><?= e(setting('team_kicker')) ?></span>
      <h2 class="display-2 reveal"><?= e(setting('team_heading')) ?></h2>
      <p class="lead-muted reveal"><?= e(setting('team_body')) ?></p>
    </div>
    <?php if ($team): ?>
      <div class="grid grid-<?= min(4, max(2, count($team))) ?> team-grid" data-stagger>
        <?php foreach (array_slice($team, 0, 4) as $t): ?>
        <article class="team-card reveal">
          <div class="team-photo"><?= picture($t['image_id'] ? (int) $t['image_id'] : null, $t['name'], ['sizes' => '(min-width: 1024px) 25vw, 50vw'], 'safari-guests') ?></div>
          <h3><?= e($t['name']) ?></h3>
          <p class="mono"><?= e($t['role']) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <?php
      $valueImgs = array_values(array_filter(array_map('intval', explode(',', (string) setting('values_images')))));
      $valueFallback = ['leopard-stalking', 'granite-boulders', 'safari-guests', 'leopard-basking'];
      ?>
      <div class="grid grid-4 values" data-stagger>
        <?php foreach (Settings::lines('values_items') as $n => $line): [$vt, $vb] = array_pad(array_map('trim', explode('|', $line, 2)), 2, ''); ?>
        <article class="value value-photo reveal">
          <div class="value-media"><?= picture($valueImgs[$n] ?? null, $vt, ['sizes' => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw'], $valueFallback[$n % 4]) ?></div>
          <div class="value-body"><h3><?= e($vt) ?></h3><p><?= e($vb) ?></p></div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="center reveal"><a class="btn btn-outline" href="/about/">About us</a></div>
  </div>
</section>
<?php endif; ?>

<!-- 7. Curated journeys -->
<?php if (Settings::bool('journeys_enabled') && $journeys): ?>
<section class="section section-sand">
  <?= View::partial('site/partials/topo', ['class' => 'topo-band']) ?>
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Itineraries</span>
        <h2 class="display-2 reveal"><?= e(setting('journeys_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/journeys/">All journeys</a>
    </div>
    <div class="h-scroll" data-stagger>
      <?php foreach ($journeys as $j): ?><?= View::partial('site/partials/card-journey', ['j' => $j]) ?><?php endforeach; ?>
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

<!-- 8. Journal -->
<?php if (Settings::bool('journal_enabled') && $posts): ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Journal</span>
        <h2 class="display-2 reveal"><?= e(setting('journal_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/journal/">All articles</a>
    </div>
    <div class="grid grid-3" data-stagger>
      <?php foreach ($posts as $p): ?><?= View::partial('site/partials/card-post', ['p' => $p]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 9. Consultation -->
<?= View::partial('site/partials/cta') ?>
