<?php
use App\Admin\Resources;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Session;
use App\Core\SettingDefs;
use App\Core\View;

$u = Auth::user();
$newCount = $u ? (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE status = 'new'") : 0;
$messages = Session::messages();
$smtpDebug = $_SESSION['smtp_debug'] ?? null;
unset($_SESSION['smtp_debug']);
$path = current_path();
$active = fn (string $p) => str_starts_with($path, rtrim(admin_url($p), '/') . '/') ? ' is-active' : '';
$exact = fn (string $p) => rtrim($path, '/') === rtrim(admin_url($p), '/') ? ' is-active' : '';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<title><?= e($title ?? 'Admin') ?> · <?= e(setting('site_name')) ?> Admin</title>
<link rel="icon" href="/assets/img/favicon-32.png">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Cormorant+Garamond:wght@600&display=swap">
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin" data-admin="<?= e(admin_path()) ?>">
<?= View::partial('partials/icons') ?>
<?php if ($u): ?>
<aside class="sidebar" data-sidebar>
  <a class="side-brand" href="<?= e(admin_url()) ?>"><img src="/assets/img/emblem-96.webp" width="38" height="38" alt=""><span><?= e(setting('site_name')) ?><small>Admin</small></span></a>
  <nav class="side-nav">
    <a class="<?= $exact('') ?>" href="<?= e(admin_url()) ?>"><?= icon('home', 18) ?> Dashboard</a>
    <?php if (Auth::can('enquiries')): ?>
    <a class="<?= $active('enquiries') ?>" href="<?= e(admin_url('enquiries')) ?>"><?= icon('inbox', 18) ?> Enquiries <?php if ($newCount): ?><b class="count"><?= $newCount ?></b><?php endif; ?></a>
    <?php endif; ?>

    <?php if (Auth::can('content')): ?>
    <span class="side-label">Content</span>
    <?php foreach (Resources::all() as $key => $r): if ($r['area'] !== 'content') continue; ?>
    <a class="<?= $active('content/' . $key) ?>" href="<?= e(admin_url('content/' . $key)) ?>"><?= icon($r['icon'], 18) ?> <?= e($r['label']) ?></a>
    <?php endforeach; ?>
    <a class="<?= $active('media') && !str_contains($path, '/media/find') ? ' is-active' : '' ?>" href="<?= e(admin_url('media')) ?>"><?= icon('image', 18) ?> Media library</a>
    <a class="<?= $active('media/find') ?>" href="<?= e(admin_url('media/find')) ?>"><?= icon('download', 18) ?> Find Jawai photos</a>
    <a class="<?= $active('menu') ?>" href="<?= e(admin_url('menu')) ?>"><?= icon('list', 18) ?> Navigation menus</a>
    <?php endif; ?>

    <?php if (Auth::can('seo')): ?>
    <span class="side-label">SEO</span>
    <a class="<?= $exact('seo') ?>" href="<?= e(admin_url('seo')) ?>"><?= icon('search', 18) ?> SEO overview</a>
    <a class="<?= $active('content/redirects') ?>" href="<?= e(admin_url('content/redirects')) ?>"><?= icon('link', 18) ?> Redirects</a>
    <a class="<?= $active('seo/robots') ?>" href="<?= e(admin_url('seo/robots')) ?>"><?= icon('file', 18) ?> robots.txt</a>
    <a href="/sitemap.xml" target="_blank" rel="noopener"><?= icon('globe', 18) ?> XML sitemap <?= icon('external', 12, 'ext') ?></a>
    <?php endif; ?>

    <?php if (Auth::can('settings') || Auth::can('email')): ?>
    <span class="side-label">Settings</span>
    <?php foreach (SettingDefs::groups() as $key => $g): if (!Auth::can(in_array($key, ['email', 'templates'], true) ? 'email' : 'settings')) continue; ?>
    <a class="<?= $active('settings/' . $key) ?>" href="<?= e(admin_url('settings/' . $key)) ?>"><?= icon($g['icon'], 18) ?> <?= e($g['title']) ?></a>
    <?php endforeach; ?>
    <?php if (Auth::can('email')): ?><a class="<?= $active('email-log') ?>" href="<?= e(admin_url('email-log')) ?>"><?= icon('send', 18) ?> Email log</a><?php endif; ?>
    <?php endif; ?>

    <?php if (Auth::can('users') || Auth::can('audit')): ?>
    <span class="side-label">Administration</span>
    <?php if (Auth::can('users')): ?><a class="<?= $active('users') ?>" href="<?= e(admin_url('users')) ?>"><?= icon('users', 18) ?> Users &amp; roles</a><?php endif; ?>
    <?php if (Auth::can('audit')): ?><a class="<?= $active('audit') ?>" href="<?= e(admin_url('audit')) ?>"><?= icon('activity', 18) ?> Activity log</a><?php endif; ?>
    <?php if (Auth::can('settings')): ?><a class="<?= $active('system') ?>" href="<?= e(admin_url('system')) ?>"><?= icon('settings', 18) ?> System</a><?php endif; ?>
    <?php endif; ?>
  </nav>
</aside>
<div class="sidebar-scrim" data-sidebar-close></div>
<?php endif; ?>

<div class="main">
  <?php if ($u): ?>
  <header class="topbar">
    <button class="icon-btn menu-btn" type="button" data-sidebar-open aria-label="Menu"><?= icon('menu', 22) ?></button>
    <h1 class="topbar-title"><?= e($title ?? '') ?></h1>
    <div class="topbar-actions">
      <a class="btn btn-light btn-sm" href="/" target="_blank" rel="noopener"><?= icon('external', 15) ?> <span class="hide-sm">View site</span></a>
      <details class="user-menu">
        <summary><span class="avatar"><?= e(mb_strtoupper(mb_substr($u['name'], 0, 1))) ?></span><span class="hide-sm"><?= e($u['name']) ?></span><?= icon('chevron-down', 14) ?></summary>
        <div class="user-pop">
          <div class="user-pop-head"><strong><?= e($u['name']) ?></strong><small><?= e(Auth::ROLES[$u['role']] ?? $u['role']) ?></small></div>
          <a href="<?= e(admin_url('profile')) ?>"><?= icon('user', 16) ?> My profile</a>
          <form method="post" action="<?= e(admin_url('logout')) ?>"><?= csrf_field() ?><button type="submit"><?= icon('log-out', 16) ?> Sign out</button></form>
        </div>
      </details>
    </div>
  </header>
  <?php endif; ?>

  <div class="content">
    <?php foreach ($messages as $m): ?>
      <div class="alert alert-<?= e($m['type']) ?>" data-dismiss><?= e($m['message']) ?><button type="button" aria-label="Dismiss" data-dismiss-btn><?= icon('close', 14) ?></button></div>
    <?php endforeach; ?>
    <?php if ($smtpDebug): ?>
      <details class="card debug" open><summary>SMTP conversation (for troubleshooting)</summary><pre><?= e(implode("\n", $smtpDebug)) ?></pre></details>
    <?php endif; ?>
    <?= $content ?>
  </div>
</div>

<?= View::partial('admin/partials/media-modal') ?>
<script src="<?= asset('js/admin.js') ?>" defer></script>
</body>
</html>
