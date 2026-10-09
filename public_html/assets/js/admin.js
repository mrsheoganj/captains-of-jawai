/* Captains of Jawai — admin interactions (no dependencies). */
(function () {
  'use strict';
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => Array.from(el.querySelectorAll(s));
  const ADMIN = document.body.dataset.admin || '/admin';
  const TOKEN = ($('meta[name="csrf-token"]') || {}).content || '';
  const post = (url, data) => fetch(url, { method: 'POST', body: data, headers: { Accept: 'application/json', 'X-CSRF-Token': TOKEN }, credentials: 'same-origin' });

  /* Sidebar (mobile) */
  const sidebar = $('[data-sidebar]');
  $$('[data-sidebar-open]').forEach((b) => b.addEventListener('click', () => sidebar && sidebar.classList.add('is-open')));
  $$('[data-sidebar-close]').forEach((b) => b.addEventListener('click', () => sidebar && sidebar.classList.remove('is-open')));

  /* Alerts */
  $$('[data-dismiss-btn]').forEach((b) => b.addEventListener('click', () => b.closest('[data-dismiss]').remove()));
  $$('.alert-success[data-dismiss]').forEach((a) => setTimeout(() => { a.style.transition = 'opacity .4s'; a.style.opacity = 0; setTimeout(() => a.remove(), 400); }, 6000));

  /* Close user menu on outside click */
  document.addEventListener('click', (e) => { $$('details.user-menu[open]').forEach((d) => { if (!d.contains(e.target)) d.open = false; }); });

  /* Clickable rows */
  document.addEventListener('click', (e) => {
    const row = e.target.closest('tr[data-href]');
    if (!row || e.target.closest('a, button, input, select, label, textarea, [data-grip]')) return;
    if (e.metaKey || e.ctrlKey) window.open(row.dataset.href, '_blank'); else location.href = row.dataset.href;
  });

  /* Confirmations */
  document.addEventListener('click', (e) => {
    const el = e.target.closest('[data-confirm]');
    if (el && !confirm(el.dataset.confirm)) { e.preventDefault(); e.stopImmediatePropagation(); }
  }, true);

  /* Unsaved changes warning */
  $$('form[data-dirty]').forEach((form) => {
    let dirty = false;
    form.addEventListener('input', () => { dirty = true; });
    form.addEventListener('change', () => { dirty = true; });
    form.addEventListener('submit', () => { dirty = false; });
    window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
  });

  /* Bulk selection */
  const bulk = $('[data-bulk]');
  if (bulk) {
    const bar = $('[data-bulkbar]', bulk); const all = $('[data-check-all]', bulk); const count = $('[data-bulk-count]', bulk);
    const update = () => { const n = $$('[data-check]:checked', bulk).length; if (bar) { bar.hidden = n === 0; count.textContent = n + ' selected'; } };
    all && all.addEventListener('change', () => { $$('[data-check]', bulk).forEach((c) => { c.checked = all.checked; }); update(); });
    bulk.addEventListener('change', (e) => { if (e.target.matches('[data-check]')) update(); });
  }

  /* Slug auto-generation */
  const slugify = (s) => s.toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9/]+/g, '-').replace(/-+/g, '-').replace(/^[-/]+|[-/]+$/g, '');
  $$('[data-slug]').forEach((slug) => {
    const src = $('[name="' + slug.dataset.slug + '"]', slug.form);
    if (!src) return;
    let touched = slug.value !== '';
    slug.addEventListener('input', () => { touched = slug.value !== ''; });
    src.addEventListener('input', () => { if (!touched) { slug.value = slugify(src.value); slug.dispatchEvent(new Event('change')); } });
  });

  /* SEO preview & counters */
  const seo = $('[data-seo-box]');
  if (seo) {
    const form = seo.closest('form');
    const t = $('[data-seo-title]', seo); const d = $('[data-seo-desc]', seo);
    const titleSrc = $('[name="' + t.dataset.fallback + '"]', form);
    const descSrc = d.dataset.fallback ? $('[name="' + d.dataset.fallback + '"]', form) : null;
    const slug = $('[data-slug]', form); const pathEl = $('[data-serp-path]', seo);
    const counter = (name, len) => {
      const c = $('[data-count="' + name + '"]', seo); const max = +c.dataset.max;
      c.textContent = len + ' / ' + max; c.className = 'counter ' + (len > max ? 'over' : len >= max * 0.7 ? 'good' : '');
    };
    const render = () => {
      const title = t.value.trim() || ((titleSrc && titleSrc.value.trim()) || 'Untitled') + (t.dataset.suffix || '');
      const desc = d.value.trim() || (descSrc ? descSrc.value.trim() : '') || 'Add a meta description to control how this page appears in Google.';
      $('[data-serp-title]', seo).textContent = title;
      $('[data-serp-desc]', seo).textContent = desc;
      if (slug && pathEl) {
        const prefix = (slug.closest('.input-prefix').querySelector('span') || {}).textContent || '/';
        pathEl.textContent = (prefix + slug.value).replace(/^\/+|\/+$/g, '').split('/').join(' › ');
      }
      counter('meta_title', title.length); counter('meta_description', (d.value.trim() || (descSrc ? descSrc.value.trim() : '')).length);
    };
    form.addEventListener('input', render); render();
  }

  /* Colour pickers */
  $$('[data-color-sync]').forEach((picker) => {
    const text = document.getElementById(picker.dataset.colorSync);
    picker.addEventListener('input', () => { text.value = picker.value.toUpperCase(); });
    text.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(text.value)) picker.value = text.value; });
  });

  /* ---------------- Media picker modal ---------------- */
  const modal = $('[data-media-modal]');
  let pickerCallback = null; let pickerMulti = false; let selected = [];
  async function loadMedia(q = '') {
    const grid = $('[data-media-grid]', modal);
    grid.innerHTML = '<p class="muted">Loading…</p>';
    const res = await fetch(ADMIN + '/media/picker?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
    const data = await res.json();
    grid.innerHTML = '';
    if (!data.items.length) grid.innerHTML = '<p class="muted">No images yet — upload one.</p>';
    data.items.forEach((it) => {
      const b = document.createElement('button'); b.type = 'button'; b.title = it.alt || it.name;
      b.innerHTML = '<img loading="lazy" alt="">'; b.firstChild.src = it.url; b.dataset.id = it.id; b.dataset.url = it.url;
      if (selected.find((s) => s.id === it.id)) b.classList.add('is-selected');
      b.addEventListener('click', () => {
        if (!pickerMulti) { selected = []; $$('button', grid).forEach((x) => x.classList.remove('is-selected')); }
        const i = selected.findIndex((s) => s.id === it.id);
        if (i >= 0) { selected.splice(i, 1); b.classList.remove('is-selected'); } else { selected.push(it); b.classList.add('is-selected'); }
        $('[data-media-choose]', modal).disabled = selected.length === 0;
      });
      b.addEventListener('dblclick', () => { selected = [it]; choose(); });
      grid.appendChild(b);
    });
  }
  function openPicker(cb, multi = false) {
    if (!modal) return;
    pickerCallback = cb; pickerMulti = multi; selected = [];
    $('[data-media-choose]', modal).disabled = true;
    $('[data-media-status]', modal).textContent = multi ? 'Select one or more images' : 'Double-click to choose quickly';
    modal.hidden = false; loadMedia();
  }
  function closePicker() { modal.hidden = true; pickerCallback = null; }
  function choose() { if (pickerCallback && selected.length) pickerCallback(pickerMulti ? selected : selected[0]); closePicker(); }
  if (modal) {
    $$('[data-modal-close]', modal).forEach((b) => b.addEventListener('click', closePicker));
    modal.addEventListener('click', (e) => { if (e.target === modal) closePicker(); });
    $('[data-media-choose]', modal).addEventListener('click', choose);
    let timer; $('[data-media-search]', modal).addEventListener('input', (e) => { clearTimeout(timer); timer = setTimeout(() => loadMedia(e.target.value), 250); });
    $('[data-media-upload]', modal).addEventListener('change', async (e) => {
      const fd = new FormData(); Array.from(e.target.files).forEach((f) => fd.append('files[]', f)); fd.append('_token', TOKEN);
      const status = $('[data-media-status]', modal); status.textContent = 'Uploading…';
      try {
        const res = await post(ADMIN + '/media/upload', fd); const data = await res.json();
        status.textContent = data.errors && data.errors.length ? data.errors.join(' ') : 'Uploaded.';
        await loadMedia();
        (data.items || []).forEach((it) => { const b = $('[data-id="' + it.id + '"]', modal); if (b) b.click(); });
      } catch (err) { status.textContent = 'Upload failed.'; }
      e.target.value = '';
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.hidden) closePicker(); });
  }

  /* Single image fields */
  $$('[data-image-field]').forEach((f) => {
    const input = $('[data-image-input]', f); const prev = $('[data-image-preview]', f); const clear = $('[data-image-clear]', f);
    $('[data-image-pick]', f).addEventListener('click', () => openPicker((it) => {
      input.value = it.id; prev.innerHTML = '<img alt="">'; prev.firstChild.src = it.url; clear.hidden = false; input.dispatchEvent(new Event('change', { bubbles: true }));
    }));
    clear.addEventListener('click', () => { input.value = ''; prev.innerHTML = '<span>No image</span>'; clear.hidden = true; input.dispatchEvent(new Event('change', { bubbles: true })); });
  });

  /* Multi image fields (hero slideshow) */
  $$('[data-images-field]').forEach((f) => {
    const input = $('[data-images-input]', f); const list = $('[data-images-list]', f);
    const sync = () => { input.value = $$('figure', list).map((x) => x.dataset.id).join(','); input.dispatchEvent(new Event('change', { bubbles: true })); };
    const add = (it) => {
      const fig = document.createElement('figure'); fig.dataset.id = it.id; fig.draggable = true;
      fig.innerHTML = '<img alt=""><button type="button" data-remove aria-label="Remove">×</button>'; fig.firstChild.src = it.url; list.appendChild(fig);
    };
    $('[data-images-add]', f).addEventListener('click', () => openPicker((items) => { items.forEach(add); sync(); }, true));
    list.addEventListener('click', (e) => { if (e.target.matches('[data-remove]')) { e.target.closest('figure').remove(); sync(); } });
    $$('figure', list).forEach((x) => { x.draggable = true; });
    dragSort(list, 'figure', sync);
  });

  /* Generic drag-sort helper */
  function dragSort(container, itemSel, onDrop) {
    let dragEl = null;
    container.addEventListener('dragstart', (e) => { dragEl = e.target.closest(itemSel); if (!dragEl) return; dragEl.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; try { e.dataTransfer.setData('text/plain', ''); } catch (x) { /* */ } });
    container.addEventListener('dragend', () => { if (dragEl) dragEl.classList.remove('dragging'); dragEl = null; onDrop && onDrop(); });
    container.addEventListener('dragover', (e) => {
      if (!dragEl) return; e.preventDefault();
      const over = e.target.closest(itemSel);
      if (!over || over === dragEl || over.parentElement !== dragEl.parentElement) return;
      const r = over.getBoundingClientRect();
      const after = container.tagName === 'TBODY' || over.tagName === 'LI' ? e.clientY > r.top + r.height / 2 : e.clientX > r.left + r.width / 2;
      over.parentElement.insertBefore(dragEl, after ? over.nextSibling : over);
    });
  }

  /* Sortable content tables */
  $$('table[data-sortable]').forEach((table) => {
    const tbody = $('tbody', table);
    $$('tr', tbody).forEach((tr) => {
      const grip = $('[data-grip]', tr);
      if (!grip) return;
      grip.addEventListener('mousedown', () => { tr.draggable = true; });
      grip.addEventListener('touchstart', () => { tr.draggable = true; }, { passive: true });
      tr.addEventListener('dragend', () => { tr.draggable = false; });
    });
    dragSort(tbody, 'tr', async () => {
      const fd = new FormData(); $$('tr', tbody).forEach((tr) => fd.append('ids[]', tr.dataset.id)); fd.append('_token', TOKEN);
      await post(table.dataset.sortable, fd);
    });
  });

  /* Dropzone uploads (media library) */
  const dz = $('[data-dropzone]');
  if (dz) {
    const input = $('[data-dropzone-input]', dz); const prog = $('[data-dropzone-progress]', dz);
    const upload = (files) => {
      if (!files.length) return;
      const fd = new FormData(dz); fd.delete('files[]'); Array.from(files).forEach((f) => fd.append('files[]', f));
      const xhr = new XMLHttpRequest(); xhr.open('POST', dz.action); xhr.setRequestHeader('Accept', 'application/json');
      prog.hidden = false;
      xhr.upload.onprogress = (e) => { if (e.lengthComputable) prog.firstElementChild.style.width = (e.loaded / e.total * 100) + '%'; };
      xhr.onload = () => {
        let data = {}; try { data = JSON.parse(xhr.responseText); } catch (e) { /* */ }
        if (data.errors && data.errors.length) alert(data.errors.join('\n'));
        location.reload();
      };
      xhr.onerror = () => alert('Upload failed — check your connection.');
      xhr.send(fd);
    };
    input.addEventListener('change', () => upload(input.files));
    ['dragenter', 'dragover'].forEach((ev) => dz.addEventListener(ev, (e) => { e.preventDefault(); dz.classList.add('is-over'); }));
    ['dragleave', 'drop'].forEach((ev) => dz.addEventListener(ev, (e) => { e.preventDefault(); dz.classList.remove('is-over'); }));
    dz.addEventListener('drop', (e) => upload(e.dataTransfer.files));
  }
  // Open the media item from URL hash
  if (location.hash && /^#m\d+$/.test(location.hash)) { const d = $(location.hash); if (d && d.tagName === 'DETAILS') { d.open = true; d.scrollIntoView({ block: 'center' }); } }

  /* ---------------- Rich text editor ---------------- */
  $$('[data-rte]').forEach((rte) => {
    const area = $('[data-rte-area]', rte); const src = $('[data-rte-source]', rte);
    area.innerHTML = src.value || '<p><br></p>';
    let sourceMode = false;
    const sync = () => { if (!sourceMode) src.value = area.innerHTML.replace(/<p><br><\/p>$/, '').trim(); };
    area.addEventListener('input', sync);
    area.addEventListener('blur', sync);
    try { document.execCommand('defaultParagraphSeparator', false, 'p'); } catch (e) { /* */ }
    area.addEventListener('paste', (e) => {
      // Paste as clean text paragraphs to avoid foreign styling.
      const html = e.clipboardData.getData('text/html');
      if (!html) return;
      e.preventDefault();
      const text = e.clipboardData.getData('text/plain');
      const paras = text.split(/\n{2,}/).map((p) => '<p>' + p.replace(/[&<>]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[c])).replace(/\n/g, '<br>') + '</p>').join('');
      document.execCommand('insertHTML', false, paras);
    });
    $$('.rte-bar [data-cmd]', rte).forEach((ctl) => {
      const cmd = ctl.dataset.cmd;
      const run = () => {
        if (cmd === 'source') {
          sourceMode = !sourceMode;
          if (sourceMode) { sync(); src.hidden = false; area.hidden = true; ctl.classList.add('is-on'); src.focus(); } else { area.innerHTML = src.value; src.hidden = true; area.hidden = false; ctl.classList.remove('is-on'); }
          return;
        }
        if (sourceMode) return;
        area.focus();
        if (cmd === 'formatBlock') { document.execCommand('formatBlock', false, '<' + ctl.value + '>'); }
        else if (cmd === 'createLink') { const url = prompt('Link URL (e.g. /safaris/ or https://…)'); if (url) document.execCommand('createLink', false, url); }
        else if (cmd === 'insertImage') {
          const range = window.getSelection().rangeCount ? window.getSelection().getRangeAt(0) : null;
          openPicker((it) => {
            area.focus(); if (range) { const s = window.getSelection(); s.removeAllRanges(); s.addRange(range); }
            const big = it.url.replace(/-400\.(jpg|png)$/, '-1600.$1');
            document.execCommand('insertHTML', false, '<figure><img src="' + big + '" alt="' + (it.alt || '').replace(/"/g, '&quot;') + '" loading="lazy"></figure><p><br></p>');
            sync();
          });
          return;
        } else { document.execCommand(cmd, false, null); }
        sync();
      };
      ctl.addEventListener(ctl.tagName === 'SELECT' ? 'change' : 'click', run);
    });
    rte.closest('form').addEventListener('submit', () => { if (sourceMode) { area.innerHTML = src.value; } else sync(); });
  });

  /* ---------------- Menu builder ---------------- */
  const menuForm = $('[data-menu-form]');
  if (menuForm) {
    const tpl = $('[data-menu-template]');
    $$('[data-menu]', menuForm).forEach((box) => {
      const list = $('[data-menu-list]', box);
      dragSort(list, 'li');
      $('[data-add-item]', box).addEventListener('click', () => {
        const li = tpl.content.firstElementChild.cloneNode(true);
        if (box.dataset.menu !== 'header') { const ind = $('[data-indent]', li); if (ind) ind.remove(); }
        list.appendChild(li); $('.m-label', li).focus();
      });
      list.addEventListener('click', (e) => {
        const li = e.target.closest('li'); if (!li) return;
        if (e.target.closest('[data-remove]')) li.remove();
        if (e.target.closest('[data-indent]')) li.classList.toggle('is-child');
      });
    });
    menuForm.addEventListener('submit', () => {
      const out = {};
      $$('[data-menu]', menuForm).forEach((box) => {
        out[box.dataset.menu] = $$('[data-menu-list] > li', box).map((li) => ({
          label: $('.m-label', li).value, url: $('.m-url', li).value, child: li.classList.contains('is-child'),
          new_tab: $('.m-new', li).checked, hidden: $('.m-hidden', li).checked,
        }));
      });
      $('[data-menu-json]', menuForm).value = JSON.stringify(out);
    });
  }
})();

/* Photo finder: selection counter + starter pack runner */
(function () {
  'use strict';
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => Array.from(el.querySelectorAll(s));
  const TOKEN = ($('meta[name="csrf-token"]') || {}).content || '';

  const results = $('[data-find-results]');
  if (results) {
    const btn = $('[data-find-submit]', results); const count = $('[data-find-count]', results);
    results.addEventListener('change', () => { const n = $$('[data-find-check]:checked', results).length; if (count) count.textContent = n; if (btn) btn.disabled = n === 0; });
    results.addEventListener('submit', () => { if (btn) { btn.disabled = true; btn.textContent = 'Importing… please wait'; } });
  }

  const pack = $('[data-pack]');
  if (!pack) return;
  const start = $('[data-pack-start]', pack); const prog = $('[data-pack-progress]', pack);
  const bar = $('[data-pack-bar]', pack); const status = $('[data-pack-status]', pack);
  const thumbs = $('[data-pack-thumbs]', pack); const errors = $('[data-pack-errors]', pack);
  const total = +pack.dataset.total;
  start.addEventListener('click', async () => {
    if (!confirm('Import the starter photo pack from Wikimedia Commons? This can take a few minutes.')) return;
    start.disabled = true; prog.hidden = false; thumbs.innerHTML = ''; errors.innerHTML = '';
    let imported = 0;
    for (let step = 0; step < total; step++) {
      status.textContent = 'Searching topic ' + (step + 1) + ' of ' + total + '…';
      const fd = new FormData();
      fd.append('_token', TOKEN); fd.append('step', step); fd.append('per', $('[data-pack-per]', pack).value);
      if ($('[data-pack-apply]', pack).checked) fd.append('apply', '1');
      try {
        const res = await fetch(pack.dataset.url, { method: 'POST', body: fd, headers: { Accept: 'application/json', 'X-CSRF-Token': TOKEN }, credentials: 'same-origin' });
        const data = await res.json();
        imported += data.imported;
        (data.thumbs || []).forEach((u) => { const i = document.createElement('img'); i.src = u; i.alt = ''; thumbs.appendChild(i); });
        (data.messages || []).slice(0, 2).forEach((m) => { const li = document.createElement('li'); li.textContent = m; errors.appendChild(li); });
        status.textContent = data.label + ': ' + data.imported + ' photo(s). Total ' + imported + '.';
        if (data.done && data.filled) status.textContent += ' ' + data.filled + ' image slot(s) on the website updated.';
      } catch (e) {
        const li = document.createElement('li'); li.textContent = 'Topic ' + (step + 1) + ' failed (network or timeout) — continuing.'; errors.appendChild(li);
      }
      bar.style.width = ((step + 1) / total * 100) + '%';
    }
    status.textContent = 'Finished — ' + imported + ' photo(s) imported. ' + status.textContent.replace(/^.*?Total \d+\.\s*/, '');
    start.disabled = false;
    const a = document.createElement('a'); a.href = location.pathname.replace(/\/find$/, ''); a.className = 'btn btn-light btn-sm mt-sm'; a.textContent = 'Open the media library →'; prog.appendChild(a);
  });
})();
