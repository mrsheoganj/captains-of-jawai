<?php
$ok = count(array_filter($checks, fn ($c) => $c[1]));
$score = $rows ? round((1 - min(1, $issueCount / (count($rows) * 3))) * 100) : 100;
?>
<div class="kpis">
  <div class="kpi"><span class="kpi-label">Content SEO score</span><strong><?= $score ?></strong><span class="kpi-delta <?= $score >= 80 ? 'up' : 'down' ?>"><?= $issueCount ?> suggestion(s) across <?= count($rows) ?> URLs</span></div>
  <div class="kpi"><span class="kpi-label">Site checks passed</span><strong><?= $ok ?>/<?= count($checks) ?></strong><span class="kpi-delta">Technical &amp; local SEO</span></div>
  <div class="kpi"><span class="kpi-label">Images without alt text</span><strong><?= $noAlt ?></strong><span class="kpi-delta"><a href="<?= e(admin_url('media?filter=noalt')) ?>">Fix in media library</a></span></div>
  <div class="kpi"><span class="kpi-label">Active redirects</span><strong><?= $redirects ?></strong><span class="kpi-delta"><a href="<?= e(admin_url('content/redirects')) ?>">Manage redirects</a></span></div>
</div>

<div class="dash-grid">
  <section class="card">
    <div class="card-head"><h2>Site checklist</h2></div>
    <ul class="checklist">
      <?php foreach ($checks as [$label, $pass, $url]): ?>
      <li class="<?= $pass ? 'pass' : 'fail' ?>"><span><?= $pass ? '✓' : '!' ?></span><a href="<?= e($url) ?>"><?= e($label) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <section class="card">
    <div class="card-head"><h2>Built-in SEO features</h2></div>
    <ul class="plain-list feature-list">
      <li>✓ Clean, canonical URLs with trailing slashes &amp; per-page canonical tags</li>
      <li>✓ Automatic <a href="/sitemap.xml" target="_blank">XML sitemap</a> (updates when you publish)</li>
      <li>✓ Editable <a href="<?= e(admin_url('seo/robots')) ?>">robots.txt</a> and 301 redirect manager</li>
      <li>✓ Schema.org JSON-LD: TravelAgency, WebSite, TouristTrip, BlogPosting, FAQPage, BreadcrumbList</li>
      <li>✓ Open Graph &amp; Twitter cards for social sharing</li>
      <li>✓ Responsive WebP images with alt text, lazy loading &amp; width/height (no layout shift)</li>
      <li>✓ Per-page meta title &amp; description with live Google preview</li>
      <li>✓ Google Analytics 4 &amp; Search Console verification in <a href="<?= e(admin_url('settings/seo')) ?>">SEO &amp; Tracking</a></li>
    </ul>
  </section>
</div>

<section class="card table-card">
  <div class="card-head pad"><h2>Every page at a glance</h2><span class="muted small">Click a row to edit its SEO fields</span></div>
  <div class="table-wrap">
  <table class="table">
    <thead><tr><th>Page</th><th>Meta title</th><th>Meta description</th><th>Suggestions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): $tl = mb_strlen($r['title']); $dl = mb_strlen($r['desc']); ?>
      <tr data-href="<?= e(admin_url('content/' . $r['res'] . '/' . $r['id'])) ?>">
        <td><a href="<?= e(admin_url('content/' . $r['res'] . '/' . $r['id'])) ?>"><strong><?= e($r['name']) ?></strong></a><br><small class="muted"><?= e($r['type']) ?> · <span class="mono"><?= e($r['url']) ?></span><?= $r['status'] !== 'published' ? ' · ' . e($r['status']) : '' ?></small></td>
        <td class="clip"><?= e($r['title'] ?: '—') ?><br><small class="<?= $tl > 60 || ($tl && $tl < 25) ? 'text-danger' : 'muted' ?>"><?= $tl ?>/60</small></td>
        <td class="clip"><?= e(excerpt($r['desc'], 90) ?: '—') ?><br><small class="<?= $dl > 160 || ($dl && $dl < 70) ? 'text-danger' : 'muted' ?>"><?= $dl ?>/155</small></td>
        <td><?php if ($r['issues']): ?><ul class="issues"><?php foreach ($r['issues'] as $i): ?><li><?= e($i) ?></li><?php endforeach; ?></ul><?php else: ?><span class="dot-yes">Looks good</span><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</section>
