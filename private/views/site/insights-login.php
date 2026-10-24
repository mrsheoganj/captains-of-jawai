<!doctype html>
<html lang="en">
<head>
<title>Website insights</title>
<?= \App\Core\View::partial('site/partials/insights-style') ?>
<style>
  .login { min-height: 100vh; display: grid; place-items: center; padding: 16px; }
  .login .card { width: min(380px, 100%); padding: 28px 24px; }
  .login label { display: block; font-weight: 600; margin: 18px 0 6px; }
  .login input { width: 100%; padding: 11px 13px; border: 1px solid var(--line); border-radius: 10px; font: inherit; background: var(--bg); color: var(--ink); }
  .login input:focus { outline: 2px solid var(--accent); outline-offset: 1px; }
  .login .btn { width: 100%; margin-top: 16px; padding: 11px; }
  .hint { font-size: 13px; margin-top: 6px; }
  .err { background: var(--accent-soft); color: var(--bad); border-radius: 10px; padding: 9px 12px; margin-top: 14px; font-size: 14px; }
</style>
</head>
<body>
<main class="login">
  <form class="card" method="post" action="/pk-insights/" autocomplete="off">
    <h1>Website insights</h1>
    <p class="muted" style="margin:4px 0 0"><?= e(setting('site_name')) ?> · private page</p>
    <?php if ($error): ?><div class="err" role="alert"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label for="pw">Password</label>
    <input id="pw" name="password" type="password" inputmode="numeric" required autofocus>
    <div class="hint muted">Hint: developer's mobile number</div>
    <button class="btn btn-primary" type="submit">Open insights</button>
  </form>
</main>
</body>
</html>
