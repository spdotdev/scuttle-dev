<!doctype html>
<html lang="@yield('lang', 'en')">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>@yield('title') | Scuttle Development</title>
  <meta name="description" content="@yield('description')" />
  <meta name="author" content="Scuttle Development" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="@yield('canonical')" />
  <meta name="theme-color" content="#5454D4" />

  <link rel="icon" type="image/svg+xml" href="{{ secure_asset('vendor/scuttle/icon.svg') }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('vendor/scuttle/favicon-32x32.png') }}" />
  <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('vendor/scuttle/favicon-16x16.png') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('vendor/scuttle/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ secure_asset('vendor/scuttle/site.webmanifest') }}" />

  <meta property="og:type" content="@yield('ogtype', 'article')" />
  <meta property="og:url" content="@yield('canonical')" />
  <meta property="og:title" content="@yield('title') | Scuttle Development" />
  <meta property="og:description" content="@yield('description')" />
  <meta property="og:image" content="{{ secure_asset('vendor/scuttle/og-image.png') }}" />
  <meta property="og:site_name" content="Scuttle Development" />
  <meta name="twitter:card" content="summary_large_image" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Geist+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ secure_asset('vendor/scuttle/site.css') }}" />
  @yield('head')
</head>

<body>
  <nav class="top" aria-label="Main navigation"><div class="wrap">
    <a class="brand" href="{{ route('scuttle.home') }}" aria-label="Scuttle Development home"><img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="30" height="24"><span>Scuttle</span></a>
    <div class="links" id="links">
      @hasSection('homelinks')
        @yield('homelinks')
      @else
        <a href="{{ route('scuttle.home') }}#services" data-i="n_services">Services</a>
        <a href="{{ route('scuttle.health-check') }}" data-i="n_hc">Health Check</a>
        <a href="{{ route('scuttle.case-study') }}" data-i="n_case">Case study</a>
        <a href="{{ route('scuttle.home') }}#contact" data-i="n_contact">Contact</a>
      @endif
    </div>
    <div class="right">
      @hasSection('homelinks')
      <div class="seg" role="group" aria-label="Language"><button id="en" aria-pressed="true" type="button">EN</button><button id="nl" aria-pressed="false" type="button">NL</button></div>
      @endif
      <button class="ic" id="theme" aria-label="Toggle light and dark" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg></button>
      <a class="btn cta" href="{{ config('scuttle-dev.booking_url') }}" data-i="n_book">Book a call</a>
      <button class="ic" id="burger" aria-label="Menu" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
    </div>
  </div></nav>

  <main id="top">
    @yield('content')
  </main>

  <footer><div class="wrap">
    <div class="col"><div class="brand"><img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="30" height="24"><span>Scuttle</span></div><p style="max-width:24em;font-size:13.5px;color:var(--muted)" data-i="ft_p">Legacy PHP modernization and security, by one senior developer in Eindhoven.</p></div>
    <div class="col"><small data-i="ft_site">Site</small><a href="{{ route('scuttle.home') }}#services" data-i="n_services">Services</a><a href="{{ route('scuttle.health-check') }}" data-i="n_hc">Health Check</a><a href="{{ route('scuttle.case-study') }}" data-i="n_case">Case study</a><a href="{{ route('scuttle.home') }}#contact" data-i="n_contact">Contact</a></div>
    <div class="col"><small data-i="ft_company">Company</small><a href="{{ route('scuttle.home') }}#about" data-i="ft_about">About</a><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a><a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a></div>
    <div class="col"><small data-i="ft_legal">Legal</small><a href="{{ route('scuttle.privacy') }}" data-i="ft_privacy">Privacy</a><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}" data-i="ft_terms">Terms and conditions</a><a href="{{ route('scuttle.legal', ['name' => 'ALGEMENE_VOORWAARDEN']) }}">Algemene voorwaarden</a><a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">NDA</a></div>
    <div class="bottom"><span>© {{ date('Y') }} SCUTTLE DEVELOPMENT · KVK 96040947 · BTW NL005184584B85</span><span>EINDHOVEN, NL</span></div>
  </div></footer>

  <script>
    window.SCUTTLE_NL = { t: { n_services: "Diensten", n_hc: "Health Check", n_case: "Casestudy", n_contact: "Contact", n_book: "Plan een gesprek", ft_p: "Modernisering en beveiliging van legacy PHP, door één senior developer in Eindhoven.", ft_site: "Site", ft_company: "Bedrijf", ft_about: "Over mij", ft_legal: "Juridisch", ft_privacy: "Privacy", ft_terms: "Terms and conditions" }, ph: {} };
  </script>
  @stack('nl')
  <script src="{{ secure_asset('vendor/scuttle/site.js') }}" defer></script>
</body>

</html>
