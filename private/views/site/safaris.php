<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Signature Expeditions', 'kicker' => 'Private 4x4 safaris', 'intro' => 'Every drive is private, naturalist-led and shaped around you — from dawn leopard tracking to slow wetland mornings.', 'image' => $items[0]['image_id'] ?? null, 'fallback' => 'leopard1', 'crumbs' => $seo['breadcrumbs']]) ?>
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
      <span class="kicker">How it works</span>
      <h2 class="display-2">No instant checkout. A conversation.</h2>
      <p class="lead-muted">Every journey is tailored. Tell us your dates, your party and what moves you — a Captain replies personally within 12 hours with ideas, availability and a written proposal.</p>
    </div>
    <ol class="steps reveal">
      <li><strong>Share your wishes</strong><span>Dates, interests and travelling party.</span></li>
      <li><strong>Personal reply in 12 hours</strong><span>From a senior Expedition Captain — never an automated quote.</span></li>
      <li><strong>Tailored proposal</strong><span>Drives, stays and transfers designed around you.</span></li>
      <li><strong>Confirm directly</strong><span>Simple, secure confirmation with our team.</span></li>
    </ol>
  </div>
</section>
<?= View::partial('site/partials/cta') ?>
