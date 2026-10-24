/* scuttle.dev: language, theme, menus, decrypt headline, lights, report animation, reveals, QR modal. No tracking. */
(function () {
  var $ = function (id) { return document.getElementById(id); };
  var root = document.documentElement;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789<>/{}#*';

  // Scramble text into place, like a decrypt. Keeps tags (for example <br>) and spaces.
  function decrypt(el, ms) {
    if (reduce || !el) return;
    var html = el.innerHTML, parts = html.split(/(<[^>]+>)/), total = 0;
    parts.forEach(function (p) { if (p && p[0] !== '<') total += p.length; });
    if (!total) return;
    var start = null, w = el.offsetWidth;
    el.style.minWidth = w ? w + 'px' : '';
    function frame(ts) {
      if (start === null) start = ts;
      var k = Math.min(1, (ts - start) / (ms || 900)), done = Math.floor(k * total), idx = 0, out = '';
      parts.forEach(function (p) {
        if (!p) return;
        if (p[0] === '<') { out += p; return; }
        for (var i = 0; i < p.length; i++, idx++) {
          var ch = p[i];
          if (ch === ' ' || ch === ' ' || idx < done) out += ch;
          else out += GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
        }
      });
      el.innerHTML = out;
      if (k < 1) requestAnimationFrame(frame); else { el.innerHTML = html; el.style.minWidth = ''; }
    }
    requestAnimationFrame(frame);
  }

  // Dutch strings. English is the text already in the HTML.
  var T = window.SCUTTLE_NL || { t: {}, ph: {} };
  var base = {};
  document.querySelectorAll('[data-i]').forEach(function (e) { base[e.dataset.i] = base[e.dataset.i] || e.innerHTML; });
  function lang(l, animate) {
    root.lang = l;
    document.querySelectorAll('[data-i]').forEach(function (e) {
      var k = e.dataset.i;
      e.innerHTML = (l === 'nl' && T.t[k]) ? T.t[k] : base[k];
    });
    var en = $('en'), nl = $('nl');
    if (en) en.setAttribute('aria-pressed', l === 'en');
    if (nl) nl.setAttribute('aria-pressed', l === 'nl');
    try { localStorage.setItem('lang', l); } catch (e) {}
    if (animate) document.querySelectorAll('[data-dec]').forEach(function (h) { decrypt(h, 700); });
  }
  if ($('en')) $('en').onclick = function () { lang('en', true); };
  if ($('nl')) $('nl').onclick = function () { lang('nl', true); };

  // Theme: light unless the visitor chose dark here before (the head script already applied it).
  function theme(t) {
    if (t === 'dark') root.setAttribute('data-theme', 'dark'); else root.removeAttribute('data-theme');
    try { localStorage.setItem('theme', t); } catch (e) {}
  }
  if ($('theme')) $('theme').onclick = function () { theme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); };

  // Phone menu and the products menu.
  var links = $('links'), megaBtn = $('mega-btn'), hasMega = $('has-mega');
  if ($('burger') && links) {
    $('burger').onclick = function () { links.classList.toggle('open'); };
    links.addEventListener('click', function (e) { if (e.target.closest('a')) links.classList.remove('open'); });
  }
  if (megaBtn) {
    megaBtn.onclick = function () { megaBtn.setAttribute('aria-expanded', megaBtn.getAttribute('aria-expanded') !== 'true'); };
    document.addEventListener('click', function (e) { if (hasMega && !hasMega.contains(e.target)) megaBtn.setAttribute('aria-expanded', 'false'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') megaBtn.setAttribute('aria-expanded', 'false'); });
  }

  // Brand: the letters rearrange on hover, the mark turns (CSS).
  var brandTxt = document.querySelector('.brand [data-scr]');
  if (brandTxt && !reduce) {
    var busy = false;
    brandTxt.parentNode.addEventListener('mouseenter', function () {
      if (busy) return; busy = true;
      decrypt(brandTxt, 450);
      setTimeout(function () { busy = false; }, 600);
    });
  }

  // Headline decrypt on load.
  document.querySelectorAll('[data-dec]').forEach(function (h) { decrypt(h, 950); });

  // Status lights: one flashes briefly every so often.
  var lights = [].slice.call(document.querySelectorAll('.lt'));
  if (lights.length && !reduce) {
    setInterval(function () {
      if (document.hidden) return;
      var l = lights[Math.floor(Math.random() * lights.length)];
      l.classList.add('on');
      setTimeout(function () { l.classList.remove('on'); }, 420);
    }, 650);
  }

  // Active section in the nav (home page only).
  var navA = [].slice.call(document.querySelectorAll('.links a[href^="#"]'));
  var secs = navA.map(function (a) { return document.querySelector(a.getAttribute('href')); });
  function onScroll() {
    var cur = -1;
    secs.forEach(function (el, i) { if (el && el.getBoundingClientRect().top <= innerHeight * 0.4) cur = i; });
    navA.forEach(function (a, i) { a.classList.toggle('cur', i === cur); });
  }
  if (navA.length) { addEventListener('scroll', onScroll, { passive: true }); onScroll(); }

  // Report panel: the scan log types, then the findings appear ranked.
  var log = $('log'), pb = $('pb'), stt = $('stt'), shot = document.querySelector('.shot');
  if (log && shot) {
    var L = [
      ['00:00', 'watch inbox → 14 new attachments', ''],
      ['00:01', 'classify → 12 invoices, 2 payment reminders', ''],
      ['00:02', 'extract fields → 12/12 · confidence ≥ 0.98', 'ok'],
      ['00:03', 'match purchase orders → 11 matched, 1 unknown supplier', 'warn'],
      ['00:04', 'post to accounting → 11 booked', 'ok'],
      ['00:05', 'unknown supplier → queued for a person (1)', 'warn'],
      ['00:05', 'reminders → 2 already paid, archived', ''],
      ['00:06', 'evaluation set → 48/48 pass', 'ok'],
      ['00:06', 'cost this run → €0.14 · 41 s', 'ac'],
      ['00:06', 'summary posted to #finance ✓', 'ok']
    ];
    var rows = [].slice.call(document.querySelectorAll('#find .row')), started = false;
    var run = function () {
      if (started) return;
      started = true;
      log.innerHTML = '';
      L.forEach(function (l, i) {
        var d = document.createElement('div');
        d.innerHTML = '<span class="t">' + l[0] + '</span><span class="' + l[2] + '">' + l[1] + '</span>';
        log.appendChild(d);
        setTimeout(function () { d.classList.add('in'); pb.style.width = ((i + 1) / L.length * 100) + '%'; }, 400 + i * 420);
      });
      setTimeout(function () {
        stt.textContent = root.lang === 'nl' ? 'klaar · 14 verwerkt' : 'done · 14 processed';
        rows.forEach(function (r, i) { setTimeout(function () { r.classList.add('in'); }, i * 140); });
      }, 400 + L.length * 420);
    };
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) run(); }); }, { threshold: 0.25 }).observe(shot);
    }
    setTimeout(run, 2500);
  }

  // Reveal on scroll, with a fallback so nothing stays hidden.
  var rv = document.querySelectorAll('.rv');
  if (rv.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.1 });
    rv.forEach(function (e, i) { e.style.transitionDelay = (i % 4) * 60 + 'ms'; io.observe(e); });
  }
  setTimeout(function () { document.querySelectorAll('.rv:not(.in)').forEach(function (e) { e.classList.add('in'); }); }, 1500);

  // QR code modal (vCard).
  var modal = $('qr-modal'), openQr = $('open-qr'), closeQr = $('close-qr'), ov = $('qr-overlay');
  if (modal && openQr && closeQr && ov) {
    var close = function () { modal.classList.remove('active'); modal.setAttribute('aria-hidden', 'true'); };
    openQr.onclick = function () { modal.classList.add('active'); modal.setAttribute('aria-hidden', 'false'); };
    closeQr.onclick = close;
    ov.onclick = close;
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('active')) close(); });
  }

  try { if (localStorage.getItem('lang') === 'nl') lang('nl'); } catch (e) {}
})();
