<?php use App\Core\Auth; ?>
<form method="post" action="<?= e(admin_url($id ? 'users/' . $id : 'users/new')) ?>" class="card form narrow-card">
  <?= csrf_field() ?>
  <div class="grid-2">
    <div class="field"><label>Name</label><input name="name" value="<?= e($u['name'] ?? '') ?>" required></div>
    <div class="field"><label>Email (login)</label><input type="email" name="email" value="<?= e($u['email'] ?? '') ?>" required></div>
    <div class="field"><label>Role</label><select name="role"><?php foreach (Auth::ROLES as $k => $l): ?><option value="<?= e($k) ?>"<?= ($u['role'] ?? '') === $k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select></div>
    <div class="field"><label><?= $id ? 'New password (leave blank to keep)' : 'Password (min. 10 characters)' ?></label><input type="password" name="password" autocomplete="new-password" <?= $id ? '' : 'required minlength="10"' ?>></div>
  </div>
  <label class="switch"><input type="checkbox" name="is_active" value="1"<?= !empty($u['is_active']) ? ' checked' : '' ?>><span class="switch-ui"></span><span>Account active</span></label>
  <div class="row-actions mt">
    <button class="btn btn-primary" type="submit">Save user</button>
    <a class="btn btn-light" href="<?= e(admin_url('users')) ?>">Cancel</a>
    <?php if ($id && $id !== Auth::id()): ?><span class="spacer"></span><button class="btn btn-danger-ghost" type="submit" formaction="<?= e(admin_url('users/' . $id . '/delete')) ?>" data-confirm="Delete this user?"><?= icon('trash', 15) ?> Delete</button><?php endif; ?>
  </div>
</form>
