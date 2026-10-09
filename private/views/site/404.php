<?= \App\Core\View::partial('site/partials/page-hero', ['title' => 'These tracks lead nowhere.', 'kicker' => 'Error 404', 'intro' => 'The page you were looking for has moved or never existed. Let us guide you back.', 'image' => (int) setting('img_404') ?: null, 'fallback' => 'granite-boulders', 'crumbs' => []]) ?>
<section class="section section-tight">
  <div class="container narrow center">
    <div class="btn-row btn-row-center">
      <a class="btn btn-primary" href="/">Return home</a>
      <a class="btn btn-outline" href="/safaris/">Explore expeditions</a>
      <a class="btn btn-outline" href="/plan-your-journey/">Plan your journey</a>
    </div>
  </div>
</section>
