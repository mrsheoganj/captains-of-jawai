<div class="dash-grid">
  <section class="card">
    <div class="card-head"><h2>Environment</h2></div>
    <dl class="dl"><?php foreach ($info as $k => $v): ?><dt><?= e($k) ?></dt><dd><?= e($v) ?></dd><?php endforeach; ?></dl>
    <p class="muted small">Database credentials, the admin path and the site address live in <code>private/config.php</code> on the server.</p>
  </section>
  <section class="card">
    <div class="card-head"><h2>Recent PHP errors</h2></div>
    <?php if ($log): ?><pre class="log"><?= e(implode('', $log)) ?></pre><?php else: ?><p class="muted">No errors logged.</p><?php endif; ?>
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

<?php if (\App\Core\Auth::role() === 'super_admin'): ?>
<section class="card reset-card" id="reset">
  <div class="card-head"><h2><?= icon('alert', 18) ?> Reset website and go live</h2></div>
  <p>Use this once, before launch, to clear everything that was added for the demo or for testing, and start clean with the real Jawai content.</p>
  <div class="reset-cols">
    <div>
      <h3>Removed</h3>
      <ul>
        <li>All enquiries, bookings, questions and their notes (demo and test)</li>
        <li>Email log, activity log and failed-login records</li>
        <li>Safaris, pages, FAQs, testimonials and menus (reloaded with the real starter content)</li>
        <li>Homepage text, form options, email templates and SEO titles (back to the defaults)</li>
      </ul>
    </div>
    <div>
      <h3>Kept</h3>
      <ul>
        <li>Every image in the media library, and the photo chosen for each safari, page and homepage section</li>
        <li>Admin users and passwords</li>
        <li>Site name, logo, favicon and colours</li>
        <li>Contact details, social links, email/SMTP settings and recipients</li>
        <li>Google Analytics, verification and custom code, redirects</li>
      </ul>
    </div>
  </div>
  <p class="muted small">Afterwards: maintenance mode is off, search engines are allowed, and real email sending is switched on (SMTP if it is set up, otherwise the server's own mail). This cannot be undone, so download a database backup from cPanel first if you want one.</p>
  <form method="post" action="<?= e(admin_url('system/reset')) ?>" class="reset-form" autocomplete="off">
    <?= csrf_field() ?>
    <label class="field"><span>Your admin password</span><input type="password" name="password" required autocomplete="current-password"></label>
    <label class="field"><span>Type <strong>RESET</strong> to confirm</span><input type="text" name="confirm" required pattern="RESET" placeholder="RESET"></label>
    <button class="btn btn-danger" type="submit" data-confirm="Reset the website now? Demo and test data will be deleted. Images, users and contact/email settings are kept."><?= icon('trash', 15) ?> Reset website</button>
  </form>
</section>
<?php endif; ?>
