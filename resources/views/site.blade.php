@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)
@php($groups = $C::grouped())

@section('title', 'Automation, AI and security for your business software, Eindhoven')
@section('description', 'Automate the work your team repeats: AI agents, integrations and secure software that keeps running. Health Check, automation, security, modernization in steps. One senior developer in Eindhoven.')
@section('canonical', 'https://scuttle.dev/')
@section('ogtype', 'website')

@section('head')
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "Scuttle Development",
    "description": "Automation with AI agents and integrations, security and full-stack development by one senior developer in Eindhoven.",
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
    "serviceType": [@foreach($C::all() as $p)"{{ $p['name'] }}"@if(!$loop->last), @endif @endforeach]
  }
  </script>
@endsection

@section('homelinks')
  <a href="#work" data-i="n_work">Work</a><a href="#case" data-i="n_case">Case study</a><a href="#sectors" data-i="n_sectors">Sectors</a><a href="#contact" data-i="n_contact">Contact</a>
@endsection

@section('content')
<header class="wrap hero">
  <img class="mark in1" alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="92" height="74">
  <h1 data-i="h1" data-dec>Automate the work<br>your team repeats</h1>
  <div class="sub in2" data-i="sub">Automation · Security · Eindhoven</div>
  <div class="cta in3"><a class="btn" href="{{ route('scuttle.product', ['slug' => 'ai-integration']) }}" data-i="cta1">Start with one workflow <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><a class="btn o" href="{{ route('scuttle.pricing') }}" data-i="cta2">See the pricing</a></div>
  <div class="cmd in3"><span>&gt; php -v&nbsp;&nbsp;<span>PHP 8.4.27</span>&nbsp;&nbsp;<b data-i="cmd_b">security release 21 Oct 2026 · 8.4.26 → 8.4.27 · two critical RCE bugs fixed · is yours patched?</b></span></div>
</header>

<div class="wrap">
  <div class="shot in4" id="demo" aria-label="Interactive examples: automation runs, reports and anonymized cases">
    <aside class="side" id="side">
      <div class="h"><span data-i="sd_h">Automations</span><span>5</span></div>
      <button class="it on" data-k="invoice-intake" type="button"><span>invoice-intake</span><small data-i="now">now</small></button>
      <button class="it" data-k="lead-followup" type="button"><span>lead-followup</span><small>2h</small></button>
      <button class="it" data-k="ticket-triage" type="button"><span>ticket-triage</span><small>9h</small></button>
      <button class="it" data-k="nightly-report" type="button"><span>nightly-report</span><small>1d</small></button>
      <button class="it" data-k="mcp-gateway" type="button"><span>mcp-gateway</span><small>live</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_r">Reports</span></div>
      <button class="it" data-k="health-check" type="button"><span data-i="sd_r1">Health Check</span><small>pdf</small></button>
      <button class="it" data-k="roadmap" type="button"><span data-i="sd_r2">Roadmap</span><small>pdf</small></button>
      <button class="it" data-k="security" type="button"><span data-i="sd_r3">Security assessment</span><small>pdf</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_c">Cases, anonymized</span></div>
      <button class="it" data-k="case-cost" type="button"><span data-i="sd_c1">Cost saving</span><small>€</small></button>
      <button class="it" data-k="case-mcp" type="button"><span data-i="sd_c2">API meets AI</span><small>mcp</small></button>
      <button class="it" data-k="case-migration" type="button"><span data-i="sd_c3">Migration in use</span><small>0 stops</small></button>
    </aside>
    <div class="main" id="main">
      <div class="top"><h4 id="sh-h">Automation run · invoice-intake</h4><div class="st"><i id="sti"></i><span id="stt">running</span></div></div>
      <div class="prog"><i id="pb"></i></div>
      <div class="view" id="view"><div class="log" id="log"></div><div class="find" id="find"></div></div>
    </div>
  </div>
  <p class="shot-note" data-i="shot_note">Click an item on the left. Runs and reports are examples with made-up names; the cases are real work, anonymized, without client names.</p>
</div>

<section class="s" id="services"><div class="wrap">
  <div class="center rv"><h2 data-i="sv_h">The foundation for a business that runs on automation</h2><p data-i="sv_p">Agents and integrations that do the repetitive work, security that keeps it safe, one developer who keeps it running. A fixed scope per step, nothing deployed without a rollback.</p></div>
  <div class="cards3">
    @foreach($groups as $key => $items)
    <div class="c g rv">
      <span class="lbl">{{ sprintf('%02d', $loop->iteration) }} <span data-i="g_{{ $key }}">{{ $C::GROUPS[$key] }}</span></span>
      <h3 data-i="gl_{{ $key }}">{{ $C::GROUP_LINES[$key] }}</h3>
      <div class="pl">
        @foreach($items as $p)
        <a href="{{ $C::url($p) }}"><i class="lt"></i><span>{{ $p['name'] }}</span><small>{{ $p['short'] }}</small></a>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
</div></section>

<section class="s tight" id="stack"><div class="wrap">
  <div class="plate belts rv">
    <div class="bh"><span class="lbl dot" data-i="st_l">Worked with</span><h2 data-i="st_h">Brands I have built for, tools I build with</h2><p data-i="st_p">In employment at agencies and product companies before 2024, and as a freelancer since. The tools are the ones in daily use, not a wish list.</p></div>
    <div class="belt" aria-label="Companies"><div class="track">
      @php($cos = ['Jumbo', 'Albert Heijn', 'Mijnwebwinkel', '@Leisure Group', 'Getnoticed', 'New Brand Activators', 'Bek 1 op 1', 'Van Os Beheer'])
      @for($r = 0; $r < 2; $r++)@foreach($cos as $co)<span class="tk"><i></i>{{ $co }}</span>@endforeach @endfor
    </div></div>
    <div class="belt rev" aria-label="Tools"><div class="track">
      @php($tools = ['PHP', 'Laravel', 'Phalcon', 'Filament', 'MySQL', 'Redis', 'Docker', 'Ansible', 'GitHub Actions', 'DigitalOcean', 'Python', 'Kotlin', 'Claude Code', 'MCP', 'Playwright', 'Linux'])
      @for($r = 0; $r < 2; $r++)@foreach($tools as $t)<span class="tk mono{{ in_array($t, ['Claude Code', 'MCP'], true) ? ' ai' : '' }}"><i></i>{{ $t }}</span>@endforeach @endfor
    </div></div>
    <small class="bn" data-i="st_s">+ the framework or hosting you already have</small>
  </div>
</div></section>

<section class="s tight" id="work"><div class="wrap">
  <div class="gridh rv"><h2 data-i="wk_h">What I build</h2><p data-i="wk_p">Twelve products with a fixed shape, automation first. Each one leaves a document in your repository, not only a result on a server.</p></div>
  <div class="tiles">
    @foreach($C::all() as $p)
    <a class="tile rv" href="{{ $C::url($p) }}">
      <div class="img"><span class="k">{{ $C::GROUPS[$p['group']] }}</span>@include('scuttle::partials.illus', ['kind' => $p['illus']])</div>
      <h3>{{ $p['name'] }}</h3><p>{{ $p['short'] }}</p>
    </a>
    @endforeach
  </div>
</div></section>

<section class="s tight" id="case"><div class="wrap">
  <span class="lbl dot" data-i="cs_l">Featured case study</span>
  <div class="case rv" style="margin-top:18px">
    <div class="pic"><div class="lk"><img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="60" height="48">Phalcon<span>→</span>Laravel</div></div>
    <div class="txt"><div class="who" data-i="cs_k">Multi-tenant operations platform · client anonymized</div>
      <q data-i="cs_q">Phalcon to Laravel, module by module, while the platform stayed in daily use. No big-bang switch, no rewrite from scratch.</q>
      <div class="by"><span data-i="cs_by">Project summary, written by Scuttle</span><small data-i="cs_sm">Covered by confidentiality</small></div>
      <div class="acts"><a class="btn" href="{{ route('scuttle.case-study') }}" data-i="cs_b1">Read the case study</a></div>
    </div>
  </div>
  <div class="kv"><div><b>60%</b><span data-i="k1s">lower latency</span></div><div><b>PHP 7.4 → 8.4</b><span data-i="k2s">runtime back in support</span></div><div><b>Phalcon 3.4 → 5.9</b><span data-i="k3s">framework upgraded on the way</span></div><div><b>0</b><span data-i="k4s">rewrites from scratch</span></div></div>
</div></section>

<section class="s tight" id="sectors"><div class="wrap">
  <div class="plate ind rv">
    <div><span class="lbl dot" data-i="in_l">Sectors</span><h2 data-i="in_h">Built for businesses that run on software they cannot switch off</h2><p data-i="in_p">Back offices, shops, planning tools, portals, booking engines. Whatever it runs on, it earns money every day, and the hours around it can be automated.</p></div>
    <div class="list">
      <div><b data-i="i1">Hospitality</b><span data-i="i1s">Booking and operations platforms, multi-property.</span></div>
      <div><b data-i="i2">Rentals and real estate</b><span data-i="i2s">Listings, contracts, payments, tenant portals.</span></div>
      <div><b data-i="i3">E-commerce</b><span data-i="i3s">Shops, ERP bridges, payment providers.</span></div>
      <div><b data-i="i4">Logistics and manufacturing</b><span data-i="i4s">Planning tools, scanners, EDI integrations.</span></div>
      <div><b data-i="i5">Healthcare and public sector</b><span data-i="i5s">Portals under strict data rules.</span></div>
      <div><b>SaaS</b><span data-i="i6s">Multi-tenant products that outgrew their first version.</span></div>
      <div><b data-i="i7">Agencies</b><span data-i="i7s">Subcontracted senior work on your client's project.</span></div>
      <div><b data-i="i8">Owner-run companies</b><span data-i="i8s">One system, one developer who left, and a business that depends on it.</span></div>
    </div>
  </div>
</div></section>

<section class="cta-end" id="contact"><div class="wrap">
  <div class="cta-box rv">
    <img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="46" height="37">
    <h2 data-i="ct_h">Which task would you hand to an agent first?</h2>
    <p data-i="ct_p">Tell me what you are running and where the hours go. I reply within one working day with a first opinion, free.</p>
    <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="ct_b0">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev" data-i="ct_b1">Email info@scuttle.dev</a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
  </div>
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
      <div><p data-i="ab_p">Stanislav Plotnikov. Senior developer and solution architect, PHP since the 2000s, Laravel and Phalcon in production, Python for automation and AI, Kotlin for Android. One person, one point of contact, from the first call to the handover.</p>
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
  n_sectors: "Sectoren",
  h1: "Automatiseer het werk<br>dat je team herhaalt", sub: "Automatisering · Beveiliging · Eindhoven", cta1: "Begin met één workflow", cta2: "Bekijk de prijzen", cmd_b: "beveiligingsrelease 21 okt 2026 · 8.4.26 → 8.4.27 · twee kritieke RCE-lekken gedicht · is die van jou gepatcht?",
  sd_h: "Automatiseringen", now: "nu", sd_r: "Rapporten", sd_r1: "Health Check", sd_r2: "Roadmap", sd_r3: "Security assessment", sd_c: "Cases, geanonimiseerd", sd_c1: "Kostenbesparing", sd_c2: "API ontmoet AI", sd_c3: "Migratie in gebruik",
  shot_note: "Klik links op een onderdeel. Runs en rapporten zijn voorbeelden met verzonnen namen; de cases zijn echt werk, geanonimiseerd, zonder klantnamen.",
  sv_h: "De basis voor een bedrijf dat op automatisering draait", sv_p: "Agents en koppelingen die het repeterende werk doen, beveiliging die het veilig houdt, één developer die het draaiend houdt. Vaste scope per stap, niets live zonder rollback.",
  gl_build: "Automatiseer het repeterende werk, bouw wat ontbreekt.", gl_assess: "Weet waar je staat voordat er iets wordt aangeraakt.", gl_run: "Houd het veilig, geback-upt en gedocumenteerd.",
  g_build: "Automatiseren",
  st_l: "Gewerkt met", st_h: "Merken waarvoor ik heb gebouwd, tools waarmee ik bouw", st_p: "In loondienst bij bureaus en productbedrijven vóór 2024, en sindsdien als freelancer. De tools zijn die van dagelijks gebruik, geen wensenlijst.", st_s: "+ het framework of de hosting die je al hebt",
  wk_h: "Wat ik bouw", wk_p: "Twaalf producten met een vaste vorm, automatisering voorop. Elk onderdeel laat een document achter in je repository, niet alleen een resultaat op een server.",
  cs_l: "Uitgelichte casestudy", cs_k: "Multi-tenant operationeel platform · klant geanonimiseerd", cs_q: "Van Phalcon naar Laravel, module voor module, terwijl het platform dagelijks in gebruik bleef. Geen big-bang-omschakeling, geen herbouw vanaf nul.", cs_by: "Projectsamenvatting, geschreven door Scuttle", cs_sm: "Onder geheimhouding", cs_b1: "Lees de casestudy",
  k1s: "lagere latency", k2s: "runtime terug binnen support", k3s: "framework onderweg geüpgraded", k4s: "keer vanaf nul herschreven",
  in_l: "Sectoren", in_h: "Gebouwd voor bedrijven die draaien op software die niet uit kan", in_p: "Backoffices, shops, planningstools, portalen, boekingssystemen. Waar het ook op draait, het verdient elke dag geld, en de uren eromheen zijn te automatiseren.", i1: "Horeca", i1s: "Boeking- en operatieplatforms, meerdere vestigingen.", i2: "Verhuur en vastgoed", i2s: "Aanbod, contracten, betalingen, huurdersportalen.", i3: "E-commerce", i3s: "Shops, ERP-koppelingen, betaalproviders.", i4: "Logistiek en maakindustrie", i4s: "Planningstools, scanners, EDI-integraties.", i5: "Zorg en overheid", i5s: "Portalen onder strenge dataregels.", i6s: "Multi-tenant producten die hun eerste versie zijn ontgroeid.", i7: "Bureaus", i7s: "Senior werk als onderaannemer op het project van jouw klant.", i8: "Eigenaar-gerunde bedrijven", i8s: "Eén systeem, één developer die vertrok, en een bedrijf dat ervan afhangt.",
  ct_h: "Welke taak zou je als eerste aan een agent geven?", ct_p: "Vertel me wat je draait en waar de uren heen gaan. Ik reageer binnen één werkdag met een eerste mening, gratis.", ct_b0: "Plan een gesprek", ct_b1: "Mail info@scuttle.dev",
  cd_l: "Bedrijf", qr_l: "Bewaar mijn contactgegevens", qr_p: "Scan de code met je telefoon, of download de vCard en voeg me in één tik toe aan je contacten.", qr_b: "vCard opslaan",
  ab_l: "Over mij", ab_p: "Stanislav Plotnikov. Senior developer en solution architect, PHP sinds de jaren 2000, Laravel en Phalcon in productie, Python voor automatisering en AI, Kotlin voor Android. Eén persoon, één aanspreekpunt, van het eerste gesprek tot de overdracht."
});
</script>
@endpush
