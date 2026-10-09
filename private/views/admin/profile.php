<form method="post" action="<?= e(admin_url('profile')) ?>" class="card form narrow-card">
  <?= csrf_field() ?>
  <div class="card-head"><h2>Your details</h2></div>
  <div class="grid-2">
    <div class="field"><label>Name</label><input name="name" value="<?= e($u['name']) ?>" required></div>
    <div class="field"><label>Email (login)</label><input type="email" name="email" value="<?= e($u['email']) ?>" required></div>
  </div>
  <div class="card-head mt"><h2>Change password</h2></div>
  <div class="grid-2">
    <div class="field"><label>Current password</label><input type="password" name="current_password" autocomplete="current-password"></div>
    <div class="field"><label>New password (min. 10 characters)</label><input type="password" name="new_password" autocomplete="new-password" minlength="10"></div>
  </div>
  <button class="btn btn-primary" type="submit">Save profile</button>
</form>
