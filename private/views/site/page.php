<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => $item['title'], 'kicker' => $item['kicker'], 'intro' => $item['intro'], 'image' => $item['image_id'] ? (int) $item['image_id'] : null, 'fallback' => \App\Core\Media::STOCK[crc32((string) $item['slug']) % count(\App\Core\Media::STOCK)], 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container narrow">
    <div class="prose"><?= $item['body'] ?></div>

    <?php if (!empty($faq)): ?>
    <div class="page-faq">
      <h2 class="display-3 faq-group">Questions people ask</h2>
      <div class="accordion">
        <?php foreach ($faq as [$q, $a]): ?>
        <details class="acc-item reveal">
          <summary><?= e($q) ?><span class="acc-icon"><?= icon('plus', 18) ?></span></summary>
          <div class="acc-body"><?= paragraphs($a) ?></div>
        </details>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($sources)): ?>
    <div class="sources">
      <p><strong>Sources and further reading.</strong> Facts on this page were checked against:</p>
      <ul>
        <?php foreach ($sources as [$u, $l]): ?>
        <li><a href="<?= e($u) ?>" rel="noopener" target="_blank"><?= e($l) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php if (!empty($related) && explode('/', (string) $item['slug'])[0] === 'jawai'): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal">
      <div><span class="kicker">The Jawai guide</span>
      <h2 class="display-2">Keep reading</h2></div>
    </div>
    <div class="guide-grid reveal">
      <?php foreach ($related as $r): ?>
      <a href="/<?= e($r['slug']) ?>/">
        <h3><?= e($r['title']) ?></h3>
        <p><?= e(excerpt($r['intro'], 120)) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php if ($item['show_cta']): ?><?= View::partial('site/partials/cta') ?><?php endif; ?>
