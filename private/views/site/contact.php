<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Contact us', 'kicker' => 'Contact', 'intro' => 'Questions about a trip, dates or prices? Write to us, call, or message us on WhatsApp.', 'image' => (int) setting('img_contact') ?: null, 'fallback' => 'bandh-shoreline', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container contact-grid">
    <div class="contact-info reveal">
      <h2 class="display-3">Get in touch</h2>
      <ul class="contact-list">
        <li><div><span class="mono">Email</span><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a></div></li>
        <?php if (setting('contact_phone')): ?><li><?= icon('phone', 20) ?><div><span class="mono">Phone</span><a href="<?= e(tel_link()) ?>"><?= e(setting('contact_phone')) ?></a></div></li><?php endif; ?>
        <?php if (whatsapp_link()): ?><li><?= icon('whatsapp', 20) ?><div><span class="mono">WhatsApp</span><a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">Message us on WhatsApp</a></div></li><?php endif; ?>
        <?php if (setting('address')): ?><li><div><span class="mono">Base</span><span><?= nl2br(e(setting('address'))) ?></span></div></li><?php endif; ?>
        <?php if (setting('office_hours')): ?><li><div><span class="mono">Hours</span><span><?= e(setting('office_hours')) ?></span></div></li><?php endif; ?>
      </ul>
      <div class="aside-card aside-cta">
        <h3>Want to book a safari?</h3>
        <p>Our short planner form gives us everything we need to send you options.</p>
        <a class="btn btn-primary btn-block" href="/book-a-safari/">Book a safari</a>
      </div>
    </div>
    <div class="form-card reveal">
      <h2 class="display-3">Send a message</h2>
      <form class="ajax-form" method="post" action="/api/contact" data-ajax-form novalidate>
        <?= View::partial('site/partials/form-guard') ?>
        <div class="field-row">
          <label class="field"><span>Your name *</span><input type="text" name="full_name" required autocomplete="name" value="<?= e(old('full_name')) ?>"></label>
          <label class="field"><span>Email *</span><input type="email" name="email" required autocomplete="email" value="<?= e(old('email')) ?>"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Phone / WhatsApp</span><input type="tel" name="phone" autocomplete="tel" value="<?= e(old('phone')) ?>"></label>
          <label class="field"><span>Country</span><input type="text" name="country" autocomplete="country-name" value="<?= e(old('country')) ?>"></label>
        </div>
        <label class="field"><span>Message *</span><textarea name="message" rows="6" required><?= e(old('message')) ?></textarea></label>
        <p class="form-note"><?= e(setting('privacy_note')) ?> <a href="/privacy-policy/">Privacy policy</a>.</p>
        <div class="form-error" data-form-error hidden></div>
        <button class="btn btn-primary btn-lg" type="submit"><span>Send message</span></button>
      </form>
      <div class="form-success" data-form-success hidden>
       <h3></h3><p></p><p class="mono" data-code></p>
      </div>
    </div>
  </div>
</section>
<?php if (setting('map_embed_url')): ?>
<section class="map-embed"><iframe src="<?= e(setting('map_embed_url')) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Map"></iframe></section>
<?php endif; ?>
