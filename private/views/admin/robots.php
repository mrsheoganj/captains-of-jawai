<form method="post" action="<?= e(admin_url('seo/robots')) ?>" class="card form">
  <?= csrf_field() ?>
  <div class="card-head"><h2>robots.txt</h2><a class="link" href="/robots.txt" target="_blank">View live</a></div>
  <p class="muted">Leave empty to use the recommended default. The sitemap line is added automatically. When “Hide entire site from search engines” is on in SEO settings, everything is blocked regardless.</p>
  <div class="field"><textarea name="robots_txt" rows="12" class="code" spellcheck="false" placeholder="<?= e($default) ?>"><?= e($value) ?></textarea></div>
  <details class="help-box"><summary>Default rules</summary><pre><?= e($default) ?></pre></details>
  <button class="btn btn-primary" type="submit">Save robots.txt</button>
</form>
