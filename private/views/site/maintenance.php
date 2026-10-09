<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title><?= e(setting('site_name')) ?></title>
<link rel="icon" href="/assets/img/favicon-32.png">
<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#121416;color:#FDFBF7;font:16px/1.6 system-ui,sans-serif;text-align:center;padding:24px}img{width:120px;height:auto;margin-bottom:24px}h1{font-family:Georgia,serif;font-weight:500;font-size:2.2rem;margin:0 0 12px}a{color:#C8963E}</style>
</head><body><main>
<img src="/assets/img/logo-240.webp" alt="">
<h1><?= e(setting('site_name')) ?></h1>
<p><?= e(setting('maintenance_message')) ?></p>
<p><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a></p>
</main></body></html>
