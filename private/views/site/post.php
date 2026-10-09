<?php use App\Core\View; View::share('headerOverlay', true); ?>
<article>
  <header class="article-hero">
    <div class="page-hero-bg"><?= picture($item['image_id'] ? (int) $item['image_id'] : null, '', ['sizes' => '100vw', 'loading' => 'eager', 'fetchpriority' => 'high'], 'landscape1') ?></div>
    <div class="container narrow page-hero-inner">
      <nav class="crumbs" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span><a href="/journal/">Field Journal</a></nav>
      <div class="meta-line meta-light"><span><?= e($item['category'] ?: 'Journal') ?></span><span><?= e(fmt_date($item['published_at'])) ?></span><?php if ($item['reading_time']): ?><span><?= (int) $item['reading_time'] ?> min read</span><?php endif; ?></div>
      <h1><?= e($item['title']) ?></h1>
      <?php if ($item['author_name']): ?><p class="byline">By <?= e($item['author_name']) ?></p><?php endif; ?>
    </div>
  </header>
  <div class="section">
    <div class="container narrow">
      <p class="lead"><?= e($item['excerpt']) ?></p>
      <div class="prose"><?= $item['body'] ?></div>
      <aside class="inline-cta">
        <h3>Ready to experience this in person?</h3>
        <p>Speak with an Expedition Captain today — a personal reply within 12 hours.</p>
        <a class="btn btn-primary" href="/plan-your-journey/">Plan Your Journey <?= icon('arrow-right', 16) ?></a>
      </aside>
      <div class="share">
        <span class="mono">Share</span>
        <a href="https://wa.me/?text=<?= e(rawurlencode($item['title'] . ' ' . abs_url('/journal/' . $item['slug'] . '/'))) ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><?= icon('whatsapp', 18) ?></a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= e(rawurlencode(abs_url('/journal/' . $item['slug'] . '/'))) ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><?= icon('facebook', 18) ?></a>
        <button type="button" data-copy="<?= e(abs_url('/journal/' . $item['slug'] . '/')) ?>" aria-label="Copy link"><?= icon('link', 18) ?></button>
      </div>
    </div>
  </div>
</article>
<?php if ($related): ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head reveal"><h2 class="display-3">Keep reading</h2></div>
    <div class="grid grid-3"><?php foreach ($related as $p): ?><?= View::partial('site/partials/card-post', ['p' => $p]) ?><?php endforeach; ?></div>
  </div>
</section>
<?php endif; ?>
