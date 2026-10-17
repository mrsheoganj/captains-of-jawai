<?php
/** "How booking works": three plain steps between choosing a safari and the drive (Settings → Homepage). */
use App\Core\Settings;

if (!Settings::bool('howto_enabled')) {
    return;
}
$steps = [];
foreach (Settings::lines('howto_steps') as $line) {
    [$t, $d] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
    if ($t !== '') {
        $steps[] = [$t, $d];
    }
}
if (!$steps) {
    return;
}
?>
<section class="section howto" id="how-booking-works">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Booking</span>
        <h2 class="display-2 reveal"><?= e(setting('howto_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="#booking">Plan your safari</a>
    </div>
    <ol class="howto-steps" data-stagger data-m-slider aria-label="Booking steps">
      <?php foreach ($steps as $n => [$t, $d]): ?>
      <li class="reveal"><span class="howto-num"><?= $n + 1 ?></span><h3><?= e($t) ?></h3><?php if ($d !== ''): ?><p><?= e($d) ?></p><?php endif; ?></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
