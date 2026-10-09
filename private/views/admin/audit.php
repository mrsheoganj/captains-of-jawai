<div class="card table-card"><div class="table-wrap">
<table class="table">
  <thead><tr><th>When</th><th>User</th><th>Action</th><th>Item</th><th>Details</th><th>IP</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td title="<?= e($r['created_at']) ?>"><?= e(fmt_date($r['created_at'], 'j M Y, H:i')) ?></td>
    <td><?= e($r['user_name'] ?? '—') ?></td>
    <td><span class="mono small"><?= e($r['action']) ?></span></td>
    <td><?= e($r['entity']) ?><?= $r['entity_id'] ? ' #' . (int) $r['entity_id'] : '' ?></td>
    <td class="clip small"><?= e(excerpt((string) $r['details'], 120)) ?></td>
    <td class="small muted"><?= e($r['ip']) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table></div></div>
<?= \App\Core\View::partial('admin/partials/pager', ['total' => $total, 'per' => 100, 'page' => $page]) ?>
