<?php
use App\Core\Menu;

$socials = array_filter([
    'instagram' => setting('social_instagram'),
    'facebook' => setting('social_facebook'),
    'youtube' => setting('social_youtube'),
    'globe' => setting('social_tripadvisor'),
    'map-pin' => setting('social_google'),
]);
$footerLogo = (int) setting('footer_logo_image');
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="/" class="footer-logo">
        <?php if ($footerLogo): ?>
          <img src="<?= e(media_url($footerLogo, 'sm')) ?>" alt="<?= e(setting('site_name')) ?>" loading="lazy">
        <?php else: ?>
          <img src="/assets/img/logo-240.webp" width="120" height="180" alt="<?= e(setting('site_name')) ?> logo" loading="lazy">
        <?php endif; ?>
      </a>
      <p><?= e(setting('site_description')) ?></p>
      <?php if ($socials): ?>
      <div class="socials">
        <?php foreach ($socials as $icon => $url): ?>
          <a href="<?= e($url) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($icon)) ?>"><?= icon($icon, 18) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div>
      <h4 class="footer-title">Explore</h4>
      <ul class="footer-links">
        <?php foreach (Menu::tree('footer') as $l): ?><li><a href="<?= e($l['url']) ?>"><?= e($l['label']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h4 class="footer-title">Company</h4>
      <ul class="footer-links">
        <?php foreach (Menu::tree('footer2') as $l): ?><li><a href="<?= e($l['url']) ?>"><?= e($l['label']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h4 class="footer-title">Speak with a Captain</h4>
      <ul class="footer-contact">
        <li><?= icon('mail', 16) ?><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a></li>
        <?php if (setting('contact_phone')): ?><li><?= icon('phone', 16) ?><a href="<?= e(tel_link()) ?>"><?= e(setting('contact_phone')) ?></a></li><?php endif; ?>
        <?php if (whatsapp_link()): ?><li><?= icon('whatsapp', 16) ?><a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">WhatsApp us</a></li><?php endif; ?>
        <?php if (setting('address')): ?><li><?= icon('map-pin', 16) ?><span><?= nl2br(e(setting('address'))) ?></span></li><?php endif; ?>
        <?php if (setting('office_hours')): ?><li><?= icon('clock', 16) ?><span><?= e(setting('office_hours')) ?></span></li><?php endif; ?>
      </ul>
      <a class="btn btn-primary btn-sm" href="/plan-your-journey/">Plan Your Journey</a>
    </div>
  </div>

  <div class="container footer-ethics">
    <p><?= icon('shield', 16) ?> Ethical tracking: engines off at sightings, respectful distances, no baiting — and never a guaranteed sighting. Enquiries only; no payment is taken online.</p>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p><?= e(str_replace('{year}', date('Y'), (string) setting('copyright_text'))) ?><?php if (setting('legal_name')): ?> · <?= e(setting('legal_name')) ?><?php endif; ?><?php if (setting('gstin')): ?> · GSTIN <?= e(setting('gstin')) ?><?php endif; ?></p>
      <ul>
        <?php foreach (Menu::tree('legal') as $l): ?><li><a href="<?= e($l['url']) ?>"><?= e($l['label']) ?></a></li><?php endforeach; ?>
        <?php if (\App\Core\DB::val("SELECT COUNT(*) FROM media WHERE source_url <> ''")): ?><li><a href="/photo-credits/">Photo credits</a></li><?php endif; ?>
      </ul>
    </div>
  </div>
</footer>
