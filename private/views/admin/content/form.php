<?php
use App\Core\View;

$main = array_filter($r['fields'], fn ($f) => empty($f[2]['side']));
$side = array_filter($r['fields'], fn ($f) => !empty($f[2]['side']));
$publicUrl = $id && !empty($r['public']) && !empty($row['slug']) ? str_replace('{slug}', $row['slug'], $r['public']) : null;
$field = fn (string $name, array $f) => View::partial('admin/partials/field', [
    'name' => $name, 'label' => $f[0], 'type' => $f[1], 'opt' => $f[2] ?? [], 'value' => $row[$name] ?? '', 'error' => $errors[$name] ?? null, 'lists' => $lists,
]);
?>
<form method="post" action="<?= e(admin_url('content/' . $r['key'] . '/' . ($id ?: 'new'))) ?>" class="form" data-dirty>
  <?= csrf_field() ?>
  <div class="page-actions sticky-actions">
    <a class="btn btn-light btn-sm" href="<?= e(admin_url('content/' . $r['key'])) ?>"><?= icon('arrow-left', 15) ?> <?= e($r['label']) ?></a>
    <span class="spacer"></span>
    <?php if ($publicUrl): ?><a class="btn btn-light btn-sm" href="<?= e($publicUrl) ?><?= ($row['status'] ?? 'published') !== 'published' ? '?preview=1' : '' ?>" target="_blank" rel="noopener"><?= icon('eye', 15) ?> View</a><?php endif; ?>
    <?php if ($canEdit): ?><button class="btn btn-primary" type="submit"><?= icon('check', 16) ?> Save</button><?php endif; ?>
  </div>

  <div class="edit-layout">
    <div class="stack">
      <section class="card">
        <?php foreach ($main as $name => $f): ?><?= $field($name, $f) ?><?php endforeach; ?>
      </section>

      <?php if (!empty($r['seo'])): ?>
      <section class="card" data-seo-box>
        <div class="card-head"><h2><?= icon('search', 18) ?> Search engine listing</h2></div>
        <div class="serp">
          <div class="serp-url"><?= e(parse_url(base_url(), PHP_URL_HOST) ?: 'captainsofjawai.com') ?> › <span data-serp-path><?= e(trim((string) ($publicUrl ?? ''), '/')) ?></span></div>
          <div class="serp-title" data-serp-title></div>
          <div class="serp-desc" data-serp-desc></div>
        </div>
        <div class="field">
          <label for="meta_title">Meta title <small class="counter" data-count="meta_title" data-max="60"></small></label>
          <input id="meta_title" name="meta_title" value="<?= e($row['meta_title'] ?? '') ?>" maxlength="255" placeholder="Defaults to the title" data-seo-title data-fallback="<?= e($r['title']) ?>" data-suffix="<?= e(setting('seo_title_suffix')) ?>">
          <small class="help">Aim for 50–60 characters. Include the main keyword (e.g. “Jawai leopard safari”).</small>
        </div>
        <div class="field">
          <label for="meta_description">Meta description <small class="counter" data-count="meta_description" data-max="155"></small></label>
          <textarea id="meta_description" name="meta_description" rows="3" maxlength="500" placeholder="Defaults to the summary" data-seo-desc data-fallback="<?= e(isset($r['fields']['excerpt']) ? 'excerpt' : (isset($r['fields']['intro']) ? 'intro' : '')) ?>"><?= e($row['meta_description'] ?? '') ?></textarea>
          <small class="help">Aim for 120–155 characters — a compelling summary that earns the click.</small>
        </div>
      </section>
      <?php endif; ?>
    </div>

    <aside class="stack">
      <?php if ($side): ?>
      <section class="card">
        <?php foreach ($side as $name => $f): ?><?= $field($name, $f) ?><?php endforeach; ?>
      </section>
      <?php endif; ?>
      <?php if ($id && $canEdit): ?>
      <section class="card">
        <div class="card-head"><h2>Actions</h2></div>
        <button class="btn btn-light btn-block" type="submit" form="dup-form"><?= icon('copy', 15) ?> Duplicate</button>
        <button class="btn btn-danger-ghost btn-block mt-sm" type="submit" form="del-form" data-confirm="Delete this <?= e(strtolower($r['singular'])) ?> permanently?"><?= icon('trash', 15) ?> Delete</button>
        <?php if (!empty($row['updated_at'])): ?><p class="muted small mt-sm">Last saved <?= e(fmt_date($row['updated_at'], 'j M Y, H:i')) ?></p><?php endif; ?>
      </section>
      <?php endif; ?>
    </aside>
  </div>
</form>
<?php if ($id && $canEdit): ?>
<form id="dup-form" method="post" action="<?= e(admin_url('content/' . $r['key'] . '/' . $id . '/duplicate')) ?>"><?= csrf_field() ?></form>
<form id="del-form" method="post" action="<?= e(admin_url('content/' . $r['key'] . '/' . $id . '/delete')) ?>"><?= csrf_field() ?></form>
<?php endif; ?>
