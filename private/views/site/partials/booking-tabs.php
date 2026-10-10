<?php
/**
 * Two flows side by side: "Book a safari" (booking request) and "Ask a question"
 * (a general enquiry about Jawai safaris). Both arrive in Admin → Enquiries, labelled
 * "Booking" and "Inquiry".
 */
use App\Core\View;

$tid = 'tabs-' . substr(md5((string) ($id ?? 'x')), 0, 5);
$topics = ['Leopard sightings', 'Best time to visit', 'Prices and availability', 'Getting to Jawai', 'Photography', 'Something else'];
?>
<div class="bk-tabs" data-tabs>
  <div class="bk-tablist" role="tablist" aria-label="Book or ask">
    <button type="button" role="tab" id="<?= $tid ?>-b" aria-controls="<?= $tid ?>-book" aria-selected="true" data-tab="book">Book a safari</button>
    <button type="button" role="tab" id="<?= $tid ?>-a" aria-controls="<?= $tid ?>-ask" aria-selected="false" tabindex="-1" data-tab="ask">Ask a question</button>
  </div>

  <div class="bk-panel" role="tabpanel" id="<?= $tid ?>-book" aria-labelledby="<?= $tid ?>-b" data-panel="book">
    <?= View::partial('site/partials/booking-form', ['id' => $id ?? 'x', 'preselect' => $preselect ?? '']) ?>
  </div>

  <div class="bk-panel" role="tabpanel" id="<?= $tid ?>-ask" aria-labelledby="<?= $tid ?>-a" data-panel="ask" hidden>
    <form class="ajax-form booking-form" method="post" action="/api/contact" data-ajax-form novalidate>
      <?= View::partial('site/partials/form-guard') ?>
      <p class="bk-intro">Not ready to book? Ask us anything about Jawai safaris and we will reply personally, usually within 12 hours.</p>
      <label class="field"><span>Your question is about</span>
        <select name="topic"><?php foreach ($topics as $t): ?><option><?= e($t) ?></option><?php endforeach; ?></select>
      </label>
      <label class="field"><span>Your question *</span><textarea name="message" rows="4" required placeholder="e.g. Is December a good month for leopards? How early do we leave?"></textarea></label>
      <label class="field"><span>Full name *</span><input type="text" name="full_name" required autocomplete="name"></label>
      <div class="field-row">
        <label class="field"><span>Email *</span><input type="email" name="email" required autocomplete="email"></label>
        <label class="field"><span>Phone / WhatsApp (optional)</span><input type="tel" name="phone" autocomplete="tel" placeholder="+91"></label>
      </div>
      <div class="form-error" data-form-error hidden></div>
      <button class="btn btn-primary btn-lg btn-block" type="submit"><span>Send question</span></button>
      <p class="form-note"><?= e(setting('privacy_note')) ?> <a href="/privacy-policy/">Privacy policy</a>.</p>
    </form>
    <div class="form-success" data-form-success hidden>
      <h3></h3><p></p><p class="mono" data-code></p>
    </div>
  </div>
</div>
