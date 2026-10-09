<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Install — Captains of Jawai</title>
<link rel="icon" href="/assets/img/favicon-32.png">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="auth-body">
<main class="auth-wrap auth-wide">
  <div class="auth-brand"><img src="/assets/img/emblem-96.webp" width="56" height="56" alt=""><div><strong>Captains of Jawai</strong><span>Website installer</span></div></div>
  <div class="card">
    <h1>Set up your website</h1>
    <p class="muted">This creates the database tables, starter content and your administrator account. It runs only once.</p>

    <details class="checks-box" <?= in_array(false, $checks, true) ? 'open' : '' ?>>
      <summary>Server check</summary>
      <ul class="syscheck">
        <?php foreach ($checks as $label => $ok): ?><li class="<?= $ok ? 'ok' : 'bad' ?>"><?= $ok ? '✓' : '✕' ?> <?= e($label) ?></li><?php endforeach; ?>
      </ul>
    </details>

    <?php if (!empty($errors['general'])): ?><div class="alert alert-error"><?= e($errors['general']) ?></div><?php endif; ?>

    <form method="post" action="/install" class="form" data-install>
      <?= csrf_field() ?>
      <h2>1. Database</h2>
      <div class="field">
        <label>Database type</label>
        <div class="seg">
          <label><input type="radio" name="db_driver" value="mysql" <?= ($old['db_driver'] ?? 'mysql') === 'mysql' ? 'checked' : '' ?>> MySQL / MariaDB (recommended)</label>
          <label><input type="radio" name="db_driver" value="sqlite" <?= ($old['db_driver'] ?? '') === 'sqlite' ? 'checked' : '' ?> <?= extension_loaded('pdo_sqlite') ? '' : 'disabled' ?>> SQLite (no setup)</label>
        </div>
        <small>On GoDaddy cPanel: create a database and user in <b>MySQL® Databases</b>, add the user to the database with ALL PRIVILEGES, then enter them below. Names are usually prefixed, e.g. <code>cpuser_jawai</code>.</small>
      </div>
      <div class="grid-2" data-mysql>
        <div class="field"><label>Host</label><input name="db_host" value="<?= e($old['db_host'] ?? 'localhost') ?>"></div>
        <div class="field"><label>Port</label><input name="db_port" value="<?= e($old['db_port'] ?? '3306') ?>"></div>
        <div class="field"><label>Database name</label><input name="db_name" value="<?= e($old['db_name'] ?? '') ?>"></div>
        <div class="field"><label>Database user</label><input name="db_user" value="<?= e($old['db_user'] ?? '') ?>" autocomplete="off"></div>
        <div class="field span-2"><label>Database password</label><input type="password" name="db_pass" autocomplete="new-password"></div>
      </div>
      <?php if (!empty($errors['db_name'])): ?><div class="alert alert-error"><?= e($errors['db_name']) ?></div><?php endif; ?>

      <h2>2. Site</h2>
      <div class="grid-2">
        <div class="field"><label>Website address</label><input name="base_url" value="<?= e($old['base_url'] ?? base_url()) ?>" placeholder="https://captainsofjawai.com"><small>Used in emails, sitemap and social previews.</small></div>
        <div class="field"><label>Enquiry email (receives notifications)</label><input type="email" name="site_email" value="<?= e($old['site_email'] ?? '') ?>" placeholder="contact@captainsofjawai.com"></div>
        <div class="field"><label>Admin panel path</label><div class="input-prefix"><span>/</span><input name="admin_path" value="<?= e($old['admin_path'] ?? 'admin') ?>"></div><small>Change it (e.g. <code>captains-desk</code>) to hide the login page from bots.</small><?php if (!empty($errors['admin_path'])): ?><em class="err"><?= e($errors['admin_path']) ?></em><?php endif; ?></div>
      </div>

      <h2>3. Your administrator account</h2>
      <div class="grid-2">
        <div class="field"><label>Your name</label><input name="admin_name" value="<?= e($old['admin_name'] ?? '') ?>" required><?php if (!empty($errors['admin_name'])): ?><em class="err"><?= e($errors['admin_name']) ?></em><?php endif; ?></div>
        <div class="field"><label>Login email</label><input type="email" name="admin_email" value="<?= e($old['admin_email'] ?? '') ?>" required><?php if (!empty($errors['admin_email'])): ?><em class="err"><?= e($errors['admin_email']) ?></em><?php endif; ?></div>
        <div class="field span-2"><label>Password (min. 10 characters)</label><input type="password" name="admin_password" required minlength="10" autocomplete="new-password"><?php if (!empty($errors['admin_password'])): ?><em class="err"><?= e($errors['admin_password']) ?></em><?php endif; ?></div>
      </div>
      <label class="switch"><input type="checkbox" name="demo_data" value="1" <?= !$old || !empty($old['demo_data']) ? 'checked' : '' ?>><span class="switch-ui"></span><span>Load sample enquiries so the dashboard and CRM are ready to demo (removable with one click)</span></label>
      <button class="btn btn-primary btn-lg" type="submit">Install website</button>
    </form>
  </div>
</main>
<script>
(function(){var f=document.querySelector('[data-install]');if(!f)return;var box=f.querySelector('[data-mysql]');
function t(){box.style.display=f.querySelector('[name=db_driver]:checked').value==='mysql'?'':'none'}
f.addEventListener('change',t);t();})();
</script>
</body>
</html>
