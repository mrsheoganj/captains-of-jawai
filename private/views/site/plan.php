<?php
use App\Core\Settings;
use App\Core\View;

View::share('bodyClass', 'page-plan');
?>
<section class="plan">
  <div class="plan-visual">
    <?= View::partial('site/partials/topo', ['class' => 'topo-plan']) ?>
    <div class="plan-visual-inner">
      <div>
        <span class="kicker" data-anim="fade">Booking</span>
        <h1 class="display-2" data-anim="fade" style="--d:.2s">Book a Jawai safari</h1>
        <ul class="trust" data-anim="fade" style="--d:.5s">
          <?php foreach (Settings::lines('form_trust_points') as $t): ?><li><?= e($t) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="arch arch-plan" data-anim="arch"><?= picture((int) setting('img_plan') ?: null, '', ['sizes' => '(min-width: 1024px) 34vw, 100vw', 'loading' => 'eager'], 'leopard-portrait') ?></div>
    </div>
  </div>

  <div class="plan-form-wrap">
    <div class="plan-form booking-card">
      <?= View::partial('site/partials/booking-tabs', ['id' => 'plan', 'preselect' => $preselect]) ?>
    </div>
  </div>
</section>
