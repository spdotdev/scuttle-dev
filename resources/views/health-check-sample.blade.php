@extends('scuttle::layout')

@section('title', 'What a Health Check report looks like')
@section('crumb', 'Sample report')
@section('description', 'The structure of the written Health Check report, as an illustration with an invented system: summary, version table, ranked findings, a plan in steps, what is not covered.')
@section('canonical', 'https://scuttle.dev/health-check/sample')

@php
$nl = [
    'sm_l' => 'Voorbeeldrapport',
    'sm_h' => 'Zo ziet het rapport van een Health Check eruit',
    'sm_lead' => 'Na twee dagen alleen-lezen onderzoek krijg je een schriftelijk rapport. Hieronder staat de opbouw, met een verzonnen systeem als voorbeeld.',
    'sm_note' => '<b>Illustratie.</b> Het systeem, de bevindingen en de getallen hieronder zijn verzonnen, zodat er geen klant te zien is. De opbouw, de controles en de data van php.net zijn echt.',
    'sm_1_h' => '1. De samenvatting op één pagina',
    'sm_1_p' => 'Een oordeel in één zin, de vijf grootste risico\'s en de eerste drie acties, geschreven voor degene die beslist.',
    'sm_1_ex' => 'Voorbeeld, verzonnen: "Het systeem kan nog twaalf maanden veilig draaien, mits eerst de drie acties hieronder gebeuren."',
    'sm_2_h' => '2. Versies en einddata',
    'sm_2_p' => 'PHP, framework, database en belangrijke afhankelijkheden, elk met de versie in gebruik, tot wanneer die ondersteund wordt (van de eigen pagina van de leverancier, met de datum van controle) en het risico.',
    'sm_2_th0' => 'Onderdeel', 'sm_2_th1' => 'Versie in gebruik', 'sm_2_th2' => 'Ondersteund tot', 'sm_2_th3' => 'Risico',
    'sm_2_r1' => 'PHP (voorbeeld)', 'sm_2_r1a' => '8.2', 'sm_2_r1b' => '31 dec 2026 (php.net)', 'sm_2_r1c' => 'Midden: plan de upgrade dit kwartaal',
    'sm_2_r2' => 'Framework (voorbeeld)', 'sm_2_r2a' => 'Een oudere hoofdversie', 'sm_2_r2b' => 'Van de supportpagina van het framework', 'sm_2_r2c' => 'Per geval beoordeeld',
    'sm_3_h' => '3. Bevindingen op volgorde van risico',
    'sm_3_p' => 'Elke bevinding heeft een titel, een ernst (kritiek, hoog, midden, laag), het bewijs (bestand en regel, commando of een regel uit de inventaris), waarom het ertoe doet in gewone woorden en een richting voor de oplossing. Wat ik niet kon controleren, markeer ik als zodanig.',
    'sm_3_ex_h' => 'Voorbeeldbevinding, verzonnen',
    'sm_3_ex_t' => 'Afhankelijkheden met bekende beveiligingsmeldingen',
    'sm_3_ex_s' => 'Ernst: hoog',
    'sm_3_ex_e' => 'Bewijs: de uitvoer van `composer audit` in de inventaris.',
    'sm_3_ex_w' => 'Waarom het ertoe doet: een pakket met een bekend lek is de makkelijkste ingang voor een aanvaller.',
    'sm_3_ex_f' => 'Oplossingsrichting: de pakketten bijwerken in een eigen release, met een test eromheen.',
    'sm_4_h' => '4. Het plan in stappen',
    'sm_4_p' => 'Oud en nieuw draaien naast elkaar, niets valt stil. Elke fase heeft een doel, een urenbereik (nooit één getal), een controle en een terugweg.',
    'sm_4_0' => 'Fase 0: een vangnet (tests, back-ups, monitoring)',
    'sm_4_1' => 'Fase 1: runtime en afhankelijkheden bijwerken',
    'sm_4_2' => 'Fase 2: het eerste stuk vervangen',
    'sm_4_3' => 'Fase 3: de volgende stukken',
    'sm_4_4' => 'Fase 4: het oude deel uitzetten',
    'sm_5_h' => '5. Wat niet is meegenomen',
    'sm_5_p' => 'Een herbouw, een penetratietest, wijzigingen in productie, productiedata en alles wat niet gelezen is. Dat staat in het rapport, zodat niemand denkt dat het gedekt is.',
    'sm_6_h' => '6. Het doorloopgesprek',
    'sm_6_p' => 'Een agenda van één pagina en het voorstel voor de volgende stap met een afgebakende prijs. Dat is een aparte offerte, 14 dagen geldig.',
    'sm_7_h' => 'Hoe de twee dagen lopen',
    'sm_7_0' => 'Vooraf: een gratis introgesprek over stack, wat pijn doet, hosting en deadlines.',
    'sm_7_1' => 'Dag 1, ochtend: inventaris, versies en einddata.',
    'sm_7_2' => 'Dag 1, middag: beveiligingsbasis, wijzigingsrisico, bevindingen met bewijs.',
    'sm_7_3' => 'Dag 2, ochtend: het plan in stappen met urenbereiken.',
    'sm_7_4' => 'Dag 2, middag: schrijven, nalezen, het rapport opmaken.',
    'sm_7_5' => 'Daarna: het doorloopgesprek.',
    'sm_r' => 'De versietabel is het soort ding dat je zelf in tien minuten kunt nakijken met de <a href="/php-check">PHP-versiecheck</a>. Wijzen de bevindingen op beveiligingsregels, dan is <a href="/services/hardening">Hardening</a> de dienst die ze oplost, en de <a href="/pricing">prijzenpagina</a> laat zien wat elke stap kost.',
    'sm_c_h' => 'Wil je er zo een voor jouw systeem?',
    'sm_c_p' => 'Twee dagen, alleen lezen, vaste prijs EUR 1.080 excl. btw (12 uur × EUR 90). Vertel me wat je draait en ik controleer of de Health Check past voordat er iets geboekt wordt.',
    'sm_c_b1' => 'Plan een gesprek',
    'sm_c_b2' => 'Wat het dekt en wat het kost',
];
@endphp

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="sm_l">Sample report</span>
  <h1 data-i="sm_h">What a Health Check report looks like</h1>
  <p class="lead" data-i="sm_lead">After two days of read-only analysis you get a written report. Below is its structure, with an invented system as the example.</p>
  <div class="callout" role="note"><p data-i="sm_note"><b>Illustration.</b> The system, the findings and the numbers below are invented, so that no client is shown. The structure, the checks and the dates from php.net are real.</p></div>

  <h2 data-i="sm_1_h">1. The summary on one page</h2>
  <p data-i="sm_1_p">A verdict in one sentence, the five biggest risks and the first three actions, written for the person who decides.</p>
  <p class="ex" data-i="sm_1_ex">Example, invented: "The system can run safely for another twelve months, provided the three actions below happen first."</p>

  <h2 data-i="sm_2_h">2. Versions and end dates</h2>
  <p data-i="sm_2_p">PHP, the framework, the database and the key dependencies, each with the version in use, until when it is supported (from the vendor's own page, with the date of the check) and the risk.</p>
  <div class="mtable" tabindex="0" role="region" aria-label="Example version table">
    <table class="tbl">
      <thead><tr><th data-i="sm_2_th0">Component</th><th data-i="sm_2_th1">Version in use</th><th data-i="sm_2_th2">Supported until</th><th data-i="sm_2_th3">Risk</th></tr></thead>
      <tbody>
        <tr><td data-i="sm_2_r1">PHP (example)</td><td data-i="sm_2_r1a">8.2</td><td data-i="sm_2_r1b">31 Dec 2026 (php.net)</td><td data-i="sm_2_r1c">Medium: plan the upgrade this quarter</td></tr>
        <tr><td data-i="sm_2_r2">Framework (example)</td><td data-i="sm_2_r2a">An older major version</td><td data-i="sm_2_r2b">From the framework's support page</td><td data-i="sm_2_r2c">Judged case by case</td></tr>
      </tbody>
    </table>
  </div>

  <h2 data-i="sm_3_h">3. Findings, ranked by risk</h2>
  <p data-i="sm_3_p">Every finding has a title, a severity (critical, high, medium, low), the evidence (file and line, a command, or a line of the inventory), why it matters in plain words and a direction for the fix. What I could not verify is marked as such.</p>
  <div class="callout" role="note">
    <h3 data-i="sm_3_ex_h">Example finding, invented</h3>
    <p><b data-i="sm_3_ex_t">Dependencies with known security advisories</b> · <span data-i="sm_3_ex_s">Severity: high</span></p>
    <p data-i="sm_3_ex_e">Evidence: the output of <code>composer audit</code> in the inventory.</p>
    <p data-i="sm_3_ex_w">Why it matters: a package with a known flaw is the easiest way in for an attacker.</p>
    <p data-i="sm_3_ex_f">Fix direction: update the packages in a release of their own, with a test around them.</p>
  </div>

  <h2 data-i="sm_4_h">4. The plan in steps</h2>
  <p data-i="sm_4_p">Old and new run side by side, nothing stops. Every phase has a goal, a range of hours (never a single number), a check and a way back.</p>
  <ol>
    <li data-i="sm_4_0">Phase 0: a safety net (tests, backups, monitoring)</li>
    <li data-i="sm_4_1">Phase 1: bring the runtime and dependencies up to date</li>
    <li data-i="sm_4_2">Phase 2: replace the first slice</li>
    <li data-i="sm_4_3">Phase 3: the next slices</li>
    <li data-i="sm_4_4">Phase 4: retire the old part</li>
  </ol>

  <h2 data-i="sm_5_h">5. What is not covered</h2>
  <p data-i="sm_5_p">A rewrite, a penetration test, changes to production, production data and anything that was not read. It is written in the report so nobody thinks it is covered.</p>

  <h2 data-i="sm_6_h">6. The walkthrough</h2>
  <p data-i="sm_6_p">A one-page agenda and the proposal for the next step with a scoped price. That is a separate quote, valid 14 days.</p>

  <h2 data-i="sm_7_h">How the two days run</h2>
  <ul>
    <li data-i="sm_7_0">Before: a free intro call about the stack, what hurts, the hosting and deadlines.</li>
    <li data-i="sm_7_1">Day 1, morning: inventory, versions and end dates.</li>
    <li data-i="sm_7_2">Day 1, afternoon: security basics, change risk, findings with evidence.</li>
    <li data-i="sm_7_3">Day 2, morning: the plan in steps with ranges of hours.</li>
    <li data-i="sm_7_4">Day 2, afternoon: writing, proofreading, laying out the report.</li>
    <li data-i="sm_7_5">After: the walkthrough call.</li>
  </ul>

  <p data-i="sm_r">The version table is the kind of thing you can check yourself in ten minutes with the <a href="{{ route('scuttle.php-check') }}">PHP version check</a>. If the findings point at security rules, <a href="{{ route('scuttle.product', ['slug' => 'hardening']) }}">Hardening</a> is the service that fixes them, and the <a href="{{ route('scuttle.pricing') }}">pricing page</a> shows what each step costs.</p>

  <h2 data-i="sm_c_h">Want one for your system?</h2>
  <p data-i="sm_c_p">Two days, read-only, fixed price EUR 1,080 excl. VAT (12 hours × EUR 90). Tell me what you run and I check that the Health Check fits before anything is booked.</p>
  <p style="display:flex;gap:10px;flex-wrap:wrap"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="sm_c_b1">Book a call</a><a class="btn o" href="{{ route('scuttle.health-check') }}" data-i="sm_c_b2">What it covers and what it costs</a></p>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json(collect($nl)->map(fn ($v) => preg_replace('/`([^`]+)`/', '<code>$1</code>', $v))->all()));
</script>
@endpush
