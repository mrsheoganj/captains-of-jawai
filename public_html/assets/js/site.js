/* Captains of Jawai — progressive enhancement, no dependencies. */
(function () {
  'use strict';
  document.documentElement.classList.add('js');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => Array.from(el.querySelectorAll(s));
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Header scroll state */
  const header = $('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 60);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Mobile navigation */
  const nav = $('[data-mobile-nav]');
  const openBtn = $('[data-nav-open]');
  if (nav && openBtn) {
    const close = () => { nav.hidden = true; document.body.style.overflow = ''; openBtn.setAttribute('aria-expanded', 'false'); openBtn.focus(); };
    openBtn.addEventListener('click', () => { nav.hidden = false; document.body.style.overflow = 'hidden'; openBtn.setAttribute('aria-expanded', 'true'); });
    $('[data-nav-close]', nav).addEventListener('click', close);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !nav.hidden) close(); });
    $$('a', nav).forEach((a) => a.addEventListener('click', () => { nav.hidden = true; document.body.style.overflow = ''; }));
  }

  /* Hero slideshow */
  const hero = $('[data-hero]');
  if (hero) {
    const slides = $$('.hero-slide', hero);
    const dots = $$('[data-hero-dot]', hero);
    let i = 0; let timer;
    const show = (n) => {
      slides[i].classList.remove('is-active'); dots[i] && dots[i].classList.remove('is-active');
      i = (n + slides.length) % slides.length;
      slides[i].classList.add('is-active'); dots[i] && dots[i].classList.add('is-active');
    };
    const play = () => { if (slides.length > 1 && !reduced) timer = setInterval(() => show(i + 1), 7000); };
    dots.forEach((d) => d.addEventListener('click', () => { clearInterval(timer); show(+d.dataset.heroDot); play(); }));
    play();
  }

  /* Testimonials rotation */
  const quotes = $$('[data-quotes] .quote');
  if (quotes.length > 1 && !reduced) {
    let q = 0;
    setInterval(() => { quotes[q].classList.remove('is-active'); q = (q + 1) % quotes.length; quotes[q].classList.add('is-active'); }, 8000);
  }

  /* Reveal on scroll */
  const reveals = $$('.reveal');
  if ('IntersectionObserver' in window && !reduced) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add('is-in'));
  }

  /* Remember UTM parameters for the session so enquiries can be attributed */
  try {
    const params = new URLSearchParams(location.search);
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach((k) => { if (params.get(k)) sessionStorage.setItem(k, params.get(k)); });
    $$('[data-utm]').forEach((inp) => { if (!inp.value) inp.value = sessionStorage.getItem(inp.dataset.utm) || ''; });
  } catch (e) { /* storage unavailable */ }

  /* Hide mobile dock while typing */
  const dock = $('[data-dock]');
  if (dock) {
    document.addEventListener('focusin', (e) => { if (e.target.matches('input, textarea, select')) dock.classList.add('is-hidden'); });
    document.addEventListener('focusout', () => dock.classList.remove('is-hidden'));
  }

  /* Number steppers */
  $$('[data-stepper]').forEach((st) => {
    const input = $('input', st);
    const set = (d) => { const v = (parseInt(input.value, 10) || 0) + d; input.value = Math.max(+input.min || 0, Math.min(+input.max || 99, v)); };
    $('[data-dec]', st).addEventListener('click', () => set(-1));
    $('[data-inc]', st).addEventListener('click', () => set(1));
  });

  /* AJAX submit (contact form + wizard) */
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
  async function submitForm(form, onFieldError) {
    clearErrors(form);
    const btn = $('[type=submit]', form);
    const label = btn && $('span', btn);
    const original = label ? label.textContent : '';
    if (btn) { btn.disabled = true; if (label) label.textContent = 'Sending…'; }
    try {
      const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
      const data = await res.json().catch(() => ({ success: false, message: 'Unexpected response. Please try again.' }));
      if (data.success) {
        const wrap = form.parentElement;
        const ok = $('[data-form-success]', wrap);
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
      if (onFieldError) onFieldError(first);
      else if (first) first.focus();
    } catch (err) {
      showErrors(form, { message: 'Network error — please check your connection and try again.' });
    } finally {
      if (btn) { btn.disabled = false; if (label) label.textContent = original; }
    }
  }
  $$('[data-ajax-form]').forEach((form) => form.addEventListener('submit', (e) => { e.preventDefault(); submitForm(form); }));

  /* Multi-step wizard */
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
      const required = $$('[required]', steps[cur]);
      for (const inp of required) {
        if (!inp.checkValidity()) {
          showErrors(wiz, { errors: { [inp.name]: inp.validationMessage || 'Required' }, message: 'Please complete the highlighted field.' });
          inp.focus(); return false;
        }
      }
      return true;
    };
    next.addEventListener('click', () => { if (validStep()) go(cur + 1); });
    prev.addEventListener('click', () => go(cur - 1));
    wiz.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && cur < steps.length - 1) { e.preventDefault(); next.click(); }
    });
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

  /* Gallery filter + lightbox */
  const filterBtns = $$('[data-filter-btn]');
  filterBtns.forEach((b) => b.addEventListener('click', () => {
    filterBtns.forEach((x) => x.classList.toggle('is-active', x === b));
    $$('.masonry-item').forEach((it) => { it.hidden = b.dataset.filterBtn !== '*' && it.dataset.cat !== b.dataset.filterBtn; });
  }));
  const lbWrap = $('[data-lightbox]'); const lb = $('[data-lightbox-view]');
  if (lbWrap && lb) {
    const img = $('img', lb); const cap = $('figcaption', lb);
    let links = []; let idx = 0;
    const show = (n) => { idx = (n + links.length) % links.length; img.src = links[idx].href; img.alt = links[idx].dataset.caption || ''; cap.textContent = links[idx].dataset.caption || ''; };
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

  /* Copy link */
  $$('[data-copy]').forEach((b) => b.addEventListener('click', async () => {
    try { await navigator.clipboard.writeText(b.dataset.copy); b.classList.add('is-copied'); setTimeout(() => b.classList.remove('is-copied'), 1500); } catch (e) { /* ignore */ }
  }));

  /* Auto-dismiss toasts */
  $$('.toast').forEach((t) => setTimeout(() => { t.style.transition = 'opacity .4s'; t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 7000));
})();
