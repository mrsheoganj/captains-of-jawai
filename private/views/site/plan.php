<?php
use App\Core\Settings;
use App\Core\View;

View::share('bodyClass', 'page-plan');
$interests = Settings::lines('form_interests');
$months = [];
for ($n = 0; $n < 15; $n++) {
    $months[] = date('F Y', strtotime("first day of +$n month"));
}
?>
<section class="plan">
  <div class="plan-visual">
    <?= View::partial('site/partials/topo', ['class' => 'topo-plan']) ?>
    <div class="plan-visual-inner">
      <div>
        <span class="kicker" data-anim="fade">Enquiries</span>
        <h1 class="display-2" data-anim="fade" style="--d:.2s">Book a Jawai safari</h1>
        <ul class="trust" data-anim="fade" style="--d:.5s">
          <?php foreach (Settings::lines('form_trust_points') as $t): ?><li><?= e($t) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="arch arch-plan" data-anim="arch"><?= picture((int) setting('img_plan') ?: null, '', ['sizes' => '(min-width: 1024px) 34vw, 100vw', 'loading' => 'eager'], 'leopard-portrait') ?></div>
    </div>
  </div>

  <div class="plan-form-wrap">
    <form class="plan-form" method="post" action="/api/enquiry" data-wizard novalidate>
      <?= View::partial('site/partials/form-guard') ?>
      <div class="wizard-progress" aria-hidden="true"><span data-progress></span></div>
      <p class="mono wizard-count">Step <b data-step-num>1</b> of 4</p>

      <fieldset class="step is-active" data-step>
        <legend class="display-3">Which safari?</legend>
        <p class="help">Choose one or more. Not sure? Pick the classic leopard safari and we will advise.</p>
        <div class="choice-grid">
          <?php foreach ($safaris as $n => $s): $on = $preselect !== '' ? strcasecmp($preselect, $s['title']) === 0 : $n === 0; ?>
          <label class="choice"><input type="checkbox" name="interests[]" value="<?= e($s['title']) ?>"<?= $on ? ' checked' : '' ?>><span><?= e($s['title']) ?></span></label>
          <?php endforeach; ?>
          <?php foreach ($interests as $opt): ?>
          <label class="choice"><input type="checkbox" name="interests[]" value="<?= e($opt) ?>"><span><?= e($opt) ?></span></label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <fieldset class="step" data-step hidden>
        <legend class="display-3">When?</legend>
        <div class="field-row">
          <label class="field"><span>Preferred month</span>
            <select name="travel_month"><option value="">Not sure yet</option><?php foreach ($months as $m): ?><option><?= e($m) ?></option><?php endforeach; ?></select>
          </label>
          <label class="field"><span>Safari date(s) (optional)</span><input type="text" name="travel_dates" placeholder="e.g. 14 and 15 December"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Drive time</span>
            <select name="drive_time"><?php foreach (Settings::lines('form_drive_times') as $o): ?><option><?= e($o) ?></option><?php endforeach; ?></select>
          </label>
          <label class="field"><span>Number of drives</span>
            <select name="drives"><?php foreach (Settings::lines('form_drives') as $o): ?><option><?= e($o) ?></option><?php endforeach; ?></select>
          </label>
        </div>
        <label class="check"><input type="checkbox" name="flexible" value="1" checked> My dates are flexible</label>
      </fieldset>

      <fieldset class="step" data-step hidden>
        <legend class="display-3">How many guests?</legend>
        <div class="field-row">
          <div class="field"><span>Adults</span><div class="stepper" data-stepper><button type="button" data-dec aria-label="Fewer adults"><?= icon('minus', 16) ?></button><input type="number" name="adults" value="2" min="1" max="50" inputmode="numeric"><button type="button" data-inc aria-label="More adults"><?= icon('plus', 16) ?></button></div></div>
          <div class="field"><span>Children (under 12)</span><div class="stepper" data-stepper><button type="button" data-dec aria-label="Fewer children"><?= icon('minus', 16) ?></button><input type="number" name="children" value="0" min="0" max="30" inputmode="numeric"><button type="button" data-inc aria-label="More children"><?= icon('plus', 16) ?></button></div></div>
        </div>
        <label class="check"><input type="checkbox" name="private_vehicle" value="1" checked> We would like a private vehicle just for our group</label>
        <p class="help"><?= e(setting('form_scope_note')) ?></p>
      </fieldset>

      <fieldset class="step" data-step hidden>
        <legend class="display-3">How can we reach you?</legend>
        <div class="field-row">
          <label class="field"><span>Full name *</span><input type="text" name="full_name" required autocomplete="name"></label>
          <label class="field"><span>Email *</span><input type="email" name="email" required autocomplete="email"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Phone / WhatsApp (with country code) *</span><input type="tel" name="phone" required autocomplete="tel" placeholder="+91 …"></label>
          <label class="field"><span>Country of residence</span><input type="text" name="country" autocomplete="country-name" value="India"></label>
        </div>
        <label class="field"><span>Anything else? (where you are staying in Jawai, photography goals, children's ages)</span><textarea name="message" rows="4"></textarea></label>
        <p class="form-note"><?= e(setting('privacy_note')) ?> <a href="/privacy-policy/">Privacy policy</a>.</p>
      </fieldset>

      <div class="form-error" data-form-error hidden></div>
      <div class="wizard-nav">
        <button type="button" class="btn btn-text" data-prev hidden>Back</button>
        <button type="button" class="btn btn-primary btn-lg" data-next>Continue</button>
        <button type="submit" class="btn btn-primary btn-lg" data-submit hidden><span>Send safari request</span></button>
      </div>
    </form>

    <div class="form-success" data-form-success hidden>
           <h2 class="display-3"></h2>
      <p></p>
      <p class="mono" data-code></p>
      <a class="btn btn-outline" href="/gallery/">See the gallery while you wait</a>
    </div>
  </div>
</section>
