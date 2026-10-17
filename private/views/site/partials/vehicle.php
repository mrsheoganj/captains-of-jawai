<?php
/** "Your safari vehicle": the open 4x4 Gypsy, with photos and the animated jeep strip (Settings → Homepage). */
use App\Core\Settings;
use App\Core\View;

if (!Settings::bool('vehicle_enabled')) {
    return;
}
$photos = [];
foreach (array_filter(array_map('intval', explode(',', (string) setting('vehicle_images')))) as $id) {
    if ($m = \App\Core\Media::find($id)) {
        $photos[] = $m;
    }
}
?>
<section class="section vehicle" id="safari-vehicle">
  <div class="container">
    <div class="vehicle-grid">
      <div class="vehicle-text">
        <span class="kicker reveal"><?= e(setting('vehicle_kicker')) ?></span>
        <h2 class="display-2 reveal"><?= e(setting('vehicle_heading')) ?></h2>
        <div class="reveal"><?= paragraphs(setting('vehicle_body')) ?></div>
        <?php if ($points = Settings::lines('vehicle_points')): ?>
        <ul class="checks reveal"><?php foreach ($points as $pt): ?><li><?= e($pt) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
      </div>
      <?php if ($photos): ?>
      <div class="vehicle-photos" data-stagger data-m-slider aria-label="Safari vehicle photos">
        <?php foreach (array_slice($photos, 0, 3) as $m): ?>
        <figure class="vehicle-photo reveal"><?= picture($m, (string) $m['alt_text'], ['sizes' => '(min-width: 1024px) 30vw, 80vw']) ?></figure>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?= View::partial('site/partials/jeep-track') ?>
</section>
