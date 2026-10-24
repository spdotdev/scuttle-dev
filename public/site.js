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

  // Interactive panel: automation runs, report pages and anonymized cases.
  var main = $('main'), view = $('view'), side = $('side');
  if (main && view && side) {
    var esc = function (x) { return String(x).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };
    var run = function (log, rows) { return { kind: 'run', log: log, rows: rows }; };
    var D = {
      'invoice-intake': Object.assign(run([
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
      ], [
        ['11 invoices booked', 'Matched to a purchase order · posted to accounting', '22 min', 'l', 'AUTO'],
        ['1 invoice for review', 'Unknown supplier · waiting for a person, with the extracted fields ready', '2 min', 'm', 'HUMAN'],
        ['2 reminders archived', 'Payment reminders for invoices already paid', '4 min', 'l', 'AUTO'],
        ['Cost this month', '€4.20 of a €50 cap · switch-off documented', 'cap', 'l', 'OK'],
        ['Evaluation set', '48 real examples with known answers · all passed before this run', '48/48', 'l', 'OK'],
        ['Summary posted', 'Finance channel · who to call when it stops', 'done', 'l', 'OK']
      ]), { title: 'Automation run · invoice-intake', done: 'done · 14 processed' }),
      'lead-followup': Object.assign(run([
        ['00:00', 'crm → 9 leads without a reply after 5 days', ''],
        ['00:01', 'read last thread per lead → 9/9', ''],
        ['00:02', 'draft follow-up in the sender\'s own words → 9 drafts', 'ok'],
        ['00:03', 'policy check → no price promises, no new claims', 'ok'],
        ['00:04', '2 drafts mention a date → routed to a person', 'warn'],
        ['00:05', '7 drafts queued for one-click approval', 'ac'],
        ['00:05', 'nothing sent without a human tap ✓', 'ok']
      ], [
        ['7 drafts ready', 'Each one shown with the thread it answers; approve or edit, then send', '35 min', 'l', 'AUTO'],
        ['2 for a person', 'They mention a meeting date; a human decides', '', 'm', 'HUMAN'],
        ['0 sent by the agent', 'Sending stays a human action by design', '', 'l', 'OK'],
        ['Cost this run', '€0.09 · 28 s', '', 'l', 'OK']
      ]), { title: 'Automation run · lead-followup', done: 'done · 7 drafts' }),
      'ticket-triage': Object.assign(run([
        ['00:00', 'helpdesk → 23 new tickets overnight', ''],
        ['00:01', 'classify → 14 how-to, 6 bugs, 3 billing', ''],
        ['00:02', 'how-to → answered from the manual, sources linked (14)', 'ok'],
        ['00:03', 'bugs → reproduced 4, logs attached, 2 need more info', 'warn'],
        ['00:04', 'billing → never automated, handed to finance (3)', ''],
        ['00:05', 'priority set on all 23 · P1: 1 (payment page down)', 'bad'],
        ['00:05', 'P1 → on-call paged ✓', 'ok']
      ], [
        ['14 answered', 'From the product manual, with the source paragraph quoted; a person sees them first', '48 min', 'l', 'AUTO'],
        ['6 bugs prepared', 'Steps, logs and environment attached to each ticket', '30 min', 'l', 'AUTO'],
        ['3 billing', 'Handed to finance untouched, by rule', '', 'm', 'HUMAN'],
        ['1 P1 paged', 'Payment page down; found within 4 minutes of the ticket', '', 'h', 'P1']
      ]), { title: 'Automation run · ticket-triage', done: 'done · 23 triaged' }),
      'nightly-report': Object.assign(run([
        ['02:00', 'orders, stock, failed jobs, queue depth → collected', ''],
        ['02:01', 'compare with the same weekday last 8 weeks', ''],
        ['02:02', 'anomaly → refunds 3.1× normal (webshop, after 18:00)', 'bad'],
        ['02:02', 'anomaly → 0 failed jobs, 0 queue backlog', 'ok'],
        ['02:03', 'one-page report written, numbers linked to their source', 'ok'],
        ['02:03', 'sent to 3 people · 07:00 delivery', 'ac']
      ], [
        ['Refunds 3.1× normal', 'Flagged with the orders behind it; turned out to be one bulk order cancelled', '', 'h', 'FLAG'],
        ['Everything else normal', 'Shown as one line, not twenty charts', '', 'l', 'OK'],
        ['Report at 07:00', 'Same layout every day, numbers click through to their source', '15 min', 'l', 'AUTO']
      ]), { title: 'Automation run · nightly-report', done: 'done · 1 flag' }),
      'mcp-gateway': Object.assign(run([
        ['00:00', 'assistant → "which orders of customer 4471 are late?"', ''],
        ['00:00', 'mcp gateway → tool orders.search(customer=4471, late=true)', 'ac'],
        ['00:00', 'auth → read-only token of the asking user · audit row written', 'ok'],
        ['00:01', 'product api → 3 orders · 1.1 s', 'ok'],
        ['00:01', 'assistant → answer with order numbers and promised dates', ''],
        ['00:01', 'write tools → not exposed; every call logged', 'ok']
      ], [
        ['One MCP server in front of the API', 'The assistant gets named tools, not raw database access', '', 'l', 'LIVE'],
        ['Read-only by default', 'Write tools exist only where a human approves the action', '', 'l', 'OK'],
        ['Every call audited', 'Who asked what, which tool ran, what came back', '', 'l', 'OK'],
        ['Works with Claude and others', 'MCP is an open standard; the same gateway serves every assistant', '', 'l', 'OK']
      ]), { title: 'Live tool call · mcp-gateway', done: 'answered · 1.1 s' }),

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
      'case-mcp': { kind: 'story', title: 'Case · API meets AI (MCP gateway)', done: 'anonymized', nums: [['1', 'MCP server in front of the API'], ['14', 'read tools exposed'], ['0', 'write tools without approval'], ['100%', 'of calls audited']],
        cols: [['Situation', '<p>A software product with a mature REST API. Customers started asking whether they could use an AI assistant on their own data. The team did not want a chatbot bolted on, and did not want the model anywhere near the database.</p>'],
               ['What I built', '<ul><li>An MCP server (Model Context Protocol, the open standard assistants use to call tools) in front of the existing API</li><li>Named, read-only tools with the user\'s own permissions; no raw queries</li><li>Audit log of every call: who, which tool, what came back</li><li>Write actions only through tools that need a human approval step</li></ul>'],
               ['Result', '<p>Customers connect Claude or another assistant and ask questions in plain language; the product answers from its own API, within the rights that user already had. The API did not change. Client anonymized; the pattern is reusable for any product with an API.</p>']],
        cta: [['AI Integration', '/products/ai-integration'], ['Integrations', '/products/integrations']] },
      'case-migration': { kind: 'story', title: 'Case · Migration while in daily use', done: 'anonymized', nums: [['60%', 'lower latency'], ['7.4 → 8.4', 'PHP, back in support'], ['0', 'rewrites from scratch'], ['0', 'planned stops for users']],
        cols: [['Situation', '<p>A hotel operations platform on an old framework and an unsupported PHP. Specialists for the framework were hard to find, and a rewrite was too risky for a system hotels use every day.</p>'],
               ['What I built', '<ul><li>A thin layer that lets old and new code run side by side (Strangler Fig)</li><li>Modules moved one at a time, each release small, each with a rollback</li><li>PHP 7.4 to 8.4 and the framework upgraded on the way, so the runtime stayed supported during the migration</li></ul>'],
               ['Result', '<p>The hotels kept working through the whole migration. 60 percent lower latency, a supported runtime, a codebase developers know. Client anonymized, covered by confidentiality.</p>']],
        cta: [['Read the case study', '/case-studies/phalcon-to-laravel'], ['Modernization in Steps', '/products/modernization']] }
    };
    var pb = $('pb'), stt = $('stt'), sti = $('sti'), shH = $('sh-h'), timers = [], cur = null;
    var clear = function () { timers.forEach(clearTimeout); timers = []; };
    var later = function (f, ms) { timers.push(setTimeout(f, ms)); };
    var show = function (k) {
      var d = D[k]; if (!d || cur === k) return; cur = k; clear();
      [].slice.call(side.querySelectorAll('.it')).forEach(function (b) { b.classList.toggle('on', b.dataset.k === k); });
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
          var r = document.createElement('div'); r.className = 'row';
          r.innerHTML = '<div><b>' + esc(x[0]) + '</b><small>' + esc(x[1]) + '</small></div><span class="hrs">' + esc(x[2]) + '</span><span class="sev ' + x[3] + '">' + esc(x[4]) + '</span>';
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
    side.addEventListener('click', function (e) { var b = e.target.closest('.it'); if (b) show(b.dataset.k); });
    var started = false, first = function () { if (started) return; started = true; show('invoice-intake'); };
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
