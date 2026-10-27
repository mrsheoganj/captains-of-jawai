<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Write a review', 'kicker' => 'Guest reviews', 'intro' => 'Been on safari with us? Tell other travellers what it was like. Your review appears on our website after a quick check.', 'image' => (int) setting('img_contact') ?: null, 'fallback' => 'leopard-basking', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container contact-grid">
    <div class="contact-info reveal">
      <h2 class="display-3">What guests say</h2>
      <?php if ($reviews): ?>
      <ul class="review-mini">
        <?php foreach ($reviews as $r): ?>
        <li>
          <?php if ($r['rating']): ?><span class="stars" aria-label="<?= (int) $r['rating'] ?> out of 5 stars"><?= str_repeat('★', (int) $r['rating']) ?></span><?php endif; ?>
          <p>“<?= e(excerpt($r['quote'], 220)) ?>”</p>
          <small><strong><?= e($r['author']) ?></strong><?php if ($r['origin']): ?> · <?= e($r['origin']) ?><?php endif; ?></small>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p class="lead-muted">Be one of the first to share your Jawai safari experience.</p>
      <?php endif; ?>
      <div class="aside-card aside-cta">
        <h3>Haven't been yet?</h3>
        <p>Leopard safaris from <?= e(\App\Controllers\SiteController::price() ?: 'a single jeep') ?> per jeep, morning or evening.</p>
        <a class="btn btn-primary btn-block" href="/book-a-safari/">Book a safari</a>
      </div>
    </div>
    <div class="form-card reveal">
      <h2 class="display-3">Your review</h2>
      <form class="ajax-form" method="post" action="/api/review" data-ajax-form novalidate>
        <?= View::partial('site/partials/form-guard') ?>
        <div class="field">
          <span>Your rating *</span>
          <div class="star-input" role="radiogroup" aria-label="Star rating">
            <?php for ($n = 5; $n >= 1; $n--): ?>
            <input type="radio" id="star<?= $n ?>" name="rating" value="<?= $n ?>"><label for="star<?= $n ?>" title="<?= $n ?> star<?= $n > 1 ? 's' : '' ?>">★</label>
            <?php endfor; ?>
          </div>
        </div>
        <label class="field"><span>Your review *</span><textarea name="review" rows="6" required maxlength="2000" placeholder="What did you see? How was the tracker, the jeep, the experience?"></textarea></label>
        <div class="field-row">
          <label class="field"><span>Your name *</span><input type="text" name="full_name" required autocomplete="name" maxlength="80"></label>
          <label class="field"><span>From (city / country)</span><input type="text" name="origin" autocomplete="address-level2" maxlength="80" placeholder="Mumbai, India"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Email * <small class="muted">(never shown)</small></span><input type="email" name="email" required autocomplete="email" maxlength="150"></label>
          <label class="field"><span>When was your safari?</span><input type="text" name="visit" maxlength="60" placeholder="November 2026"></label>
        </div>
        <label class="field"><span class="check" style="align-items:flex-start;font-weight:400"><input type="checkbox" name="consent" value="1" required> I went on a safari with <?= e(setting('site_name')) ?> and this is my own honest review. It may be shown on the website with my name and city.</span></label>
        <div class="form-error" data-form-error hidden></div>
        <button class="btn btn-primary btn-lg" type="submit"><span>Send review</span></button>
      </form>
      <div class="form-success" data-form-success hidden>
        <h3></h3><p></p><p class="mono" data-code></p>
      </div>
    </div>
  </div>
</section>
