<div class="card empty-state">
  <?= icon('shield', 40) ?>
  <h2><?= !empty($notFound) ? 'Page not found' : 'You do not have access to this section' ?></h2>
  <p class="muted"><?= !empty($notFound) ? 'The admin page you requested does not exist.' : 'Ask a Super Admin to change your role if you need access.' ?></p>
  <a class="btn btn-primary" href="<?= e(admin_url()) ?>">Back to dashboard</a>
</div>
