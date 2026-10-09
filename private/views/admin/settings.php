<?php
use App\Core\Auth;
use App\Core\View;
?>
<div class="tabs-scroll">
  <?php foreach ($groups as $key => $gg): if (!Auth::can(in_array($key, ['email', 'templates'], true) ? 'email' : 'settings')) continue; ?>
  <a class="tab<?= $key === $group ? ' is-active' : '' ?>" href="<?= e(admin_url('settings/' . $key)) ?>"><?= e($gg['title']) ?></a>
  <?php endforeach; ?>
</div>

<form method="post" action="<?= e(admin_url('settings/' . $group)) ?>" class="form" data-dirty>
  <?= csrf_field() ?>
  <div class="page-actions sticky-actions">
    <p class="muted"><?= e($g['intro']) ?></p>
    <span class="spacer"></span>
    <?php if ($canEdit): ?><button class="btn btn-primary" type="submit"><?= icon('check', 16) ?> Save settings</button><?php endif; ?>
  </div>
  <section class="card settings-card">
    <?php foreach ($g['fields'] as $f):
        [$key, $label, $type] = $f;
        $opt = ['help' => $f[4] ?? null];
        if ($type === 'select') { $opt['choices'] = $f[5] ?? []; }
        if ($type === 'secret') { $opt['has'] = $hasSecret[$key] ?? false; }
        if ($type === 'code' && Auth::role() !== 'super_admin') { $opt['help'] = 'Only a Super Admin can change custom code.'; }
        if (in_array($key, ['seo_home_title'], true)) { $opt['help'] = ($opt['help'] ?? '') . ' Aim for 50–60 characters.'; }
        if (in_array($key, ['seo_home_description'], true)) { $opt['help'] = ($opt['help'] ?? '') . ' Aim for 120–155 characters.'; }
        echo View::partial('admin/partials/field', ['name' => $key, 'label' => $label, 'type' => $type === 'emails' ? 'text' : $type, 'opt' => $opt, 'value' => $values[$key] ?? '', 'error' => null, 'lists' => []]);
    endforeach; ?>
  </section>
</form>

<?php if ($group === 'email'): ?>
<section class="card" id="test">
  <div class="card-head"><h2><?= icon('send', 18) ?> Send a test email</h2></div>
  <p class="muted">Save your settings first, then send a test. If it fails, the SMTP conversation is shown at the top of the page to help troubleshoot.</p>
  <form method="post" action="<?= e(admin_url('settings/email/test')) ?>" class="inline-form">
    <?= csrf_field() ?>
    <input type="email" name="test_to" value="<?= e(Auth::user()['email'] ?? '') ?>" placeholder="you@example.com" required>
    <button class="btn btn-primary" type="submit">Send test</button>
    <a class="btn btn-light" href="<?= e(admin_url('email-log')) ?>">View email log</a>
  </form>
  <details class="help-box">
    <summary>Common SMTP settings</summary>
    <div class="table-wrap"><table class="table compact">
      <thead><tr><th>Provider</th><th>Host</th><th>Port / Encryption</th><th>Username</th></tr></thead>
      <tbody>
        <tr><td>GoDaddy cPanel mailbox</td><td>mail.yourdomain.com (or localhost)</td><td>465 / SSL</td><td>full email address</td></tr>
        <tr><td>GoDaddy Professional Email (Titan)</td><td>smtp.titan.email</td><td>465 / SSL</td><td>full email address</td></tr>
        <tr><td>Microsoft 365 (GoDaddy)</td><td>smtp.office365.com</td><td>587 / TLS</td><td>full email address</td></tr>
        <tr><td>Google Workspace / Gmail</td><td>smtp.gmail.com</td><td>587 / TLS</td><td>email + App Password</td></tr>
        <tr><td>Zoho Mail (India)</td><td>smtp.zoho.in</td><td>465 / SSL</td><td>full email address</td></tr>
        <tr><td>SendGrid</td><td>smtp.sendgrid.net</td><td>587 / TLS</td><td>apikey</td></tr>
        <tr><td>Amazon SES (Mumbai)</td><td>email-smtp.ap-south-1.amazonaws.com</td><td>587 / TLS</td><td>SMTP credentials</td></tr>
      </tbody>
    </table></div>
    <p class="small muted">Tip: GoDaddy shared hosting may block outgoing SMTP to external servers on some plans — if external providers time out, use your cPanel mailbox with host <code>localhost</code>, port 25/465.</p>
  </details>
</section>
<?php endif; ?>
