<?php
/** Closing call-to-action band. Optional vars: $heading, $body, $image */
$heading ??= setting('cta_heading');
$body ??= setting('cta_body');
$image ??= (int) setting('cta_image') ?: null;
?>
<section class="cta-band">
  <div class="cta-bg"><?= picture($image, '', ['sizes' => '100vw'], 'safari1') ?></div>
  <div class="container cta-inner reveal">
    <span class="kicker kicker-light">Private consultation</span>
    <h2><?= e($heading) ?></h2>
    <p><?= e($body) ?></p>
    <div class="btn-row">
      <a class="btn btn-primary btn-lg" href="/plan-your-journey/">Plan Your Journey <?= icon('arrow-right', 18) ?></a>
      <?php if (whatsapp_link()): ?><a class="btn btn-ghost btn-lg" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp a Captain</a><?php endif; ?>
    </div>
  </div>
</section>
