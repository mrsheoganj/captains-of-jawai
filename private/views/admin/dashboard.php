<?php
use App\Admin\EnquiryController;

$delta = $prev7 ? round(($new7 - $prev7) / $prev7 * 100) : null;
$max = max(1, ...array_values($daily));
?>
<div class="kpis">
  <div class="kpi">
    <span class="kpi-label">New enquiries · 7 days</span>
    <strong><?= $new7 ?></strong>
    <?php if ($delta !== null): ?><span class="kpi-delta <?= $delta >= 0 ? 'up' : 'down' ?>"><?= $delta >= 0 ? '▲' : '▼' ?> <?= abs($delta) ?>% vs previous 7 days</span><?php else: ?><span class="kpi-delta">—</span><?php endif; ?>
  </div>
  <div class="kpi"><span class="kpi-label">Active proposals</span><strong><?= $active ?></strong><span class="kpi-delta">Qualified, proposal sent or follow-up</span></div>
  <div class="kpi"><span class="kpi-label">Conversion · 30 days</span><strong><?= $total30 ? round($won30 / $total30 * 100) : 0 ?>%</strong><span class="kpi-delta"><?= $won30 ?> confirmed of <?= $total30 ?></span></div>
  <div class="kpi"><span class="kpi-label">Avg. first response</span><strong><?= $avgResp === null ? '—' : ($avgResp < 1 ? round($avgResp * 60) . 'm' : round($avgResp, 1) . 'h') ?></strong><span class="kpi-delta <?= $avgResp !== null && $avgResp > 12 ? 'down' : '' ?>">Target: under 12 hours</span></div>
</div>

<?php if ($mailFailures): ?>
<div class="alert alert-error"><?= $mailFailures ?> email(s) failed to send in the last 7 days. <a href="<?= e(admin_url('email-log')) ?>">Open the email log</a> and check <a href="<?= e(admin_url('settings/email')) ?>">SMTP settings</a>.</div>
<?php endif; ?>

<div class="dash-grid">
  <section class="card">
    <div class="card-head"><h2>Needs attention</h2><a class="link" href="<?= e(admin_url('enquiries?status=open')) ?>">All open</a></div>
    <?php if ($priority): ?>
    <ul class="lead-list">
      <?php foreach ($priority as $p): ?>
      <li>
        <a href="<?= e(admin_url('enquiries/' . $p['id'])) ?>">
          <span class="lead-name"><?= e($p['full_name']) ?> <small class="mono"><?= e($p['code']) ?></small></span>
          <span class="lead-meta"><?= status_badge($p['status']) ?> <?= $p['status'] === 'new' ? 'waiting ' . e(time_ago($p['created_at'])) : 'follow-up due ' . e(fmt_date($p['follow_up_date'])) ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php else: ?><p class="muted">Nothing overdue. New enquiries older than 6 hours and due follow-ups appear here.</p><?php endif; ?>
  </section>

  <section class="card">
    <div class="card-head"><h2>Enquiries · last 30 days</h2></div>
    <div class="bars" role="img" aria-label="Enquiries per day">
      <?php foreach ($daily as $day => $n): ?><span style="--h:<?= round($n / $max * 100) ?>%" title="<?= e(fmt_date($day)) ?>: <?= $n ?>"></span><?php endforeach; ?>
    </div>
    <div class="bars-axis"><span><?= e(fmt_date(array_key_first($daily), 'j M')) ?></span><span>Today</span></div>
    <div class="pipeline">
      <?php foreach (EnquiryController::STATUSES as $k => $label): if ($k === 'spam') continue; ?>
      <a href="<?= e(admin_url('enquiries?status=' . $k)) ?>"><strong><?= $byStatus[$k] ?? 0 ?></strong><span><?= e($label) ?></span></a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="card span-2">
    <div class="card-head"><h2>Latest enquiries</h2><a class="link" href="<?= e(admin_url('enquiries')) ?>">Open CRM</a></div>
    <?php if ($latest): ?>
    <div class="table-wrap"><table class="table">
      <thead><tr><th>Guest</th><th>Interests</th><th>Travel</th><th>Status</th><th>Received</th></tr></thead>
      <tbody>
      <?php foreach ($latest as $e): ?>
        <tr data-href="<?= e(admin_url('enquiries/' . $e['id'])) ?>">
          <td><a href="<?= e(admin_url('enquiries/' . $e['id'])) ?>"><strong><?= e($e['full_name']) ?></strong></a><br><small class="muted"><?= e($e['country']) ?> · <?= e($e['type']) ?></small></td>
          <td><?= e(excerpt($e['interests'] ?: $e['message'], 60)) ?></td>
          <td><?= e($e['travel_window']) ?></td>
          <td><?= status_badge($e['status']) ?></td>
          <td><?= e(time_ago($e['created_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php else: ?><p class="muted">No enquiries yet. Test the form on <a href="/plan-your-journey/" target="_blank">Plan Your Journey</a>.</p><?php endif; ?>
  </section>

  <?php if ($setup): ?>
  <section class="card">
    <div class="card-head"><h2>Launch checklist</h2></div>
    <ul class="todo">
      <?php foreach ($setup as [$label, $url]): ?><li><a href="<?= e($url) ?>"><?= icon('alert', 16) ?> <?= e($label) ?></a></li><?php endforeach; ?>
    </ul>
  </section>
  <?php endif; ?>

  <section class="card">
    <div class="card-head"><h2>Where guests come from</h2></div>
    <?php if ($byCountry): $cmax = max(array_column($byCountry, 'n')); ?>
    <ul class="hbars"><?php foreach ($byCountry as $c): ?><li><span><?= e($c['country']) ?></span><i style="--w:<?= round($c['n'] / $cmax * 100) ?>%"></i><b><?= (int) $c['n'] ?></b></li><?php endforeach; ?></ul>
    <?php else: ?><p class="muted">No data yet.</p><?php endif; ?>
    <div class="card-head mt"><h2>Published content</h2></div>
    <div class="mini-stats"><?php foreach ($content as $k => $v): ?><div><strong><?= $v ?></strong><span><?= e($k) ?></span></div><?php endforeach; ?></div>
  </section>
</div>
