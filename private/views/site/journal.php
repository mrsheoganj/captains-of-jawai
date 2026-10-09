<?php use App\Core\View; ?>
<?= View::partial('site/partials/page-hero', ['title' => 'The Field Journal', 'kicker' => 'Stories & guides', 'intro' => 'Leopard behaviour, wetland birding, Rabari culture and practical advice for planning your journey.', 'image' => $posts[0]['image_id'] ?? null, 'fallback' => 'landscape1', 'crumbs' => $seo['breadcrumbs']]) ?>
<section class="section">
  <div class="container">
    <?php if ($categories): ?>
    <div class="chips reveal">
      <a class="chip<?= $cat === '' ? ' is-active' : '' ?>" href="/journal/">All</a>
      <?php foreach ($categories as $c): ?><a class="chip<?= $cat === $c['category'] ? ' is-active' : '' ?>" href="/journal/?category=<?= e(rawurlencode($c['category'])) ?>"><?= e($c['category']) ?> <small><?= (int) $c['n'] ?></small></a><?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="grid grid-3">
      <?php foreach ($posts as $p): ?><?= View::partial('site/partials/card-post', ['p' => $p]) ?><?php endforeach; ?>
    </div>
    <?php if (!$posts): ?><p class="empty">No articles yet.</p><?php endif; ?>
    <?php $pages = (int) ceil($total / $per); if ($pages > 1): ?>
    <nav class="pager" aria-label="Pagination">
      <?php for ($n = 1; $n <= $pages; $n++): ?>
        <a class="<?= $n === $page ? 'is-active' : '' ?>" href="/journal/?<?= e(http_build_query(array_filter(['category' => $cat, 'page' => $n > 1 ? $n : null]))) ?>"><?= $n ?></a>
      <?php endfor; ?>
    </nav>
    <?php endif; ?>
  </div>
</section>
