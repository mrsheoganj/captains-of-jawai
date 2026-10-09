<?php
$cats = ['Leopards', 'Landscape', 'Birds & Wetland', 'Wildlife', 'Culture', 'Safari Life'];
?>
<div class="page-actions">
  <a class="btn btn-light btn-sm" href="<?= e(admin_url('media')) ?>"><?= icon('arrow-left', 15) ?> Media library</a>
  <p class="muted">Search <strong>Wikimedia Commons</strong> for photos you are allowed to use commercially (CC BY, CC BY-SA, CC0, public domain). Imported photos keep the photographer credit and licence, which are shown on the gallery and the <a class="link" href="/photo-credits/" target="_blank">photo credits</a> page.</p>
</div>

<?php if ($canEdit): ?>
<section class="card pack-card" data-pack data-url="<?= e(admin_url('media/find/pack')) ?>" data-total="<?= count($presets) ?>">
  <div class="card-head"><h2><?= icon('image', 18) ?> Starter photo pack</h2><span class="muted small"><?= $imported ?> imported so far · <?= $placeholders ?> placeholder(s)</span></div>
  <p>One click: finds about 3 photos for each of <?= count($presets) ?> Jawai-related topics (leopards, Jawai Dam, granite hills, flamingos, cranes, crocodiles, Rabari herders, Ranakpur…), imports them into the gallery, and then puts them on the homepage, safaris, journeys, articles and pages <em>wherever a placeholder is still used</em>. Anything your team already chose is never overwritten.</p>
  <div class="row-actions">
    <label class="check-inline">Photos per topic <select data-pack-per><option>2</option><option selected>3</option><option>4</option><option>6</option></select></label>
    <label class="check-inline"><input type="checkbox" data-pack-apply checked> Use them across the website when finished</label>
    <span class="spacer"></span>
    <button class="btn btn-primary" type="button" data-pack-start><?= icon('download', 16) ?> Import starter photo pack</button>
  </div>
  <div class="pack-progress" hidden data-pack-progress>
    <div class="progress"><span data-pack-bar></span></div>
    <p class="small muted" data-pack-status></p>
    <div class="pack-thumbs" data-pack-thumbs></div>
    <ul class="small text-danger" data-pack-errors></ul>
  </div>
  <form method="post" action="<?= e(admin_url('media/find/apply')) ?>" class="mt-sm"><?= csrf_field() ?><button class="btn btn-light btn-sm" type="submit"><?= icon('check', 14) ?> Use already-imported photos across the website</button></form>
</section>
<?php endif; ?>

<section class="card">
  <form method="get" class="find-form">
    <div class="search"><?= icon('search', 16) ?><input type="search" name="q" value="<?= e($q) ?>" placeholder="e.g. Jawai leopard, Jawai dam, Rabari, flamingo…" autofocus></div>
    <button class="btn btn-primary" type="submit">Search</button>
  </form>
  <div class="chips-admin">
    <?php foreach ($presets as [$label, $query]): ?><a class="chip-admin<?= $q === $query ? ' is-active' : '' ?>" href="?q=<?= e(rawurlencode($query)) ?>"><?= e($label) ?></a><?php endforeach; ?>
  </div>
</section>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?> Your server needs outbound internet access (normal on GoDaddy). You can always upload your own photos in the media library.</div><?php endif; ?>

<?php if ($q !== '' && !$error): ?>
<form method="post" action="<?= e(admin_url('media/find/import')) ?>" data-find-results>
  <?= csrf_field() ?>
  <input type="hidden" name="q" value="<?= e($q) ?>">
  <div class="page-actions sticky-actions">
    <strong><?= count($items) ?> freely-licensed result(s)</strong>
    <span class="spacer"></span>
    <?php if ($canEdit && $items): ?>
    <label class="check-inline">Gallery category <input name="gallery_category" list="find-cats" value="<?= e($presets[array_search($q, array_column($presets, 1), true)][2] ?? '') ?>" class="input-sm"></label>
    <label class="check-inline"><input type="checkbox" name="in_gallery" value="1" checked> Show in gallery</label>
    <button class="btn btn-primary" type="submit" data-find-submit disabled><?= icon('download', 15) ?> Import selected (<span data-find-count>0</span>)</button>
    <?php endif; ?>
  </div>
  <div class="find-grid">
    <?php foreach ($items as $it): ?>
    <label class="find-item<?= $it['imported'] ? ' is-imported' : '' ?>">
      <?php if (!$it['imported'] && $canEdit): ?><input type="checkbox" name="items[]" value="<?= e(base64_encode(json_encode($it))) ?>" data-find-check><?php endif; ?>
      <img src="<?= e($it['thumb']) ?>" alt="<?= e($it['name']) ?>" loading="lazy">
      <span class="find-meta">
        <strong><?= e(excerpt($it['name'], 70)) ?></strong>
        <small><?= e(excerpt($it['artist'], 50)) ?> · <?= e($it['license']) ?> · <?= (int) $it['width'] ?>×<?= (int) $it['height'] ?></small>
        <a href="<?= e($it['page']) ?>" target="_blank" rel="noopener" class="small link">Source ↗</a>
        <?php if ($it['imported']): ?><b class="find-done">Imported</b><?php endif; ?>
      </span>
    </label>
    <?php endforeach; ?>
  </div>
  <?php if (!$items): ?><div class="card empty-state"><?= icon('search', 36) ?><p>No freely-licensed photos found. Try a broader search (e.g. “leopard Rajasthan”).</p></div><?php endif; ?>
</form>
<?php endif; ?>
<datalist id="find-cats"><?php foreach ($cats as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?></datalist>
