/* scuttle.dev: language, theme, menu, report animation, reveals, QR modal. No tracking. */
(function () {
  var $ = function (id) { return document.getElementById(id); };
  var root = document.documentElement;

  // Dutch strings. English is the text already in the HTML.
  var T = window.SCUTTLE_NL || { t: {}, ph: {} };
  var base = {};
  document.querySelectorAll('[data-i]').forEach(function (e) { base[e.dataset.i] = base[e.dataset.i] || e.innerHTML; });
  function lang(l) {
    root.lang = l;
    document.querySelectorAll('[data-i]').forEach(function (e) {
      var k = e.dataset.i;
      e.innerHTML = (l === 'nl' && T.t[k]) ? T.t[k] : base[k];
    });
    var en = $('en'), nl = $('nl');
    if (en) en.setAttribute('aria-pressed', l === 'en');
    if (nl) nl.setAttribute('aria-pressed', l === 'nl');
    try { localStorage.setItem('lang', l); } catch (e) {}
    updateEol();
  }
  if ($('en')) $('en').onclick = function () { lang('en'); };
  if ($('nl')) $('nl').onclick = function () { lang('nl'); };

  // Theme: light unless the visitor chose dark here before.
  function theme(t) {
    if (t === 'dark') root.setAttribute('data-theme', 'dark'); else root.removeAttribute('data-theme');
    try { localStorage.setItem('theme', t); } catch (e) {}
  }
  try { if (localStorage.getItem('theme') === 'dark') theme('dark'); } catch (e) {}
  if ($('theme')) $('theme').onclick = function () { theme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); };

  // Phone menu.
  var links = $('links');
  if ($('burger') && links) {
    $('burger').onclick = function () { links.classList.toggle('open'); };
    links.addEventListener('click', function () { links.classList.remove('open'); });
  }

  // Copy the command in the hero.
  if ($('copy')) $('copy').onclick = function () { try { navigator.clipboard.writeText('php -v'); } catch (e) {} };

  // Active section in the nav (home page only).
  var navA = [].slice.call(document.querySelectorAll('.links a[href^="#"]'));
  var secs = navA.map(function (a) { return document.querySelector(a.getAttribute('href')); });
  function onScroll() {
    var cur = -1;
    secs.forEach(function (el, i) { if (el && el.getBoundingClientRect().top <= innerHeight * 0.4) cur = i; });
    navA.forEach(function (a, i) { a.classList.toggle('cur', i === cur); });
  }
  if (navA.length) { addEventListener('scroll', onScroll, { passive: true }); onScroll(); }

  // Days since PHP 7.4 lost support (28 Nov 2022), shown in the hero command line.
  function updateEol() {
    var b = document.querySelector('.cmd b');
    if (!b) return;
    var d = Math.floor((Date.now() - new Date('2022-11-28T00:00:00Z')) / 864e5);
    b.textContent = (root.lang === 'nl' ? 'end of life sinds 28 nov 2022 · ' + d + ' dagen' : 'end of life since 28 Nov 2022 · ' + d + ' days');
  }

  // Report panel: the scan log types, then the findings appear ranked.
  var log = $('log'), pb = $('pb'), stt = $('stt'), shot = document.querySelector('.shot');
  if (log && shot) {
    var L = [
      ['00:00', 'ssh hotel-platform · connected', ''],
      ['00:01', 'cat /etc/os-release → CentOS Linux 7', 'bad'],
      ['00:01', 'php -v → 7.4.33 · EOL 2022-11-28', 'bad'],
      ['00:02', 'mysql --version → 5.7.44 · EOL 2023-10', 'bad'],
      ['00:03', 'composer outdated → 14 packages, 3 advisories', 'warn'],
      ['00:04', 'nmap -sT → 22, 80, 443, 3306 open', 'warn'],
      ['00:05', 'ls /backups → nightly dump, last restore: never', 'warn'],
      ['00:06', 'tests/ → 0 files', 'warn'],
      ['00:07', 'ranking 6 findings by risk · 57 h total', 'ac'],
      ['00:07', 'report.pdf written ✓', 'ok']
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
        stt.textContent = root.lang === 'nl' ? 'klaar · 6 bevindingen' : 'done · 6 findings';
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
  setTimeout(function () { document.querySelectorAll('.rv:not(.in)').forEach(function (e) { e.classList.add('in'); }); }, 2000);

  // QR code modal (vCard).
  var modal = $('qr-modal'), openQr = $('open-qr'), closeQr = $('close-qr'), ov = $('qr-overlay');
  if (modal && openQr && closeQr && ov) {
    var close = function () { modal.classList.remove('active'); modal.setAttribute('aria-hidden', 'true'); };
    openQr.onclick = function () { modal.classList.add('active'); modal.setAttribute('aria-hidden', 'false'); };
    closeQr.onclick = close;
    ov.onclick = close;
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('active')) close(); });
  }

  try { if (localStorage.getItem('lang') === 'nl') lang('nl'); else updateEol(); } catch (e) { updateEol(); }
})();
