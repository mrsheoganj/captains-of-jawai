<?php
use App\Core\Menu;
use App\Core\Session;
use App\Core\View;

$siteName = (string) setting('site_name');
$overlay = false; // full light theme: the header is always the light glass bar
$wa = whatsapp_link();
$tel = tel_link();
$headerMenu = Menu::tree('header');
$logoId = (int) setting('logo_image');
$messages = Session::messages();
$bodyClass = trim(($overlay ? 'has-overlay-header ' : '') . View::shared('bodyClass', ''));
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<script>document.documentElement.classList.add('js','is-loading')</script>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($seo['title']) ?></title>
<meta name="description" content="<?= e($seo['description']) ?>">
<link rel="canonical" href="<?= e($seo['canonical']) ?>">
<?php if ($seo['noindex']): ?><meta name="robots" content="noindex, follow"><?php else: ?><meta name="robots" content="index, follow, max-image-preview:large"><?php endif; ?>

<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:type" content="<?= e($seo['type']) ?>">
<meta property="og:title" content="<?= e($seo['title']) ?>">
<meta property="og:description" content="<?= e($seo['description']) ?>">
<meta property="og:url" content="<?= e($seo['canonical']) ?>">
<meta property="og:image" content="<?= e($seo['image']) ?>">
<meta property="og:locale" content="en_IN">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($seo['title']) ?>">
<meta name="twitter:description" content="<?= e($seo['description']) ?>">
<meta name="twitter:image" content="<?= e($seo['image']) ?>">
<?php if (setting('google_verification')): ?><meta name="google-site-verification" content="<?= e(setting('google_verification')) ?>"><?php endif; ?>

<meta name="theme-color" content="#FDFBF7">
<meta name="color-scheme" content="light">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32.png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Mono&display=swap">
<link rel="stylesheet" href="<?= asset('css/site.css') ?>">
<style>:root{--accent:<?= e(setting('color_accent')) ?>;--accent-hover:<?= e(setting('color_accent_hover')) ?>;--dark:<?= e(setting('color_dark')) ?>;--crimson:<?= e(setting('color_crimson')) ?>}</style>
<?php
$crumbs = $seo['breadcrumbs'] ?? [];
$schema = $seo['schema'] ?? [];
if ($crumbs) {
    $list = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => abs_url('/')]];
    foreach ($crumbs as $n => [$label, $url]) {
        $list[] = ['@type' => 'ListItem', 'position' => $n + 2, 'name' => $label, 'item' => abs_url($url)];
    }
    $schema[] = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
}
foreach ($schema as $s): ?>
<script type="application/ld+json"><?= json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
<?php endforeach; ?>
<?php if ($ga = trim((string) setting('ga4_id'))): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',<?= json_encode($ga) ?>);</script>
<?php endif; ?>
<?= setting('head_code') ?>
</head>
<body class="<?= e($bodyClass) ?>">
<div class="preloader" data-preloader aria-hidden="true">
  <div class="preloader-inner">
    <span class="preloader-ring"></span>
    <img src="/assets/img/emblem-96.webp" width="64" height="64" alt="">
  </div>
  <span class="preloader-text">Captains of Jawai</span>
</div>
<noscript><style>.preloader{display:none!important}</style></noscript>
<div class="scroll-progress" data-progress aria-hidden="true"></div>
<?= View::partial('partials/icons') ?>
<a class="skip-link" href="#main">Skip to content</a>

<?php if (\App\Core\Settings::bool('announcement_enabled') && setting('announcement_text')): ?>
<div class="announce">
  <a href="<?= e(setting('announcement_link') ?: '#') ?>"><?= e(setting('announcement_text')) ?> <?= icon('arrow-right', 14) ?></a>
</div>
<?php endif; ?>

<header class="site-header<?= $overlay ? ' is-overlay' : '' ?>" data-header>
  <div class="container header-inner">
    <a class="brand" href="/" aria-label="<?= e($siteName) ?> — home">
      <?php if ($logoId): ?>
        <img class="brand-custom" src="<?= e(media_url($logoId, 'sm')) ?>" alt="<?= e($siteName) ?>" height="52">
      <?php else: ?>
        <img class="brand-mark" src="/assets/img/emblem-96.webp" width="48" height="48" alt="">
        <span class="brand-text"><span class="brand-name"><?= e($siteName) ?></span><span class="brand-sub"><?= e(setting('site_tagline')) ?></span></span>
      <?php endif; ?>
    </a>

    <nav class="main-nav" aria-label="Main">
      <ul>
        <?php foreach ($headerMenu as $item): ?>
        <li class="<?= $item['children'] ? 'has-sub' : '' ?><?= is_active($item['url']) ? ' is-active' : '' ?>">
          <a href="<?= e($item['url']) ?>"<?= $item['new_tab'] ? ' target="_blank" rel="noopener"' : '' ?>><?= e($item['label']) ?><?php if ($item['children']): ?> <?= icon('chevron-down', 14) ?><?php endif; ?></a>
          <?php if ($item['children']): ?>
          <ul class="sub">
            <?php foreach ($item['children'] as $c): ?>
            <li><a href="<?= e($c['url']) ?>"<?= $c['new_tab'] ? ' target="_blank" rel="noopener"' : '' ?>><?= e($c['label']) ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="header-actions">
      <?php if ($wa): ?><a class="icon-btn hide-sm" href="<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="WhatsApp us"><?= icon('whatsapp', 20) ?></a><?php endif; ?>
      <?php if ($tel): ?><a class="icon-btn hide-sm" href="<?= e($tel) ?>" aria-label="Call us"><?= icon('phone', 19) ?></a><?php endif; ?>
      <a class="btn btn-primary btn-sm hide-md" href="/plan-your-journey/">Plan Your Journey</a>
      <button class="icon-btn nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav" data-nav-open><?= icon('menu', 24) ?></button>
    </div>
  </div>
</header>

<div class="mobile-nav" id="mobile-nav" hidden data-mobile-nav>
  <div class="mobile-nav-head">
    <a class="brand" href="/"><img class="brand-mark" src="/assets/img/emblem-96.webp" width="44" height="44" alt=""><span class="brand-name"><?= e($siteName) ?></span></a>
    <button class="icon-btn" type="button" aria-label="Close menu" data-nav-close><?= icon('close', 26) ?></button>
  </div>
  <nav aria-label="Mobile">
    <ul>
      <?php foreach ($headerMenu as $item): ?>
      <li>
        <?php if ($item['children']): ?>
          <details>
            <summary><?= e($item['label']) ?> <?= icon('chevron-down', 18) ?></summary>
            <ul>
              <?php foreach ($item['children'] as $c): ?><li><a href="<?= e($c['url']) ?>"><?= e($c['label']) ?></a></li><?php endforeach; ?>
            </ul>
          </details>
        <?php else: ?>
          <a href="<?= e($item['url']) ?>"><?= e($item['label']) ?></a>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="mobile-nav-foot">
    <a class="btn btn-primary btn-block" href="/plan-your-journey/">Start Your Journey Enquiry</a>
    <?php if ($wa): ?><a class="btn btn-ghost-dark btn-block" href="<?= e($wa) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp a Captain</a><?php endif; ?>
    <?php if (setting('contact_phone')): ?><a class="mobile-contact" href="<?= e($tel) ?>"><?= icon('phone', 16) ?> <?= e(setting('contact_phone')) ?></a><?php endif; ?>
    <a class="mobile-contact" href="mailto:<?= e(setting('contact_email')) ?>"><?= icon('mail', 16) ?> <?= e(setting('contact_email')) ?></a>
  </div>
</div>

<?php if ($messages): ?>
<div class="toast-stack" role="status">
  <?php foreach ($messages as $m): ?><div class="toast toast-<?= e($m['type']) ?>"><?= e($m['message']) ?></div><?php endforeach; ?>
</div>
<?php endif; ?>

<main id="main">
<?= $content ?>
</main>

<?= View::partial('site/partials/footer') ?>

<div class="mobile-dock" data-dock>
  <?php if ($wa): ?><a class="dock-wa" href="<?= e($wa) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 18) ?> WhatsApp</a><?php elseif ($tel): ?><a class="dock-wa" href="<?= e($tel) ?>"><?= icon('phone', 18) ?> Call</a><?php endif; ?>
  <a class="dock-plan" href="/plan-your-journey/">Plan Journey <?= icon('arrow-right', 16) ?></a>
</div>
<?php if ($wa): ?><a class="wa-float" href="<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('whatsapp', 28) ?></a><?php endif; ?>

<button class="to-top" type="button" data-to-top aria-label="Back to top">
  <svg viewBox="0 0 44 44" aria-hidden="true"><circle cx="22" cy="22" r="20" pathLength="100" data-to-top-ring/></svg>
  <?= icon('arrow-right', 16, 'to-top-arrow') ?>
</button>
<script src="<?= asset('js/site.js') ?>" defer></script>
<?= setting('body_code') ?>
</body>
</html>
