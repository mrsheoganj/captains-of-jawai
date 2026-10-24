<?php
use App\Controllers\InsightsController;
use App\Core\Insights;

$max = max(1, ...array_column($series, 'views'));
$step = max(1, (int) ceil($max / 4));
$top = $step * 4;
$sel = $totals[$range];
$change = static function (int $now, int $was): string {
    if ($was === 0) {
        return $now > 0 ? 'new' : '';
    }
    $p = (int) round(($now - $was) / $was * 100);
    return ($p > 0 ? '+' : '') . $p . '%';
};
$every = count($series) > 12 ? (int) ceil(count($series) / 8) : 1;
$list = static function (string $title, array $items, string $empty = 'No visits yet') {
    $big = max(1, ...array_column($items ?: [['views' => 1]], 'views'));
    ob_start(); ?>
    <section class="card">
      <h2><?= e($title) ?></h2>
      <?php if (!$items): ?><p class="muted"><?= e($empty) ?></p><?php else: ?>
      <table class="rank">
        <thead><tr><th></th><th>Views</th><th>People</th></tr></thead>
        <tbody>
        <?php foreach ($items as $name => $v): ?>
          <tr><td><div class="rank-name"><span class="rank-bar" style="width:<?= round($v['views'] / $big * 100, 1) ?>%"></span><span><?= e((string) $name) ?></span></div></td><td><?= number_format($v['views']) ?></td><td><?= number_format($v['people']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </section>
    <?php return (string) ob_get_clean();
};
?><!doctype html>
<html lang="en">
<head>
<title>Website insights · <?= e(setting('site_name')) ?></title>
<?= \App\Core\View::partial('site/partials/insights-style') ?>
<style>
  .top { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; margin-bottom: 18px; }
  .tabs { display: inline-flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 3px; }
  .tabs a { padding: 6px 14px; border-radius: 999px; text-decoration: none; color: var(--ink-2); font-weight: 600; font-size: 14px; }
  .tabs a[aria-current] { background: var(--accent); color: #fff; }
  .tiles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 12px; }
  .tile { text-decoration: none; color: inherit; }
  .tile.is-on { border-color: var(--accent); box-shadow: inset 0 0 0 1px var(--accent); }
  .tile .k { font-size: 13px; color: var(--muted); font-weight: 600; }
  .tile .v { font-size: 32px; font-weight: 750; letter-spacing: -.02em; line-height: 1.15; margin-top: 4px; }
  .tile .s { font-size: 14px; color: var(--ink-2); }
  .chart-head { display: flex; flex-wrap: wrap; gap: 8px 18px; align-items: baseline; justify-content: space-between; }
  .delta { font-size: 13px; color: var(--muted); }
  .delta b { color: var(--ink); }
  .chart { position: relative; display: grid; grid-template-columns: 34px 1fr; gap: 8px; margin-top: 10px; }
  .yaxis { position: relative; height: 190px; font-size: 11px; color: var(--muted); }
  .yaxis span { position: absolute; right: 0; transform: translateY(50%); }
  .plot { position: relative; height: 190px; display: flex; align-items: flex-end; gap: 2px; border-bottom: 1px solid var(--line); }
  .plot .gl { position: absolute; left: 0; right: 0; border-top: 1px solid var(--grid); pointer-events: none; }
  .col { position: relative; flex: 1; height: 100%; display: flex; align-items: flex-end; cursor: default; }
  .col i { display: block; width: 100%; max-width: 34px; margin: 0 auto; background: var(--bar); border-radius: 4px 4px 0 0; min-height: 0; }
  .col:hover i, .col:focus i { filter: brightness(1.15); }
  .col:focus { outline: none; }
  .xaxis { display: flex; gap: 2px; margin-left: 42px; font-size: 11px; color: var(--muted); margin-top: 4px; }
  .xaxis span { flex: 1; text-align: center; white-space: nowrap; overflow: visible; }
  .tip { position: absolute; z-index: 5; pointer-events: none; background: var(--ink); color: var(--card); font-size: 12px; line-height: 1.4; padding: 6px 9px; border-radius: 8px; white-space: nowrap; transform: translate(-50%, -100%); opacity: 0; transition: opacity .12s; }
  .tip.on { opacity: 1; }
  .grid2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-top: 12px; }
  .grid3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px; }
  table { width: 100%; border-collapse: collapse; font-size: 14px; }
  th { text-align: left; font-size: 12px; color: var(--muted); font-weight: 600; padding: 4px 6px 6px; border-bottom: 1px solid var(--line); }
  td { padding: 6px; border-bottom: 1px solid var(--grid); vertical-align: top; }
  .rank th:not(:first-child), .rank td:not(:first-child) { text-align: right; width: 64px; font-variant-numeric: tabular-nums; }
  .rank-name { position: relative; padding: 1px 6px; word-break: break-all; }
  .rank-bar { position: absolute; left: 0; top: 0; bottom: 0; background: var(--accent-soft); border-radius: 4px; z-index: 0; }
  .rank-name span:last-child { position: relative; }
  .log { margin-top: 12px; }
  .log .scroll { overflow: auto; max-height: 640px; }
  .log thead th { position: sticky; top: 0; background: var(--card); }
  .log table { min-width: 860px; }
  .log td { font-size: 13px; }
  .log .ip { font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 12px; }
  .foot { margin-top: 18px; font-size: 13px; }
  @media (max-width: 860px) { .grid3 { grid-template-columns: 1fr; } .grid2 { grid-template-columns: 1fr; } }
  @media (max-width: 600px) { .tiles { grid-template-columns: 1fr; } .tile .v { font-size: 26px; } }
</style>
</head>
<body>
<div class="wrap">
  <header class="top">
    <div>
      <h1>Website insights</h1>
      <div class="muted"><?= e(setting('site_name')) ?> · times in India time (IST)</div>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <nav class="tabs" aria-label="Time range">
        <?php foreach ($ranges as $key => $name): ?>
        <a href="?range=<?= e($key) ?>"<?= $key === $range ? ' aria-current="page"' : '' ?>><?= e(str_replace('Last ', '', $name)) ?></a>
        <?php endforeach; ?>
      </nav>
      <form method="post" action="/pk-insights/logout"><?= csrf_field() ?><button class="btn" type="submit">Sign out</button></form>
    </div>
  </header>

  <div class="tiles">
    <?php foreach ($totals as $key => $t): ?>
    <a class="card tile<?= $key === $range ? ' is-on' : '' ?>" href="?range=<?= e($key) ?>">
      <div class="k"><?= e($t['label']) ?></div>
      <div class="v"><?= number_format($t['views']) ?> <span class="s">page views</span></div>
      <div class="s"><?= number_format($t['people']) ?> visitors</div>
    </a>
    <?php endforeach; ?>
  </div>

  <section class="card">
    <div class="chart-head">
      <h2 style="margin:0">Page views by <?= $hourly ? 'hour' : 'day' ?> · <?= e(strtolower($rangeLabel)) ?></h2>
      <div class="delta">
        <?php $c1 = $change($sel['views'], $before['views']); $c2 = $change($sel['people'], $before['people']); ?>
        Compared with the <?= $hourly ? '24 hours' : (str_contains($range, '30') ? '30 days' : '7 days') ?> before:
        <b><?= e($c1 ?: 'no change') ?></b> views, <b><?= e($c2 ?: 'no change') ?></b> visitors
      </div>
    </div>
    <div class="chart" data-chart>
      <div class="yaxis" aria-hidden="true">
        <?php for ($i = 0; $i <= 4; $i++): ?><span style="bottom:<?= $i * 25 ?>%"><?= number_format($step * $i) ?></span><?php endfor; ?>
      </div>
      <div class="plot" role="img" aria-label="Bar chart of page views per <?= $hourly ? 'hour' : 'day' ?>; the table below the chart lists every visit">
        <?php for ($i = 1; $i <= 4; $i++): ?><div class="gl" style="bottom:<?= $i * 25 ?>%"></div><?php endfor; ?>
        <?php foreach ($series as $p): ?>
        <div class="col" tabindex="0" data-tip="<?= e($p['full']) ?>|<?= number_format($p['views']) ?> page views · <?= number_format($p['people']) ?> visitors"><i style="height:<?= round($p['views'] / $top * 100, 2) ?>%"></i></div>
        <?php endforeach; ?>
        <div class="tip" data-tipbox></div>
      </div>
    </div>
    <div class="xaxis" aria-hidden="true">
      <?php foreach ($series as $i => $p): ?><span><?= ($i % $every === 0 || $i === count($series) - 1) ? e($p['label']) : '' ?></span><?php endforeach; ?>
    </div>
  </section>

  <div class="grid2">
    <?= $list('Most visited pages', $pages) ?>
    <?= $list('Where visitors came from', $sources) ?>
  </div>
  <div class="grid3">
    <?= $list('Locations', $places) ?>
    <?= $list('Devices', $devices) ?>
    <?= $list('Browsers', $browsers) ?>
  </div>

  <section class="card log">
    <h2>Every visit · <?= e(strtolower($rangeLabel)) ?> <span class="muted" style="font-weight:400">(<?= number_format($total) ?><?= $total > count($recent) ? ', newest ' . count($recent) . ' shown' : '' ?>)</span></h2>
    <?php if (!$recent): ?><p class="muted">No visits in this period yet.</p><?php else: ?>
    <div class="scroll">
      <table>
        <thead><tr><th>When</th><th>Location</th><th>IP address</th><th>Page</th><th>Came from</th><th>Device</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $r): ?>
          <tr>
            <td style="white-space:nowrap"><?= e(date('j M, g:i a', strtotime((string) $r['created_at']))) ?></td>
            <td><?= e(InsightsController::place($r)) ?><?php if (!empty($r['org'])): ?><div class="muted" style="font-size:12px"><?= e($r['org']) ?></div><?php endif; ?></td>
            <td class="ip"><?= e($r['ip']) ?></td>
            <td><?= e($r['path']) ?></td>
            <td><?= e(Insights::source($r['referrer'])) ?></td>
            <td><?= e($r['device'] . ' · ' . $r['browser'] . ' · ' . $r['os']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </section>

  <p class="foot muted">Counts real people's page views on the public website. Search-engine bots, admin users and this page are not counted. A "visitor" is one device and browser per day. Locations are approximate (from the IP address), looked up a few at a time when this page opens. Visit records are deleted automatically after <?= Insights::RETAIN_DAYS ?> days.</p>
</div>
<script>
(() => {
  const box = document.querySelector('[data-tipbox]');
  const plot = box && box.parentElement;
  if (!box) return;
  const show = (col) => {
    const [a, b] = col.dataset.tip.split('|');
    const head = document.createElement('b');
    head.textContent = a;
    box.replaceChildren(head, document.createElement('br'), document.createTextNode(b));
    const pr = plot.getBoundingClientRect(), cr = col.getBoundingClientRect(), bar = col.firstElementChild.getBoundingClientRect();
    let x = cr.left - pr.left + cr.width / 2;
    const half = box.offsetWidth / 2;
    x = Math.max(half, Math.min(pr.width - half, x));
    box.style.left = x + 'px';
    box.style.top = Math.max(0, bar.top - pr.top - 8) + 'px';
    box.classList.add('on');
  };
  plot.querySelectorAll('.col').forEach((c) => {
    c.addEventListener('mouseenter', () => show(c));
    c.addEventListener('focus', () => show(c));
    c.addEventListener('click', () => show(c));
  });
  plot.addEventListener('mouseleave', () => box.classList.remove('on'));
})();
</script>
</body>
</html>
