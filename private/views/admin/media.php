<?php use App\Core\Media; ?>
<?php if ($canEdit): ?>
<form class="dropzone card" method="post" action="<?= e(admin_url('media/upload')) ?>" enctype="multipart/form-data" data-dropzone>
  <?= csrf_field() ?>
  <?= icon('upload', 30) ?>
  <p><strong>Drop images here</strong> or <label class="link">browse<input type="file" name="files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple hidden data-dropzone-input></label></p>
  <p class="muted small">JPG, PNG, WebP or GIF up to 15 MB (server limit <?= e(ini_get('upload_max_filesize')) ?>). Images are resized to 1600 / 800 / 400 px and converted to WebP automatically.</p>
  <label class="check-inline"><input type="checkbox" name="in_gallery" value="1"> Also show in the public gallery</label>
  <div class="progress" hidden data-dropzone-progress><span></span></div>
</form>
<?php endif; ?>

<div class="page-actions">
  <a class="btn btn-primary btn-sm" href="<?= e(admin_url('media/find')) ?>"><?= icon('search', 15) ?> Find Jawai photos (free licence)</a>
  <form class="search inline" method="get"><?= icon('search', 16) ?><input type="search" name="q" value="<?= e($q) ?>" placeholder="Search images…"><?php if ($filter): ?><input type="hidden" name="filter" value="<?= e($filter) ?>"><?php endif; ?></form>
  <div class="seg-links">
    <a class="<?= $filter === '' ? 'is-active' : '' ?>" href="?">All (<?= count($items) ?>)</a>
    <a class="<?= $filter === 'gallery' ? 'is-active' : '' ?>" href="?filter=gallery">In gallery</a>
    <a class="<?= $filter === 'noalt' ? 'is-active' : '' ?>" href="?filter=noalt">Missing alt text</a>
    <a class="<?= $filter === 'placeholder' ? 'is-active' : '' ?>" href="?filter=placeholder">Demo photos</a>
  </div>
</div>

<div class="media-grid">
  <?php foreach ($items as $m): ?>
  <details class="media-item" id="m<?= (int) $m['id'] ?>">
    <summary>
      <img src="<?= e(Media::url($m, 'sm')) ?>" alt="<?= e($m['alt_text']) ?>" loading="lazy">
      <span class="media-badges"><?php if ($m['in_gallery']): ?><b>Gallery</b><?php endif; ?><?php if (trim((string) $m['alt_text']) === ''): ?><b class="warn">No alt</b><?php endif; ?><?php if (str_starts_with($m['path'], 'assets/photos/')): ?><b>Demo photo</b><?php endif; ?></span>
    </summary>
    <form class="media-edit" method="post" action="<?= e(admin_url('media/' . $m['id'])) ?>">
      <?= csrf_field() ?>
      <div class="media-edit-head"><img src="<?= e(Media::url($m, 'md')) ?>" alt=""><div class="small muted"><?= e($m['filename']) ?><br><?= (int) $m['width'] ?>×<?= (int) $m['height'] ?> · ID <?= (int) $m['id'] ?><br><a href="<?= e(Media::url($m, 'lg')) ?>" target="_blank">Open full size</a></div></div>
      <div class="field"><label>Alt text (describe the image)</label><input name="alt_text" value="<?= e($m['alt_text']) ?>" <?= $canEdit ? '' : 'disabled' ?>></div>
      <div class="field"><label>Caption</label><input name="caption" value="<?= e($m['caption']) ?>" <?= $canEdit ? '' : 'disabled' ?>></div>
      <div class="grid-2">
        <div class="field"><label>Photographer / credit</label><input name="credit" value="<?= e($m['credit']) ?>" <?= $canEdit ? '' : 'disabled' ?>></div>
        <div class="field"><label>Licence</label><input name="license" value="<?= e($m['license']) ?>" list="licences" <?= $canEdit ? '' : 'disabled' ?>></div>
      </div>
      <label class="switch"><input type="checkbox" name="in_gallery" value="1"<?= $m['in_gallery'] ? ' checked' : '' ?> <?= $canEdit ? '' : 'disabled' ?>><span class="switch-ui"></span><span>Show in public gallery</span></label>
      <div class="grid-2">
        <div class="field"><label>Gallery category</label><input name="gallery_category" value="<?= e($m['gallery_category']) ?>" list="gcats" <?= $canEdit ? '' : 'disabled' ?>></div>
        <div class="field"><label>Gallery order</label><input type="number" name="sort_order" value="<?= (int) $m['sort_order'] ?>" <?= $canEdit ? '' : 'disabled' ?>></div>
      </div>
      <?php if ($canEdit): ?>
      <div class="row-actions">
        <button class="btn btn-primary btn-sm" type="submit">Save</button>
        <button class="btn btn-danger-ghost btn-sm" type="submit" formaction="<?= e(admin_url('media/' . $m['id'] . '/delete')) ?>" data-confirm="Delete this image? Pages using it will fall back to a default photo."><?= icon('trash', 14) ?> Delete</button>
        <button class="btn btn-text btn-sm" type="submit" name="force" value="1" formaction="<?= e(admin_url('media/' . $m['id'] . '/delete')) ?>" data-confirm="Delete even if the image is in use?">Force delete</button>
      </div>
      <?php endif; ?>
    </form>
  </details>
  <?php endforeach; ?>
</div>
<?php if (!$items): ?><div class="card empty-state"><?= icon('image', 36) ?><p>No images found.</p></div><?php endif; ?>
<datalist id="licences"><option value="Client Owned"><option value="Licensed Stock"><option value="CC BY 2.0"><option value="CC BY-SA 4.0"><option value="Unsplash License"><option value="Pexels License"></datalist>
<datalist id="gcats"><?php foreach ($cats as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?><option value="Leopards"><option value="Birds & Wetland"><option value="Landscape"><option value="Culture"><option value="Safari Life"></datalist>
