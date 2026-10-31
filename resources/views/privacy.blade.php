@extends('scuttle::layout')

@section('title', 'Privacy statement')
@section('description', 'What data Scuttle Development uses, why, which processors see it, that this site sets no tracking or analytics cookies, and how you opt out or have your data removed.')
@section('canonical', 'https://scuttle.dev/privacy')

@section('content')
<article class="wrap page">
    <h1 data-i="pv_h1">Privacy statement</h1>
    <p class="updated" data-i="pv_upd">Last updated: 31 October 2026</p>

    <h2 data-i="pv_h_who">Who I am</h2>
    <p data-i="pv_who">Scuttle Development is the sole proprietorship of Stanislav Plotnikov, freelance developer and software architect in Eindhoven, the Netherlands. For anything about your data, use the <a href="{{ route('scuttle.contact.page') }}">contact form</a>.</p>

    <h2 data-i="pv_h_what">What data I use</h2>
    <p data-i="pv_what">To approach companies that could use a developer, I collect public business data:</p>
    <ul data-i="pv_what_l">
      <li>company name, website, place of business and sector;</li>
      <li>a general business email address (such as info@) and phone number;</li>
      <li>data from the public Chamber of Commerce register and public job postings.</li>
    </ul>
    <p data-i="pv_what2">If you email me or reply to an email of mine, I keep those messages. If you write through the contact form, I store your name, email address, subject and message on my own server, so a message is not lost when email fails, together with a keyed hash of your IP address (not the address itself) to keep abuse in check. I collect no sensitive personal data.</p>

    <h2 data-i="pv_h_why">Why</h2>
    <p data-i="pv_why">I use this data to send a company one email about my services (software development, modernization of existing software, automation and security) and to follow up on replies. The legal basis is my legitimate interest in finding business customers (article 6(1)(f) GDPR).</p>

    <h2 data-i="pv_h_cookies">Cookies and statistics</h2>
    @if(config('scuttle-dev.analytics_key'))
    <p data-i="pv_cookies">This site sets no tracking or analytics cookies and loads no third-party analytics. Reading the pages sets no cookie at all and stores nothing in your browser. Only the contact page sets a technically necessary session cookie, which protects the form against forged requests and is gone after two hours.</p>
    <p data-i="pv_stats">I count visits with a small tool that runs on my own server, so no other company sees them. For each page you open it records: the page address, the website you came from (the site name only, not the full address), the campaign tags in the link you used (utm_source, utm_medium, utm_campaign), the kind of device, browser and system, clicks on links that leave this site, the contact form being sent, and the time. It does not store your IP address or your full browser identification. To tell two visits of the same day apart, the server makes a short code from your IP address and browser details with a random key; that key is deleted after two days, so the code cannot be traced back to you and cannot be linked to a visit on another day. If your browser sends Do Not Track or Global Privacy Control, nothing is recorded. Single visits are kept for 30 days; the daily totals that remain contain no personal data. The basis is my legitimate interest in knowing which pages are useful (article 6(1)(f) GDPR). The requests for this counting are left out of the server access log.</p>
    @else
    <p data-i="pv_cookies">This site sets no tracking or analytics cookies and loads no third-party analytics. Reading the pages sets no cookie at all. Only the contact page sets a technically necessary session cookie, which protects the form against forged requests and is gone after two hours. Should I ever add visitor statistics, the site will ask for your consent first, nothing will be set before you choose, and this statement will say what is measured and why.</p>
    @endif

    <h2 data-i="pv_h_out">Opting out</h2>
    <p data-i="pv_out">Every email contains an unsubscribe link. You can also simply reply that you are not interested, or send a message through the <a href="{{ route('scuttle.contact.page') }}">contact form</a>. After that you receive no more email from me. After an opt-out I keep only the email address and the domain on a block list, so you are not approached again.</p>

    <h2 data-i="pv_h_long">How long</h2>
    <p data-i="pv_long">I keep the data as long as it is needed for this purpose. If you ask for removal, I remove your data at once, except the block list.</p>

    <h2 data-i="pv_h_share">Who I share data with</h2>
    <p data-i="pv_share">I sell no data. For my work I use these services, which process data only on my instructions:</p>
    <ul data-i="pv_share_l">
      <li>DigitalOcean: the server my systems run on is in Amsterdam;</li>
      <li>Google: for email and for backups to my own Google Drive;</li>
      <li>Anthropic: the Claude models that summarise public company information and draft the first version of an email, which I review before anything is sent.</li>
    </ul>

    <h2 data-i="pv_h_rights">Your rights</h2>
    <p data-i="pv_rights">You can view, correct or remove your data, and object to its use. Use the <a href="{{ route('scuttle.contact.page') }}">contact form</a>; you get an answer within a month. If you disagree with how I handle your data, you can complain to the Dutch Data Protection Authority, the <a href="https://autoriteitpersoonsgegevens.nl" rel="noopener">Autoriteit Persoonsgegevens</a>.</p>

    <h2 data-i="pv_h_rclone">The Google connection for backups</h2>
    <p data-i="pv_rclone">I use an own Google Cloud app ("rclone") only to copy my own backups to my own Google Drive. That app has no access to other people's data and shares nothing with third parties.</p>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'pv_h1' => 'Privacyverklaring',
    'pv_upd' => 'Laatst bijgewerkt: 31 oktober 2026',
    'pv_h_who' => 'Wie ik ben',
    'pv_who' => 'Scuttle Development is de eenmanszaak van Stanislav Plotnikov, freelance developer en software architect in Eindhoven. Voor alles over je gegevens gebruik je het <a href="'.route('scuttle.contact.page').'">contactformulier</a>.',
    'pv_h_what' => 'Welke gegevens ik gebruik',
    'pv_what' => 'Om bedrijven te benaderen die een developer kunnen gebruiken, verzamel ik openbare zakelijke gegevens:',
    'pv_what_l' => '<li>bedrijfsnaam, website, vestigingsplaats en branche;</li><li>een algemeen zakelijk e-mailadres (zoals info@) en telefoonnummer;</li><li>gegevens uit het openbare KvK-register en openbare vacatures.</li>',
    'pv_what2' => 'Als je me mailt of op een e-mail van mij reageert, bewaar ik die berichten. Als je via het contactformulier schrijft, sla ik je naam, e-mailadres, onderwerp en bericht op mijn eigen server op, zodat een bericht niet verloren gaat als e-mail uitvalt, samen met een gehashte vorm van je IP-adres (niet het adres zelf) om misbruik te beperken. Ik verzamel geen gevoelige persoonsgegevens.',
    'pv_h_why' => 'Waarom',
    'pv_why' => 'Ik gebruik deze gegevens om een bedrijf eenmalig een e-mail te sturen over mijn diensten (softwareontwikkeling, modernisering van bestaande software, automatisering en beveiliging) en om reacties op te volgen. De grondslag is mijn gerechtvaardigd belang om zakelijke klanten te werven (artikel 6 lid 1 onder f AVG).',
    'pv_h_cookies' => 'Cookies en statistieken',
    'pv_cookies' => config('scuttle-dev.analytics_key')
        ? 'Deze site zet geen tracking- of analysecookies en laadt geen analytics van derden. Het lezen van de pagina\'s zet helemaal geen cookie en slaat niets op in je browser. Alleen de contactpagina zet een technisch noodzakelijke sessiecookie, die het formulier beschermt tegen vervalste verzoeken en na twee uur verdwijnt.'
        : 'Deze site zet geen tracking- of analysecookies en laadt geen analytics van derden. Het lezen van de pagina\'s zet helemaal geen cookie. Alleen de contactpagina zet een technisch noodzakelijke sessiecookie, die het formulier beschermt tegen vervalste verzoeken en na twee uur verdwijnt. Mocht ik ooit bezoekersstatistieken toevoegen, dan vraagt de site eerst om je toestemming, wordt er niets gezet voordat je kiest, en zegt deze verklaring wat er gemeten wordt en waarom.',
    'pv_stats' => 'Ik tel bezoeken met een klein programma dat op mijn eigen server draait, dus geen ander bedrijf ziet ze. Per pagina die je opent legt het vast: het adres van de pagina, de website waar je vandaan kwam (alleen de sitenaam, niet het volledige adres), de campagnelabels in de link die je gebruikte (utm_source, utm_medium, utm_campaign), het soort apparaat, browser en systeem, klikken op links die deze site verlaten, het versturen van het contactformulier en het tijdstip. Je IP-adres en je volledige browseridentificatie worden niet bewaard. Om twee bezoeken op dezelfde dag uit elkaar te houden maakt de server een korte code van je IP-adres en browsergegevens met een willekeurige sleutel; die sleutel wordt na twee dagen verwijderd, waardoor de code niet naar jou terug te leiden is en niet gekoppeld kan worden aan een bezoek op een andere dag. Stuurt je browser Do Not Track of Global Privacy Control mee, dan wordt er niets vastgelegd. Losse bezoeken bewaar ik 30 dagen; de dagtotalen die blijven bevatten geen persoonsgegevens. De grondslag is mijn gerechtvaardigd belang om te weten welke pagina\'s nuttig zijn (artikel 6, lid 1, onder f AVG). De verzoeken voor deze telling worden niet in het toegangslogboek van de server opgenomen.',
    'pv_h_out' => 'Afmelden',
    'pv_out' => 'Elke e-mail bevat een afmeldlink. Je kunt ook gewoon antwoorden dat je geen interesse hebt, of een bericht sturen via het <a href="'.route('scuttle.contact.page').'">contactformulier</a>. Daarna krijg je van mij geen e-mail meer. Na een afmelding bewaar ik alleen het e-mailadres en het domein op een blokkeerlijst, zodat je niet opnieuw benaderd wordt.',
    'pv_h_long' => 'Hoe lang',
    'pv_long' => 'Ik bewaar de gegevens zolang ze nodig zijn voor dit doel. Vraag je om verwijdering, dan verwijder ik je gegevens direct, op de blokkeerlijst na.',
    'pv_h_share' => 'Met wie ik gegevens deel',
    'pv_share' => 'Ik verkoop geen gegevens. Voor mijn werk gebruik ik deze diensten, die gegevens alleen in mijn opdracht verwerken:',
    'pv_share_l' => '<li>DigitalOcean: de server waarop mijn systemen draaien staat in Amsterdam;</li><li>Google: voor e-mail en voor back-ups op mijn eigen Google Drive;</li><li>Anthropic: de Claude-modellen die openbare bedrijfsinformatie samenvatten en de eerste versie van een e-mail opstellen, die ik nakijk voordat er iets verstuurd wordt.</li>',
    'pv_h_rights' => 'Je rechten',
    'pv_rights' => 'Je kunt je gegevens inzien, laten corrigeren of laten verwijderen, en bezwaar maken tegen het gebruik ervan. Gebruik daarvoor het <a href="'.route('scuttle.contact.page').'">contactformulier</a>; je krijgt binnen een maand antwoord. Ben je het niet eens met hoe ik met je gegevens omga, dan kun je een klacht indienen bij de <a href="https://autoriteitpersoonsgegevens.nl" rel="noopener">Autoriteit Persoonsgegevens</a>.',
    'pv_h_rclone' => 'Google-koppeling voor back-ups',
    'pv_rclone' => 'Ik gebruik een eigen Google Cloud-app ("rclone") alleen om mijn eigen back-ups naar mijn eigen Google Drive te kopiëren. Die app heeft geen toegang tot gegevens van anderen en deelt niets met derden.',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
