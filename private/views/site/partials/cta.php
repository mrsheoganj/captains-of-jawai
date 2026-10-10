<?php
/** Closing consultation band (light). Optional vars: $heading, $body, $image */
$heading ??= setting('cta_heading');
$body ??= setting('cta_body');
$image ??= (int) setting('cta_image') ?: null;
?>
<section class="cta-band">
  <div class="container cta-grid">
    <div class="cta-text">
      <span class="kicker reveal">Enquiries</span>
      <h2 class="display-2 reveal"><?= e($heading) ?></h2>
      <p class="reveal"><?= e($body) ?></p>
      <div class="btn-row reveal">
        <a class="btn btn-primary btn-lg" href="/plan-your-journey/">Plan your trip</a>
        <?php if (whatsapp_link()): ?><a class="btn btn-outline btn-lg" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp us</a><?php endif; ?>
      </div>
      <ul class="cta-points reveal">
        <li>Reply within 12 hours</li>
        <li>No payment taken online</li>
      </ul>
    </div>
    <div class="cta-media">
      <div class="arch arch-sm reveal-img" data-parallax="0.06"><?= picture($image, '', ['sizes' => '(min-width: 1024px) 40vw, 100vw'], 'safari1') ?></div>
      
    </div>
  </div>
</section>
