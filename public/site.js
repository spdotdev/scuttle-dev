/* scuttle.dev: language, theme, menus, decrypt headline, lights, report animation, reveals, QR modal. No tracking. */
(function () {
  var $ = function (id) { return document.getElementById(id); };
  var root = document.documentElement;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789<>/{}[]#*&%$@!?=+~^|';

  // Scramble text into place, like a decrypt. Keeps tags (for example <br>) and spaces.
  function decrypt(el, ms) {
    if (reduce || !el) return;
    var html = el.innerHTML, parts = html.split(/(<[^>]+>)/), total = 0;
    parts.forEach(function (p) { if (p && p[0] !== '<') total += p.length; });
    if (!total) return;
    var start = null, lh = parseFloat(getComputedStyle(el).lineHeight) || 40, oneLine = el.offsetHeight < lh * 1.6 && html.indexOf('<br') < 0;
    // Random glyphs are wider than the real letters: keep a one-line headline on one line while it settles.
    if (oneLine) el.style.whiteSpace = 'nowrap';
    // Pin the box height while the glyphs settle, so the content under it never jumps (layout shift).
    el.style.height = el.offsetHeight + 'px'; el.style.width = el.offsetWidth + 'px';
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
      if (k < 1) requestAnimationFrame(frame); else { el.innerHTML = html; el.style.whiteSpace = ''; el.style.height = ''; el.style.width = ''; }
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

  // Theme: system by default, or the visitor's choice (the head script applied it before paint).
  var thBtns = [].slice.call(document.querySelectorAll('.seg.th button'));
  function theme(t) {
    if (t === 'dark' || t === 'light') root.setAttribute('data-theme', t); else { root.removeAttribute('data-theme'); t = 'system'; }
    thBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.dataset.th === t); });
    try { localStorage.setItem('theme', t); } catch (e) {}
  }
  thBtns.forEach(function (b) { b.onclick = function () { theme(b.dataset.th); }; });
  try { var t0 = localStorage.getItem('theme'); thBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.dataset.th === ((t0 === 'dark' || t0 === 'light') ? t0 : 'system')); }); } catch (e) {}

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

  // Brand: the letters rearrange on hover and the three squares fade in again from nothing.
  var brandTxt = document.querySelector('.brand [data-scr]'), brandLogo = document.querySelector('.brand .lg');
  if (brandTxt && !reduce) {
    var busy = false;
    brandTxt.parentNode.addEventListener('mouseenter', function () {
      if (busy) return; busy = true;
      decrypt(brandTxt, 450);
      if (brandLogo) { brandLogo.classList.remove('anim'); void brandLogo.getBoundingClientRect(); brandLogo.classList.add('anim'); }
      setTimeout(function () { busy = false; }, 900);
    });
  }

  // Case studies: swipe, arrows, dots, auto-rotate every 7 s (paused on hover, off under reduced motion).
  var car = $('car'), track = $('car-track');
  if (car && track) {
    var slides = [].slice.call(track.children), n = slides.length, idx = 0, dots = $('car-dots'), auto, x0 = null;
    slides.forEach(function (_, i) { var d = document.createElement('button'); d.type = 'button'; d.setAttribute('aria-label', 'Case ' + (i + 1)); d.onclick = function () { go(i, true); }; dots.appendChild(d); });
    var go = function (i, manual) {
      idx = (i + n) % n; track.style.transform = 'translateX(' + (-idx * 100) + '%)';
      [].slice.call(dots.children).forEach(function (d, j) { d.classList.toggle('on', j === idx); });
      if (manual) restart();
    };
    var restart = function () { clearInterval(auto); if (!reduce) auto = setInterval(function () { if (!document.hidden) go(idx + 1); }, 7000); };
    $('car-prev').onclick = function () { go(idx - 1, true); };
    $('car-next').onclick = function () { go(idx + 1, true); };
    car.addEventListener('mouseenter', function () { clearInterval(auto); });
    car.addEventListener('mouseleave', restart);
    car.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    car.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; x0 = null; if (Math.abs(dx) > 40) go(idx + (dx < 0 ? 1 : -1), true); }, { passive: true });
    document.addEventListener('keydown', function (e) { if (e.target.closest && e.target.closest('#car')) { if (e.key === 'ArrowRight') go(idx + 1, true); if (e.key === 'ArrowLeft') go(idx - 1, true); } });
    go(0); restart();
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

  // Interactive panel: automation runs, report pages and anonymized cases.
  var main = $('main'), view = $('view'), side = $('side');
  if (main && view && side) {
    var esc = function (x) { return String(x).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };
    var run = function (log, rows) { return { kind: 'run', log: log, rows: rows }; };
    var D = {
      'server-setup': Object.assign(run([
        ['09:14:02', 'ansible-playbook site.yml --limit new-droplet', 'ac'],
        ['09:14:09', 'digitalocean → droplet created · ams3 · 2 vCPU · 4 GB', 'ok'],
        ['09:14:41', 'ssh ready · root login disabled · deploy user with key only', 'ok'],
        ['09:15:10', 'hardening → firewall 22/80/443 · fail2ban · unattended security updates', 'ok'],
        ['09:15:48', 'docker + compose installed · log rotation set', 'ok'],
        ['09:16:20', 'caddy up → certificate requested and issued for app.example.nl', 'ok'],
        ['09:17:05', 'app deployed from the registry · migrations run · health check 200', 'ok'],
        ['09:17:30', 'backups → nightly dump to object storage · first restore test scheduled', ''],
        ['09:17:44', 'monitoring → uptime, disk, certificate alerts to one phone', 'ok'],
        ['09:17:51', 'done · 3 min 49 s · same playbook for staging and production', 'ac']
      ], [
        ['3 min 49 s from nothing to a hardened server', 'Droplet, firewall, Docker, TLS, app, backups, alerts. By hand this is most of a day, and never twice the same.', 'hardening', 'l', 'ANSIBLE'],
        ['Same playbook, every server', 'Staging and production are the same machine; a new server is a line in a file, not a weekend.', 'hardening', 'l', 'REPEAT'],
        ['Rebuild instead of repair', 'When a server misbehaves it is rebuilt from the playbook, with the data restored from last night.', 'maintenance-retainer', 'l', 'OK'],
        ['Sold as', 'Hardening and Secure Pipeline, or inside any build and modernization step', 'hardening', 'l', '→']
      ]), { title: 'Automation run · server-setup (Ansible, DigitalOcean)', done: 'done · 3 min 49 s' }),
      'release-pipeline': Object.assign(run([
        ['14:02:10', 'git push → pull request #412 opened', ''],
        ['14:02:15', 'ci → lint, static analysis, 312 tests · 1 min 40 s', 'ok'],
        ['14:03:58', 'security gates → dependency audit, secret scan, SAST · clean', 'ok'],
        ['14:04:30', 'review → agent review posted 2 comments, human approves', ''],
        ['14:09:12', 'merge → image built and tagged v2.31.0', 'ok'],
        ['14:10:05', 'deploy → staging, smoke test 200, then production', 'ok'],
        ['14:11:20', 'post-deploy → migrations ok, error rate flat, rollback image kept', 'ok'],
        ['14:11:26', 'done · 9 min from push to production · nobody touched a server', 'ac']
      ], [
        ['9 minutes from push to production', 'Tests, security gates, review, build, staging, production, in one pipeline that fails fast.', 'hardening', 'l', 'CI/CD'],
        ['Nothing merges red', 'A failing test or a leaked secret stops the release before a person has to notice.', 'hardening', 'l', 'GATE'],
        ['Rollback in one command', 'The previous image stays tagged; undoing a release takes a minute, not a night.', 'full-stack-build', 'l', 'SAFE'],
        ['Sold as', 'Hardening and Secure Pipeline, included in every Full-stack Build', 'full-stack-build', 'l', '→']
      ]), { title: 'Automation run · release-pipeline (GitHub Actions)', done: 'done · 9 min' }),
      'claude-skill': Object.assign(run([
        ['14:21:00', 'skill release-notes → merged pull requests since v2.30.0: 14', ''],
        ['14:21:04', 'read each PR: title, description, linked issue, changed files', ''],
        ['14:21:30', 'group → 3 features, 8 fixes, 2 security, 1 internal', 'ok'],
        ['14:21:38', 'write notes in the house style · link every item to its PR', 'ok'],
        ['14:21:40', 'check → no internal ticket numbers, no customer names, no secrets', 'ok'],
        ['14:21:45', 'draft release v2.31.0 opened for a human to publish', 'ac']
      ], [
        ['45 seconds instead of an hour', 'Release notes that used to be written on Friday afternoon, done before the deploy finishes.', 'ai-integration', 'l', 'SKILL'],
        ['Your procedure, written down', 'A skill is the checklist the agent must follow: what to read, how to write, what never to include.', 'ai-integration', 'l', 'REPEAT'],
        ['A person still publishes', 'The agent drafts; the release button stays human.', 'ai-integration', 'l', 'HUMAN'],
        ['Sold as', 'AI Integration: custom skills for the chores your team repeats', 'ai-integration', 'l', '→']
      ]), { title: 'Automation run · release-notes skill (Claude Code)', done: 'done · 45 s' }),
      'mcp-gateway': Object.assign(run([
        ['10:42:12', 'assistant → "which orders of customer 4471 are late?"', ''],
        ['10:42:12', 'mcp gateway → tool orders.search(customer=4471, late=true)', 'ac'],
        ['10:42:12', 'auth → read-only token of the asking user · audit row written', 'ok'],
        ['10:42:13', 'product api → 3 orders · 1.1 s', 'ok'],
        ['10:42:13', 'assistant → answer with order numbers and promised dates', ''],
        ['10:42:13', 'write tools → not exposed; every call logged', 'ok']
      ], [
        ['One MCP server in front of the API', 'The assistant gets named tools, not raw database access.', 'ai-integration', 'l', 'LIVE'],
        ['Read-only by default', 'Write tools exist only where a human approves the action.', 'ai-integration', 'l', 'OK'],
        ['Every call audited', 'Who asked what, which tool ran, what came back.', 'integrations', 'l', 'OK'],
        ['Sold as', 'AI Integration, on top of Integrations when the API is not there yet', 'ai-integration', 'l', '→']
      ]), { title: 'Live tool call · mcp-gateway (Model Context Protocol)', done: 'answered · 1.1 s' }),
      'lead-followup': Object.assign(run([
        ['08:30:00', 'crm → 9 leads without a reply after 5 days', ''],
        ['08:30:06', 'read last thread per lead → 9/9', ''],
        ['08:30:40', 'draft follow-up in the sender\'s own words → 9 drafts', 'ok'],
        ['08:30:52', 'policy check → no price promises, no new claims', 'ok'],
        ['08:31:00', '2 drafts mention a date → routed to a person', 'warn'],
        ['08:31:05', '7 drafts queued for one-click approval', 'ac'],
        ['08:31:05', 'nothing sent without a human tap ✓', 'ok']
      ], [
        ['7 drafts ready at 08:31', 'Each one shown with the thread it answers; approve or edit, then send. About 35 minutes of writing saved a day.', 'ai-integration', 'l', 'AUTO'],
        ['2 for a person', 'They mention a meeting date; a human decides.', 'ai-integration', 'm', 'HUMAN'],
        ['Sending stays human', 'The agent drafts; a person taps send, by design.', 'ai-integration', 'l', 'OK'],
        ['Sold as', 'AI Integration, connected to your CRM through Integrations', 'ai-integration', 'l', '→']
      ]), { title: 'Automation run · lead-followup', done: 'done · 7 drafts' }),
      'ticket-triage': Object.assign(run([
        ['07:00:00', 'helpdesk → 23 new tickets overnight', ''],
        ['07:00:08', 'classify → 14 how-to, 6 bugs, 3 billing', ''],
        ['07:00:35', 'how-to → answered from the manual, sources linked (14)', 'ok'],
        ['07:01:20', 'bugs → reproduced 4, logs attached, 2 need more info', 'warn'],
        ['07:01:25', 'billing → never automated, handed to finance (3)', ''],
        ['07:01:30', 'priority set on all 23 · P1: 1 (payment page down)', 'bad'],
        ['07:01:31', 'P1 → on-call paged ✓', 'ok']
      ], [
        ['14 answered before 07:02', 'From the product manual, with the source paragraph quoted; a person sees them first. About 48 minutes a day.', 'ai-integration', 'l', 'AUTO'],
        ['6 bugs prepared', 'Steps, logs and environment attached to each ticket.', 'ai-integration', 'l', 'AUTO'],
        ['1 P1 paged', 'Payment page down; found within 90 seconds of the ticket.', 'maintenance-retainer', 'h', 'P1'],
        ['Sold as', 'AI Integration, with the Maintenance Retainer watching the P1s', 'ai-integration', 'l', '→']
      ]), { title: 'Automation run · ticket-triage', done: 'done · 23 triaged' }),

      'health-check': { kind: 'report', title: 'Report · Health Check', done: '12 pages · 2 shown', pages: [
        '<div class="ph"><span>Health Check</span><span>scuttle.dev</span></div><h5>Health Check: order platform</h5><p>Two-day assessment, read-only. Report for the owner and the developer who inherits the system.</p><h6>Summary</h6><p>The platform earns money every day and runs on a runtime that stopped receiving fixes. Three items need work this quarter; the rest can wait and is priced below. Around 11 hours of manual work per week can be automated with the integrations already in place.</p><h6>Top findings</h6><table><tr><th>Finding</th><th class="r">Hours</th><th class="r">Risk</th></tr><tr><td>Runtime out of support</td><td class="r">24</td><td class="r"><span class="tag h">HIGH</span></td></tr><tr><td>Backups never restored</td><td class="r">3</td><td class="r"><span class="tag h">HIGH</span></td></tr><tr><td>Order export by hand, daily</td><td class="r">12</td><td class="r"><span class="tag m">AUTOMATE</span></td></tr><tr><td>No runbook</td><td class="r">4</td><td class="r"><span class="tag l">LOW</span></td></tr></table><span class="pn">1 / 12</span>',
        '<div class="ph"><span>Findings, ranked</span><span>page 2</span></div><h6>1 · Runtime out of support</h6><p>Security fixes stopped. A known vulnerability of the current version is public. Fix: upgrade in two steps with the test suite written first.</p><div class="bar"><i style="width:92%"></i></div><h6>2 · Backups exist, restore never tested</h6><p>Nightly dump present. A restore has never been performed; recovery time unknown. Fix: restore to a scratch server, time it, write it down.</p><div class="bar"><i style="width:80%"></i></div><h6>3 · Daily order export by hand</h6><p>An employee exports and re-keys orders into accounting, about 2 hours a day. Fix: an integration with retry and a daily check, or an agent with a human approval step.</p><div class="bar"><i style="width:64%"></i></div><h6>4 · Dependencies</h6><p>14 packages outdated, 3 with advisories.</p><div class="bar"><i style="width:40%"></i></div><span class="pn">2 / 12</span>',
        'x:Pages 3 to 12<br>confidential to the client'
      ] },
      'roadmap': { kind: 'report', title: 'Report · Roadmap, 6 steps', done: '8 pages · 2 shown', pages: [
        '<div class="ph"><span>Roadmap</span><span>scuttle.dev</span></div><h5>Six steps, each one releasable</h5><p>Order chosen so the business never stops: safety net first, then the items that remove risk, then the ones that save hours.</p><h6>Timeline</h6><div class="tl"><div><b>wk 1</b><i style="width:30%"></i><small>8 h</small></div><div><b>wk 2</b><i style="width:55%"></i><small>16 h</small></div><div><b>wk 3-4</b><i style="width:90%"></i><small>24 h</small></div><div><b>wk 5</b><i style="width:45%"></i><small>12 h</small></div><div><b>wk 6</b><i style="width:40%"></i><small>12 h</small></div><div><b>wk 7</b><i style="width:25%"></i><small>6 h</small></div></div><h6>Steps</h6><table><tr><td>1</td><td>Tested restore, monitoring, runbook v1</td></tr><tr><td>2</td><td>Characterization tests around the order flow</td></tr><tr><td>3</td><td>Runtime upgrade, two releases</td></tr><tr><td>4</td><td>Order export automated, human approval step</td></tr><tr><td>5</td><td>Dependencies and the 3 advisories</td></tr><tr><td>6</td><td>Handover pack, quarterly review</td></tr></table><span class="pn">1 / 8</span>',
        '<div class="ph"><span>Step 1 in detail</span><span>page 2</span></div><h6>Goal</h6><p>Know that the system can be brought back, before anything is changed.</p><h6>Work</h6><table><tr><td>Restore last night\'s dump to a scratch server</td><td class="r">2 h</td></tr><tr><td>Time it, write the recovery procedure</td><td class="r">1 h</td></tr><tr><td>Uptime, disk and certificate alerts to one phone</td><td class="r">3 h</td></tr><tr><td>Runbook v1: deploy, roll back, restore</td><td class="r">2 h</td></tr></table><h6>Done when</h6><p>A person who has never seen the system restores it from the runbook alone, in under 30 minutes.</p><h6>Price</h6><p>8 hours × EUR 90 = EUR 720 excl. VAT. Quote valid 14 days.</p><span class="pn">2 / 8</span>',
        'x:Pages 3 to 8<br>confidential to the client'
      ] },
      'security': { kind: 'report', title: 'Report · Security assessment', done: '16 pages · 2 shown', pages: [
        '<div class="ph"><span>Security assessment</span><span>scuttle.dev</span></div><h5>Web application and server</h5><p>Authorized test window of 5 days. Findings below, retest after the fixes included.</p><h6>By severity</h6><table><tr><th>Severity</th><th class="r">Found</th><th class="r">Fixed at retest</th></tr><tr><td><span class="tag h">HIGH</span></td><td class="r">2</td><td class="r">2</td></tr><tr><td><span class="tag m">MEDIUM</span></td><td class="r">5</td><td class="r">5</td></tr><tr><td><span class="tag l">LOW</span></td><td class="r">7</td><td class="r">4</td></tr></table><h6>High findings</h6><p><b>H1</b> Password reset token reusable for 24 hours. <b>H2</b> Export endpoint returns other tenants\' rows with a guessed id.</p><h6>Scope</h6><p>Public web application, API, one production-like server. No denial of service, no social engineering.</p><span class="pn">1 / 16</span>',
        '<div class="ph"><span>H2 in detail</span><span>page 2</span></div><h5>Export returns other tenants\' rows</h5><h6>Where</h6><p>GET /api/exports/{id} checks the login, not the tenant of the export.</p><h6>Proof</h6><p>Logged in as tenant A, request id 1042 of tenant B: 200, 318 rows. Reproduced three times in the test window.</p><h6>Impact</h6><p>Any customer can read any other customer\'s exports. Personal data, so a reportable breach if abused.</p><h6>Fix</h6><p>Scope the query by the tenant of the session; add a test that asserts 404 across tenants; audit the other 11 endpoints with an id in the path (list on page 3).</p><h6>Retest</h6><p>Fixed on day 3, verified: 404 across tenants, test present in CI.</p><span class="pn">2 / 16</span>',
        'x:Pages 3 to 16<br>confidential to the client'
      ] },

      'case-cost': { kind: 'story', title: 'Case · Cost saving, worked example', done: 'worked example', nums: [['22 h', 'a month by hand, before'], ['2 h', 'a month of review, after'], ['€4.20', 'model cost a month'], ['×11', 'return on the build in year one']],
        cols: [['Situation', '<p>A back office receives supplier invoices by mail. Someone opens each one, types the fields into accounting and matches it to an order. About 22 hours a month, every month, with the odd typo that costs a call to the supplier.</p>'],
               ['What I built', '<ul><li>An agent that reads the inbox, extracts the fields and matches orders</li><li>Posts to accounting only when the match is certain; the rest goes to a person with the fields pre-filled</li><li>An evaluation set of 48 real invoices it must pass before any change ships</li><li>A cost cap, a monthly report and a switch-off documented in the runbook</li></ul>'],
               ['Result', '<p>Two hours of review a month instead of twenty-two of typing. At EUR 45 an hour of staff time that is about EUR 900 a month back, against a build quoted per step at EUR 90 an hour and a model cost under EUR 5 a month.</p><p class="note">Worked example with illustrative numbers. Your own numbers come out of the Health Check.</p>']],
        cta: [['Start with one workflow', '/products/ai-integration'], ['Health Check', '/health-check']] },
      'case-mcp': { kind: 'story', title: 'Case · API meets AI (MCP gateway)', done: 'anonymized', nums: [['1', 'MCP server in front of the API'], ['14', 'read tools exposed'], ['human', 'approval before any write'], ['every call', 'audited']],
        cols: [['Situation', '<p>A software product with a mature REST API. Customers started asking whether they could use an AI assistant on their own data. The team did not want a chatbot bolted on, and did not want the model anywhere near the database.</p>'],
               ['What I built', '<ul><li>An MCP server (Model Context Protocol, the open standard assistants use to call tools) in front of the existing API</li><li>Named, read-only tools with the user\'s own permissions; no raw queries</li><li>Audit log of every call: who, which tool, what came back</li><li>Write actions only through tools that need a human approval step</li></ul>'],
               ['Result', '<p>Customers connect Claude or another assistant and ask questions in plain language; the product answers from its own API, within the rights that user already had. The API did not change. Client anonymized; the pattern is reusable for any product with an API.</p>']],
        cta: [['AI Integration', '/products/ai-integration'], ['Integrations', '/products/integrations']] },
      'case-migration': { kind: 'story', title: 'Case · Migration while in daily use', done: 'anonymized', nums: [['60%', 'lower latency'], ['7.4 → 8.4', 'PHP, back in support'], ['3.4 → 5.9', 'framework, on the way'], ['in use', 'the whole migration']],
        cols: [['Situation', '<p>A hotel operations platform on an old framework and an unsupported PHP. Specialists for the framework were hard to find, and a rewrite was too risky for a system hotels use every day.</p>'],
               ['What I built', '<ul><li>A thin layer that lets old and new code run side by side (Strangler Fig)</li><li>Modules moved one at a time, each release small, each with a rollback</li><li>PHP 7.4 to 8.4 and the framework upgraded on the way, so the runtime stayed supported during the migration</li></ul>'],
               ['Result', '<p>The hotels kept working through the whole migration. 60 percent lower latency, a supported runtime, a codebase developers know. Client anonymized, covered by confidentiality.</p>']],
        cta: [['Read the case study', '/case-studies/phalcon-to-laravel'], ['Modernization', '/products/modernization']] }
    };
    var pb = $('pb'), stt = $('stt'), sti = $('sti'), shH = $('sh-h'), timers = [], cur = null;
    var clear = function () { timers.forEach(clearTimeout); timers = []; };
    var later = function (f, ms) { timers.push(setTimeout(f, ms)); };
    var show = function (k) {
      var d = D[k]; if (!d || cur === k) return; cur = k; clear();
      [].slice.call(side.querySelectorAll('.it')).forEach(function (b) { b.classList.toggle('on', b.dataset.k === k); b.style.setProperty('--p', 0); });
      main.scrollTop = 0;
      shH.textContent = d.title; stt.textContent = d.kind === 'run' ? 'running' : 'opened'; sti.className = ''; pb.style.width = '0';
      view.style.animation = 'none'; void view.offsetWidth; view.style.animation = '';
      if (d.kind === 'run') {
        view.innerHTML = '<div class="log" id="log"></div><div class="find" id="find"></div>';
        var log = $('log'), find = $('find');
        d.log.forEach(function (l, i) {
          var r = document.createElement('div');
          r.innerHTML = '<span class="t">' + esc(l[0]) + '</span><span class="' + l[2] + '">' + esc(l[1]) + '</span>';
          log.appendChild(r);
          later(function () { r.classList.add('in'); pb.style.width = ((i + 1) / d.log.length * 100) + '%'; }, 300 + i * (reduce ? 0 : 380));
        });
        d.rows.forEach(function (x) {
          var r = document.createElement('a'); r.className = 'row'; r.href = '/products/' + x[2]; r.title = 'See the product behind this';
          r.innerHTML = '<div><b>' + esc(x[0]) + '</b><small>' + esc(x[1]) + '</small></div><span class="sev ' + x[3] + '">' + esc(x[4]) + '</span>';
          find.appendChild(r);
        });
        later(function () {
          stt.textContent = d.done; sti.className = 'ok';
          [].slice.call(find.children).forEach(function (r, i) { later(function () { r.classList.add('in'); }, i * 120); });
        }, 300 + d.log.length * (reduce ? 0 : 380));
      } else if (d.kind === 'report') {
        var h = '<div class="pages">';
        d.pages.forEach(function (pg) {
          if (pg.indexOf('x:') === 0) {
            var bl = ''; for (var i = 0; i < 26; i++) bl += '<span></span>';
            h += '<div class="pg x"><div class="blur">' + bl + '</div><div class="lockc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg><span>' + pg.slice(2) + '</span></div></div>';
          } else h += '<div class="pg">' + pg + '</div>';
        });
        h += '</div><div class="cta-row"><a class="btn" href="/health-check">What a Health Check costs</a><span class="note">Example pages, made-up names. Every client gets the full document, and only the client.</span></div>';
        view.innerHTML = h; pb.style.width = '100%'; later(function () { stt.textContent = d.done; sti.className = 'ok'; }, 400);
      } else {
        var g = '<div class="nums">' + d.nums.map(function (n) { return '<div><b>' + esc(n[0]) + '</b><span>' + esc(n[1]) + '</span></div>'; }).join('') + '</div><div class="story">' +
          d.cols.map(function (c) { return '<div class="sc"><small>' + esc(c[0]) + '</small>' + c[1] + '</div>'; }).join('') + '</div><div class="cta-row">' +
          d.cta.map(function (c, i) { return '<a class="btn' + (i ? ' o' : '') + '" href="' + c[1] + '">' + esc(c[0]) + '</a>'; }).join('') + '</div>';
        view.innerHTML = g; pb.style.width = '100%'; later(function () { stt.textContent = d.done; sti.className = 'ok'; }, 400);
      }
    };
    // Auto-rotate through the menu; a click pauses it for a while, hovering pauses it.
    var keys = [].slice.call(side.querySelectorAll('.it')).map(function (b) { return b.dataset.k; }), ROT = 11000, rotT = null, rotStart = 0, hold = false, hover = false, raf;
    var tick = function () {
      var on = side.querySelector('.it.on');
      if (on && !hold && !hover) on.style.setProperty('--p', Math.min(1, (Date.now() - rotStart) / ROT));
      raf = requestAnimationFrame(tick);
    };
    var rotate = function () {
      clearTimeout(rotT); rotStart = Date.now();
      rotT = setTimeout(function () {
        if (hold || hover || document.hidden) { rotate(); return; }
        show(keys[(keys.indexOf(cur) + 1) % keys.length]); rotate();
      }, ROT);
    };
    main.addEventListener('mouseenter', function () { hover = true; }); main.addEventListener('mouseleave', function () { hover = false; rotStart = Date.now(); rotate(); });
    side.addEventListener('mouseenter', function () { hover = true; }); side.addEventListener('mouseleave', function () { hover = false; });
    side.addEventListener('click', function (e) { var b = e.target.closest('.it'); if (b) { show(b.dataset.k); hold = true; clearTimeout(rotT); setTimeout(function () { hold = false; rotate(); }, 30000); } });
    var started = false, first = function () { if (started) return; started = true; show('server-setup'); if (!reduce) { rotate(); raf = requestAnimationFrame(tick); } };
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) first(); }); }, { threshold: 0.2 }).observe(main);
    }
    setTimeout(first, 2500);
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

  // Modals: QR code, photo, map (the map iframe is created only when opened).
  function modal(id, openBtn) {
    var m = $(id); if (!m) return;
    var close = function () { m.classList.remove('active'); m.setAttribute('aria-hidden', 'true'); };
    var open = function () {
      m.classList.add('active'); m.setAttribute('aria-hidden', 'false');
      var f = m.querySelector('.frame[data-src]');
      if (f && !f.firstChild) { var i = document.createElement('iframe'); i.src = f.dataset.src; i.loading = 'lazy'; i.referrerPolicy = 'no-referrer-when-downgrade'; i.setAttribute('allowfullscreen', ''); i.title = 'Map'; f.appendChild(i); }
    };
    if (openBtn) openBtn.onclick = open;
    [].slice.call(m.querySelectorAll('[data-close], .ov, .x')).forEach(function (b) { b.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && m.classList.contains('active')) close(); });
  }
  modal('qr-modal', $('open-qr'));
  modal('pic-modal', $('open-pic'));
  modal('map-modal', $('open-map'));

  // Contact form: preselect the product from ?about= and scroll to a sent or failed form.
  var cf = $('cform');
  if (cf) {
    try {
      var about = new URLSearchParams(location.search).get('about'), sel = $('cf-about');
      if (about && sel) [].slice.call(sel.options).forEach(function (o) { if (o.value === about) sel.value = about; });
    } catch (e) {}
    if (cf.querySelector('.sent, .err')) setTimeout(function () { cf.scrollIntoView({ block: 'center' }); }, 100);
  }

  // Cookie banner: Google Analytics loads only after Agree; Deny sets nothing and is remembered.
  var ck = $('cookie');
  if (ck && window.SCUTTLE_GA) {
    var loadGa = function () {
      var g = document.createElement('script'); g.async = true; g.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(window.SCUTTLE_GA); document.head.appendChild(g);
      window.dataLayer = window.dataLayer || []; function gtag() { window.dataLayer.push(arguments); } gtag('js', new Date()); gtag('config', window.SCUTTLE_GA, { anonymize_ip: true });
    };
    var choice = null; try { choice = localStorage.getItem('consent'); } catch (e) {}
    if (choice === 'agree') loadGa(); else if (choice !== 'deny') ck.hidden = false;
    $('ck-agree').onclick = function () { try { localStorage.setItem('consent', 'agree'); } catch (e) {} ck.hidden = true; loadGa(); };
    $('ck-deny').onclick = function () { try { localStorage.setItem('consent', 'deny'); } catch (e) {} ck.hidden = true; };
  }

  try { if (localStorage.getItem('lang') === 'nl') lang('nl'); } catch (e) {}
})();
