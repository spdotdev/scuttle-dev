<!doctype html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privacyverklaring | Scuttle Development</title>
  <meta name="description" content="Welke gegevens Scuttle Development gebruikt, waarom, en hoe u zich afmeldt of uw gegevens laat verwijderen." />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://scuttle.dev/privacy" />
  <meta name="theme-color" content="#5454D4" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('vendor/scuttle/favicon-32x32.png') }}" />
  <style>
    :root { color-scheme: dark; }
    body { margin: 0; background: #111118; color: #d8d8e4; font: 16px/1.65 system-ui, -apple-system, "Segoe UI", sans-serif; }
    main { max-width: 760px; margin: 0 auto; padding: 48px 20px 64px; }
    a { color: #8f8ff0; }
    h1 { color: #fff; font-size: 2rem; margin: 0 0 4px; }
    h2 { color: #fff; font-size: 1.2rem; margin: 32px 0 8px; }
    .updated { color: #8a8a9c; font-size: .9rem; margin: 0 0 24px; }
    ul { padding-left: 1.2em; }
    .back { display: inline-block; margin-bottom: 24px; }
    .en { margin-top: 48px; padding-top: 24px; border-top: 1px solid #2a2a38; color: #a8a8b8; }
  </style>
</head>

<body>
  <main>
    <a class="back" href="{{ route('scuttle.home') }}">&larr; scuttle.dev</a>
    <h1>Privacyverklaring</h1>
    <p class="updated">Laatst bijgewerkt: 28 september 2026</p>

    <h2>Wie ben ik</h2>
    <p>Scuttle Development is de eenmanszaak van Stanislav Plotnikov, freelance developer en software architect in
      Eindhoven. Voor alles over uw gegevens kunt u mij mailen op <a href="mailto:stas@scuttle.dev">stas@scuttle.dev</a>.</p>

    <h2>Welke gegevens ik gebruik</h2>
    <p>Om bedrijven te benaderen die een developer kunnen gebruiken, verzamel ik openbare zakelijke gegevens:</p>
    <ul>
      <li>bedrijfsnaam, website, vestigingsplaats en branche;</li>
      <li>een algemeen zakelijk e-mailadres (zoals info@) en telefoonnummer;</li>
      <li>gegevens uit het openbare KvK-register en openbare vacatures.</li>
    </ul>
    <p>Als u mij mailt of op een e-mail van mij reageert, bewaar ik die berichten. Ik verzamel geen gevoelige
      persoonsgegevens.</p>

    <h2>Waarom</h2>
    <p>Ik gebruik deze gegevens om een bedrijf eenmalig een e-mail te sturen over mijn diensten (softwareontwikkeling,
      modernisering van bestaande software, automatisering en beveiliging) en om reacties op te volgen. De grondslag is
      mijn gerechtvaardigd belang om zakelijke klanten te werven (artikel 6 lid 1 onder f AVG).</p>

    <h2>Afmelden</h2>
    <p>Elke e-mail bevat een afmeldlink. U kunt ook gewoon antwoorden dat u geen interesse heeft, of mailen naar
      <a href="mailto:stas@scuttle.dev">stas@scuttle.dev</a>. Daarna krijgt u van mij geen e-mail meer. Na een afmelding
      bewaar ik alleen het e-mailadres en het domein op een blokkeerlijst, zodat u niet opnieuw benaderd wordt.</p>

    <h2>Hoe lang</h2>
    <p>Ik bewaar de gegevens zolang ze nodig zijn voor dit doel. Vraagt u om verwijdering, dan verwijder ik uw gegevens
      direct, op de blokkeerlijst na.</p>

    <h2>Met wie ik gegevens deel</h2>
    <p>Ik verkoop geen gegevens. Voor mijn werk gebruik ik deze diensten, die gegevens alleen in mijn opdracht verwerken:</p>
    <ul>
      <li>DigitalOcean: de server waarop mijn systemen draaien staat in Amsterdam;</li>
      <li>Google: voor e-mail, voor back-ups op mijn eigen Google Drive, en voor het samenvatten van openbare
        bedrijfsinformatie met Google Gemini.</li>
    </ul>

    <h2>Uw rechten</h2>
    <p>U kunt uw gegevens inzien, laten corrigeren of laten verwijderen, en bezwaar maken tegen het gebruik ervan. Mail
      daarvoor naar <a href="mailto:stas@scuttle.dev">stas@scuttle.dev</a>; u krijgt binnen een maand antwoord. Bent u
      het niet eens met hoe ik met uw gegevens omga, dan kunt u een klacht indienen bij de
      <a href="https://autoriteitpersoonsgegevens.nl" rel="noopener">Autoriteit Persoonsgegevens</a>.</p>

    <h2>Deze website</h2>
    <p>scuttle.dev gebruikt geen tracking- of analysecookies. Er wordt alleen een technisch noodzakelijk sessiecookie
      gezet.</p>

    <h2>Google-koppeling voor back-ups</h2>
    <p>Ik gebruik een eigen Google Cloud-app ("rclone") alleen om mijn eigen back-ups naar mijn eigen Google Drive te
      kopi&euml;ren. Die app heeft geen toegang tot gegevens van anderen en deelt niets met derden.</p>

    <div class="en">
      <h2>In English</h2>
      <p>Scuttle Development (Stanislav Plotnikov, Eindhoven, Netherlands) uses public business contact details to send
        a single business email about software development services, based on legitimate interest (GDPR art. 6(1)(f)).
        Every email has an unsubscribe link; after you opt out you are not emailed again. For access, correction or
        deletion, email <a href="mailto:stas@scuttle.dev">stas@scuttle.dev</a>. Processors: DigitalOcean (server in
        Amsterdam) and Google (email, backups to my own Google Drive, Gemini for summarising public company
        information). The "rclone" Google Cloud app only copies my own backups to my own Google Drive. This site sets no
        tracking or analytics cookies.</p>
    </div>
  </main>
</body>

</html>
