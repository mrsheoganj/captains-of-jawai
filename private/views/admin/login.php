<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title>Sign in · <?= e(setting('site_name')) ?></title>
<link rel="icon" href="/assets/img/favicon-32.png">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Cormorant+Garamond:wght@600&display=swap">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="auth-body">
<div class="auth-split">
  <div class="auth-visual">
    <?= picture((int) setting('img_login') ?: null, '', ['sizes' => '55vw', 'loading' => 'eager'], 'leopard2') ?>
    <div class="auth-quote"><span>Captains of Jawai</span><p>Mastering the granite wilderness.</p></div>
  </div>
  <main class="auth-wrap">
    <div class="auth-brand"><img src="/assets/img/emblem-96.webp" width="56" height="56" alt=""><div><strong><?= e(setting('site_name')) ?></strong><span>Admin &amp; CRM</span></div></div>
    <h1>Welcome back</h1>
    <p class="muted">Sign in to manage enquiries, content and settings.</p>
    <?php foreach ($messages as $m): ?><div class="alert alert-<?= e($m['type']) ?>"><?= e($m['message']) ?></div><?php endforeach; ?>
    <form method="post" action="<?= e(admin_url('login')) ?>" class="form">
      <?= csrf_field() ?>
      <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= e(old('email')) ?>" required autofocus autocomplete="username"></div>
      <div class="field"><label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
      <button class="btn btn-primary btn-lg btn-block" type="submit">Sign in</button>
    </form>
    <p class="muted small">Forgotten your password? Ask a Super Admin to reset it in Users &amp; roles.</p>
    <a class="muted small" href="/">← Back to website</a>
  </main>
</div>
</body>
</html>
