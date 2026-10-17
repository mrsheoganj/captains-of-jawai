<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Jawai safaris', 'kicker' => 'What we offer', 'intro' => (\App\Core\Settings::bool('vehicle_shared_enabled') ? 'Book a private jeep for your group or a seat in a shared jeep' : 'Every drive is in a private open 4x4 gypsy for your group') . ', always with a local tracker. Choose leopard tracking, birding at Jawai Dam, or a mix of both.', 'image' => (int) setting('img_safaris') ?: ($items[0]['image_id'] ?? null), 'fallback' => 'leopard2', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container">
    <div class="grid grid-2 grid-lg-2">
      <?php foreach ($items as $s): ?><?= View::partial('site/partials/card-safari', ['s' => $s]) ?><?php endforeach; ?>
    </div>
    <?php if (!$items): ?><p class="empty">Expeditions will be published here soon.</p><?php endif; ?>
  </div>
</section>
<section class="section section-alt">
  <div class="container split">
    <div class="reveal">
      <span class="kicker">Booking</span>
      <h2 class="display-2">How booking works</h2>
      <p class="lead-muted">There's no online checkout. You send us your dates and interests, we reply personally with options and prices, and you confirm when you're happy.</p>
    </div>
    <ol class="steps reveal">
      <li><strong>Send us your dates</strong><span>Plus who is travelling and what you would like to see.</span></li>
      <li><strong>We reply</strong><span>Usually within 12 hours, from one of our team.</span></li>
      <li><strong>You get a plan and a price</strong><span>Safari, date, drive time and price, in writing.</span></li>
      <li><strong>You confirm</strong><span>Directly with us, when you are ready.</span></li>
    </ol>
  </div>
</section>
<?= View::partial('site/partials/cta') ?>
