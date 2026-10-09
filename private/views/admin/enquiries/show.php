<?php
use App\Admin\EnquiryController;
use App\Core\Auth;

$wa = preg_replace('/\D+/', '', (string) $e['phone']);
$first = explode(' ', trim($e['full_name']))[0];
$details = array_filter([
    'Email' => $e['email'], 'Phone / WhatsApp' => $e['phone'], 'Country' => $e['country'],
    'Interests' => $e['interests'], 'Travel window' => $e['travel_window'], 'Length of stay' => $e['nights'],
    'Party' => $e['type'] === 'journey' ? (int) $e['adults'] . ' adult(s), ' . (int) $e['children'] . ' child(ren)' : '',
    'Private vehicle' => $e['type'] === 'journey' ? $e['private_vehicle'] : '',
    'Accommodation' => $e['accommodation'], 'Transfer' => $e['transfer'],
], fn ($v) => $v !== null && $v !== '');
?>
<div class="page-actions">
  <a class="btn btn-light btn-sm" href="<?= e(admin_url('enquiries')) ?>"><?= icon('arrow-left', 15) ?> All enquiries</a>
  <span class="spacer"></span>
  <?php if ($prevId): ?><a class="btn btn-light btn-sm" href="<?= e(admin_url('enquiries/' . $prevId)) ?>" title="Older"><?= icon('chevron-left', 15) ?></a><?php endif; ?>
  <?php if ($nextId): ?><a class="btn btn-light btn-sm" href="<?= e(admin_url('enquiries/' . $nextId)) ?>" title="Newer"><?= icon('chevron-right', 15) ?></a><?php endif; ?>
</div>

<div class="detail-layout">
  <div class="stack">
    <section class="card">
      <div class="lead-head">
        <div class="avatar avatar-lg"><?= e(mb_strtoupper(mb_substr($e['full_name'], 0, 1))) ?></div>
        <div>
          <h2><?= e($e['full_name']) ?></h2>
          <p class="muted"><span class="mono"><?= e($e['code']) ?></span> · <?= e(ucfirst($e['type'])) ?> enquiry · <?= e(fmt_date($e['created_at'], 'j M Y, H:i')) ?> (<?= e(time_ago($e['created_at'])) ?>)</p>
        </div>
        <?= status_badge($e['status']) ?>
      </div>
      <div class="quick-actions">
        <a class="btn btn-light" href="mailto:<?= e($e['email']) ?>?subject=<?= e(rawurlencode('Your Jawai journey — ' . $e['code'])) ?>&body=<?= e(rawurlencode("Dear $first,\n\n")) ?>"><?= icon('mail', 16) ?> Email</a>
        <?php if ($wa): ?><a class="btn btn-whatsapp" href="https://wa.me/<?= e($wa) ?>?text=<?= e(rawurlencode("Hello $first, this is " . (Auth::user()['name'] ?? '') . ' from ' . setting('site_name') . ' regarding your enquiry ' . $e['code'] . '.')) ?>" target="_blank" rel="noopener"><?= icon('whatsapp', 16) ?> WhatsApp</a>
        <a class="btn btn-light" href="tel:<?= e(preg_replace('/[^\d+]/', '', $e['phone'])) ?>"><?= icon('phone', 16) ?> Call</a><?php endif; ?>
      </div>
      <dl class="dl">
        <?php foreach ($details as $k => $v): ?><dt><?= e($k) ?></dt><dd><?= $k === 'Email' ? '<a href="mailto:' . e($v) . '">' . e($v) . '</a>' : e($v) ?></dd><?php endforeach; ?>
      </dl>
      <?php if ($e['message']): ?><h3 class="sub">Message</h3><div class="message-box"><?= nl2br(e($e['message'])) ?></div><?php endif; ?>
    </section>

    <section class="card" id="notes">
      <div class="card-head"><h2>Notes &amp; activity</h2></div>
      <?php if ($canEdit): ?>
      <form method="post" action="<?= e(admin_url('enquiries/' . $e['id'] . '/note')) ?>" class="note-form">
        <?= csrf_field() ?>
        <textarea name="note" rows="3" placeholder="Log a call, WhatsApp conversation, quote or special requirement…" required></textarea>
        <button class="btn btn-primary btn-sm" type="submit">Add note</button>
      </form>
      <?php endif; ?>
      <ul class="timeline-admin">
        <?php foreach ($notes as $n): ?>
        <li class="t-note"><div><strong><?= e($n['user_name'] ?? 'Someone') ?></strong> <small class="muted"><?= e(fmt_date($n['created_at'], 'j M Y, H:i')) ?></small></div><p><?= nl2br(e($n['note'])) ?></p></li>
        <?php endforeach; ?>
        <?php foreach ($history as $h): $d = json_decode((string) $h['details'], true) ?: []; ?>
        <li class="t-event"><small><strong><?= e($h['user_name'] ?? 'System') ?></strong> · <?= e(str_replace('_', ' ', $h['action'])) ?><?php foreach ($d as $k => $v): if (is_array($v) && count($v) === 2 && array_is_list($v)): ?> · <?= e($k) ?>: <?= e((string) ($v[0] ?? '—')) ?> → <?= e((string) ($v[1] ?? '—')) ?><?php endif; endforeach; ?> · <?= e(fmt_date($h['created_at'], 'j M, H:i')) ?></small></li>
        <?php endforeach; ?>
        <li class="t-event"><small>Enquiry received via <?= e($e['source_page'] ?: 'website') ?> · <?= e(fmt_date($e['created_at'], 'j M Y, H:i')) ?></small></li>
      </ul>
    </section>
  </div>

  <aside class="stack">
    <form class="card" method="post" action="<?= e(admin_url('enquiries/' . $e['id'])) ?>">
      <?= csrf_field() ?>
      <div class="card-head"><h2>Pipeline</h2></div>
      <div class="field"><label>Status</label>
        <select name="status" <?= $canEdit ? '' : 'disabled' ?>><?php foreach (EnquiryController::STATUSES as $k => $label): ?><option value="<?= e($k) ?>"<?= $e['status'] === $k ? ' selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
      </div>
      <div class="field"><label>Assigned to</label>
        <select name="assigned_user_id" <?= $canEdit ? '' : 'disabled' ?>><option value="">— Unassigned —</option><?php foreach ($users as $usr): ?><option value="<?= (int) $usr['id'] ?>"<?= (int) $e['assigned_user_id'] === (int) $usr['id'] ? ' selected' : '' ?>><?= e($usr['name']) ?></option><?php endforeach; ?></select>
      </div>
      <div class="field"><label>Next follow-up</label><input type="date" name="follow_up_date" value="<?= e($e['follow_up_date']) ?>" <?= $canEdit ? '' : 'disabled' ?>></div>
      <?php if ($canEdit): ?><button class="btn btn-primary btn-block" type="submit">Save changes</button><?php endif; ?>
      <?php if ($e['first_contacted_at']): ?><p class="muted small">First contacted <?= e(fmt_date($e['first_contacted_at'], 'j M, H:i')) ?> — response time <?= e(round((strtotime($e['first_contacted_at']) - strtotime($e['created_at'])) / 3600, 1)) ?>h</p><?php endif; ?>
    </form>

    <section class="card">
      <div class="card-head"><h2>Source</h2></div>
      <dl class="dl dl-compact">
        <dt>Page</dt><dd><?= e($e['source_page'] ?: '—') ?></dd>
        <?php if ($e['utm_source'] || $e['utm_campaign']): ?><dt>Campaign</dt><dd><?= e(trim($e['utm_source'] . ' / ' . $e['utm_medium'] . ' / ' . $e['utm_campaign'], ' /')) ?></dd><?php endif; ?>
        <dt>IP</dt><dd><?= e($e['ip']) ?></dd>
      </dl>
      <?php if ($others): ?>
      <h3 class="sub">Previous enquiries from this email</h3>
      <ul class="plain-list"><?php foreach ($others as $o): ?><li><a href="<?= e(admin_url('enquiries/' . $o['id'])) ?>"><?= e($o['code']) ?></a> · <?= e(fmt_date($o['created_at'])) ?> · <?= status_badge($o['status']) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>
    </section>

    <?php if ($canEdit): ?>
    <section class="card">
      <div class="card-head"><h2>Tools</h2></div>
      <form method="post" action="<?= e(admin_url('enquiries/' . $e['id'])) ?>"><?= csrf_field() ?><input type="hidden" name="action" value="resend"><input type="hidden" name="status" value="<?= e($e['status']) ?>"><button class="btn btn-light btn-block" type="submit"><?= icon('send', 15) ?> Re-send notification emails</button></form>
      <?php if (in_array(Auth::role(), ['super_admin', 'manager'], true)): ?>
      <form method="post" action="<?= e(admin_url('enquiries/' . $e['id'] . '/delete')) ?>" class="mt-sm"><?= csrf_field() ?><button class="btn btn-danger-ghost btn-block" type="submit" data-confirm="Delete this enquiry and its notes permanently?"><?= icon('trash', 15) ?> Delete enquiry</button></form>
      <?php endif; ?>
    </section>
    <?php endif; ?>
  </aside>
</div>
