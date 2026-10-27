<?php
/** Homepage guest reviews (approved only), with a link for guests to write one. */
if (!\App\Core\Settings::bool('testimonials_enabled')) {
    return;
}
$reviews = $testimonials ?? [];
?>
<section class="section reviews-home" id="reviews">
  <div class="container narrow">
    <div class="section-head section-head-center reveal"><div>
      <span class="kicker">Guest reviews</span>
      <h2 class="display-2">What our guests say</h2></div>
    </div>
    <?php if ($reviews): ?>
    <div class="quotes" data-quotes>
      <?php foreach ($reviews as $n => $q): ?>
      <figure class="quote<?= $n === 0 ? ' is-active' : '' ?>">
        <?php if (!empty($q['rating'])): ?><div class="stars" aria-label="<?= (int) $q['rating'] ?> out of 5 stars"><?= str_repeat('★', (int) $q['rating']) ?></div><?php endif; ?>
        <blockquote><?= e($q['quote']) ?></blockquote>
        <figcaption><strong><?= e($q['author']) ?></strong><?php if ($q['origin']): ?> · <?= e($q['origin']) ?><?php endif; ?><?php if ($q['source'] && $q['source'] !== 'Website'): ?> <span class="mono">via <?= e($q['source']) ?></span><?php endif; ?></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="lead-muted center">Been on safari with us? We would love to hear about it.</p>
    <?php endif; ?>
    <div class="center reviews-cta"><a class="btn btn-outline" href="/write-a-review/"><?= icon('star', 16) ?> Write a review</a></div>
  </div>
</section>
