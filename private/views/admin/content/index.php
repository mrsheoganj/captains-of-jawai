<?php
use App\Core\Auth;

$canEdit = Auth::can($r['area'], 'edit');
$sortable = !empty($r['sortable']) && $q === '' && $status === '' && $canEdit;
?>
<div class="page-actions">
  <form class="search inline" method="get"><?= icon('search', 16) ?><input type="search" name="q" value="<?= e($q) ?>" placeholder="Search <?= e(strtolower($r['label'])) ?>…"><?php if ($status): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?></form>
  <?php if (isset($r['fields']['status'])): ?>
  <div class="seg-links">
    <a class="<?= $status === '' ? 'is-active' : '' ?>" href="?">All</a>
    <?php foreach ($r['fields']['status'][2]['choices'] as $k => $l): ?><a class="<?= $status === $k ? 'is-active' : '' ?>" href="?status=<?= e($k) ?>"><?= e($l) ?></a><?php endforeach; ?>
  </div>
  <?php endif; ?>
  <span class="spacer"></span>
  <?php if ($canEdit): ?><a class="btn btn-primary" href="<?= e(admin_url('content/' . $r['key'] . '/new')) ?>"><?= icon('plus', 16) ?> New <?= e(strtolower($r['singular'])) ?></a><?php endif; ?>
</div>

<?php if (!empty($r['empty']) && !$rows): ?><div class="alert alert-info"><?= e($r['empty']) ?></div><?php endif; ?>

<div class="card table-card">
  <div class="table-wrap">
  <table class="table" <?= $sortable ? 'data-sortable="' . e(admin_url('content/' . $r['key'] . '/reorder')) . '"' : '' ?>>
    <thead><tr>
      <?php if ($sortable): ?><th class="w-grip"></th><?php endif; ?>
      <?php if (!empty($r['image'])): ?><th class="w-thumb"></th><?php endif; ?>
      <?php foreach ($r['columns'] as $c => $label): ?><th><?= e($label) ?></th><?php endforeach; ?>
      <th class="w-actions"></th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $row): $edit = admin_url('content/' . $r['key'] . '/' . $row['id']); ?>
      <tr data-id="<?= (int) $row['id'] ?>" data-href="<?= e($edit) ?>">
        <?php if ($sortable): ?><td class="w-grip" data-grip title="Drag to reorder"><?= icon('grip', 16) ?></td><?php endif; ?>
        <?php if (!empty($r['image'])): ?><td class="w-thumb"><?php if ($row[$r['image']]): ?><img src="<?= e(media_url((int) $row[$r['image']], 'sm')) ?>" alt="" loading="lazy"><?php else: ?><span class="thumb-empty"><?= icon('image', 16) ?></span><?php endif; ?></td><?php endif; ?>
        <?php $firstCol = true; foreach ($r['columns'] as $c => $label): $v = $row[$c] ?? ''; ?>
          <td>
            <?php if ($firstCol): ?><a href="<?= e($edit) ?>"><strong><?= e(excerpt((string) $v, 90)) ?></strong></a>
              <?php if (!empty($r['public']) && isset($row['slug'])): ?><br><small class="muted mono"><?= e(str_replace('{slug}', $row['slug'], $r['public'])) ?></small><?php endif; ?>
            <?php elseif ($c === 'status'): ?><?= status_badge((string) $v) ?><?php if ($r['key'] === 'posts' && $v === 'published' && ($row['published_at'] ?? '') > now()): ?> <small class="muted">scheduled</small><?php endif; ?>
            <?php elseif (str_starts_with($c, 'is_')): ?><?= $v ? '<span class="dot-yes">Yes</span>' : '<span class="muted">—</span>' ?>
            <?php elseif ($c === 'published_at'): ?><?= e(fmt_date((string) $v)) ?>
            <?php elseif ($c === 'slug'): ?><span class="mono">/<?= e($v) ?>/</span>
            <?php else: ?><?= e(excerpt((string) $v, 60)) ?><?php endif; ?>
          </td>
        <?php $firstCol = false; endforeach; ?>
        <td class="w-actions">
          <?php if (!empty($r['public']) && isset($row['slug'])): ?><a class="icon-btn sm" href="<?= e(str_replace('{slug}', $row['slug'], $r['public'])) ?><?= ($row['status'] ?? '') !== 'published' ? '?preview=1' : '' ?>" target="_blank" rel="noopener" title="View"><?= icon('eye', 16) ?></a><?php endif; ?>
          <a class="icon-btn sm" href="<?= e($edit) ?>" title="Edit"><?= icon('edit', 16) ?></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php if (!$rows): ?><div class="empty-state"><?= icon($r['icon'], 36) ?><p>No <?= e(strtolower($r['label'])) ?> yet.</p><?php if ($canEdit): ?><a class="btn btn-primary" href="<?= e(admin_url('content/' . $r['key'] . '/new')) ?>">Create the first one</a><?php endif; ?></div><?php endif; ?>
</div>
<?php if ($sortable && count($rows) > 1): ?><p class="muted small">Drag rows by the handle to change the order on the website.</p><?php endif; ?>
<?= \App\Core\View::partial('admin/partials/pager', compact('total', 'per', 'page')) ?>
