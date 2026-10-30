@extends('scuttle::layout')

@section('title', 'Which PHP version are you running? A ten-minute check')
@section('crumb', 'PHP version check')
@section('description', 'Which PHP version does your system run, and until when does it get security fixes? A ten-minute check with the dates from php.net, and what a Health Check does with the answer.')
@section('canonical', 'https://scuttle.dev/php-check')

@php
$nl = [
    'pc_d00' => '8 dec 2022',
    'pc_d01' => '31 dec 2024',
    'pc_d02' => '31 dec 2026',
    'pc_d10' => '23 nov 2023',
    'pc_d11' => '31 dec 2025',
    'pc_d12' => '31 dec 2027',
    'pc_d20' => '21 nov 2024',
    'pc_d21' => '31 dec 2026',
    'pc_d22' => '31 dec 2028',
    'pc_d30' => '20 nov 2025',
    'pc_d31' => '31 dec 2027',
    'pc_d32' => '31 dec 2029',
    'pc_l' => 'PHP-versiecheck',
    'pc_h' => 'Welke PHP-versie draait je systeem, en tot wanneer krijgt die beveiligingsupdates?',
    'pc_lead' => 'Een check van tien minuten die je zelf kunt doen, met de data van php.net. Maakt het antwoord je ongerust, dan zet een Health Check het om in een plan: twee dagen, alleen lezen, EUR 1.080 excl. btw, vaste prijs.',
    'pc_b1' => 'Plan een gesprek over een Health Check',
    'pc_b2' => 'Zo ziet het rapport eruit',
    'pc_t_h' => 'De versies die vandaag ondersteund worden',
    'pc_t_p' => 'Bron: <a href="https://www.php.net/supported-versions.php" rel="noopener">php.net/supported-versions.php</a>, gelezen op 30 oktober 2026. Een versie die hier niet in staat, krijgt geen beveiligingsupdates meer.',
    'pc_th0' => 'Versie', 'pc_th1' => 'Uitgebracht', 'pc_th2' => 'Actieve support tot', 'pc_th3' => 'Beveiligingsupdates tot',
    'pc_s_h' => 'Check de jouwe in tien minuten',
    'pc_s1' => 'Vraag het de server. Draai `php -v` op de opdrachtregel en kijk wat de website zelf gebruikt: de versie op een phpinfo-pagina kan afwijken van die op de opdrachtregel (php-fpm tegenover CLI). Haal zo\'n pagina daarna weer weg.',
    'pc_s2' => 'Lees composer.json. De regel require.php zegt wat de code verwacht; composer.lock laat zien wat er echt is geïnstalleerd.',
    'pc_s3' => 'Draai `composer audit`. Het toont geïnstalleerde pakketten met bekende beveiligingsmeldingen (Composer 2.4 en nieuwer).',
    'pc_s4' => 'Draai `composer outdated --direct`. Het toont welke directe afhankelijkheden achterlopen.',
    'pc_s5' => 'Zoek je framework op (Laravel, Symfony, Phalcon of wat je ook draait) op de eigen supportpagina en noteer tot wanneer jouw hoofdversie fixes krijgt.',
    'pc_s6' => 'Vraag wanneer een back-up voor het laatst is teruggezet, niet wanneer hij is gemaakt.',
    'pc_m_h' => 'Wat het antwoord betekent',
    'pc_m1' => 'Ouder dan 8.2: geen beveiligingsupdates meer. Plan een upgrade, in stappen en met een terugweg per stap.',
    'pc_m2' => '8.2: beveiligingsupdates tot 31 december 2026. Dat is de komende maanden, dus plan nu.',
    'pc_m3' => '8.3, 8.4 of 8.5: ondersteund. Kijk dan naar de afhankelijkheden, want de zwakke plek is meestal een pakket en niet PHP zelf.',
    'pc_p_h' => 'Van antwoord naar plan',
    'pc_p' => 'Een Health Check legt runtime, database en framework naast hun officiële einddata, zoekt afhankelijkheden met bekende kwetsbaarheden, kijkt hoe geheimen worden beheerd en waar wijzigen riskant is, en eindigt in een schriftelijk plan in stappen met uren per punt. Twee dagen, alleen lezen, vaste prijs EUR 1.080 excl. btw (12 uur × EUR 90). Een gratis introgesprek gaat vooraf.',
    'pc_p_a' => 'Wat het dekt en wat het kost',
    'pc_p_b' => 'Een voorbeeldrapport',
    'pc_w_h' => 'Wie dit schrijft',
    'pc_w' => 'Stanislav Plotnikov, senior developer en solution architect in Eindhoven. Ik programmeer sinds de jaren 2000, eerst PHP. Scuttle Development bestaat sinds december 2024, KvK 96040947. Je praat met degene die het werk doet.',
    'pc_w_a' => 'Meer over mij en hoe ik werk',
    'pc_c_h' => 'Wil je dat ik ernaar kijk?',
    'pc_c_p' => 'Vertel me welke versie je draait en wat er pijn doet. Ik reageer binnen één werkdag met een eerste mening, gratis.',
    'pc_c_b' => 'Plan een gesprek',
];
@endphp

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="pc_l">PHP version check</span>
  <h1 data-i="pc_h">Which PHP version is your system running, and until when does it get security fixes?</h1>
  <p class="lead" data-i="pc_lead">A ten-minute check you can run yourself, with the dates from php.net. If the answer worries you, a Health Check turns it into a plan: two days, read-only, EUR 1,080 excl. VAT, fixed.</p>
  <div class="cta" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px">
    <a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="pc_b1">Book a call about a Health Check</a>
    <a class="btn o" href="{{ route('scuttle.health-check.sample') }}" data-i="pc_b2">See what the report looks like</a>
  </div>

  <h2 data-i="pc_t_h">The versions that are supported today</h2>
  <div class="mtable" tabindex="0" role="region" aria-label="Supported PHP versions">
    <table class="tbl">
      <thead><tr><th data-i="pc_th0">Version</th><th data-i="pc_th1">Released</th><th data-i="pc_th2">Active support until</th><th data-i="pc_th3">Security fixes until</th></tr></thead>
      <tbody>
        <tr><td>8.2</td><td data-i="pc_d00">8 Dec 2022</td><td data-i="pc_d01">31 Dec 2024</td><td data-i="pc_d02">31 Dec 2026</td></tr>
        <tr><td>8.3</td><td data-i="pc_d10">23 Nov 2023</td><td data-i="pc_d11">31 Dec 2025</td><td data-i="pc_d12">31 Dec 2027</td></tr>
        <tr><td>8.4</td><td data-i="pc_d20">21 Nov 2024</td><td data-i="pc_d21">31 Dec 2026</td><td data-i="pc_d22">31 Dec 2028</td></tr>
        <tr><td>8.5</td><td data-i="pc_d30">20 Nov 2025</td><td data-i="pc_d31">31 Dec 2027</td><td data-i="pc_d32">31 Dec 2029</td></tr>
      </tbody>
    </table>
  </div>
  <p data-i="pc_t_p">Source: <a href="https://www.php.net/supported-versions.php" rel="noopener">php.net/supported-versions.php</a>, read on 30 October 2026. A version that is not in this table no longer gets security fixes.</p>

  <h2 data-i="pc_s_h">Check yours in ten minutes</h2>
  <ol>
    <li data-i="pc_s1">Ask the server. Run <code>php -v</code> on the command line, and look at what the website itself uses: the version on a phpinfo page can differ from the command line (php-fpm against CLI). Remove such a page afterwards.</li>
    <li data-i="pc_s2">Read composer.json. The <code>require.php</code> line says what the code expects; composer.lock shows what is really installed.</li>
    <li data-i="pc_s3">Run <code>composer audit</code>. It lists installed packages with known security advisories (Composer 2.4 and newer).</li>
    <li data-i="pc_s4">Run <code>composer outdated --direct</code>. It shows which direct dependencies are behind.</li>
    <li data-i="pc_s5">Look up your framework (Laravel, Symfony, Phalcon or whatever you run) on its own support page and note until when your major version gets fixes.</li>
    <li data-i="pc_s6">Ask when a backup was last restored, not when it was made.</li>
  </ol>

  <h2 data-i="pc_m_h">What the answer means</h2>
  <ul>
    <li data-i="pc_m1">Older than 8.2: no security fixes any more. Plan an upgrade, in steps and with a way back for each step.</li>
    <li data-i="pc_m2">8.2: security fixes until 31 December 2026. That is the coming months, so plan now.</li>
    <li data-i="pc_m3">8.3, 8.4 or 8.5: supported. Then look at the dependencies, because the weak spot is usually a package and not PHP itself.</li>
  </ul>

  <h2 data-i="pc_p_h">From the answer to a plan</h2>
  <p data-i="pc_p">A Health Check lays the runtime, the database and the framework next to their official end-of-life dates, finds dependencies with known vulnerabilities, looks at how secrets are handled and where changing the code is risky, and ends in a written plan in steps with hours per item. Two days, read-only, fixed price EUR 1,080 excl. VAT (12 hours × EUR 90). A free intro call comes first.</p>
  <p><a href="{{ route('scuttle.health-check') }}" data-i="pc_p_a">What it covers and what it costs</a> · <a href="{{ route('scuttle.health-check.sample') }}" data-i="pc_p_b">A sample report</a></p>

  <div class="callout who" role="note">
    <img src="{{ secure_asset('vendor/scuttle/portrait.jpg') }}" alt="Stanislav Plotnikov" width="72" height="72" loading="lazy">
    <div>
      <h2 data-i="pc_w_h">Who writes this</h2>
      <p data-i="pc_w">Stanislav Plotnikov, senior developer and solution architect in Eindhoven. I have been programming since the 2000s, PHP first. Scuttle Development exists since December 2024, KvK 96040947. You talk to the person who does the work.</p>
      <p><a href="{{ route('scuttle.company') }}" data-i="pc_w_a">More about me and how I work</a></p>
    </div>
  </div>

  <h2 data-i="pc_c_h">Want me to look at it?</h2>
  <p data-i="pc_c_p">Tell me which version you run and what hurts. I reply within one working day with a first opinion, free.</p>
  <p><a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="pc_c_b">Book a call</a></p>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json(collect($nl)->map(fn ($v) => preg_replace('/`([^`]+)`/', '<code>$1</code>', $v))->all()));
</script>
@endpush
