<?php
use App\Core\Settings;
use App\Core\View;

View::share('headerOverlay', true);
View::share('bodyClass', 'page-home');
$heroIds = $heroIds ?: [null];
$fallbacks = ['leopard1', 'landscape1', 'leopard2'];
$stats = [];
foreach (Settings::lines('stats') as $line) {
    $parts = array_map('trim', explode('|', $line, 2));
    $stats[] = [$parts[0], $parts[1] ?? ''];
}
?>
<!-- 1. Hero -->
<section class="hero" data-hero>
  <div class="hero-slides">
    <?php foreach ($heroIds as $n => $id): ?>
      <div class="hero-slide<?= $n === 0 ? ' is-active' : '' ?>">
        <?= picture($id, '', ['sizes' => '100vw', 'loading' => $n === 0 ? 'eager' : 'lazy', 'fetchpriority' => $n === 0 ? 'high' : 'low'], $fallbacks[$n % 3]) ?>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="hero-shade"></div>
  <div class="container hero-inner">
    <span class="kicker kicker-light hero-kicker"><?= e(setting('hero_kicker')) ?></span>
    <h1 class="hero-title"><?= e(setting('hero_title')) ?></h1>
    <p class="hero-sub"><?= e(setting('hero_subtitle')) ?></p>
    <div class="btn-row">
      <a class="btn btn-primary btn-lg" href="/plan-your-journey/"><?= e(setting('hero_cta_primary')) ?> <?= icon('arrow-right', 18) ?></a>
      <a class="btn btn-ghost btn-lg" href="#expeditions"><?= e(setting('hero_cta_secondary')) ?></a>
    </div>
  </div>
  <?php if (count($heroIds) > 1): ?>
  <div class="hero-dots" role="tablist" aria-label="Hero images">
    <?php foreach ($heroIds as $n => $id): ?><button type="button" class="<?= $n === 0 ? 'is-active' : '' ?>" aria-label="Show image <?= $n + 1 ?>" data-hero-dot="<?= $n ?>"></button><?php endforeach; ?>
  </div>
  <?php endif; ?>
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
    <div class="split-text reveal">
      <span class="kicker"><?= e(setting('intro_kicker')) ?></span>
      <h2 class="display-2"><?= e(setting('intro_heading')) ?></h2>
      <div class="prose-lg"><?= paragraphs(setting('intro_body')) ?></div>
      <a class="link-arrow" href="/jawai/">Discover the Jawai wilderness <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="split-media reveal">
      <div class="framed"><?= picture((int) setting('intro_image') ?: null, 'Leopard on the granite hills of Jawai', ['sizes' => '(min-width: 1024px) 45vw, 100vw'], 'leopard2') ?></div>
      <div class="coords mono">25°06'N · 73°10'E</div>
    </div>
  </div>
  <?php if ($stats): ?>
  <div class="container">
    <dl class="stats reveal">
      <?php foreach ($stats as [$v, $l]): ?><div><dt><?= e($v) ?></dt><dd><?= e($l) ?></dd></div><?php endforeach; ?>
    </dl>
  </div>
  <?php endif; ?>
</section>

<!-- 4. Signature expeditions -->
<?php if ($safaris): ?>
<section class="section section-alt" id="expeditions">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <span class="kicker"><?= e(setting('safaris_kicker')) ?></span>
        <h2 class="display-2"><?= e(setting('safaris_heading')) ?></h2>
      </div>
      <a class="link-arrow" href="/safaris/">All expeditions <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="grid grid-3">
      <?php foreach ($safaris as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. Coexistence feature -->
<?php if (Settings::bool('coexist_enabled')): ?>
<section class="feature-band">
  <div class="feature-bg"><?= picture((int) setting('coexist_image') ?: null, '', ['sizes' => '100vw'], 'landscape1') ?></div>
  <div class="container">
    <div class="feature-card reveal">
      <span class="kicker kicker-crimson"><?= e(setting('coexist_kicker')) ?></span>
      <h2 class="display-2"><?= e(setting('coexist_heading')) ?></h2>
      <?= paragraphs(setting('coexist_body')) ?>
      <?php if (setting('coexist_link')): ?><a class="link-arrow" href="<?= e(setting('coexist_link')) ?>">The Rabari & the leopard <?= icon('arrow-right', 16) ?></a><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 6. The Captains -->
<?php if (Settings::bool('team_enabled')): ?>
<section class="section">
  <div class="container">
    <div class="section-head section-head-center reveal">
      <span class="kicker"><?= e(setting('team_kicker')) ?></span>
      <h2 class="display-2"><?= e(setting('team_heading')) ?></h2>
      <p class="lead-muted"><?= e(setting('team_body')) ?></p>
    </div>
    <?php if ($team): ?>
      <div class="grid grid-<?= min(4, max(2, count($team))) ?> team-grid">
        <?php foreach (array_slice($team, 0, 4) as $t): ?>
        <article class="team-card reveal">
          <div class="team-photo"><?= picture($t['image_id'] ? (int) $t['image_id'] : null, $t['name'], ['sizes' => '(min-width: 1024px) 25vw, 50vw'], 'safari1') ?></div>
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
      <div class="grid grid-4 values">
        <?php foreach (Settings::lines('values_items') as $n => $line): [$vt, $vb] = array_pad(array_map('trim', explode('|', $line, 2)), 2, ''); ?>
        <article class="value value-photo reveal">
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
<section class="section section-dark">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <span class="kicker kicker-light">Bespoke itineraries</span>
        <h2 class="display-2"><?= e(setting('journeys_heading')) ?></h2>
      </div>
      <a class="link-arrow link-light" href="/journeys/">All journeys <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="h-scroll">
      <?php foreach ($journeys as $j): ?><?= View::partial('site/partials/card-journey', ['j' => $j]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Guest words (only real testimonials entered in admin) -->
<?php if (Settings::bool('testimonials_enabled') && $testimonials): ?>
<section class="section section-alt">
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
    <div class="section-head reveal">
      <div>
        <span class="kicker">Field dispatches</span>
        <h2 class="display-2"><?= e(setting('journal_heading')) ?></h2>
      </div>
      <a class="link-arrow" href="/journal/">Read the journal <?= icon('arrow-right', 16) ?></a>
    </div>
    <div class="grid grid-3">
      <?php foreach ($posts as $p): ?><?= View::partial('site/partials/card-post', ['p' => $p]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 9. Consultation -->
<?= View::partial('site/partials/cta') ?>
