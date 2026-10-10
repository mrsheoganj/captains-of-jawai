<?= \App\Core\View::partial('site/partials/page-hero', ['title' => 'Page not found', 'kicker' => 'Error 404', 'intro' => 'The page you were looking for has moved or does not exist.', 'image' => (int) setting('img_404') ?: null, 'fallback' => 'granite-boulders', 'crumbs' => []]) ?>
<section class="section section-tight">
  <div class="container narrow center">
    <div class="btn-row btn-row-center">
      <a class="btn btn-primary" href="/">Return home</a>
      <a class="btn btn-outline" href="/safaris/">See the safaris</a>
      <a class="btn btn-outline" href="/book-a-safari/">Book a safari</a>
    </div>
  </div>
</section>
