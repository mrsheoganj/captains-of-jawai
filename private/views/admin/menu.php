<?php use App\Admin\MenuController; ?>
<form method="post" action="<?= e(admin_url('menu')) ?>" data-menu-form>
  <?= csrf_field() ?>
  <input type="hidden" name="menus" data-menu-json>
  <div class="page-actions sticky-actions">
    <p class="muted small">Drag to reorder. In the main navigation, indent an item (→) to make it a dropdown child of the item above.</p>
    <span class="spacer"></span>
    <button class="btn btn-primary" type="submit"><?= icon('check', 16) ?> Save menus</button>
  </div>
  <div class="menu-grid">
    <?php foreach (MenuController::LOCATIONS as $loc => $label): ?>
    <section class="card" data-menu="<?= e($loc) ?>">
      <div class="card-head"><h2><?= e($label) ?></h2></div>
      <ol class="menu-list" data-menu-list>
        <?php foreach ($items[$loc] as $it): ?>
        <li class="menu-row<?= $it['parent_id'] ? ' is-child' : '' ?>" draggable="true">
          <span class="grip" title="Drag"><?= icon('grip', 16) ?></span>
          <input class="m-label" value="<?= e($it['label']) ?>" placeholder="Label" aria-label="Label">
          <input class="m-url" value="<?= e($it['url']) ?>" placeholder="/path/ or https://" list="menu-links" aria-label="Link">
          <span class="menu-opts">
            <?php if ($loc === 'header'): ?><button type="button" class="icon-btn sm" data-indent title="Make child / parent">⇥</button><?php endif; ?>
            <label title="Open in new tab"><input type="checkbox" class="m-new" <?= $it['new_tab'] ? 'checked' : '' ?>> new tab</label>
            <label title="Hide"><input type="checkbox" class="m-hidden" <?= $it['is_active'] ? '' : 'checked' ?>> hidden</label>
            <button type="button" class="icon-btn sm" data-remove title="Remove"><?= icon('trash', 15) ?></button>
          </span>
        </li>
        <?php endforeach; ?>
      </ol>
      <button type="button" class="btn btn-light btn-sm" data-add-item><?= icon('plus', 14) ?> Add link</button>
    </section>
    <?php endforeach; ?>
  </div>
</form>
<datalist id="menu-links"><?php foreach ($links as $url => $l): ?><option value="<?= e($url) ?>"><?= e($l) ?></option><?php endforeach; ?></datalist>
<template data-menu-template>
  <li class="menu-row" draggable="true">
    <span class="grip"><?= icon('grip', 16) ?></span>
    <input class="m-label" placeholder="Label" aria-label="Label">
    <input class="m-url" placeholder="/path/ or https://" list="menu-links" aria-label="Link">
    <span class="menu-opts">
      <button type="button" class="icon-btn sm" data-indent title="Make child / parent">⇥</button>
      <label><input type="checkbox" class="m-new"> new tab</label>
      <label><input type="checkbox" class="m-hidden"> hidden</label>
      <button type="button" class="icon-btn sm" data-remove title="Remove"><?= icon('trash', 15) ?></button>
    </span>
  </li>
</template>
