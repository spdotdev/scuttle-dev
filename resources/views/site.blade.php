@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)
@php($groups = $C::grouped())

@section('title', 'Automation, AI and security for your business software, Eindhoven')
@section('description', 'Automate the work your team repeats: AI agents, integrations and secure software that keeps running. One senior developer in Eindhoven.')
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

@section('content')
<header class="wrap hero">
  <div class="mark in1">@include('scuttle::partials.logo', ['id' => 'hero', 'w' => 92, 'h' => 74, 'cls' => 'anim'])</div>
  <h1 data-i="h1" data-dec>Automate the work <br>your team repeats</h1>
  <div class="sub in2" data-i="sub">Automation · Security · Eindhoven</div>
  <p class="offer in2"><b data-i="of_b">Start with a Health Check.</b> <span data-i="of_t">Two days, read-only, a written plan. Fixed price: EUR 1,080 excl. VAT.</span></p>
  <div class="cta in3"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="cta1">Book a call about a Health Check <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><a class="btn o" href="{{ route('scuttle.health-check') }}" data-i="cta2">What it covers and what you get</a></div>
  <div class="cmd in3"><span>&gt; php -v&nbsp;&nbsp;<span>PHP 8.4.27</span>&nbsp;&nbsp;<b data-i="cmd_b"><span>security release 21 Oct 2026</span><span>8.4.26 → 8.4.27</span><span>two critical RCE bugs fixed</span><span>is yours patched?</span></b></span></div>
</header>

<section class="s" id="services"><div class="wrap">
  <div class="center rv"><h2 data-i="sv_h">The foundation for a business that runs on automation</h2><p data-i="sv_p">Agents and integrations that do the repetitive work, security that keeps it safe, one developer who keeps it running. A fixed scope per step, nothing deployed without a rollback.</p></div>
</div></section>

<section class="s tight" id="cases"><div class="wrap">
  <div class="car-h"><a class="lbl dot" href="{{ route('scuttle.cases') }}" data-i="cs_l">Case studies, all four →</a><div class="car-nav"><button type="button" id="car-prev" aria-label="Previous case">←</button><span id="car-dots" class="dots"></span><button type="button" id="car-next" aria-label="Next case">→</button></div></div>
  <div class="car rv" id="car" style="margin-top:18px">
    <div class="car-track" id="car-track">
      @foreach(\Spdotdev\ScuttleDev\Cases::all() as $cx)
      <div class="case">
        <div class="pic"><div class="lk">@include('scuttle::partials.logo', ['id' => 'case-'.$loop->index, 'w' => 60, 'h' => 48])<span class="t" data-i="cxl_{{ $cx['slug'] }}">{{ $loop->index === 0 ? 'Phalcon → Laravel' : ($loop->index === 1 ? 'API → MCP → AI' : ($loop->index === 2 ? 'audio → small model' : 'agents → pipeline')) }}</span></div></div>
        <div class="txt"><div class="who" data-i="cxk_{{ $cx['slug'] }}">{{ $cx['kicker'] }}</div>
          <q data-i="cxq_{{ $cx['slug'] }}">{{ $cx['quote'] }}</q>
          <div class="by"><span data-i="cxf0_{{ $cx['slug'] }}">{{ $cx['facts'][0][0] }} · {{ $cx['facts'][0][1] }}</span><small data-i="cxf1_{{ $cx['slug'] }}">{{ $cx['facts'][1][0] }} · {{ $cx['facts'][1][1] }}</small></div>
          <div class="acts"><a class="btn" href="{{ \Spdotdev\ScuttleDev\Cases::url($cx['slug']) }}" data-i="cs_b1">Read the case study</a></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div></section>

<section class="s tight" id="work"><div class="wrap">
  <div class="gridh rv"><h2 data-i="wk_h">How I work</h2><p data-i="wk_p">Every engagement runs through the same five steps, and a running system goes round again. Each service leaves a document in your repository, not only a result on a server. <a href="{{ route('scuttle.work') }}">See what you hold afterwards →</a></p></div>
  @include('scuttle::partials.loop')
</div></section>

<section class="s tight" id="trust"><div class="wrap">
  <div class="dark-panel trust rv">
    <div class="dh"><span class="lbl dot" data-i="tr_l">Security and governance</span><h2 data-i="tr_h">Checks you can verify, not badges I bought</h2><p data-i="tr_p">No certificate is claimed here that I do not hold. Every tile links to the document or the page behind it.</p><a class="lnk-arrow" href="{{ $C::url($C::get('compliance')) }}" data-i="tr_a">About Compliance Alignment →</a></div>
    <div class="tiles4">
      <a class="tt" href="{{ route('scuttle.privacy') }}"><i class="ti"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg></i><div><b>GDPR</b><span class="pill" data-i="tr_1">Privacy statement, no tracking <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg></span></div></a>
      <a class="tt" href="{{ $C::url($C::get('hardening')) }}"><i class="ti"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="20.50" cy="12.00" r="1.1" fill="currentColor"/><circle cx="19.36" cy="16.25" r="1.1" fill="currentColor"/><circle cx="16.25" cy="19.36" r="1.1" fill="currentColor"/><circle cx="12.00" cy="20.50" r="1.1" fill="currentColor"/><circle cx="7.75" cy="19.36" r="1.1" fill="currentColor"/><circle cx="4.64" cy="16.25" r="1.1" fill="currentColor"/><circle cx="3.50" cy="12.00" r="1.1" fill="currentColor"/><circle cx="4.64" cy="7.75" r="1.1" fill="currentColor"/><circle cx="7.75" cy="4.64" r="1.1" fill="currentColor"/><circle cx="12.00" cy="3.50" r="1.1" fill="currentColor"/><circle cx="16.25" cy="4.64" r="1.1" fill="currentColor"/><circle cx="19.36" cy="7.75" r="1.1" fill="currentColor"/><rect x="8.5" y="9" width="7" height="2.6" rx=".8"/><rect x="8.5" y="12.6" width="7" height="2.6" rx=".8"/></svg></i><div><b data-i="tr_2h">EU hosting</b><span class="pill" data-i="tr_2">Servers in Amsterdam <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg></span></div></a>
      <a class="tt" href="{{ route('scuttle.legal', ['name' => 'NDA']) }}"><i class="ti"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></i><div><b>NDA</b><span class="pill" data-i="tr_3">Signed before anything is shared <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg></span></div></a>
      <a class="tt" href="{{ $C::url($C::get('compliance')) }}"><i class="ti"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4.5h6M9 11h6M9 15h4"/></svg></i><div><b>NIS2 · DORA</b><span class="pill" data-i="tr_4">Gap analysis with evidence <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg></span></div></a>
    </div>
  </div>
</div></section>

<div class="wrap demo-wrap">
  <div class="shot-hint in4" aria-hidden="true"><svg viewBox="0 0 120 70" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M112 6c-30 2-62 10-80 36"/><path d="M40 30l-9 13 15 3"/></svg><span data-i="hint">A live window, not a picture. Click through, or let it play.</span></div>
  <div class="shot in4" id="demo" aria-label="Interactive examples: automation runs, reports and anonymized cases">
    <div class="side" id="side">
      <div class="h"><span data-i="sd_h">Automations</span><span>6</span></div>
      <button class="it on" data-k="server-setup" type="button"><i class="led"></i><span>server-setup</span><small data-i="now">now</small></button>
      <button class="it" data-k="release-pipeline" type="button"><i class="led"></i><span>release-pipeline</span><small>14:02</small></button>
      <button class="it" data-k="claude-skill" type="button"><i class="led"></i><span>release-notes-skill</span><small>14:21</small></button>
      <button class="it" data-k="mcp-gateway" type="button"><i class="led"></i><span>mcp-gateway</span><small>live</small></button>
      <button class="it" data-k="lead-followup" type="button"><i class="led"></i><span>lead-followup</span><small>08:30</small></button>
      <button class="it" data-k="ticket-triage" type="button"><i class="led"></i><span>ticket-triage</span><small>07:00</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_r">Reports</span></div>
      <button class="it" data-k="health-check" type="button"><i class="led"></i><span data-i="sd_r1">Health Check</span><small>pdf</small></button>
      <button class="it" data-k="roadmap" type="button"><i class="led"></i><span data-i="sd_r2">Roadmap</span><small>pdf</small></button>
      <button class="it" data-k="security" type="button"><i class="led"></i><span data-i="sd_r3">Security assessment</span><small>pdf</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_c">Cases, anonymized</span></div>
      <button class="it" data-k="case-cost" type="button"><i class="led"></i><span data-i="sd_c1">Cost saving</span><small>€</small></button>
      <button class="it" data-k="case-mcp" type="button"><i class="led"></i><span data-i="sd_c2">API meets AI</span><small>mcp</small></button>
      <button class="it" data-k="case-migration" type="button"><i class="led"></i><span data-i="sd_c3">Migration in use</span><small>live</small></button>
    </div>
    <div class="main" id="main">
      <div class="top"><p class="sh" id="sh-h">Automation run · server-setup</p><div class="st"><i id="sti"></i><span id="stt">running</span></div></div>
      <div class="prog"><i id="pb"></i></div>
      <div class="view" id="view"><div class="log" id="log"></div><div class="find" id="find"></div></div>
    </div>
  </div>
  <p class="shot-note" data-i="shot_note">Runs and reports are examples with made-up names. The cases are real work, anonymized. Every result line links to the product behind it.</p>
</div>

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

<section class="cta-end" id="contact"><div class="wrap">
  <div class="cta-box rv">
    @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
    <h2 data-i="ct_h">Which task would you hand to an agent first?</h2>
    <p data-i="ct_p">Tell me what you are running and where the hours go. I reply within one working day with a first opinion, free.</p>
    <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}" data-i="ct_b2">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}" data-i="ct_b0">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev" data-i="ct_b1">Email info@scuttle.dev</a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
  </div>
</div></section>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json($C::nlLoopStrings() + \Spdotdev\ScuttleDev\Cases::nlStrings()));
Object.assign(window.SCUTTLE_NL.t, {
  h1: "Automatiseer het werk <br>dat je team herhaalt", sub: "Automatisering · Beveiliging · Eindhoven", of_b: "Begin met een Health Check.", of_t: "Twee dagen, alleen lezen, een schriftelijk plan. Vaste prijs: EUR 1.080 excl. btw.", cta1: "Plan een gesprek over een Health Check", cta2: "Wat het dekt en wat je krijgt", cmd_b: "<span>beveiligingsrelease 21 okt 2026</span><span>8.4.26 → 8.4.27</span><span>twee kritieke RCE-lekken verholpen</span><span>is die van jou gepatcht?</span>",
  sd_h: "Automatiseringen", now: "nu", sd_r: "Rapporten", sd_r1: "Health Check", sd_r2: "Roadmap", sd_r3: "Security assessment", sd_c: "Cases, geanonimiseerd", sd_c1: "Kostenbesparing", sd_c2: "API ontmoet AI", sd_c3: "Migratie in gebruik",
  shot_note: "Runs en rapporten zijn voorbeelden met verzonnen namen. De cases zijn echt werk, geanonimiseerd. Elke resultaatregel linkt naar het product erachter.", hint: "Een live venster, geen plaatje. Klik erdoorheen, of laat het lopen.",
  sv_h: "De basis voor een bedrijf dat op automatisering draait", sv_p: "Agents en koppelingen die het repeterende werk doen, beveiliging die het veilig houdt, één developer die het draaiend houdt. Vaste scope per stap, niets live zonder rollback.",
  gl_build: "Automatiseer het repeterende werk, bouw wat ontbreekt.", gl_assess: "Weet waar je staat voordat er iets wordt aangeraakt.", gl_run: "Houd het veilig, geback-upt en gedocumenteerd.",
  g_build: "Automatiseren",
  st_l: "Gewerkt met", st_h: "Merken waarvoor ik heb gebouwd, tools waarmee ik bouw", st_p: "In loondienst bij bureaus en productbedrijven vóór 2024, en sindsdien als freelancer. De tools zijn die van dagelijks gebruik, geen wensenlijst.", st_s: "+ het framework of de hosting die je al hebt",
  wk_h: "Hoe ik werk", wk_p: "Elke opdracht loopt door dezelfde vijf stappen, en een draaiend systeem gaat opnieuw de ronde. Elk onderdeel laat een document achter in je repository, niet alleen een resultaat op een server. <a href=\"/services\">Zie wat je daarna in handen hebt →</a>",
  cs_l: "Casestudy's, alle vier →", cs_b1: "Lees de casestudy",
  in_l: "Sectoren", in_h: "Gebouwd voor bedrijven die draaien op software die niet uit kan", in_p: "Backoffices, shops, planningstools, portalen, boekingssystemen. Waar het ook op draait, het verdient elke dag geld, en de uren eromheen zijn te automatiseren.", i1: "Horeca", i1s: "Boeking- en operatieplatforms, meerdere vestigingen.", i2: "Verhuur en vastgoed", i2s: "Aanbod, contracten, betalingen, huurdersportalen.", i3: "E-commerce", i3s: "Shops, ERP-koppelingen, betaalproviders.", i4: "Logistiek en maakindustrie", i4s: "Planningstools, scanners, EDI-integraties.", i5: "Zorg en overheid", i5s: "Portalen onder strenge dataregels.", i6s: "Multi-tenant producten die hun eerste versie zijn ontgroeid.", i7: "Bureaus", i7s: "Senior werk als onderaannemer op het project van jouw klant.", i8: "Eigenaar-gerunde bedrijven", i8s: "Eén systeem, één developer die vertrok, en een bedrijf dat ervan afhangt.",
  tr_l: "Beveiliging en governance", tr_h: "Controles die je kunt nakijken, geen gekochte badges", tr_p: "Hier wordt geen certificaat geclaimd dat ik niet heb. Elke tegel linkt naar het document of de pagina erachter.", tr_a: "Over Compliance Alignment →", tr_1: "Privacyverklaring, geen tracking <svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m5 12 5 5L20 7\"/></svg>", tr_2h: "Hosting in de EU", tr_2: "Servers in Amsterdam <svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m5 12 5 5L20 7\"/></svg>", tr_3: "Getekend voordat er iets gedeeld wordt <svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m5 12 5 5L20 7\"/></svg>", tr_4: "Gap-analyse met bewijs <svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m5 12 5 5L20 7\"/></svg>",
  ct_h: "Welke taak zou je als eerste aan een agent geven?", ct_p: "Vertel me wat je draait en waar de uren heen gaan. Ik reageer binnen één werkdag met een eerste mening, gratis.", ct_b0: "Plan een gesprek", ct_b1: "Mail info@scuttle.dev", ct_b2: "Gebruik het contactformulier",
});
</script>
@endpush
