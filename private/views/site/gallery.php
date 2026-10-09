<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'Gallery', 'kicker' => 'Moments from the field', 'intro' => 'Leopards on granite, wetland wings and the light of the Jawai hills.', 'image' => (int) setting('img_gallery') ?: null, 'fallback' => 'leopard-basking', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container">
    <?php if (count($categories) > 1): ?>
    <div class="chips" data-filter>
      <button type="button" class="chip is-active" data-filter-btn="*">All</button>
      <?php foreach ($categories as $c): ?><button type="button" class="chip" data-filter-btn="<?= e($c) ?>"><?= e($c) ?></button><?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="masonry" data-lightbox>
      <?php foreach ($items as $m): ?>
      <figure class="masonry-item" data-cat="<?= e($m['gallery_category']) ?>">
        <a href="<?= e(media_url($m, 'lg')) ?>" data-caption="<?= e(trim(($m['caption'] ?: $m['alt_text']) . (($m['credit'] && $m['credit'] !== 'Placeholder') ? ' — Photo: ' . $m['credit'] . ($m['license'] ? ', ' . $m['license'] : '') : ''))) ?>"><?= picture($m, $m['alt_text'], ['sizes' => '(min-width: 1024px) 33vw, 50vw']) ?></a>
        <?php $hasCredit = $m['credit'] && $m['credit'] !== 'Placeholder'; if ($hasCredit): ?>
        <figcaption>
          <?= e(excerpt($m['caption'] ?: $m['alt_text'], 90)) ?>
          <span class="photo-credit">Photo: <?= e($m['credit']) ?><?php if ($m['license']): ?> · <?php if ($m['license_url']): ?><a href="<?= e($m['license_url']) ?>" target="_blank" rel="noopener license"><?= e($m['license']) ?></a><?php else: ?><?= e($m['license']) ?><?php endif; ?><?php endif; ?></span>
        </figcaption>
        <?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>
    <?php if (!$items): ?><p class="empty">Photographs coming soon.</p><?php endif; ?>
  </div>
</section>
<div class="lightbox" hidden data-lightbox-view>
  <button type="button" class="lb-close" aria-label="Close" data-lb-close><?= icon('close', 28) ?></button>
  <button type="button" class="lb-prev" aria-label="Previous" data-lb-prev><?= icon('chevron-left', 32) ?></button>
  <figure><img src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" alt=""><figcaption></figcaption></figure>
  <button type="button" class="lb-next" aria-label="Next" data-lb-next><?= icon('chevron-right', 32) ?></button>
</div>
