<div class="modal" hidden data-media-modal>
  <div class="modal-box modal-lg">
    <div class="modal-head">
      <h3>Choose an image</h3>
      <input type="search" placeholder="Search images…" data-media-search>
      <label class="btn btn-primary btn-sm upload-btn"><?= icon('upload', 15) ?> Upload<input type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple hidden data-media-upload></label>
      <button class="icon-btn" type="button" data-modal-close aria-label="Close"><?= icon('close', 20) ?></button>
    </div>
    <div class="modal-body"><div class="picker-grid" data-media-grid><p class="muted">Loading…</p></div></div>
    <div class="modal-foot"><span class="muted small" data-media-status></span><button class="btn btn-light" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="button" data-media-choose disabled>Use selected</button></div>
  </div>
</div>
