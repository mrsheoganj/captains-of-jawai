/* Captains of Jawai — interactions & motion (no dependencies). */
(function () {
  'use strict';
  const root = document.documentElement;
  root.classList.add('js');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => Array.from(el.querySelectorAll(s));
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------- Preloader ---------------- */
  const preloader = $('[data-preloader]');
  let ready = false;
  const markReady = () => {
    if (ready) return;
    ready = true;
    root.classList.remove('is-loading');
    root.classList.add('is-loaded', 'is-ready');
    if (preloader) preloader.classList.remove('is-visible');
  };
  if (document.readyState === 'complete') markReady();
  else {
    window.addEventListener('load', markReady);
    setTimeout(markReady, 2500); // never hold the page longer than this
  }
  // Show the spinner while navigating to another page (only if it takes a moment).
  let navTimer;
  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href]');
    if (!a || !preloader || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    const url = new URL(a.href, location.href);
    if (a.target === '_blank' || a.hasAttribute('download') || url.origin !== location.origin || /^(mailto|tel):/.test(a.href)) return;
    if (url.pathname === location.pathname && url.hash) return;
    if (/\.(xml|txt|pdf|jpg|png|webp)$/i.test(url.pathname) || a.closest('[data-lightbox]')) return;
    navTimer = setTimeout(() => preloader.classList.add('is-visible'), 180);
  });
  window.addEventListener('pageshow', () => { clearTimeout(navTimer); if (preloader) preloader.classList.remove('is-visible'); });

  // Page-load image unmask: drop the clip once finished so shadows show.
  $$('[data-anim="reveal-img"]').forEach((el) => el.addEventListener('animationend', () => el.classList.add('is-done')));

  /* ---------------- Header: shadow on scroll, hide on scroll down ---------------- */
  const header = $('[data-header]');
  const progress = $('[data-progress]');
  const toTop = $('[data-to-top]');
  let lastY = window.scrollY;
  let ticking = false;
  const onScroll = () => {
    const y = window.scrollY;
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const p = max > 0 ? Math.min(1, y / max) : 0;
    if (header) {
      header.classList.toggle('is-scrolled', y > 20);
      // Header stays visible at all times (no hide-on-scroll).
    }
    if (progress) progress.style.setProperty('--p', p.toFixed(4));
    if (toTop) { toTop.style.setProperty('--p', p.toFixed(4)); toTop.classList.toggle('is-visible', y > 700); }
    lastY = y;
    ticking = false;
    parallax();
  };
  window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
  if (toTop) toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' }));

  /* ---------------- Parallax ---------------- */
  const parallaxEls = reduced ? [] : $$('[data-parallax]');
  function parallax() {
    const vh = window.innerHeight;
    parallaxEls.forEach((el) => {
      const r = el.parentElement.getBoundingClientRect();
      if (r.bottom < -100 || r.top > vh + 100) return;
      const speed = parseFloat(el.dataset.parallax) || 0.1;
      const offset = (r.top + r.height / 2 - vh / 2) * -speed;
      el.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0)';
    });
  }
  onScroll();

  /* ---------------- Mobile navigation ---------------- */
  const nav = $('[data-mobile-nav]');
  const openBtn = $('[data-nav-open]');
  if (nav && openBtn) {
    const close = () => { nav.hidden = true; document.body.style.overflow = ''; openBtn.setAttribute('aria-expanded', 'false'); openBtn.focus(); };
    openBtn.addEventListener('click', () => { nav.hidden = false; document.body.style.overflow = 'hidden'; openBtn.setAttribute('aria-expanded', 'true'); });
    $('[data-nav-close]', nav).addEventListener('click', close);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !nav.hidden) close(); });
    $$('a', nav).forEach((a) => a.addEventListener('click', () => { nav.hidden = true; document.body.style.overflow = ''; }));
  }

  /* ---------------- Hero slideshow ---------------- */
  const hero = $('[data-hero]');
  if (hero) {
    const slides = $$('.hero-slide', hero);
    const dots = $$('[data-hero-dot]', hero);
    let i = 0; let timer;
    const show = (n) => {
      slides[i].classList.remove('is-active'); if (dots[i]) dots[i].classList.remove('is-active');
      i = (n + slides.length) % slides.length;
      slides[i].classList.add('is-active'); if (dots[i]) dots[i].classList.add('is-active');
    };
    const play = () => { if (slides.length > 1 && !reduced) timer = setInterval(() => show(i + 1), 6500); };
    const go = (n) => { clearInterval(timer); show(n); play(); };
    dots.forEach((d) => d.addEventListener('click', () => go(+d.dataset.heroDot)));
    const prev = $('[data-hero-prev]', hero); const next = $('[data-hero-next]', hero);
    if (prev) prev.addEventListener('click', () => go(i - 1));
    if (next) next.addEventListener('click', () => go(i + 1));
    // Swipe on touch screens
    let x0 = null;
    hero.addEventListener('touchstart', (e) => { x0 = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend', (e) => {
      if (x0 === null || slides.length < 2) return;
      const dx = e.changedTouches[0].clientX - x0; x0 = null;
      if (Math.abs(dx) > 50) go(dx < 0 ? i + 1 : i - 1);
    });
    play();
  }

  /* ---------------- Phone sliders (swipeable card rows, CSS scroll-snap) ---------------- */
  $$('[data-m-slider]').forEach((track) => {
    const items = Array.from(track.children);
    if (items.length < 2) return;
    const dots = document.createElement('div');
    dots.className = 'm-dots';
    dots.setAttribute('aria-hidden', 'true'); // swipe or scroll is the real control; dots only show position
    const btns = items.map((_, n) => {
      const b = document.createElement('button');
      b.type = 'button'; b.tabIndex = -1;
      b.addEventListener('click', () => track.scrollTo({ left: items[n].offsetLeft - items[0].offsetLeft, behavior: reduced ? 'auto' : 'smooth' }));
      dots.appendChild(b);
      return b;
    });
    track.after(dots);
    let raf = 0;
    const update = () => {
      raf = 0;
      const step = items[1].offsetLeft - items[0].offsetLeft || 1;
      const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
      const n = atEnd ? items.length - 1 : Math.round(track.scrollLeft / step);
      btns.forEach((b, k) => b.classList.toggle('is-active', k === n));
    };
    track.addEventListener('scroll', () => { if (!raf) raf = requestAnimationFrame(update); }, { passive: true });
    update();
  });

  /* ---------------- Safari jeep strip: drive only while on screen ---------------- */
  $$('[data-jeep-track]').forEach((el) => {
    if (reduced || !('IntersectionObserver' in window)) return;
    new IntersectionObserver((en) => en.forEach((e) => el.classList.toggle('is-driving', e.isIntersecting)), { threshold: 0.05 }).observe(el);
  });

  /* ---------------- Distance map (interactive) ---------------- */
  const map = $('[data-map]');
  if (map) {
    const routes = $$('[data-route]', map);
    const pins = $$('[data-place]', map);
    const items = $$('[data-dist]');
    const info = $('[data-map-info]', map);
    const hint = info ? info.innerHTML : '';
    let active = null; let touring = !reduced; let tourTimer;
    const esc = (t) => String(t).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

    const select = (i) => {
      active = i;
      map.classList.toggle('has-active', i !== null);
      routes.forEach((r) => r.classList.toggle('is-hot', r.dataset.route === String(i)));
      pins.forEach((p) => p.classList.toggle('is-active', p.dataset.place === String(i)));
      items.forEach((li) => li.classList.toggle('is-hot', li.dataset.dist === String(i)));
      const pin = pins.find((p) => p.dataset.place === String(i));
      if (!info) return;
      if (!pin) { info.innerHTML = hint; return; }
      const d = pin.dataset;
      info.innerHTML = '<div class="info-place"><span class="info-kind">' + esc(d.kind) + '</span><span class="info-name">' + esc(d.name) + '</span></div>'
        + '<div class="info-dist"><span class="info-km">' + esc(d.km) + '</span>' + (d.time ? '<span class="info-time">' + esc(d.time) + ' by road from Bera</span>' : '') + '</div>'
        + '<a class="map-dir" href="' + esc(d.gmaps) + '" target="_blank" rel="noopener">'
        + '<svg class="icon" width="16" height="16" aria-hidden="true"><use href="#i-map-pin"/></svg>Directions'
        + '<svg class="icon" width="14" height="14" aria-hidden="true"><use href="#i-arrow-up-right"/></svg></a>';
    };
    const stopTour = () => { touring = false; clearInterval(tourTimer); };
    const user = (i) => { stopTour(); select(active === i ? null : i); };

    pins.forEach((p) => p.addEventListener('click', () => user(+p.dataset.place)));
    items.forEach((li) => {
      li.style.cursor = 'pointer';
      li.addEventListener('click', () => user(+li.dataset.dist));
      li.addEventListener('mouseenter', () => { if (!touring) select(+li.dataset.dist); });
    });

    // Auto tour once the map is on screen, until the visitor interacts
    if ('IntersectionObserver' in window) {
      const tio = new IntersectionObserver((es) => es.forEach((e) => {
        if (!e.isIntersecting || !touring || tourTimer) return;
        let k = -1;
        setTimeout(() => {
          if (!touring) return;
          tourTimer = setInterval(() => { if (!touring) return; k = (k + 1) % pins.length; select(+pins[k].dataset.place); }, 2600);
        }, 2600);
        tio.disconnect();
      }), { threshold: .4 });
      tio.observe(map);
    }

    // Count the distances up when they scroll into view ("155 km" -> 0..155 km)
    const counters = $$('[data-count]');
    const run = (el) => {
      const m = el.textContent.match(/^(\D*)(\d+)(.*)$/); if (!m || reduced) return;
      const end = +m[2]; const t0 = performance.now(); const dur = 1400;
      const step = (t) => { const p = Math.min(1, (t - t0) / dur); el.textContent = m[1] + Math.round(end * (1 - Math.pow(1 - p, 3))) + m[3]; if (p < 1) requestAnimationFrame(step); };
      requestAnimationFrame(step);
    };
    if ('IntersectionObserver' in window) {
      const io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } }), { threshold: .6 });
      counters.forEach((c) => io.observe(c));
    }
  }

  /* ---------------- Testimonials ---------------- */
  const quotes = $$('[data-quotes] .quote');
  if (quotes.length > 1 && !reduced) {
    let q = 0;
    setInterval(() => { quotes[q].classList.remove('is-active'); q = (q + 1) % quotes.length; quotes[q].classList.add('is-active'); }, 8000);
  }

  /* ---------------- Scroll reveals (auto-staggered) ---------------- */
  // Stagger siblings: cards in a grid, items in a list, etc.
  $$('.reveal, .reveal-img').forEach((el) => {
    if (el.style.getPropertyValue('--d')) return;
    const parent = el.parentElement;
    const sibs = Array.from(parent.children).filter((c) => c.classList.contains('reveal') || c.classList.contains('reveal-img'));
    const idx = sibs.indexOf(el);
    if (idx > 0) el.style.setProperty('--d', Math.min(idx * 0.09, 0.6) + 's');
  });
  // Elements that reveal on scroll: .reveal, .reveal-img, timeline items, split headings inside .reveal
  $$('.timeline li').forEach((li) => li.classList.add('reveal'));
  const revealEls = $$('.reveal, .reveal-img');
  const onIn = (el) => {
    el.classList.add('is-in');
    if (el.classList.contains('reveal-img')) setTimeout(() => el.classList.add('is-done'), 1600); // restore shadows once unmasked
  };
  if ('IntersectionObserver' in window && !reduced) {
    // Clipped elements report zero visibility, so masked images are watched via their parent.
    const targets = new Map();
    revealEls.forEach((el) => {
      const t = el.classList.contains('reveal-img') ? el.parentElement : el;
      if (!targets.has(t)) targets.set(t, []);
      targets.get(t).push(el);
    });
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) { (targets.get(en.target) || []).forEach(onIn); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.08 });
    targets.forEach((_, t) => io.observe(t));
  } else {
    revealEls.forEach(onIn);
  }

  /* ---------------- UTM attribution ---------------- */
  try {
    const params = new URLSearchParams(location.search);
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach((k) => { if (params.get(k)) sessionStorage.setItem(k, params.get(k)); });
    $$('[data-utm]').forEach((inp) => { if (!inp.value) inp.value = sessionStorage.getItem(inp.dataset.utm) || ''; });
  } catch (e) { /* storage unavailable */ }

  /* ---------------- Mobile dock hides while typing ---------------- */
  const dock = $('[data-dock]');
  if (dock) {
    document.addEventListener('focusin', (e) => { if (e.target.matches('input, textarea, select')) dock.classList.add('is-hidden'); });
    document.addEventListener('focusout', () => dock.classList.remove('is-hidden'));
  }

  /* ---------------- Number steppers ---------------- */
  $$('[data-stepper]').forEach((st) => {
    const input = $('input', st);
    const set = (d) => { const v = (parseInt(input.value, 10) || 0) + d; input.value = Math.max(+input.min || 0, Math.min(+input.max || 99, v)); };
    $('[data-dec]', st).addEventListener('click', () => set(-1));
    $('[data-inc]', st).addEventListener('click', () => set(1));
  });

  /* ---------------- Forms (AJAX) with button spinners ---------------- */
  function clearErrors(form) {
    $$('.field.has-error', form).forEach((f) => f.classList.remove('has-error'));
    $$('.field-msg', form).forEach((m) => m.remove());
    const box = $('[data-form-error]', form); if (box) { box.hidden = true; box.textContent = ''; }
  }
  function showErrors(form, data) {
    const errors = data.errors || {};
    let first = null;
    Object.keys(errors).forEach((name) => {
      const input = form.querySelector('[name="' + name + '"]');
      if (!input) return;
      const field = input.closest('.field');
      if (field) {
        field.classList.add('has-error');
        const m = document.createElement('span'); m.className = 'field-msg'; m.textContent = errors[name]; field.appendChild(m);
      }
      first = first || input;
    });
    const box = $('[data-form-error]', form);
    if (box) { box.textContent = data.message || 'Please check the form.'; box.hidden = false; }
    return first;
  }
  function setBusy(btn, busy) {
    if (!btn) return;
    const label = $('span', btn);
    if (busy) {
      btn.dataset.label = label ? label.textContent : '';
      btn.disabled = true;
      if (label) label.textContent = 'Sending…';
      const icon = $('svg', btn); if (icon) icon.style.display = 'none';
      const sp = document.createElement('i'); sp.className = 'btn-spin'; sp.setAttribute('aria-hidden', 'true'); btn.appendChild(sp);
    } else {
      btn.disabled = false;
      if (label) label.textContent = btn.dataset.label || label.textContent;
      const icon = $('svg', btn); if (icon) icon.style.display = '';
      $$('.btn-spin', btn).forEach((s) => s.remove());
    }
  }
  // If sending fails for a reason other than a field, offer the same request on WhatsApp.
  function offerWhatsApp(form) {
    const wa = $('[data-wa-book]', form); const box = $('[data-form-error]', form);
    if (!wa || !box) return;
    const link = document.createElement('a');
    link.href = '#'; link.className = 'form-error-wa'; link.textContent = 'Send this request on WhatsApp instead';
    link.addEventListener('click', (e) => { e.preventDefault(); wa.click(); window.open(wa.href, '_blank', 'noopener'); });
    box.append(' ', link);
  }
  async function submitForm(form, onFieldError) {
    clearErrors(form);
    const btn = $('[type=submit]', form);
    setBusy(btn, true);
    try {
      const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
      const data = await res.json().catch(() => ({ success: false, message: 'Unexpected response. Please try again.' }));
      if (data.success) {
        const ok = $('[data-form-success]', form.parentElement);
        form.hidden = true;
        if (ok) {
          $('h2, h3', ok).textContent = data.title || 'Thank you';
          $('p', ok).textContent = data.message || '';
          const code = $('[data-code]', ok); if (code && data.code) code.textContent = 'Reference ' + data.code;
          ok.hidden = false;
          ok.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
        }
        if (window.gtag) window.gtag('event', 'generate_lead', { form: form.action });
        return;
      }
      const first = showErrors(form, data);
      if (!data.errors || data.errors._form) offerWhatsApp(form);
      if (onFieldError) onFieldError(first); else if (first) first.focus();
    } catch (err) {
      showErrors(form, { message: 'Network error — please check your connection and try again.' });
      offerWhatsApp(form);
    } finally {
      setBusy(btn, false);
    }
  }
  $$('[data-ajax-form]').forEach((form) => form.addEventListener('submit', (e) => { e.preventDefault(); submitForm(form); }));

  /* ---------------- Book on WhatsApp: pre-fill the message from the form ---------------- */
  $$('[data-wa-book]').forEach((a) => a.addEventListener('click', () => {
    const f = a.closest('form'); if (!f) return;
    const val = (n) => { const el = f.querySelector('[name="' + n + '"]:checked') || f.querySelector('[name="' + n + '"]'); return el ? el.value.trim() : ''; };
    const lines = ['Hello Captains of Jawai, I would like to book a safari.', 'Safari: ' + val('booking_type'), 'Vehicle: ' + val('vehicle')];
    if (val('travel_date')) lines.push('Date: ' + val('travel_date'));
    lines.push('Guests: ' + val('adults'));
    if (val('full_name')) lines.push('Name: ' + val('full_name'));
    if (val('message')) lines.push('Note: ' + val('message'));
    a.href = 'https://wa.me/' + a.dataset.waBook + '?text=' + encodeURIComponent(lines.join('\n'));
  }));

  /* ---------------- Book / Ask tabs ---------------- */
  $$('[data-tabs]').forEach((box) => {
    const tabs = $$('[data-tab]', box);
    const show = (name, focus) => {
      tabs.forEach((t) => {
        const on = t.dataset.tab === name;
        t.setAttribute('aria-selected', on); t.tabIndex = on ? 0 : -1;
        if (on && focus) t.focus();
      });
      $$('[data-panel]', box).forEach((p) => { p.hidden = p.dataset.panel !== name; });
    };
    tabs.forEach((t, i) => {
      t.addEventListener('click', () => show(t.dataset.tab));
      t.addEventListener('keydown', (e) => {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
        const n = tabs[(i + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length];
        show(n.dataset.tab, true);
      });
    });
    if (location.hash === '#ask') show('ask');
  });
  // Links to "#ask" open the question tab of the nearest booking box
  $$('a[href$="#ask"]').forEach((a) => a.addEventListener('click', () => {
    const t = $('[data-tab="ask"]'); if (t) setTimeout(() => t.click(), 50);
  }));

  /* ---------------- Multi-step wizard ---------------- */
  const wiz = $('[data-wizard]');
  if (wiz) {
    const steps = $$('[data-step]', wiz);
    const prev = $('[data-prev]', wiz); const next = $('[data-next]', wiz); const submit = $('[data-submit]', wiz);
    const bar = $('[data-progress]', wiz); const num = $('[data-step-num]', wiz);
    let cur = 0;
    const go = (n) => {
      steps[cur].hidden = true; steps[cur].classList.remove('is-active');
      cur = Math.max(0, Math.min(steps.length - 1, n));
      steps[cur].hidden = false; steps[cur].classList.add('is-active');
      prev.hidden = cur === 0; next.hidden = cur === steps.length - 1; submit.hidden = cur !== steps.length - 1;
      bar.style.width = ((cur + 1) / steps.length * 100) + '%'; num.textContent = cur + 1;
      const top = wiz.getBoundingClientRect().top + window.scrollY - 100;
      if (window.scrollY > top) window.scrollTo({ top, behavior: reduced ? 'auto' : 'smooth' });
    };
    const validStep = () => {
      clearErrors(wiz);
      for (const inp of $$('[required]', steps[cur])) {
        if (!inp.checkValidity()) {
          showErrors(wiz, { errors: { [inp.name]: inp.validationMessage || 'Required' }, message: 'Please complete the highlighted field.' });
          inp.focus(); return false;
        }
      }
      return true;
    };
    next.addEventListener('click', () => { if (validStep()) go(cur + 1); });
    prev.addEventListener('click', () => go(cur - 1));
    wiz.addEventListener('keydown', (e) => { if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && cur < steps.length - 1) { e.preventDefault(); next.click(); } });
    wiz.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!validStep()) return;
      submitForm(wiz, (first) => {
        if (!first) return;
        const idx = steps.findIndex((s) => s.contains(first));
        if (idx >= 0 && idx !== cur) go(idx);
        first.focus();
      });
    });
  }

  /* ---------------- Gallery filter + lightbox ---------------- */
  const filterBtns = $$('[data-filter-btn]');
  filterBtns.forEach((b) => b.addEventListener('click', () => {
    filterBtns.forEach((x) => x.classList.toggle('is-active', x === b));
    $$('.masonry-item').forEach((it) => { it.hidden = b.dataset.filterBtn !== '*' && it.dataset.cat !== b.dataset.filterBtn; });
  }));
  const lbWrap = $('[data-lightbox]'); const lb = $('[data-lightbox-view]');
  if (lbWrap && lb) {
    const img = $('img', lb); const cap = $('figcaption', lb);
    let links = []; let idx = 0;
    const show = (n) => { idx = (n + links.length) % links.length; img.src = links[idx].href; img.alt = links[idx].dataset.caption || ''; cap.textContent = links[idx].dataset.caption || ''; img.style.animation = 'none'; void img.offsetWidth; img.style.animation = ''; };
    const close = () => { lb.hidden = true; document.body.style.overflow = ''; };
    lbWrap.addEventListener('click', (e) => {
      const a = e.target.closest('a'); if (!a) return;
      e.preventDefault();
      links = $$('.masonry-item:not([hidden]) a', lbWrap);
      show(links.indexOf(a)); lb.hidden = false; document.body.style.overflow = 'hidden';
    });
    $('[data-lb-close]', lb).addEventListener('click', close);
    $('[data-lb-prev]', lb).addEventListener('click', () => show(idx - 1));
    $('[data-lb-next]', lb).addEventListener('click', () => show(idx + 1));
    lb.addEventListener('click', (e) => { if (e.target === lb) close(); });
    document.addEventListener('keydown', (e) => {
      if (lb.hidden) return;
      if (e.key === 'Escape') close(); if (e.key === 'ArrowLeft') show(idx - 1); if (e.key === 'ArrowRight') show(idx + 1);
    });
  }

  /* ---------------- Copy link ---------------- */
  $$('[data-copy]').forEach((b) => b.addEventListener('click', async () => {
    try { await navigator.clipboard.writeText(b.dataset.copy); b.classList.add('is-copied'); setTimeout(() => b.classList.remove('is-copied'), 1500); } catch (e) { /* ignore */ }
  }));

  /* ---------------- Toasts ---------------- */
  $$('.toast').forEach((t) => setTimeout(() => { t.style.transition = 'opacity .4s, transform .4s'; t.style.opacity = '0'; t.style.transform = 'translateX(30px)'; setTimeout(() => t.remove(), 400); }, 7000));
})();
