<?php
use App\Core\Settings;

/** "Village life" photos (homepage and the Jawai culture page). $compact: no heading text, used inside a page. */
$compact = $compact ?? false;
$ids = array_values(array_filter(array_map('intval', explode(',', (string) setting('village_images')))));
$photos = [];
foreach ($ids as $id) {
    if ($m = \App\Core\Media::find($id)) {
        $photos[] = $m;
    }
}
if (!Settings::bool('village_enabled') || count($photos) < 2) {
    return;
}
// Full rows only (4 per row on wide screens, 2 on phones).
$photos = array_slice($photos, 0, count($photos) >= 4 ? intdiv(min(count($photos), 8), 4) * 4 : count($photos));
?>
<section class="section village<?= $compact ? ' village-compact' : '' ?>">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal"><?= e(setting('village_kicker')) ?></span>
        <h2 class="display-2 reveal"><?= e(setting('village_heading')) ?></h2>
      </div>
      <?php if (!$compact): ?><a class="link-arrow reveal" href="/jawai/culture/">Jawai culture</a><?php endif; ?>
    </div>
    <?php if (!$compact && setting('village_body')): ?><div class="village-intro reveal"><?= paragraphs(setting('village_body')) ?></div><?php endif; ?>
    <div class="village-grid" data-stagger data-m-slider aria-label="Village life photos">
      <?php foreach ($photos as $m): ?>
      <figure class="village-item reveal">
        <?= picture($m, (string) $m['alt_text'], ['sizes' => '(min-width: 1024px) 25vw, 50vw']) ?>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
