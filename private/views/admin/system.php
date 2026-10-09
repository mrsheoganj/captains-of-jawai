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
