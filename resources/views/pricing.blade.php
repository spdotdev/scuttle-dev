@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)
@php($groups = $C::grouped())

@section('title', 'Pricing')
@section('description', 'How Scuttle Development prices its work: EUR 90 per hour excl. VAT for hourly work and projects in steps, and fixed prices for the Health Check (EUR 1,080), a security quick scan, an architecture review and the maintenance retainer. No plans to pick, no surprises.')
@section('canonical', 'https://scuttle.dev/pricing')

@section('content')
<article class="prod pricing">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="pr_l">Pricing</span>
      <h1 data-dec data-i="pr_h">One rate, two ways to buy it</h1>
      <p class="lead" data-i="pr_lead">I get asked what things cost before anything else, so here it is. One hourly rate for work by the hour or in steps, and fixed prices for the things with a fixed shape. Every amount is excl. VAT.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="plate rv">
      <div class="pb-h"><span class="lbl dot" data-i="pr_p1l">By the hour, quoted per step</span><p data-i="pr_p1p">For work whose size only shows once we look. The rate is the same everywhere; what changes is how it is packaged. All amounts excl. VAT.</p></div>
      <div class="plans">
        <div class="plan hi">
          <span class="badge" data-i="pr_bd1">Recommended</span>
          <span class="lbl" data-i="pr_c1l">01 · Per step</span>
          <h3 data-i="pr_c1h">Projects in steps</h3><div class="amt"><span data-i="pr_c1a">hours × €90</span><small data-i="pr_c1s">per step, quoted up front</small></div>
          <p data-i="pr_c1p">Automations, integrations, builds and security work. Every step gets its own written scope and price before it starts, and you see it running before the next one is quoted.</p>
          <hr>
          <ul>
            <li data-i="pr_c1i0">The quote shows the math: estimated hours times the rate, as a range.</li>
            <li data-i="pr_c1i1">Quote valid 14 days. You can stop after any step.</li>
            <li data-i="pr_c1i2">If a step grows past the estimate, you hear it before the hours are spent.</li>
          </ul>
          <a class="btn" href="{{ config('scuttle-dev.booking_url') }}"><span data-i="pr_c1b">Book a call →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c2l">02 · Per hour</span>
          <h3 data-i="pr_c2h">Hourly work</h3><div class="amt"><span data-i="pr_c2a">€90</span><small data-i="pr_c2s">per hour</small></div>
          <p data-i="pr_c2p">Small, well-defined tasks and anything that does not fit a scope yet.</p>
          <hr>
          <ul>
            <li data-i="pr_c2i0">Invoiced monthly, with the hours listed per task.</li>
            <li data-i="pr_c2i1">Optional cap per month, agreed up front.</li>
            <li data-i="pr_c2i2">A written task or ticket is all it takes to start.</li>
          </ul>
          <a class="btn o" href="{{ route('scuttle.home') }}#contact"><span data-i="pr_c2b">Ask a question →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c3l">03 · Per day</span>
          <h3 data-i="pr_c3h">Subcontracting</h3><div class="amt"><span data-i="pr_c3a">€720</span><small data-i="pr_c3s">per day of 8 hours</small></div>
          <p data-i="pr_c3p">Senior hands on your client's project, under your terms or mine. Eight hours at the same rate, booked per day.</p>
          <hr>
          <ul>
            <li data-i="pr_c3i0">Your tools, your repository, your standards.</li>
            <li data-i="pr_c3i1">NDA signed before anything is shared.</li>
            <li data-i="pr_c3i2">Weekly written progress, so you can report to your client.</li>
          </ul>
          <a class="btn o" href="{{ route('scuttle.home') }}#contact"><span data-i="pr_c3b">Check availability →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c4l">04 · Per hour</span>
          <h3 data-i="pr_c4h">Incident help</h3><div class="amt"><span data-i="pr_c4a">€90</span><small data-i="pr_c4s">per hour, no surcharge</small></div>
          <p data-i="pr_c4p">Something is down, the developer is gone, and it has to work today. Read-only on production until you say otherwise.</p>
          <hr>
          <ul>
            <li data-i="pr_c4i0">Same rate, no emergency surcharge.</li>
            <li data-i="pr_c4i1">First hour: find the cause, say what the fix costs.</li>
            <li data-i="pr_c4i2">Every change written down, so the next person understands it.</li>
          </ul>
          <a class="btn o" href="tel:+31618233175"><span data-i="pr_c4b">Call +31 6 18 23 31 75</span></a>
        </div>
      </div>
    </div>
  </div>

  <div class="wrap" style="margin-top:24px">
    <div class="plate rv">
      <div class="pb-h"><span class="lbl dot" data-i="pr_p2l">Fixed price, fixed shape</span><p data-i="pr_p2p">Known price before you say yes. Each one is a fixed number of hours at the same rate; the math is shown so nothing is hidden. All amounts excl. VAT.</p></div>
      <div class="plans">
        <div class="plan hi">
          <span class="badge" data-i="pr_bd2">Recommended</span>
          <span class="lbl" data-i="pr_c5l">05 · Fixed price</span>
          <h3 data-i="pr_c5h">Health Check</h3><div class="amt"><span data-i="pr_c5a">€1,080</span><small data-i="pr_c5s">fixed, 12 hours</small></div>
          <p data-i="pr_c5p">Where automation saves hours, what is risky, which parts are out of support, and a step-by-step plan. Read-only, two days.</p>
          <hr>
          <ul>
            <li data-i="pr_c5i0">12 hours × EUR 90, fixed.</li>
            <li data-i="pr_c5i1">A free intro call first, to check it fits.</li>
            <li data-i="pr_c5i2">Separate from any work that follows: the plan is the product.</li>
          </ul>
          <a class="btn" href="{{ route('scuttle.health-check') }}"><span data-i="pr_c5b">Read what is in it →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c6l">06 · Per month</span>
          <h3 data-i="pr_c6h">Maintenance retainer</h3><div class="amt"><span data-i="pr_c6a">from €720</span><small data-i="pr_c6s">per month, blocks of 8 hours</small></div>
          <p data-i="pr_c6p">A monthly block of hours for patches, monitoring, backups, small changes and a report, by the person who knows the system.</p>
          <hr>
          <ul>
            <li data-i="pr_c6i0">Blocks of 8, 16 or 24 hours a month at EUR 90.</li>
            <li data-i="pr_c6i1">Unused hours are reported, never hidden.</li>
            <li data-i="pr_c6i2">Review every quarter: up, down or stop.</li>
          </ul>
          <a class="btn" href="{{ route('scuttle.product', ['slug' => 'maintenance-retainer']) }}"><span data-i="pr_c6b">Read the retainer →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c7l">07 · Fixed price</span>
          <h3 data-i="pr_c7h">Security quick scan</h3><div class="amt"><span data-i="pr_c7a">€720</span><small data-i="pr_c7s">fixed, 8 hours</small></div>
          <p data-i="pr_c7p">One day on your web application and server: open ports, TLS, headers, dependencies with known vulnerabilities, the obvious access-control holes. Written authorization first.</p>
          <hr>
          <ul>
            <li data-i="pr_c7i0">8 hours × EUR 90, fixed.</li>
            <li data-i="pr_c7i1">Findings ranked, each with the fix.</li>
            <li data-i="pr_c7i2">A full assessment with retest is quoted from its findings.</li>
          </ul>
          <a class="btn o" href="{{ route('scuttle.product', ['slug' => 'security-assessment']) }}"><span data-i="pr_c7b">Read the assessment →</span></a>
        </div>
        <div class="plan">
          <span class="lbl" data-i="pr_c8l">08 · Fixed price</span>
          <h3 data-i="pr_c8h">Architecture review</h3><div class="amt"><span data-i="pr_c8a">€1,440</span><small data-i="pr_c8s">fixed, 16 hours</small></div>
          <p data-i="pr_c8p">A decision on stack, cloud, integration or build versus buy: options compared, a written recommendation, decision records.</p>
          <hr>
          <ul>
            <li data-i="pr_c8i0">16 hours × EUR 90, fixed.</li>
            <li data-i="pr_c8i1">One review session with your team included.</li>
            <li data-i="pr_c8i2">Bigger questions are quoted after the intro call.</li>
          </ul>
          <a class="btn o" href="{{ route('scuttle.product', ['slug' => 'architecture-review']) }}"><span data-i="pr_c8b">Read the review →</span></a>
        </div>
      </div>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot" data-i="pr_in_l">Included in every price</span><h2 data-i="pr_in_h">What you always get</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b data-i="pr_f1b">The engineer, not an account manager</b><p data-i="pr_f1p">You talk to the person who does the work, from the first call to the handover.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="pr_f2b">Tests before changes</b><p data-i="pr_f2p">Nothing is changed without a test that proves the old behaviour first.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="pr_f3b">A rollback for every release</b><p data-i="pr_f3p">Small releases, each one undoable. No three-month branches.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="pr_f4b">Documentation in your repository</b><p data-i="pr_f4p">Runbook, decisions and architecture notes land next to the code, not in my head.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="pr_f5b">Your accounts, your code</b><p data-i="pr_f5p">Servers, domains and repositories stay in your name. Rights pass to you on full payment.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="pr_f6b">Plain reporting</b><p data-i="pr_f6p">Hours per task on every invoice, and a straight answer when something is not worth its price.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot" data-i="pr_q_l">How a quote comes about</span>
      <ol class="steps">
        <li><span>01</span><p data-i="pr_s1">A free intro call of about 30 minutes. What you run, what hurts, what must not stop.</p></li>
        <li><span>02</span><p data-i="pr_s2">When the system is unknown territory: the Health Check, so the plan rests on facts.</p></li>
        <li><span>03</span><p data-i="pr_s3">A written quote per step: deliverables, hours as a range, price as the math, what is not included. Valid 14 days.</p></li>
        <li><span>04</span><p data-i="pr_s4">You accept in writing, the step starts, and you see it running before the next one is quoted.</p></li>
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot" data-i="pr_fq_l">Questions I get</span>
      <dl class="faq">
        <dt data-i="pr_q1">Is VAT included?</dt><dd data-i="pr_a1">No. Every amount on this site is excl. VAT (btw). Invoices show it separately.</dd>
        <dt data-i="pr_q2">When do I pay?</dt><dd data-i="pr_a2">Within 14 days of the invoice. Acceptance of delivered work within 7 days.</dd>
        <dt data-i="pr_q3">Who owns the code?</dt><dd data-i="pr_a3">You do, as soon as the invoice is paid. Everything lives in repositories and accounts in your name from day one.</dd>
        <dt data-i="pr_q4">Do you sign an NDA?</dt><dd data-i="pr_a4">Yes. My mutual NDA is on this site as a PDF, or I sign yours after reading it.</dd>
        <dt data-i="pr_q5">Fixed price for a whole project?</dt><dd data-i="pr_a5">Per step, yes, once the scope is clear. For a whole unknown system, no: that is what the Health Check is for.</dd>
        <dt data-i="pr_q6">Do you work through agencies?</dt><dd data-i="pr_a6">Yes, as a subcontractor on your client's project, under your terms or mine.</dd>
      </dl>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    <span class="lbl dot" data-i="pr_pl">Every product and how it is priced</span>
    <div class="pricelist rv">
      @foreach($groups as $key => $items)
      <div class="pg"><small>{{ $C::GROUPS[$key] }}</small>
        @foreach($items as $p)
        <a href="{{ $C::url($p) }}"><b>{{ $p['name'] }}</b><span data-i="pr_pl_{{ str_replace('-', '_', $p['slug']) }}">{{ $p['price'] }}</span></a>
        @endforeach
      </div>
      @endforeach
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      <img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="46" height="37">
      <h2 data-i="pr_e_h">Want a number for your situation?</h2>
      <p data-i="pr_e_p">Tell me what you are running. I reply within one working day with a first opinion, free.</p>
      <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}"><span data-i="pr_e_b1">Book a call</span></a><a class="btn o" href="{{ route('scuttle.home') }}#contact"><span data-i="pr_e_b2">Use the contact form</span></a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json(collect($C::all())->mapWithKeys(fn ($p) => ['pr_pl_'.str_replace('-', '_', $p['slug']) => $p['nl']['price']])->all()));
Object.assign(window.SCUTTLE_NL.t, {
 "pr_l": "Prijzen",
 "pr_h": "Eén tarief, twee manieren om het te kopen",
 "pr_lead": "Ik krijg altijd eerst de vraag wat dingen kosten, dus hier staat het. Eén uurtarief voor werk per uur of in stappen, en vaste prijzen voor dingen met een vaste vorm. Alle bedragen zijn excl. btw.",
 "pr_p1l": "Per uur, per stap geoffreerd",
 "pr_p1p": "Voor werk waarvan de omvang pas blijkt als we ervoor zitten. Het tarief is overal gelijk; wat verandert is hoe het is verpakt. Alle bedragen excl. btw.",
 "pr_p2l": "Vaste prijs, vaste vorm",
 "pr_p2p": "Je kent de prijs voordat je ja zegt. Elk pakket is een vast aantal uren tegen hetzelfde tarief; de som staat erbij zodat niets verborgen is. Alle bedragen excl. btw.",
 "pr_c1l": "01 · Per stap",
 "pr_c1h": "Projecten in stappen",
 "pr_c1a": "uren × €90",
 "pr_c1s": "per stap, vooraf geoffreerd",
 "pr_c1p": "Automatiseringen, koppelingen, bouwwerk en beveiligingswerk. Elke stap krijgt vooraf een eigen schriftelijke scope en prijs, en je ziet hem draaien voordat de volgende wordt geoffreerd.",
 "pr_c1i0": "De offerte laat de som zien: geschatte uren maal het tarief, als bandbreedte.",
 "pr_c1i1": "Offerte 14 dagen geldig. Je kunt na elke stap stoppen.",
 "pr_c1i2": "Als een stap boven de schatting uitkomt, hoor je dat voordat de uren zijn gemaakt.",
 "pr_c1b": "Plan een gesprek →",
 "pr_c2l": "02 · Per uur",
 "pr_c2h": "Werk per uur",
 "pr_c2a": "€90",
 "pr_c2s": "per uur",
 "pr_c2p": "Kleine, duidelijke taken en alles wat nog niet in een scope past.",
 "pr_c2i0": "Maandelijks gefactureerd, met de uren per taak.",
 "pr_c2i1": "Optioneel een maximum per maand, vooraf afgesproken.",
 "pr_c2i2": "Een geschreven taak of ticket is genoeg om te starten.",
 "pr_c2b": "Stel een vraag →",
 "pr_c3l": "03 · Per dag",
 "pr_c3h": "Inhuur als onderaannemer",
 "pr_c3a": "€720",
 "pr_c3s": "per dag van 8 uur",
 "pr_c3p": "Ervaren handen op het project van jouw klant, onder jouw voorwaarden of de mijne. Acht uur tegen hetzelfde tarief, per dag geboekt.",
 "pr_c3i0": "Jouw tools, jouw repository, jouw standaarden.",
 "pr_c3i1": "NDA getekend voordat er iets gedeeld wordt.",
 "pr_c3i2": "Wekelijks een schriftelijke voortgangsupdate, zodat je aan je klant kunt rapporteren.",
 "pr_c3b": "Check beschikbaarheid →",
 "pr_c4l": "04 · Per uur",
 "pr_c4h": "Hulp bij incidenten",
 "pr_c4a": "€90",
 "pr_c4s": "per uur, geen toeslag",
 "pr_c4p": "Iets ligt eruit, de ontwikkelaar is weg en het moet vandaag werken. Alleen-lezen op productie tot je anders zegt.",
 "pr_c4i0": "Zelfde tarief, geen spoedtoeslag.",
 "pr_c4i1": "Eerste uur: de oorzaak vinden en zeggen wat de oplossing kost.",
 "pr_c4i2": "Elke wijziging opgeschreven, zodat de volgende het begrijpt.",
 "pr_c4b": "Bel +31 6 18 23 31 75",
 "pr_c5l": "05 · Vaste prijs",
 "pr_c5h": "Health Check",
 "pr_c5a": "€1,080",
 "pr_c5s": "vast, 12 uur",
 "pr_c5p": "Waar automatisering uren bespaart, wat risicovol is, welke onderdelen geen support meer hebben, en een plan per stap. Alleen-lezen, twee dagen.",
 "pr_c5i0": "12 uur × EUR 90, vast.",
 "pr_c5i1": "Eerst een gratis kennismakingsgesprek, om te kijken of het past.",
 "pr_c5i2": "Los van het werk dat daarna volgt: het plan is het product.",
 "pr_c5b": "Lees wat erin zit →",
 "pr_c6l": "06 · Per maand",
 "pr_c6h": "Maintenance retainer",
 "pr_c6a": "vanaf €720",
 "pr_c6s": "per maand, blokken van 8 uur",
 "pr_c6p": "Een maandelijks blok uren voor patches, monitoring, back-ups, kleine wijzigingen en een rapport, door de persoon die het systeem kent.",
 "pr_c6i0": "Blokken van 8, 16 of 24 uur per maand tegen EUR 90.",
 "pr_c6i1": "Ongebruikte uren worden gemeld, nooit verstopt.",
 "pr_c6i2": "Elk kwartaal een evaluatie: omhoog, omlaag of stoppen.",
 "pr_c6b": "Lees over de retainer →",
 "pr_c7l": "07 · Vaste prijs",
 "pr_c7h": "Security quick scan",
 "pr_c7a": "€720",
 "pr_c7s": "vast, 8 uur",
 "pr_c7p": "Eén dag op je webapplicatie en server: open poorten, TLS, headers, afhankelijkheden met bekende kwetsbaarheden, de voor de hand liggende gaten in toegangsbeheer. Eerst schriftelijke toestemming.",
 "pr_c7i0": "8 uur × EUR 90, vast.",
 "pr_c7i1": "Bevindingen op volgorde van ernst, elk met de oplossing.",
 "pr_c7i2": "Een volledige assessment met hertest wordt geoffreerd op basis van de bevindingen.",
 "pr_c7b": "Lees over de assessment →",
 "pr_c8l": "08 · Vaste prijs",
 "pr_c8h": "Architecture review",
 "pr_c8a": "€1,440",
 "pr_c8s": "vast, 16 uur",
 "pr_c8p": "Een besluit over stack, cloud, integratie of zelf bouwen versus kopen: opties vergeleken, een schriftelijk advies, besluitenlogboek.",
 "pr_c8i0": "16 uur × EUR 90, vast.",
 "pr_c8i1": "Eén reviewsessie met je team inbegrepen.",
 "pr_c8i2": "Grotere vragen worden geoffreerd na het kennismakingsgesprek.",
 "pr_c8b": "Lees over de review →",
 "pr_bd1": "Aanbevolen",
 "pr_bd2": "Aanbevolen",
 "pr_in_l": "Inbegrepen in elke prijs",
 "pr_in_h": "Wat je altijd krijgt",
 "pr_f1b": "De engineer, geen accountmanager",
 "pr_f1p": "Je praat met degene die het werk doet, van het eerste gesprek tot de overdracht.",
 "pr_f2b": "Tests vóór wijzigingen",
 "pr_f2p": "Er wordt niets gewijzigd zonder eerst een test die het oude gedrag aantoont.",
 "pr_f3b": "Een rollback bij elke release",
 "pr_f3p": "Kleine releases, elk terug te draaien. Geen branches van drie maanden.",
 "pr_f4b": "Documentatie in jouw repository",
 "pr_f4p": "Runbook, besluiten en architectuurnotities staan naast de code, niet in mijn hoofd.",
 "pr_f5b": "Jouw accounts, jouw code",
 "pr_f5p": "Servers, domeinen en repositories blijven op jouw naam. De rechten gaan na volledige betaling naar jou.",
 "pr_f6b": "Duidelijke rapportage",
 "pr_f6p": "Uren per taak op elke factuur, en een eerlijk antwoord als iets zijn prijs niet waard is.",
 "pr_q_l": "Hoe een offerte tot stand komt",
 "pr_s1": "Een gratis kennismakingsgesprek van ongeveer 30 minuten. Wat je draait, wat pijn doet, wat niet mag stilvallen.",
 "pr_s2": "Als het systeem onbekend terrein is: de Health Check, zodat het plan op feiten rust.",
 "pr_s3": "Een schriftelijke offerte per stap: opleveringen, uren als bandbreedte, prijs als som, wat er niet bij zit. 14 dagen geldig.",
 "pr_s4": "Je accepteert schriftelijk, de stap start, en je ziet hem draaien voordat de volgende wordt geoffreerd.",
 "pr_fq_l": "Vragen die ik krijg",
 "pr_q1": "Zit btw erbij?",
 "pr_a1": "Nee. Elk bedrag op deze site is excl. btw. Op facturen staat de btw apart.",
 "pr_q2": "Wanneer betaal ik?",
 "pr_a2": "Binnen 14 dagen na de factuur. Het opgeleverde werk accepteer je binnen 7 dagen.",
 "pr_q3": "Van wie is de code?",
 "pr_a3": "Van jou, zodra de factuur is betaald. Alles staat vanaf dag één in repositories en accounts op jouw naam.",
 "pr_q4": "Teken je een NDA?",
 "pr_a4": "Ja. Mijn wederzijdse NDA staat als pdf op deze site, of ik teken de jouwe na het te hebben gelezen.",
 "pr_q5": "Vaste prijs voor een heel project?",
 "pr_a5": "Per stap wel, zodra de scope duidelijk is. Voor een heel onbekend systeem niet: daar dient de Health Check voor.",
 "pr_q6": "Werk je via bureaus?",
 "pr_a6": "Ja, als onderaannemer op het project van jouw klant, onder jouw voorwaarden of de mijne.",
 "pr_pl": "Elk product en wat het kost",
 "pr_e_h": "Wil je een bedrag voor jouw situatie?",
 "pr_e_p": "Vertel me wat je draait. Ik reageer binnen één werkdag met een eerste mening, gratis.",
 "pr_e_b1": "Plan een gesprek",
 "pr_e_b2": "Gebruik het contactformulier"
});
</script>
@endpush
