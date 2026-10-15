<?php
/**
 * One-step safari booking request, used on the homepage and on /book-a-safari/.
 * Booking types come from Admin → Settings → Enquiry Form ("Booking types").
 */
use App\Core\Settings;
use App\Core\View;

$types = [];
foreach (Settings::lines('booking_types') as $line) {
    [$name, $desc, $price] = array_pad(array_map('trim', explode('|', $line, 3)), 3, '');
    if ($name !== '') {
        $types[] = compact('name', 'desc', 'price');
    }
}
$preselect = (string) ($preselect ?? '');
$selected = 0;
foreach ($types as $n => $t) {
    if ($preselect !== '' && strcasecmp($preselect, $t['name']) === 0) {
        $selected = $n;
    }
}
// Coming from a safari page whose name is not a booking type: offer it as the first choice.
if ($preselect !== '' && !array_filter($types, fn ($t) => strcasecmp($preselect, $t['name']) === 0)) {
    array_unshift($types, ['name' => $preselect, 'desc' => 'The safari you were looking at', 'price' => '']);
    $selected = 0;
}
$uid = 'bk' . substr(md5((string) ($id ?? 'form')), 0, 4);
?>
<form class="ajax-form booking-form" method="post" action="/api/enquiry" data-ajax-form novalidate>
  <?= View::partial('site/partials/form-guard') ?>
  <fieldset class="bk-types">
    <legend class="bk-label">1. Choose your safari</legend>
    <div class="bk-type-grid">
      <?php foreach ($types as $n => $t): ?>
      <label class="bk-type">
        <input type="radio" name="booking_type" value="<?= e($t['name']) ?>"<?= $n === $selected ? ' checked' : '' ?>>
        <span class="bk-type-card">
          <strong><?= e($t['name']) ?></strong>
          <?php if ($t['desc'] !== ''): ?><small><?= e($t['desc']) ?></small><?php endif; ?>
          <?php if ($t['price'] !== ''): ?><em><?= e($t['price']) ?></em><?php endif; ?>
        </span>
      </label>
      <?php endforeach; ?>
    </div>
  </fieldset>

  <p class="bk-label">2. When and how many?</p>
  <div class="field-row">
    <label class="field"><span>Date</span><input type="date" name="travel_date" min="<?= date('Y-m-d') ?>"></label>
    <label class="field"><span>Guests</span>
      <select name="adults"><?php for ($g = 1; $g <= 12; $g++): ?><option value="<?= $g ?>"<?= $g === 2 ? ' selected' : '' ?>><?= $g ?> <?= $g === 1 ? 'guest' : 'guests' ?></option><?php endfor; ?></select>
    </label>
  </div>

  <div class="bk-vehicle" role="radiogroup" aria-label="Vehicle">
    <label class="bk-pill"><input type="radio" name="vehicle" value="Private gypsy" checked><span>Private gypsy <small>just your group</small></span></label>
    <label class="bk-pill"><input type="radio" name="vehicle" value="Shared gypsy"><span>Shared gypsy <small>pay per seat</small></span></label>
  </div>

  <p class="bk-label">3. Your details</p>
  <label class="field"><span>Full name *</span><input type="text" name="full_name" required autocomplete="name"></label>
  <div class="field-row">
    <label class="field"><span>Phone / WhatsApp *</span><input type="tel" name="phone" required autocomplete="tel" placeholder="+91"></label>
    <label class="field"><span>Email *</span><input type="email" name="email" required autocomplete="email"></label>
  </div>
  <label class="field"><span>Anything else? (optional)</span><input type="text" name="message" placeholder="Where you are staying, children's ages, camera gear…"></label>

  <div class="form-error" data-form-error hidden></div>
  <button class="btn btn-primary btn-lg btn-block" type="submit"><span>Request booking</span></button>
  <?php if ($waNum = preg_replace('/\D+/', '', (string) setting('whatsapp_number'))): ?>
  <a class="btn btn-whatsapp btn-block" href="https://wa.me/<?= e($waNum) ?>" target="_blank" rel="noopener" data-wa-book="<?= e($waNum) ?>"><?= icon('whatsapp', 18) ?> Or book on WhatsApp</a>
  <?php endif; ?>
  <p class="form-note"><?= e(setting('form_scope_note')) ?> <?= e(setting('privacy_note')) ?> <a href="/privacy-policy/">Privacy policy</a>.</p>
</form>
<div class="form-success" data-form-success hidden>
  <h3></h3><p></p><p class="mono" data-code></p>
</div>
