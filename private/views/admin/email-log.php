<div class="page-actions"><p class="muted">The last 200 emails the website tried to send.</p><span class="spacer"></span><a class="btn btn-light btn-sm" href="<?= e(admin_url('settings/email')) ?>"><?= icon('settings', 15) ?> Email settings</a></div>
<div class="card table-card"><div class="table-wrap">
<table class="table">
  <thead><tr><th>When</th><th>To</th><th>Subject</th><th>Type</th><th>Status</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td title="<?= e($r['created_at']) ?>"><?= e(time_ago($r['created_at'])) ?></td>
      <td class="clip"><?= e($r['to_email']) ?></td>
      <td><?= e($r['subject']) ?><?php if ($r['error']): ?><br><small class="text-danger"><?= e($r['error']) ?></small><?php endif; ?></td>
      <td><span class="mono small"><?= e($r['context']) ?></span></td>
      <td><?= status_badge($r['status']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div>
<?php if (!$rows): ?><div class="empty-state"><?= icon('send', 36) ?><p>No emails sent yet.</p></div><?php endif; ?>
</div>
