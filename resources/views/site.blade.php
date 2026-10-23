@extends('scuttle::layout')

@section('title', 'Legacy PHP modernization and security, Eindhoven')
@section('description', 'Keep your legacy PHP application in support. Legacy Health Check, step-by-step modernization (Phalcon and legacy PHP to Laravel), hardening and handover. One senior developer in Eindhoven.')
@section('canonical', 'https://scuttle.dev/')
@section('ogtype', 'website')

@section('head')
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "Scuttle Development",
    "description": "Legacy PHP modernization, security hardening and full-stack development by one senior developer in Eindhoven.",
    "url": "https://scuttle.dev",
    "logo": "{{ secure_asset('vendor/scuttle/android-chrome-512x512.png') }}",
    "image": "{{ secure_asset('vendor/scuttle/og-image.png') }}",
    "telephone": "+31618233175",
    "email": "info@scuttle.dev",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Dirigentplein 144",
      "addressLocality": "Eindhoven",
      "postalCode": "5642 RK",
      "addressCountry": "NL"
    },
    "openingHours": "Mo-Fr 09:00-18:00",
    "priceRange": "EUR 90 per hour",
    "vatID": "NL005184584B85",
    "taxID": "96040947",
    "founder": { "@type": "Person", "name": "Stanislav Plotnikov" },
    "sameAs": ["https://github.com/spdotdev", "https://www.linkedin.com/in/stanislavplotnikov/"],
    "serviceType": ["Legacy PHP modernization", "Legacy Health Check", "Phalcon to Laravel migration", "Security hardening", "Full-stack development"]
  }
  </script>
@endsection

@section('homelinks')
  <a href="#services" data-i="n_services">Services</a><a href="#stack" data-i="n_stack">Stack</a><a href="#work" data-i="n_work">Work</a><a href="#case" data-i="n_case">Case study</a><a href="#sectors" data-i="n_sectors">Sectors</a><a href="#contact" data-i="n_contact">Contact</a>
@endsection

@section('content')
<header class="wrap hero">
  <img class="mark" alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="92" height="74">
  <h1 data-i="h1">Keep your legacy PHP<br>in support</h1>
  <div class="sub" data-i="sub">Modernization · Security · Eindhoven</div>
  <div class="cta"><a class="btn" href="{{ route('scuttle.health-check') }}" data-i="cta1">Book a Health Check <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><a class="btn o" href="{{ route('scuttle.case-study') }}" data-i="cta2">Read the case study</a></div>
  <div class="cmd"><span>&gt; php -v&nbsp;&nbsp;<span>PHP 7.4.33</span>&nbsp;&nbsp;<b>end of life since 28 Nov 2022</b></span><button aria-label="Copy" id="copy" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5h10"/></svg></button></div>
</header>

<div class="wrap">
  <div class="shot rv" aria-label="Example of a Legacy Health Check report">
    <aside class="side">
      <div class="h"><span data-i="sd_h">Systems</span><span>4</span></div>
      <div class="it on"><span>hotel-platform</span><small data-i="now">now</small></div>
      <div class="it"><span>rental-platform</span><small>2d</small></div>
      <div class="it"><span>webshop-legacy</span><small>6d</small></div>
      <div class="it"><span>erp-bridge</span><small>12d</small></div>
      <div class="h" style="margin-top:22px"><span data-i="sd_r">Reports</span></div>
      <div class="it"><span>Legacy Health Check</span><small>pdf</small></div>
      <div class="it"><span data-i="sd_r2">Roadmap, 6 steps</span><small>pdf</small></div>
      <div class="it"><span>Security assessment</span><small>pdf</small></div>
    </aside>
    <div class="main">
      <div class="top"><h4>Legacy Health Check · hotel-platform</h4><div class="st"><i></i><span id="stt" data-i="sh_run">scanning</span></div></div>
      <div class="prog"><i id="pb"></i></div>
      <div class="log" id="log"></div>
      <div class="find" id="find">
        <div class="row"><div><b>CentOS 7</b><small data-i="f1">Operating system · support ended 30 Jun 2024 · no security updates</small></div><span class="hrs">8 h</span><span class="sev h">HIGH</span></div>
        <div class="row"><div><b>PHP 7.4 → 8.3</b><small data-i="f2">Runtime · support ended 28 Nov 2022 · 14 deprecated calls found</small></div><span class="hrs">24 h</span><span class="sev h">HIGH</span></div>
        <div class="row"><div><b>MySQL 5.7 → 8.0</b><small data-i="f3">Database · support ended Oct 2023 · 2 incompatible queries</small></div><span class="hrs">12 h</span><span class="sev h">HIGH</span></div>
        <div class="row"><div><b>Composer</b><small data-i="f4">14 packages outdated · 3 with known vulnerabilities</small></div><span class="hrs">6 h</span><span class="sev m">MED</span></div>
        <div class="row"><div><b data-i="f5h">Backups</b><small data-i="f5">Nightly dump exists · restore never tested</small></div><span class="hrs">3 h</span><span class="sev m">MED</span></div>
        <div class="row"><div><b data-i="f6h">Documentation</b><small data-i="f6">No runbook · setup known by one person</small></div><span class="hrs">4 h</span><span class="sev l">LOW</span></div>
      </div>
    </div>
  </div>
  <p class="shot-note" data-i="shot_note">Example report with made-up system names. Yours lists your own servers and versions.</p>
</div>

<section class="s" id="services"><div class="wrap">
  <div class="center rv"><h2 data-i="sv_h">The foundation for a system that stays in support</h2><p data-i="sv_p">Assess, upgrade in steps, hand over. One developer, fixed scope per step, nothing deployed without a rollback.</p></div>
  <div class="cards3">
    <a class="c rv" href="{{ route('scuttle.health-check') }}"><span class="lbl">01 <span data-i="c1l">Assess</span></span><h3>Legacy Health Check</h3><p data-i="c1p">Every layer checked against its official end-of-life date. You get a ranked report with hours per fix and a step-by-step plan, so you can plan the budget before anything is touched.</p><span class="lbl acc" data-i="c1x">from €1,600 · excl. VAT · 2 days</span><span class="more" data-i="c1m">Read more →</span></a>
    <div class="c rv"><span class="lbl">02 <span data-i="c2l">Upgrade</span></span><h3 data-i="c2h">Modernization in steps</h3><p data-i="c2p">OS, database, PHP, framework. One layer at a time, each step deployed on its own with tests written first. Phalcon and legacy PHP to Laravel, Docker and CI/CD included.</p><span class="lbl acc" data-i="c2x">€90 per hour · excl. VAT · quoted per step</span></div>
    <div class="c rv"><span class="lbl">03 <span data-i="c3l">Hand over</span></span><h3 data-i="c3h">Hardening and handover</h3><p data-i="c3p">Ports closed, TLS current, backups restored for real, a runbook any developer can follow. You own the result and never depend on me to keep it alive.</p><span class="lbl acc" data-i="c3x">included in every project</span></div>
  </div>
</div></section>

<section class="s tight" id="stack"><div class="wrap">
  <div class="dp rv">
    <div><span class="lbl dot" data-i="st_l">Stack</span><h2 style="margin-top:16px" data-i="st_h">Move between old and new without a rewrite</h2><p data-i="st_p">I work in the stack you already run and bring it forward in place. New builds and AI tooling use the same discipline: tests first, small releases, documented.</p></div>
    <div class="chips">
      <span class="chip"><i></i>PHP</span><span class="chip"><i></i>Laravel</span><span class="chip"><i></i>Phalcon</span><span class="chip"><i></i>MySQL</span><span class="chip"><i></i>Docker</span><span class="chip"><i></i>Ansible</span><span class="chip"><i></i>Linux</span><span class="chip"><i></i>Python</span><span class="chip"><i></i>Kotlin</span><span class="chip"><i></i>DigitalOcean</span>
      <span class="chip ai"><i></i>Claude Code</span><span class="chip ai"><i></i>Agentic workflows</span><span class="chip ai"><i></i>Custom skills</span><span class="chip ai"><i></i>MCP servers</span><span class="chip ai"><i></i>RAG</span><span class="chip ai"><i></i>SaaS</span>
      <small data-i="st_s">+ the framework or hosting you already have</small>
    </div>
  </div>
</div></section>

<section class="s tight" id="work"><div class="wrap">
  <div class="gridh rv"><h2 data-i="wk_h">What I build</h2><p data-i="wk_p">Fixed, visible deliverables. Each one leaves a document in your repository, not only a result on a server.</p></div>
  <div class="tiles">
    <div class="tile rv"><div class="img"><span class="k">report</span><div class="bars"><i class="a" style="--w:90%"></i><i class="a" style="--w:70%"></i><i style="--w:45%"></i><i style="--w:30%"></i></div></div><h3>Health Check</h3><p data-i="t1p">Ranked findings, hours per fix, roadmap.</p></div>
    <div class="tile rv"><div class="img"><span class="k">release</span><div class="tl"><i style="--h:30%"></i><i style="--h:45%"></i><i class="a" style="--h:60%"></i><i style="--h:40%"></i><i class="a" style="--h:80%"></i><i class="a" style="--h:100%"></i></div></div><h3 data-i="t2h">Step upgrades</h3><p data-i="t2p">OS, database, PHP, framework. Weekly releases.</p></div>
    <div class="tile rv"><div class="img"><span class="k">migration</span><div class="box3"><i></i><i class="a"></i><i class="a"></i><i class="a"></i></div></div><h3 data-i="t3h">Phalcon to Laravel</h3><p data-i="t3p">Module by module, behind the running system.</p></div>
    <div class="tile rv"><div class="img"><span class="k">security</span><div class="lock"></div><span>ports 22, 443 · TLS 1.3</span></div><h3 data-i="t4h">Hardening</h3><p data-i="t4p">Attack surface closed, assessment report.</p></div>
    <div class="tile rv"><div class="img"><span class="k">terminal</span><div class="term"><span>docker compose up</span><span>ansible-playbook site.yml</span><span>phpunit --testsuite all</span></div></div><h3 data-i="t5h">Docker and CI/CD</h3><p data-i="t5p">Dev, staging and production identical.</p></div>
    <div class="tile rv"><div class="img"><span class="k">backup</span><span>restore 02:14 ✓</span><span>checksum match ✓</span><span>rto 18 min</span></div><h3 data-i="t6h">Backups that restore</h3><p data-i="t6p">Tested restore, documented recovery time.</p></div>
    <div class="tile rv"><div class="img"><span class="k">agent</span><div class="diag"><i class="a"></i><i></i><i></i><i></i><i class="a"></i><i></i><i></i><i></i><i class="a"></i></div></div><h3 data-i="t7h">AI tooling</h3><p data-i="t7p">Agentic workflows, custom skills, MCP, RAG.</p></div>
    <div class="tile rv"><div class="img"><span class="k">handover</span><span>runbook.md</span><span>architecture.md</span><span>decisions/</span></div><h3 data-i="t8h">Handover pack</h3><p data-i="t8p">Runbook, architecture, decisions. Yours.</p></div>
  </div>
</div></section>

<section class="s tight" id="case"><div class="wrap">
  <span class="lbl dot" data-i="cs_l">Featured case study</span>
  <div class="case rv" style="margin-top:18px">
    <div class="pic"><div class="lk"><img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="60" height="48">Phalcon<span>→</span>Laravel</div></div>
    <div class="txt"><div class="who" data-i="cs_k">Hotel software platform · client anonymized</div>
      <q data-i="cs_q">Phalcon to Laravel, module by module, while the hotels kept booking. No big-bang switch, no rewrite from scratch.</q>
      <div class="by"><span data-i="cs_by">Project summary, written by Scuttle</span><small data-i="cs_sm">Covered by confidentiality, references on request</small></div>
      <div class="acts"><a class="btn" href="{{ route('scuttle.case-study') }}" data-i="cs_b1">Read the case study</a><a class="btn o" href="mailto:info@scuttle.dev?subject=References" data-i="cs_b2">Ask for references</a></div>
    </div>
  </div>
  <div class="kv"><div><b>60%</b><span data-i="k1s">lower latency</span></div><div><b>PHP 7.4 → 8.4</b><span data-i="k2s">runtime back in support</span></div><div><b>Phalcon 3.4 → 5.9</b><span data-i="k3s">framework upgraded on the way</span></div><div><b>0</b><span data-i="k4s">rewrites from scratch</span></div></div>
</div></section>

<section class="s tight" id="sectors"><div class="wrap">
  <div class="ind rv">
    <div><span class="lbl dot" data-i="in_l">Sectors</span><h2 style="margin-top:16px" data-i="in_h">Built for businesses that run on PHP</h2><p data-i="in_p">Systems that earn money every day and cannot stop for a rewrite.</p></div>
    <div class="list">
      <div><b data-i="i1">Hospitality</b><span data-i="i1s">Booking and operations platforms, multi-property.</span></div>
      <div><b data-i="i2">Rentals and real estate</b><span data-i="i2s">Listings, contracts, payments, tenant portals.</span></div>
      <div><b data-i="i3">E-commerce</b><span data-i="i3s">Legacy shops, ERP bridges, payment providers.</span></div>
      <div><b data-i="i4">Logistics</b><span data-i="i4s">Planning tools, scanners, EDI integrations.</span></div>
      <div><b data-i="i5">Healthcare IT</b><span data-i="i5s">Patient-facing portals under strict data rules.</span></div>
      <div><b>SaaS</b><span data-i="i6s">Multi-tenant products that outgrew their first version.</span></div>
    </div>
  </div>
</div></section>

<section class="cta-end" id="contact"><div class="wrap">
  <h2 data-i="ct_h">Ready to bring your system back into support?</h2>
  <p data-i="ct_p">Tell me what you are running. I reply within one working day with a first opinion, free.</p>
  <div class="row"><a class="btn" href="mailto:info@scuttle.dev" data-i="ct_b1">Email info@scuttle.dev</a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
</div></section>

<div class="wrap contact" id="about">
  <div class="cc rv">
    <span class="lbl" data-i="cd_l">Company</span>
    <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Scuttle Development<br>Dirigentplein 144<br>5642 RK Eindhoven<br>The Netherlands</span></div>
    <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:info@scuttle.dev">info@scuttle.dev</a></div>
    <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 2z"/></svg><a href="tel:+31618233175">+31 (0) 6 18 23 31 75</a></div>
    <div class="line mono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span>KvK 96040947<br>BTW NL005184584B85</span></div>
  </div>
  <div class="cc rv">
    <span class="lbl" data-i="qr_l">Save my contact details</span>
    <div class="qr">
      <button id="open-qr" type="button" aria-label="Show the QR code larger"><img src="{{ secure_asset('vendor/scuttle/qr/svg/My_QR_Code_1-1024.svg') }}" alt="QR code with my contact details (vCard)" width="104" height="104"></button>
      <div><p data-i="qr_p">Scan the code with your phone, or download the vCard and add me to your contacts in one tap.</p><a class="btn o" href="{{ secure_asset('vendor/scuttle/scuttledev.vcf') }}" download data-i="qr_b">Save vCard</a></div>
    </div>
  </div>
  <div class="cc rv">
    <span class="lbl" data-i="ab_l">About</span>
    <div class="about">
      <img src="{{ secure_asset('vendor/scuttle/portrait.jpg') }}" alt="Stanislav Plotnikov" width="96" height="96" loading="lazy">
      <div><p data-i="ab_p">Stanislav Plotnikov. Senior developer and solution architect, PHP since the 2000s, Laravel and Phalcon in production, Python for automation and AI. One person, one point of contact, from the first call to the handover.</p>
        <div class="soc"><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div></div>
    </div>
  </div>
</div>

<div id="qr-modal" class="modal" aria-hidden="true" role="dialog" aria-label="QR code with my contact details">
  <div class="ov" id="qr-overlay"></div>
  <div class="box">
    <button class="x" id="close-qr" type="button" aria-label="Close">&times;</button>
    <img src="{{ secure_asset('vendor/scuttle/qr/svg/My_QR_Code_1-4096.svg') }}" alt="QR code with my contact details (vCard), large" width="360" height="360">
    <div class="cap">scan · vCard · Scuttle Development</div>
  </div>
</div>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
  n_stack: "Stack", n_work: "Werk", n_sectors: "Sectoren",
  h1: "Houd je legacy PHP<br>binnen support", sub: "Modernisering · Beveiliging · Eindhoven", cta1: "Boek een Health Check", cta2: "Lees de casestudy",
  sd_h: "Systemen", now: "nu", sd_r: "Rapporten", sd_r2: "Roadmap, 6 stappen", sh_run: "scannen",
  f1: "Besturingssysteem · support beëindigd 30 jun 2024 · geen beveiligingsupdates", f2: "Runtime · support beëindigd 28 nov 2022 · 14 verouderde aanroepen", f3: "Database · support beëindigd okt 2023 · 2 incompatibele queries", f4: "14 pakketten verouderd · 3 met bekende kwetsbaarheden", f5h: "Back-ups", f5: "Nachtelijke dump aanwezig · herstel nooit getest", f6h: "Documentatie", f6: "Geen runbook · installatie bekend bij één persoon",
  shot_note: "Voorbeeldrapport met verzonnen systeemnamen. Dat van jou toont je eigen servers en versies.",
  sv_h: "De basis voor een systeem dat binnen support blijft", sv_p: "Beoordelen, in stappen upgraden, overdragen. Eén developer, vaste scope per stap, niets live zonder rollback.",
  c1l: "Beoordelen", c1p: "Elke laag gecontroleerd tegen de officiële end-of-life-datum. Je krijgt een gerangschikt rapport met uren per fix en een stappenplan, zodat je het budget plant voordat er iets wordt aangeraakt.", c1x: "vanaf €1.600 · excl. btw · 2 dagen", c1m: "Lees meer →",
  c2l: "Upgraden", c2h: "Modernisering in stappen", c2p: "OS, database, PHP, framework. Eén laag tegelijk, elke stap apart live met tests vooraf. Phalcon en legacy PHP naar Laravel, Docker en CI/CD inbegrepen.", c2x: "€90 per uur · excl. btw · per stap geoffreerd",
  c3l: "Overdragen", c3h: "Hardening en overdracht", c3p: "Poorten dicht, TLS actueel, back-ups echt teruggezet, een runbook dat elke developer kan volgen. Het resultaat is van jou en je hangt nooit van mij af.", c3x: "inbegrepen bij elk project",
  st_l: "Stack", st_h: "Van oud naar nieuw zonder herbouw", st_p: "Ik werk in de stack die je al draait en breng die ter plekke vooruit. Nieuwbouw en AI-tooling volgen dezelfde discipline: tests eerst, kleine releases, gedocumenteerd.", st_s: "+ het framework of de hosting die je al hebt",
  wk_h: "Wat ik bouw", wk_p: "Vaste, zichtbare opleveringen. Elk onderdeel laat een document achter in je repository, niet alleen een resultaat op een server.",
  t1p: "Gerangschikte bevindingen, uren per fix, roadmap.", t2h: "Stapsgewijze upgrades", t2p: "OS, database, PHP, framework. Wekelijkse releases.", t3h: "Phalcon naar Laravel", t3p: "Module voor module, achter het draaiende systeem.", t4h: "Hardening", t4p: "Aanvalsoppervlak dicht, assessmentrapport.", t5h: "Docker en CI/CD", t5p: "Dev, staging en productie identiek.", t6h: "Back-ups die terugzetten", t6p: "Geteste restore, gedocumenteerde hersteltijd.", t7h: "AI-tooling", t7p: "Agentic workflows, eigen skills, MCP, RAG.", t8h: "Overdrachtspakket", t8p: "Runbook, architectuur, besluiten. Van jou.",
  cs_l: "Uitgelichte casestudy", cs_k: "Hotelsoftwareplatform · klant geanonimiseerd", cs_q: "Van Phalcon naar Laravel, module voor module, terwijl de hotels bleven boeken. Geen big-bang-omschakeling, geen herbouw vanaf nul.", cs_by: "Projectsamenvatting, geschreven door Scuttle", cs_sm: "Onder geheimhouding, referenties op aanvraag", cs_b1: "Lees de casestudy", cs_b2: "Vraag om referenties",
  k1s: "lagere latency", k2s: "runtime terug binnen support", k3s: "framework onderweg geüpgraded", k4s: "keer vanaf nul herschreven",
  in_l: "Sectoren", in_h: "Gebouwd voor bedrijven die op PHP draaien", in_p: "Systemen die elke dag geld verdienen en niet kunnen stoppen voor een herbouw.", i1: "Horeca en hotels", i1s: "Boeking- en operatieplatforms, meerdere vestigingen.", i2: "Verhuur en vastgoed", i2s: "Aanbod, contracten, betalingen, huurdersportalen.", i3: "E-commerce", i3s: "Legacy shops, ERP-koppelingen, betaalproviders.", i4: "Logistiek", i4s: "Planningstools, scanners, EDI-integraties.", i5: "Zorg-IT", i5s: "Patiëntportalen onder strenge dataregels.", i6s: "Multi-tenant producten die hun eerste versie zijn ontgroeid.",
  ct_h: "Klaar om je systeem terug binnen support te brengen?", ct_p: "Vertel me wat je draait. Ik reageer binnen één werkdag met een eerste mening, gratis.", ct_b1: "Mail info@scuttle.dev",
  cd_l: "Bedrijf", qr_l: "Bewaar mijn contactgegevens", qr_p: "Scan de code met je telefoon, of download de vCard en voeg me in één tik toe aan je contacten.", qr_b: "vCard opslaan",
  ab_l: "Over mij", ab_p: "Stanislav Plotnikov. Senior developer en solution architect, PHP sinds de jaren 2000, Laravel en Phalcon in productie, Python voor automatisering en AI. Eén persoon, één aanspreekpunt, van het eerste gesprek tot de overdracht."
});
</script>
@endpush
