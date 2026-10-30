@extends('scuttle::layout')

@section('title', 'Company: one senior developer in Eindhoven')
@section('crumb', 'Company')
@section('description', 'One senior developer and solution architect in Eindhoven, founded 2024. How I work, what I believe about software, the facts, and how to work with me.')
@section('canonical', 'https://scuttle.dev/company')

@section('content')
<article class="prod company">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="cp_l">Company</span>
      <h1 data-dec data-i="cp_h">One person, the whole stack</h1>
      <p class="lead" data-i="cp_lead">Scuttle Development is Stanislav Plotnikov, senior developer and solution architect in Eindhoven, founded in 2024. I automate the repetitive work in and around business software, keep that software secure and in support, and build what is missing. You talk to the person who does the work.</p>
    </div>
  </header>

  <div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot" data-i="cp_w_l">How I work</span>
      <ol class="steps">
        <li><span>01</span><p data-i="cp_s1">Assess before touching. A written plan with the order of steps, so nothing stops while it is done.</p></li>
        <li><span>02</span><p data-i="cp_s2">Small releases, each with a rollback. Tests around the old behaviour before it changes.</p></li>
        <li><span>03</span><p data-i="cp_s3">Automate what repeats, by hand what is judgment. A person taps before anything leaves the building.</p></li>
        <li><span>04</span><p data-i="cp_s4">Everything written down in your repository: runbook, decisions, architecture. You own the accounts from day one.</p></li>
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot" data-i="cp_b_l">What I believe about software</span>
      <ul class="terms beliefs">
        <li data-i="cp_be1"><b>Return on investment over trends.</b> A stack is chosen on the people who can run it and its total cost, not on what is popular this year.</li>
        <li data-i="cp_be2"><b>A modular monolith over microservices</b> for a team of one to ten. Services when they earn their cost, not before.</li>
        <li data-i="cp_be3"><b>Old and new side by side.</b> The Strangler Fig pattern: move one part at a time, keep the business running.</li>
        <li data-i="cp_be4"><b>Security first, as a habit.</b> Ports closed, secrets out of the code, backups that have been restored, a pipeline that blocks red.</li>
        <li data-i="cp_be5"><b>AI is a tool with guardrails.</b> Agents with evaluation sets, cost caps and a human step, never a model with a database connection.</li>
        <li data-i="cp_be6"><b>Open prices and straight answers.</b> Every service and how it is priced is on this site. A hard question gets "I need to look before I answer", not a dodge.</li>
      </ul>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot" data-i="cp_f_l">The facts</span><h2>Scuttle Development</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa1b">Founded</b><p data-i="cp_fa1p">December 2024, Eindhoven, the Netherlands. Sole proprietorship (eenmanszaak).</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa2b">Registered</b><p data-i="cp_fa2p"><a href="https://www.kvk.nl/zoeken/#/96040947000061394009/" rel="noopener" target="_blank">KvK 96040947</a> · BTW NL005184584B85</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa3b">Address</b><p data-i="cp_fa3p">Dirigentplein 144, 5642 RK Eindhoven. On site in the Eindhoven region, remote everywhere else.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa4b">Languages</b><p data-i="cp_fa4p">Dutch, English, Russian. Documents in Dutch or English, your choice.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa5b">Terms</b><p data-i="cp_fa5p"><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}">General terms</a>, <a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">mutual NDA</a>, <a href="{{ route('scuttle.privacy') }}">privacy statement</a>. 14 days payment, 7 days acceptance, rights pass on full payment.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="cp_fa6b">Background</b><p data-i="cp_fa6p">Programming since the 2000s, PHP first, then Python, Kotlin and AI tooling. Agencies and product companies in employment, freelance since 2024. More on <a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a>.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv" id="proof">
      <span class="lbl dot" data-i="cp_pr_l">What you can check yourself</span>
      <ul class="terms">
        <li data-i="cp_pr1"><b>Public code.</b> Sixteen repositories are public on <a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a>, including <a href="https://github.com/spdotdev/scuttle-dev" rel="noopener" target="_blank">this website</a>, with its tests and its commit history.</li>
        <li data-i="cp_pr2"><b>Measured on my own sites.</b> On 30 October 2026 I ran Lighthouse (mobile, lab) on all 73 pages of scuttle.dev and splotnikov.dev: 100 for accessibility, best practices and SEO on every page, 96 to 100 for performance. An automated accessibility test (axe) found nothing in light or dark theme.</li>
        <li data-i="cp_pr3"><b>Tools you can run.</b> The <a href="{{ route('scuttle.php-check') }}">PHP version check</a> and the <a href="{{ route('scuttle.health-check.sample') }}">sample Health Check report</a> show how I work before you write.</li>
        <li data-i="cp_pr4"><b>Client work stays private.</b> Client names are covered by NDA, so the <a href="{{ route('scuttle.cases') }}">case studies</a> are told without names.</li>
      </ul>
    </div>
    <div class="plate rv">
      <span class="lbl dot" data-i="cp_ex_l">What happens when you write</span>
      <ol class="steps">
        <li><span>01</span><p data-i="cp_ex1">You write or book a call. I am one person, so I answer myself.</p></li>
        <li><span>02</span><p data-i="cp_ex2">The first call is free, thirty minutes, and ends with a straight answer: yes, no, or what I need to look at first.</p></li>
        <li><span>03</span><p data-i="cp_ex3">A mutual NDA is available before details are shared. Prices are on this site, and a written quote is valid for 14 days.</p></li>
        <li><span>04</span><p data-i="cp_ex4">The work is handed over with a runbook in your repository, so it does not depend on me being around.</p></li>
      </ol>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv" id="careers">
      <span class="lbl dot" data-i="cp_ww_l">Working with me</span>
      <p class="not" data-i="cp_ww_p">There are no vacancies; this is a one-person company by design. Three ways we can still work together:</p>
      <ul class="terms">
        <li data-i="cp_ww1"><b>Agencies:</b> senior hands on your client's project at a day rate, under your terms or mine, NDA first. See <a href="{{ route('scuttle.pricing') }}">pricing</a>.</li>
        <li data-i="cp_ww2"><b>Peers:</b> a second pair of eyes on an architecture or security question, or an overflow partner when you are full.</li>
        <li data-i="cp_ww3"><b>Open source:</b> the skills and tools on the <a href="{{ route('scuttle.projects') }}">projects page</a> take issues and pull requests.</li>
      </ul>
    </div>
    <div class="plate rv">
      <span class="lbl dot" data-i="cp_n_l">Where to go next</span>
      <ul class="terms">
        <li data-i="cp_n1"><a href="{{ route('scuttle.home') }}#services">Services</a>: the twelve things I sell, in three groups.</li>
        <li data-i="cp_n2"><a href="{{ route('scuttle.pricing') }}">Pricing</a>: one rate, two ways to buy it, every price on the page.</li>
        <li data-i="cp_n3"><a href="{{ route('scuttle.cases') }}">Case studies</a>: work, told the way it went.</li>
        <li data-i="cp_n4"><a href="{{ route('scuttle.projects') }}">Projects</a>: open source and own products, with documentation.</li>
        <li data-i="cp_n5"><a href="{{ route('scuttle.news') }}">News</a>: what changed in the field and what it means for your software.</li>
      </ul>
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="cp_e_h">Want to know if we fit?</h2>
      <p data-i="cp_e_p">A thirty-minute call is free and ends with a straight answer: yes, no, or what I need to look at first.</p>
      <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}"><span data-i="cp_e_b1">Book a call</span></a><a class="btn o" href="{{ route('scuttle.contact.page') }}"><span data-i="cp_e_b2">Use the contact form</span></a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
 "cp_l": "Bedrijf",
 "cp_h": "Eén persoon, de hele stack",
 "cp_lead": "Scuttle Development is Stanislav Plotnikov, senior developer en solution architect in Eindhoven, opgericht in 2024. Ik automatiseer het repeterende werk in en rond bedrijfssoftware, houd die software veilig en in support, en bouw wat ontbreekt. Je praat met degene die het werk doet.",
 "cp_w_l": "Hoe ik werk",
 "cp_s1": "Eerst beoordelen, dan aanraken. Een schriftelijk plan met de volgorde van stappen, zodat niets stilvalt terwijl het gebeurt.",
 "cp_s2": "Kleine releases, elk met een rollback. Tests rond het oude gedrag voordat het verandert.",
 "cp_s3": "Automatiseer wat zich herhaalt, doe met de hand wat oordeel vraagt. Een mens geeft akkoord voordat er iets naar buiten gaat.",
 "cp_s4": "Alles opgeschreven in je repository: runbook, besluiten, architectuur. De accounts zijn vanaf dag één van jou.",
 "cp_b_l": "Wat ik vind van software",
 "cp_be1": "<b>Rendement boven trends.</b> Een stack kies je op de mensen die hem kunnen draaien en op de totale kosten, niet op wat dit jaar populair is.",
 "cp_be2": "<b>Een modulaire monoliet boven microservices</b> voor een team van één tot tien. Services als ze hun kosten waard zijn, niet eerder.",
 "cp_be3": "<b>Oud en nieuw naast elkaar.</b> Het Strangler Fig-patroon: verplaats één onderdeel tegelijk en houd het bedrijf draaiend.",
 "cp_be4": "<b>Beveiliging eerst, als gewoonte.</b> Poorten dicht, secrets uit de code, back-ups die echt zijn hersteld, een pipeline die op rood blokkeert.",
 "cp_be5": "<b>AI is een gereedschap met vangrails.</b> Agents met evaluatiesets, kostenlimieten en een menselijke stap, nooit een model met een databaseverbinding.",
 "cp_be6": "<b>Open prijzen en eerlijke antwoorden.</b> Elke dienst en wat die kost staat op deze site. Een lastige vraag krijgt \"ik moet eerst kijken voordat ik antwoord\", geen ontwijking.",
 "cp_f_l": "De feiten",
 "cp_fa1b": "Opgericht",
 "cp_fa1p": "December 2024, Eindhoven, Nederland. Eenmanszaak.",
 "cp_fa2b": "Geregistreerd",
 "cp_fa2p": "<a href=\"https://www.kvk.nl/zoeken/#/96040947000061394009/\" rel=\"noopener\" target=\"_blank\">KvK 96040947</a> · BTW NL005184584B85",
 "cp_fa3b": "Adres",
 "cp_fa3p": "Dirigentplein 144, 5642 RK Eindhoven. Op locatie in de regio Eindhoven, overal elders op afstand.",
 "cp_fa4b": "Talen",
 "cp_fa4p": "Nederlands, Engels, Russisch. Documenten in het Nederlands of Engels, jouw keuze.",
 "cp_fa5b": "Voorwaarden",
 "cp_fa5p": "<a href=\"/legal/ALGEMENE_VOORWAARDEN.pdf\">Algemene voorwaarden</a>, <a href=\"/legal/GEHEIMHOUDINGSOVEREENKOMST.pdf\">wederzijdse NDA</a>, <a href=\"/privacy\">privacyverklaring</a>. 14 dagen betaaltermijn, 7 dagen acceptatie, rechten gaan over na volledige betaling.",
 "cp_fa6b": "Achtergrond",
 "cp_fa6p": "Programmeren sinds de jaren 2000, eerst PHP, daarna Python, Kotlin en AI-tooling. In loondienst bij bureaus en productbedrijven, freelance sinds 2024. Meer op <a href=\"https://splotnikov.dev\" rel=\"noopener\" target=\"_blank\">splotnikov.dev</a>.",
 "cp_pr_l": "Wat je zelf kunt controleren",
 "cp_pr1": "<b>Openbare code.</b> Zestien repositories zijn openbaar op <a href=\"https://github.com/spdotdev\" rel=\"noopener\" target=\"_blank\">GitHub</a>, waaronder <a href=\"https://github.com/spdotdev/scuttle-dev\" rel=\"noopener\" target=\"_blank\">deze website</a>, met tests en commitgeschiedenis.",
 "cp_pr2": "<b>Gemeten op mijn eigen sites.</b> Op 30 oktober 2026 draaide ik Lighthouse (mobiel, lab) op alle 73 pagina's van scuttle.dev en splotnikov.dev: 100 voor toegankelijkheid, best practices en SEO op elke pagina, 96 tot 100 voor prestaties. Een geautomatiseerde toegankelijkheidstest (axe) vond niets in het lichte of donkere thema.",
 "cp_pr3": "<b>Tools die je zelf kunt draaien.</b> De <a href=\"/php-check\">PHP-versiecheck</a> en het <a href=\"/health-check/sample\">voorbeeldrapport van de Health Check</a> laten zien hoe ik werk voordat je schrijft.",
 "cp_pr4": "<b>Klantwerk blijft privé.</b> Klantnamen vallen onder geheimhouding, daarom zijn de <a href=\"/case-studies\">casestudy's</a> zonder namen verteld.",
 "cp_ex_l": "Wat er gebeurt als je schrijft",
 "cp_ex1": "Je schrijft of plant een gesprek. Ik ben één persoon, dus ik antwoord zelf.",
 "cp_ex2": "Het eerste gesprek is gratis, dertig minuten, en eindigt met een eerlijk antwoord: ja, nee, of wat ik eerst moet bekijken.",
 "cp_ex3": "Een wederzijdse NDA is beschikbaar voordat details worden gedeeld. De prijzen staan op deze site en een schriftelijke offerte is 14 dagen geldig.",
 "cp_ex4": "Het werk wordt overgedragen met een runbook in je repository, zodat het niet afhangt van mijn aanwezigheid.",
 "cp_ww_l": "Samenwerken",
 "cp_ww_p": "Er zijn geen vacatures; dit is bewust een eenmansbedrijf. Op drie manieren kunnen we toch samenwerken:",
 "cp_ww1": "<b>Bureaus:</b> ervaren handen op het project van jouw klant tegen een dagtarief, onder jouw voorwaarden of de mijne, eerst NDA. Zie <a href=\"/pricing\">prijzen</a>.",
 "cp_ww2": "<b>Vakgenoten:</b> een tweede paar ogen op een architectuur- of securityvraag, of een overloop-partner als je vol zit.",
 "cp_ww3": "<b>Open source:</b> de skills en tools op de <a href=\"/projects\">projectenpagina</a> nemen issues en pull requests aan.",
 "cp_n_l": "Waar nu heen",
 "cp_e_h": "Wil je weten of we bij elkaar passen?",
 "cp_e_p": "Een gesprek van dertig minuten is gratis en eindigt met een eerlijk antwoord: ja, nee, of wat ik eerst moet bekijken.",
 "cp_e_b1": "Plan een gesprek",
 "cp_e_b2": "Gebruik het contactformulier",
 "cp_n1": "<a href=\"/#services\">Diensten</a>: de twaalf dingen die ik verkoop, in drie groepen.",
 "cp_n2": "<a href=\"/pricing\">Prijzen</a>: één tarief, twee manieren om het te kopen, elke prijs op de pagina.",
 "cp_n3": "<a href=\"/case-studies\">Casestudy's</a>: werk, verteld zoals het ging.",
 "cp_n4": "<a href=\"/projects\">Projecten</a>: open source en eigen producten, met documentatie.",
 "cp_n5": "<a href=\"/news\">Nieuws</a>: wat er veranderd is in het vak en wat dat voor je software betekent."
});
</script>
@endpush
