<?php
use App\Admin\EnquiryController;
use App\Core\Auth;

$S = EnquiryController::STATUSES;
$qs = fn (array $over) => '?' . http_build_query(array_filter(array_merge($f, $over), fn ($v) => $v !== '' && $v !== null));
?>
<div class="tabs-scroll">
  <a class="tab<?= $f['status'] === '' ? ' is-active' : '' ?>" href="<?= e($qs(['status' => ''])) ?>">All <b><?= array_sum($counts) - ($counts['spam'] ?? 0) ?></b></a>
  <a class="tab<?= $f['status'] === 'open' ? ' is-active' : '' ?>" href="<?= e($qs(['status' => 'open'])) ?>">Open</a>
  <?php foreach ($S as $k => $label): ?>
  <a class="tab<?= $f['status'] === $k ? ' is-active' : '' ?>" href="<?= e($qs(['status' => $k])) ?>"><?= e($label) ?> <b><?= $counts[$k] ?? 0 ?></b></a>
  <?php endforeach; ?>
</div>

<form class="toolbar card" method="get">
  <input type="hidden" name="status" value="<?= e($f['status']) ?>">
  <div class="search"><?= icon('search', 16) ?><input type="search" name="q" value="<?= e($f['q']) ?>" placeholder="Search name, email, phone, reference…"></div>
  <select name="type"><option value="">All types</option><option value="journey"<?= $f['type'] === 'journey' ? ' selected' : '' ?>>Journey enquiries</option><option value="contact"<?= $f['type'] === 'contact' ? ' selected' : '' ?>>Contact messages</option></select>
  <select name="assigned"><option value="">Anyone</option><option value="me"<?= $f['assigned'] === 'me' ? ' selected' : '' ?>>Assigned to me</option><option value="none"<?= $f['assigned'] === 'none' ? ' selected' : '' ?>>Unassigned</option></select>
  <input type="date" name="from" value="<?= e($f['from']) ?>" aria-label="From date">
  <input type="date" name="to" value="<?= e($f['to']) ?>" aria-label="To date">
  <button class="btn btn-light" type="submit">Filter</button>
  <a class="btn btn-light" href="<?= e(admin_url('enquiries/export') . $qs([])) ?>"><?= icon('download', 15) ?> CSV</a>
</form>

<form method="post" action="<?= e(admin_url('enquiries/bulk')) ?>" class="card table-card" data-bulk>
  <?= csrf_field() ?>
  <?php if (Auth::can('enquiries', 'edit')): ?>
  <div class="bulkbar" data-bulkbar hidden>
    <span data-bulk-count>0 selected</span>
    <select name="bulk_action">
      <option value="">Bulk action…</option>
      <option value="assign_me">Assign to me</option>
      <?php foreach ($S as $k => $label): ?><option value="<?= e($k) ?>">Mark as: <?= e($label) ?></option><?php endforeach; ?>
      <?php if (in_array(Auth::role(), ['super_admin', 'manager'], true)): ?><option value="delete">Delete permanently</option><?php endif; ?>
    </select>
    <button class="btn btn-primary btn-sm" type="submit" data-confirm="Apply this action to the selected enquiries?">Apply</button>
  </div>
  <?php endif; ?>
  <div class="table-wrap">
  <table class="table">
    <thead><tr><th class="w-check"><input type="checkbox" data-check-all aria-label="Select all"></th><th>Guest</th><th>Reference</th><th>Interests / message</th><th>Travel</th><th>Party</th><th>Status</th><th>Owner</th><th>Received</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $e): ?>
      <tr class="<?= $e['status'] === 'new' ? 'is-new' : '' ?>" data-href="<?= e(admin_url('enquiries/' . $e['id'])) ?>">
        <td class="w-check"><input type="checkbox" name="ids[]" value="<?= (int) $e['id'] ?>" data-check aria-label="Select"></td>
        <td><a href="<?= e(admin_url('enquiries/' . $e['id'])) ?>"><strong><?= e($e['full_name']) ?></strong></a><br><small class="muted"><?= e($e['email']) ?></small></td>
        <td><span class="mono"><?= e($e['code']) ?></span><br><small class="muted"><?= e(ucfirst($e['type'])) ?></small></td>
        <td class="clip"><?= e(excerpt($e['interests'] ?: $e['message'], 70)) ?></td>
        <td><?= e($e['travel_window']) ?><?php if ($e['nights']): ?><br><small class="muted"><?= e($e['nights']) ?></small><?php endif; ?></td>
        <td><?= $e['type'] === 'journey' ? (int) $e['adults'] . 'A' . ((int) $e['children'] ? ' ' . (int) $e['children'] . 'C' : '') : '—' ?></td>
        <td><?= status_badge($e['status']) ?><?php if ($e['follow_up_date']): ?><br><small class="<?= $e['follow_up_date'] <= date('Y-m-d') ? 'text-danger' : 'muted' ?>">↻ <?= e(fmt_date($e['follow_up_date'], 'j M')) ?></small><?php endif; ?></td>
        <td><?= e($e['assignee'] ?? '—') ?></td>
        <td title="<?= e($e['created_at']) ?>"><?= e(time_ago($e['created_at'])) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php if (!$rows): ?><div class="empty-state"><?= icon('inbox', 36) ?><p>No enquiries match these filters.</p></div><?php endif; ?>
</form>
<?= \App\Core\View::partial('admin/partials/pager', compact('total', 'per', 'page')) ?>
