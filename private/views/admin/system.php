<div class="dash-grid">
  <section class="card">
    <div class="card-head"><h2>Environment</h2></div>
    <dl class="dl"><?php foreach ($info as $k => $v): ?><dt><?= e($k) ?></dt><dd><?= e($v) ?></dd><?php endforeach; ?></dl>
    <p class="muted small">Database credentials, the admin path and the site address live in <code>private/config.php</code> on the server.</p>
  </section>
  <section class="card">
    <div class="card-head"><h2>Recent PHP errors</h2></div>
    <?php if ($log): ?><pre class="log"><?= e(implode('', $log)) ?></pre><?php else: ?><p class="muted">No errors logged. 🎉</p><?php endif; ?>
  </section>
</div>
<section class="card">
  <div class="card-head"><h2><?= icon('image', 18) ?> Demo content</h2></div>
  <p class="muted">Use these for presentations. Sample enquiries are marked with codes starting <span class="mono">DEMO-</span> and are never emailed to anyone.</p>
  <div class="row-actions">
    <form method="post" action="<?= e(admin_url('demo/load')) ?>"><?= csrf_field() ?><button class="btn btn-light" type="submit"><?= icon('inbox', 15) ?> Load sample enquiries</button></form>
    <form method="post" action="<?= e(admin_url('demo/clear')) ?>"><?= csrf_field() ?><button class="btn btn-light" type="submit" data-confirm="Remove all DEMO- sample enquiries?"><?= icon('trash', 15) ?> Remove sample enquiries</button></form>
    <form method="post" action="<?= e(admin_url('demo/fill-images')) ?>"><?= csrf_field() ?><button class="btn btn-light" type="submit"><?= icon('image', 15) ?> Fill any empty image slots</button></form>
    <form method="post" action="<?= e(admin_url('demo/reset-images')) ?>"><?= csrf_field() ?><button class="btn btn-danger-ghost" type="submit" data-confirm="Reset EVERY image on the site back to the built-in photo set? Your own image choices will be replaced."><?= icon('copy', 15) ?> Reset all images to demo set</button></form>
  </div>
</section>
