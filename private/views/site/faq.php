<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Frequently asked questions', 'kicker' => 'FAQ', 'intro' => 'Wildlife, seasons, getting here and how booking works.', 'image' => (int) setting('img_faq') ?: null, 'fallback' => 'safari-jeep', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container narrow">
    <?php foreach ($groups as $cat => $faqs): ?>
    <h2 class="display-3 faq-group"><?= e($cat) ?></h2>
    <div class="accordion">
      <?php foreach ($faqs as $f): ?>
      <details class="acc-item reveal">
        <summary><?= e($f['question']) ?><span class="acc-icon"><?= icon('plus', 18) ?></span></summary>
        <div class="acc-body"><?= paragraphs($f['answer']) ?></div>
      </details>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <div class="inline-cta">
      <h3>Have another question?</h3>
      <p>Write to us and we will answer personally.</p>
      <a class="btn btn-primary" href="/contact/">Contact us</a>
    </div>
  </div>
</section>
