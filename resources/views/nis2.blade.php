@extends('scuttle::layout')

@section('title', 'NIS2 and the Cyber Resilience Act: what applies to a Dutch company')
@section('crumb', 'NIS2 and CRA')
@section('description', 'The Dutch NIS2 law has applied since 15 August 2026 and the CRA reporting duty since 11 September 2026. Who is in scope, what they must do, and how I help: a technical gap analysis.')
@section('canonical', 'https://scuttle.dev/nis2')

@php
$C = \Spdotdev\ScuttleDev\Catalog::class;
$nl = [
    'nx_l' => 'NIS2 en CRA',
    'nx_h' => 'NIS2 en de Cyber Resilience Act: wat geldt er voor een Nederlands bedrijf',
    'nx_lead' => 'De Cyberbeveiligingswet, de Nederlandse NIS2-wet, geldt sinds 15 augustus 2026. De meldplicht van de Cyber Resilience Act geldt sinds 11 september 2026. Wie valt eronder, wat moet er gebeuren en waar ik bij help.',
    'nx_note' => 'Ik ben geen jurist. Dit is een technische lezing van wat de officiële pagina\'s zeggen, met de bronnen onderaan. Gebruik voor een definitief antwoord over je eigen organisatie de zelftest van het NCSC.',
    'nx_1_h' => 'Wie valt eronder',
    'nx_1_p1' => 'Het NCSC noemt ruim 8.000 organisaties die essentiële of belangrijke diensten leveren in kritieke sectoren. De omvang bepaalt in welke klasse je valt, en voor bepaalde soorten organisaties gelden uitzonderingen op de omvangscriteria.',
    'nx_1_p2' => 'Aanbieders van beheerde diensten en beheerde beveiligingsdiensten worden genoemd in de vragen en antwoorden van de NCTV: essentieel als ze groot zijn, belangrijk als ze middelgroot zijn. Een leverancier valt niet automatisch zelf onder de wet. Of een heel kleine leverancier eronder valt, hangt af van de omvangscriteria en de sector; gebruik de zelftest van het NCSC en ga er niet van uit.',
    'nx_2_h' => 'Wat een organisatie onder de wet moet doen',
    'nx_2_1' => 'Zich registreren in het entiteitenregister van het NCSC.',
    'nx_2_2' => 'Een risicoanalyse doen en passende, evenredige maatregelen nemen voor netwerk- en informatiesystemen (de zorgplicht).',
    'nx_2_3' => 'Incidenten zo snel mogelijk melden aan het CSIRT en de toezichthouder, en in ieder geval binnen 24 uur.',
    'nx_3_h' => 'Waarom ook een kleine leverancier het merkt',
    'nx_3_p' => 'De wet verplicht organisaties de risico\'s in hun toeleveringsketen in kaart te brengen en maatregelen te nemen. Ze mogen eisen stellen aan de cyberbeveiliging van hun rechtstreekse leveranciers. In de praktijk komt dat als een contractclausule of een vragenlijst: hoe ga je om met patches, toegang, back-ups en incidenten. Een leverancier die met een document kan antwoorden, wint van een die met een belofte antwoordt.',
    'nx_4_h' => 'De Cyber Resilience Act is een tweede spoor',
    'nx_4_p' => 'De Cyber Resilience Act, Verordening (EU) 2024/2847, gaat over producten, niet over organisaties. Hij is in werking getreden op 10 december 2024. De meldplicht voor actief uitgebuite kwetsbaarheden geldt sinds 11 september 2026. De hoofdverplichtingen voor fabrikanten van producten met digitale elementen gelden vanaf 11 december 2027. De Commissie publiceerde op 27 juli 2026 praktische richtlijnen voor bedrijven van elke omvang en zegt dat de verordening een bijzondere benadering kent voor vrije en opensourcesoftware. Lever je software die anderen installeren of draaien, lees het dan nu, niet in 2027.',
    'nx_5_h' => 'Waar ik bij help',
    'nx_5_p' => 'Het technische deel: een gap-analyse tegen NIS2, AVG of DORA, met jouw maatregelen per eis getoetst in een tabel die je aan een auditor of een klant kunt geven, en het herstel in een afgesproken volgorde. Het is technisch werk, geen juridisch advies en geen certificering. Boetes, advocaten en de auditor zelf zitten niet in de offerte.',
    'nx_5_p2' => 'Compliance Alignment: op offerte per regime, EUR 90 per uur excl. btw. Wil je eerst weten waar je technisch staat, dan is de Security quick scan een vaste prijs van EUR 720 excl. btw (8 uur).',
    'nx_5_a' => 'Lees over Compliance Alignment',
    'nx_5_b' => 'Plan een gesprek',
    'nx_6_h' => 'Bronnen, gelezen op 30 oktober 2026',
    'nx_c_h' => 'Heeft een klant je erom gevraagd?',
    'nx_c_p' => 'Vertel me welke klant welke vraag stelde. Ik reageer binnen één werkdag met een eerste mening, gratis.',
];
@endphp

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="nx_l">NIS2 and CRA</span>
  <h1 data-i="nx_h">NIS2 and the Cyber Resilience Act: what applies to a Dutch company</h1>
  <p class="lead" data-i="nx_lead">The Cyberbeveiligingswet, the Dutch NIS2 law, has applied since 15 August 2026. The reporting duty of the Cyber Resilience Act has applied since 11 September 2026. Who is in scope, what must happen, and where I help.</p>
  <div class="callout" role="note"><p data-i="nx_note">I am not a lawyer. This is a technical reading of what the official pages say, with the sources at the bottom. For a definite answer about your own organisation, use the NCSC self-check.</p></div>

  <h2 data-i="nx_1_h">Who is in scope</h2>
  <p data-i="nx_1_p1">The NCSC says the law covers more than 8,000 organisations that provide essential or important services in critical sectors. Size decides which class you fall in, and for some kinds of organisation there are exceptions to the size criteria.</p>
  <p data-i="nx_1_p2">Providers of managed services, and of managed security services, are named in the NCTV questions and answers: essential if they are large, important if they are medium-sized. A supplier is not automatically in scope itself. Whether a very small supplier falls under the law depends on the size criteria and the sector; use the NCSC self-check and do not assume.</p>

  <h2 data-i="nx_2_h">What an in-scope organisation must do</h2>
  <ul>
    <li data-i="nx_2_1">Register in the entity register at the NCSC.</li>
    <li data-i="nx_2_2">Carry out a risk analysis and take appropriate, proportionate measures for its network and information systems (the zorgplicht).</li>
    <li data-i="nx_2_3">Report incidents to the CSIRT and the supervisor as soon as possible, and within 24 hours at the latest.</li>
  </ul>

  <h2 data-i="nx_3_h">Why a small supplier still feels it</h2>
  <p data-i="nx_3_p">The law makes an in-scope organisation map the risks in its supply chain and take measures. It may set cybersecurity requirements for its direct suppliers. In practice that arrives as a clause in a contract or a questionnaire: how do you handle patches, access, backups, incidents. A supplier who can answer with a document wins over one who answers with a promise.</p>

  <h2 data-i="nx_4_h">The Cyber Resilience Act is a second track</h2>
  <p data-i="nx_4_p">The Cyber Resilience Act, Regulation (EU) 2024/2847, is about products, not organisations. It entered into force on 10 December 2024. The duty to report actively exploited vulnerabilities applies from 11 September 2026. The main obligations for manufacturers of products with digital elements apply from 11 December 2027. The Commission published practical guidance on 27 July 2026, written for businesses of all sizes, and says the act takes a special approach to free and open-source software. If you sell software that other people install or run, read it now, not in 2027.</p>

  <h2 data-i="nx_5_h">Where I help</h2>
  <p data-i="nx_5_p">The technical part: a gap analysis against NIS2, GDPR or DORA, with your measures tested against each requirement in a table you can hand to an auditor or a customer, and the remediation done in an agreed order. It is technical work, not legal advice and not a certification. Fines, lawyers and the auditor are not in the quote.</p>
  <p data-i="nx_5_p2">Compliance Alignment: quoted per regime, EUR 90 per hour excl. VAT. If you first want to know where you stand technically, the security quick scan is a fixed EUR 720 excl. VAT (8 hours).</p>
  <p style="display:flex;gap:10px;flex-wrap:wrap"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="nx_5_b">Book a call</a><a class="btn o" href="{{ $C::url($C::get('compliance')) }}" data-i="nx_5_a">Read about Compliance Alignment</a></p>

  <h2 data-i="nx_6_h">Sources, read on 30 October 2026</h2>
  <ul>
    <li><a href="https://www.ncsc.nl/cyberbeveiligingswet-nis2" rel="noopener">NCSC: Cyberbeveiligingswet (NIS2)</a></li>
    <li><a href="https://www.nctv.nl/onderwerpen/c/cyberbeveiligingswet/vragen-en-antwoorden" rel="noopener">NCTV: questions and answers about the Cyberbeveiligingswet</a></li>
    <li><a href="https://zoek.officielebekendmakingen.nl/stb-2026-189.html" rel="noopener">Staatsblad 2026, 189: Cyberbeveiligingsbesluit, article 35 (entry into force 15 August 2026)</a></li>
    <li><a href="https://digital-strategy.ec.europa.eu/en/policies/cyber-resilience-act" rel="noopener">European Commission: Cyber Resilience Act</a></li>
  </ul>

  <h2 data-i="nx_c_h">Did a customer ask you for this?</h2>
  <p data-i="nx_c_p">Tell me which customer asked which question. I reply within one working day with a first opinion, free.</p>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
