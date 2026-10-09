<?php use App\Core\Auth; ?>
<div class="page-actions"><p class="muted">Give each team member their own login. Roles control what they can see and change.</p><span class="spacer"></span><a class="btn btn-primary" href="<?= e(admin_url('users/new')) ?>"><?= icon('plus', 16) ?> New user</a></div>
<div class="card table-card"><div class="table-wrap">
<table class="table">
  <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last login</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $u): ?>
    <tr data-href="<?= e(admin_url('users/' . $u['id'])) ?>">
      <td><a href="<?= e(admin_url('users/' . $u['id'])) ?>"><strong><?= e($u['name']) ?></strong></a><?= (int) $u['id'] === Auth::id() ? ' <small class="muted">(you)</small>' : '' ?></td>
      <td><?= e($u['email']) ?></td>
      <td><?= e(Auth::ROLES[$u['role']] ?? $u['role']) ?></td>
      <td><?= $u['is_active'] ? '<span class="dot-yes">Active</span>' : '<span class="muted">Disabled</span>' ?></td>
      <td><?= e($u['last_login_at'] ? time_ago($u['last_login_at']) : 'Never') ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div></div>
<section class="card">
  <div class="card-head"><h2>Role permissions</h2></div>
  <div class="table-wrap"><table class="table compact">
    <thead><tr><th>Role</th><th>Enquiries (CRM)</th><th>Content &amp; media</th><th>SEO</th><th>Settings</th><th>Email / SMTP</th><th>Users</th></tr></thead>
    <tbody>
      <tr><td>Super Admin</td><td>Full</td><td>Full</td><td>Full</td><td>Full</td><td>Full</td><td>Full</td></tr>
      <tr><td>Expedition Manager</td><td>Full</td><td>Full</td><td>Full</td><td>Full</td><td>—</td><td>—</td></tr>
      <tr><td>Sales / Concierge</td><td>View &amp; edit</td><td>—</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
      <tr><td>Content Editor</td><td>—</td><td>Full</td><td>Full</td><td>—</td><td>—</td><td>—</td></tr>
      <tr><td>Read Only</td><td>View</td><td>View</td><td>View</td><td>—</td><td>—</td><td>—</td></tr>
    </tbody>
  </table></div>
</section>
