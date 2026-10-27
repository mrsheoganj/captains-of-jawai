<?php
use App\Admin\ReviewController;
use App\Core\Auth;

$canEdit = Auth::can('reviews', 'edit');
$canDelete = in_array(Auth::role(), ['super_admin', 'manager'], true);
$stars = fn ($n) => $n ? str_repeat('★', (int) $n) . str_repeat('☆', 5 - (int) $n) : '';
?>
<div class="page-actions">
  <p class="muted">Guests write reviews at <a href="/write-a-review/" target="_blank" rel="noopener"><?= e(abs_url('/write-a-review/')) ?></a>. Only approved reviews appear on the website.</p>
</div>
<div class="tabs-scroll">
  <?php foreach (ReviewController::TABS as $k => $label): ?>
  <a class="tab<?= $tab === $k ? ' is-active' : '' ?>" href="?tab=<?= e($k) ?>"><?= e($label) ?> <b><?= $counts[$k] ?? 0 ?></b></a>
  <?php endforeach; ?>
</div>
<div class="review-list">
<?php foreach ($rows as $r): ?>
  <article class="card review-item">
    <div class="review-head">
      <div>
        <strong><?= e($r['author']) ?></strong><?php if ($r['origin']): ?> <span class="muted">· <?= e($r['origin']) ?></span><?php endif; ?>
        <?php if ($r['rating']): ?><div class="review-stars" aria-label="<?= (int) $r['rating'] ?> out of 5 stars"><?= $stars($r['rating']) ?></div><?php endif; ?>
      </div>
      <div class="muted small" style="text-align:right">
        <?= $r['created_at'] ? e(fmt_date($r['created_at'], 'j M Y, H:i')) : '' ?>
        <?php if ($r['visit']): ?><br>Safari: <?= e($r['visit']) ?><?php endif; ?>
        <?php if ($r['source']): ?><br>via <?= e($r['source']) ?><?php endif; ?>
      </div>
    </div>
    <p class="review-text"><?= nl2br(e($r['quote'])) ?></p>
    <?php if ($r['email']): ?><p class="muted small">Email (private): <a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></p><?php endif; ?>
    <?php if ($canEdit): ?>
    <div class="review-actions">
      <?php if ($r['status'] !== 'published'): ?>
      <form method="post" action="<?= e(admin_url('reviews/' . $r['id'] . '/approve')) ?>"><?= csrf_field() ?><button class="btn btn-primary btn-sm" type="submit"><?= icon('check', 15) ?> Approve and show</button></form>
      <?php endif; ?>
      <?php if ($r['status'] !== 'draft'): ?>
      <form method="post" action="<?= e(admin_url('reviews/' . $r['id'] . '/hide')) ?>"><?= csrf_field() ?><button class="btn btn-light btn-sm" type="submit"><?= icon('eye', 15) ?> <?= $r['status'] === 'published' ? 'Hide from website' : 'Hide' ?></button></form>
      <?php endif; ?>
      <?php if ($canDelete): ?>
      <form method="post" action="<?= e(admin_url('reviews/' . $r['id'] . '/delete')) ?>"><?= csrf_field() ?><button class="btn btn-danger-ghost btn-sm" type="submit" data-confirm="Delete this review permanently?"><?= icon('trash', 15) ?> Delete</button></form>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </article>
<?php endforeach; ?>
<?php if (!$rows): ?><div class="card empty-state"><?= icon('star', 36) ?><p><?= $tab === 'pending' ? 'No reviews waiting. New reviews from guests appear here first.' : 'Nothing here yet.' ?></p></div><?php endif; ?>
</div>
