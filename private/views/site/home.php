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
      <h1 class="hero-title" data-split><?= e(setting('hero_title')) ?></h1>
      <p class="hero-sub" data-anim="fade" style="--d:.55s"><?= e(setting('hero_subtitle')) ?></p>
      <div class="btn-row" data-anim="fade" style="--d:.7s">
        <a class="btn btn-primary btn-lg" href="/plan-your-journey/" data-magnetic><?= e(setting('hero_cta_primary')) ?> <?= icon('arrow-right', 18) ?></a>
        <a class="btn btn-outline btn-lg" href="#expeditions"><?= e(setting('hero_cta_secondary')) ?></a>
      </div>
      <ul class="hero-trust" data-anim="fade" style="--d:.85s">
        <li><?= icon('compass', 18) ?> Private 4x4 expeditions</li>
        <li><?= icon('binoculars', 18) ?> Expert local trackers</li>
        <li><?= icon('shield', 18) ?> Ethical, never guaranteed</li>
      </ul>
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
      <div class="float-card fc-1" data-float>
        <span class="fc-dot"></span>
        <div><strong>Leopard country</strong><small class="mono">25°06'N · 73°10'E</small></div>
      </div>
      <div class="float-card fc-2" data-float style="--fd:1.4s">
        <?= icon('sun', 22) ?>
        <div><strong>Dawn &amp; dusk</strong><small>Drives timed to the light</small></div>
      </div>
      <span class="sun-disc" aria-hidden="true"></span>
      <?php if (count($heroIds) > 1): ?>
      <div class="hero-dots" role="tablist" aria-label="Hero images">
        <?php foreach ($heroIds as $n => $id): ?><button type="button" class="<?= $n === 0 ? 'is-active' : '' ?>" aria-label="Show image <?= $n + 1 ?>" data-hero-dot="<?= $n ?>"></button><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <a class="scroll-cue" href="#intro" aria-label="Scroll down"><span></span></a>
</section>

<!-- 2. Field ticker -->
<?php if (Settings::bool('ticker_enabled') && ($ticker = Settings::lines('ticker_items'))): ?>
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php for ($r = 0; $r < 2; $r++): foreach ($ticker as $t): ?><span><?= e($t) ?></span><i>✦</i><?php endforeach; endfor; ?>
  </div>
</div>
<?php endif; ?>

<!-- 3. Narrative hook -->
<section class="section" id="intro">
  <div class="container split split-60">
    <div class="split-text">
      <span class="kicker reveal"><?= e(setting('intro_kicker')) ?></span>
      <h2 class="display-2 reveal" data-split><?= e(setting('intro_heading')) ?></h2>
      <div class="prose-lg reveal"><?= paragraphs(setting('intro_body')) ?></div>
      <a class="link-arrow reveal" href="/jawai/">Discover the Jawai wilderness <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="split-media">
      <div class="framed reveal-img" data-parallax="0.06"><?= picture((int) setting('intro_image') ?: null, 'Leopard on the granite hills of Jawai', ['sizes' => '(min-width: 1024px) 45vw, 100vw'], 'leopard-portrait') ?></div>
      <div class="coords mono reveal" style="--d:.3s">25°06'N · 73°10'E</div>
    </div>
  </div>
  <?php if ($stats): ?>
  <div class="container">
    <dl class="stats" data-stagger>
      <?php foreach ($stats as [$v, $l]): ?><div class="reveal"><dt data-count="<?= e($v) ?>"><?= e($v) ?></dt><dd><?= e($l) ?></dd></div><?php endforeach; ?>
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
        <h2 class="display-2 reveal" data-split><?= e(setting('safaris_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/safaris/">All expeditions <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="grid grid-3" data-stagger>
      <?php foreach ($safaris as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?>
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
      <?php if (setting('coexist_link')): ?><a class="link-arrow" href="<?= e(setting('coexist_link')) ?>">The Rabari &amp; the leopard <?= icon('arrow-right', 16) ?></a><?php endif; ?>
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
      <h2 class="display-2 reveal" data-split><?= e(setting('team_heading')) ?></h2>
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
      $valueIcons = ['binoculars', 'leaf', 'compass', 'shield'];
      $valueImgs = array_values(array_filter(array_map('intval', explode(',', (string) setting('values_images')))));
      $valueFallback = ['leopard-stalking', 'granite-boulders', 'safari-guests', 'leopard-basking'];
      ?>
      <div class="grid grid-4 values" data-stagger>
        <?php foreach (Settings::lines('values_items') as $n => $line): [$vt, $vb] = array_pad(array_map('trim', explode('|', $line, 2)), 2, ''); ?>
        <article class="value value-photo reveal" data-tilt>
          <div class="value-media"><?= picture($valueImgs[$n] ?? null, $vt, ['sizes' => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw'], $valueFallback[$n % 4]) ?></div>
          <div class="value-body"><?= icon($valueIcons[$n % 4], 26) ?><h3><?= e($vt) ?></h3><p><?= e($vb) ?></p></div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="center reveal"><a class="btn btn-outline" href="/about/">Our story &amp; ethics</a></div>
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
        <span class="kicker reveal">Bespoke itineraries</span>
        <h2 class="display-2 reveal" data-split><?= e(setting('journeys_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/journeys/">All journeys <?= icon('arrow-right', 16) ?></a>
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
        <?= icon('quote', 36) ?>
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
        <span class="kicker reveal">Field dispatches</span>
        <h2 class="display-2 reveal" data-split><?= e(setting('journal_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/journal/">Read the journal <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="grid grid-3" data-stagger>
      <?php foreach ($posts as $p): ?><?= View::partial('site/partials/card-post', ['p' => $p]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 9. Consultation -->
<?= View::partial('site/partials/cta') ?>
