<?php
/** "Jawai Dam through the seasons": winter, summer and monsoon, each with its own photo (Settings → Homepage). */
$seasons = [
    'winter' => ['flamingo-flock', 'Cool and clear'],
    'summer' => ['sunset-valley', 'Hot and dry'],
    'monsoon' => ['lake-reflection', 'Green and wet'],
];
?>
<section class="section seasons">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Seasons</span>
        <h2 class="display-2 reveal"><?= e(setting('seasons_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/jawai/best-time-to-visit/">Best time to visit</a>
    </div>
    <div class="season-grid" data-stagger>
      <?php foreach ($seasons as $key => [$fallback, $tag]): ?>
      <article class="season-card season-<?= $key ?> reveal">
        <div class="season-photo">
          <?= picture((int) setting("season_{$key}_image") ?: null, 'Jawai Dam in ' . $key, ['sizes' => '(min-width: 1024px) 33vw, 100vw'], $fallback) ?>
          <span class="season-tag"><?= e($tag) ?></span>
        </div>
        <div class="season-body">
          <h3><?= e(setting("season_{$key}_title")) ?></h3>
          <p><?= e(setting("season_{$key}_text")) ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
