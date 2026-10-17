<?php
/** A few common questions on the homepage; the full list is on /faq/. */
/** @var array $faqs */
if (empty($faqs) || !\App\Core\Settings::bool('home_faq_enabled')) {
    return;
}
?>
<section class="section home-faq">
  <div class="container narrow">
    <div class="section-head">
      <div>
        <span class="kicker reveal">Questions</span>
        <h2 class="display-2 reveal"><?= e(setting('home_faq_heading')) ?></h2>
      </div>
      <a class="link-arrow reveal" href="/faq/">All questions</a>
    </div>
    <div class="accordion">
      <?php foreach ($faqs as $f): ?>
      <details class="acc-item reveal">
        <summary><?= e($f['question']) ?><span class="acc-icon"><?= icon('plus', 18) ?></span></summary>
        <div class="acc-body"><?= paragraphs($f['answer']) ?></div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
