<!doctype html>
<html lang="@yield('lang', 'en')">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  @php
    // Search results cut titles near 60 characters and descriptions near 160: keep both inside, at a word boundary.
    $rawTitle = trim(preg_replace('/\s+/', ' ', $__env->yieldContent('title')));
    $seoTitle = mb_strlen($rawTitle.' | Scuttle Development') <= 62 ? $rawTitle.' | Scuttle Development' : $rawTitle;
    if (mb_strlen($seoTitle) > 70) { $seoTitle = rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($seoTitle, 0, 68)), " ,.:;-").'…'; }
    $rawDesc = trim(preg_replace('/\s+/', ' ', html_entity_decode($__env->yieldContent('description'))));
    $seoDesc = $rawDesc;
    if (mb_strlen($rawDesc) > 160) {
        $cut = mb_substr($rawDesc, 0, 160);
        $seoDesc = preg_match('/^(.{80,}(?<!excl)(?<!e\.g)[.!?])\s/us', $cut, $m) ? $m[1] : rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($rawDesc, 0, 157)), " ,.:;-").'…';
    }
    $canon = trim($__env->yieldContent('canonical'));
    $crumbName = trim($__env->yieldContent('crumb'));
    $enPath = '/'.trim((string) parse_url($canon, PHP_URL_PATH), '/');
    $isNl = request()->is('nl') || request()->is('nl/*');
    $paired = \Spdotdev\ScuttleDev\Dutch::paired($enPath);
  @endphp
  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDesc }}" />
  <meta name="author" content="Scuttle Development" />
  <meta name="robots" content="@yield('robots', 'index, follow')" />
  <link rel="canonical" href="@yield('canonical')" />
  @if($paired)
  <link rel="alternate" hreflang="en" href="{{ \Spdotdev\ScuttleDev\Dutch::enUrl($enPath) }}" />
  <link rel="alternate" hreflang="nl" href="{{ \Spdotdev\ScuttleDev\Dutch::nlUrl($enPath) }}" />
  <link rel="alternate" hreflang="x-default" href="{{ \Spdotdev\ScuttleDev\Dutch::enUrl($enPath) }}" />
  @endif
  <meta name="theme-color" content="#f5f5f5" />

  <link rel="icon" type="image/svg+xml" href="{{ secure_asset('vendor/scuttle/icon.svg') }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('vendor/scuttle/favicon-32x32.png') }}" />
  <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('vendor/scuttle/favicon-16x16.png') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('vendor/scuttle/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ secure_asset('vendor/scuttle/site.webmanifest') }}" />

  <meta property="og:type" content="@yield('ogtype', 'website')" />
  <meta property="og:url" content="{{ $canon }}" />
  <meta property="og:title" content="{{ $seoTitle }}" />
  <meta property="og:description" content="{{ $seoDesc }}" />
  <meta property="og:image" content="{{ \Spdotdev\ScuttleDev\Dutch::ogImage($enPath) }}" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="Scuttle Development: automation, AI and security for business software" />
  <meta property="og:site_name" content="Scuttle Development" />
  <meta property="og:locale" content="en_GB" />
  <meta property="og:locale:alternate" content="nl_NL" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $seoTitle }}" />
  <meta name="twitter:description" content="{{ $seoDesc }}" />
  <meta name="twitter:image" content="{{ \Spdotdev\ScuttleDev\Dutch::ogImage($enPath) }}" />
  @php
    // BreadcrumbList for every inner page: Home, the section it lives in, the page.
    $path = trim(parse_url($canon, PHP_URL_PATH) ?: '', '/');
    if ($path !== '') {
        $segs = explode('/', $path); $crumbs = [['Home', 'https://scuttle.dev/']]; $acc = '';
        $names = ['case-studies' => 'Case studies', 'news' => 'News', 'projects' => 'Projects', 'services' => 'Services', 'docs' => 'Docs'];
        foreach ($segs as $i => $seg) {
            $acc .= '/'.$seg; $last = $i === count($segs) - 1;
            if ($seg === 'services') continue;
            $crumbs[] = [$last ? ($crumbName ?: $rawTitle) : ($names[$seg] ?? \Illuminate\Support\Str::headline($seg)), 'https://scuttle.dev'.$acc];
        }
        $ld = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array_map(fn ($c, $k) => ['@type' => 'ListItem', 'position' => $k + 1, 'name' => $c[0], 'item' => $c[1]], $crumbs, array_keys($crumbs))];
    }
  @endphp
  @isset($ld)<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>@endisset

  <link rel="preload" href="{{ secure_asset('vendor/scuttle/fonts/geist-latin.woff2') }}" as="font" type="font/woff2" crossorigin />
  <link rel="preload" href="{{ secure_asset('vendor/scuttle/fonts/geist-mono-latin.woff2') }}" as="font" type="font/woff2" crossorigin />
  <link rel="stylesheet" href="{{ secure_asset('vendor/scuttle/site.css') }}" />
  <script>try{var t=localStorage.getItem('theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-theme',t)}catch(e){}</script>
  @yield('head')
</head>

<body>
  @php($groups = \Spdotdev\ScuttleDev\Catalog::grouped())
  <a class="skip" href="#top" data-i="skip">Skip to content</a>
  <nav class="top" aria-label="Main navigation"><div class="wrap">
    <a class="brand" href="{{ route('scuttle.home') }}" aria-label="Scuttle Development home">@include('scuttle::partials.logo', ['id' => 'nav'])<span data-scr="Scuttle">Scuttle</span></a>
    <div class="links" id="links">
      <div class="has-mega" id="has-mega">
        <button type="button" id="mega-btn" aria-expanded="false" aria-controls="mega"><span data-i="n_products">Services</span><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></button>
        <div class="mega" id="mega">
          @foreach($groups as $key => $items)
          <div class="col">
            <small data-i="g_{{ $key }}">{{ \Spdotdev\ScuttleDev\Catalog::GROUPS[$key] }}</small>
            @foreach($items as $p)
            <a href="{{ \Spdotdev\ScuttleDev\Catalog::url($p) }}">{{ $p['name'] }}</a>
            @endforeach
          </div>
          @endforeach
          <a class="all" href="{{ route('scuttle.work') }}" data-i="n_all">All services, and what you hold afterwards →</a>
        </div>
      </div>
      <a href="{{ route('scuttle.pricing') }}" data-i="n_pricing">Pricing</a>
      <a href="{{ route('scuttle.cases') }}" data-i="n_case">Case studies</a>
      <a href="{{ route('scuttle.company') }}" data-i="n_company">Company</a>
      <a href="{{ route('scuttle.contact.page') }}" data-i="n_contact">Contact</a>
    </div>
    <div class="right">
      @if($paired)
      <div class="seg" role="group" aria-label="Language"><a id="en" href="{{ \Spdotdev\ScuttleDev\Dutch::enUrl($enPath) }}" hreflang="en" lang="en" @if(! $isNl) aria-current="true" @endif>EN</a><a id="nl" href="{{ \Spdotdev\ScuttleDev\Dutch::nlUrl($enPath) }}" hreflang="nl" lang="nl" @if($isNl) aria-current="true" @endif>NL</a></div>
      @else
      <div class="seg" role="group" aria-label="Language"><button id="en" aria-pressed="true" type="button">EN</button><button id="nl" aria-pressed="false" type="button">NL</button></div>
      @endif
      <div class="seg th" role="group" aria-label="Theme"><button type="button" data-th="system" aria-pressed="true" title="System"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg></button><button type="button" data-th="light" aria-pressed="false" title="Light"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg></button><button type="button" data-th="dark" aria-pressed="false" title="Dark"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg></button></div>
      <a class="btn cta" href="{{ config('scuttle-dev.booking_url') }}" data-i="n_book">Book a call</a>
      <button class="ic" id="burger" aria-label="Menu" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
    </div>
  </div></nav>

  <main id="top">
    @yield('content')
  </main>

  <div class="wrap"><footer>
    <div class="col"><div class="brand"><img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="30" height="24"><span>Scuttle</span></div><p data-i="ft_p">Automation with AI agents and integrations, secure software that keeps running, by one senior developer in Eindhoven.</p></div>
    <div class="col"><small data-i="n_products">Services</small>
      @foreach($groups as $key => $items)@foreach(array_slice($items, 0, 2) as $p)<a href="{{ \Spdotdev\ScuttleDev\Catalog::url($p) }}">{{ $p['name'] }}</a>@endforeach @endforeach
      <a href="{{ route('scuttle.pricing') }}" data-i="n_pricing">Pricing</a></div>
    <div class="col"><small data-i="ft_company">Company</small><a href="{{ route('scuttle.company') }}" data-i="n_company">Company</a><a href="{{ route('scuttle.cases') }}" data-i="n_case">Case studies</a><a href="{{ route('scuttle.projects') }}" data-i="n_projects">Projects</a><a href="{{ route('scuttle.news') }}" data-i="n_news">News</a><a href="{{ route('scuttle.contact.page') }}" data-i="n_contact">Contact</a><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div>
    <div class="col"><small data-i="ft_legal">Legal</small><a href="{{ route('scuttle.privacy') }}" data-i="ft_privacy">Privacy</a><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}" data-i="ft_terms">Terms and conditions</a><a href="{{ route('scuttle.legal', ['name' => 'ALGEMENE_VOORWAARDEN']) }}">Algemene voorwaarden</a><a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">NDA</a></div>
    <div class="bottom"><span>© {{ date('Y') }} SCUTTLE DEVELOPMENT · KVK 96040947 · BTW NL005184584B85</span><span>EINDHOVEN, NL</span></div>
  </footer></div>

  <script>
    window.SCUTTLE_NL = { t: { skip: "Naar de inhoud", n_all: "Alle diensten, en wat je daarna in handen hebt →", n_products: "Diensten", n_projects: "Projecten", n_news: "Nieuws", n_company: "Bedrijf", g_assess: "Beoordelen", g_build: "Automatiseren", g_run: "Beheren", n_pricing: "Prijzen", n_work: "Werk", n_case: "Casestudy's", n_contact: "Contact", n_book: "Plan een gesprek", ft_p: "Automatisering met AI-agents en koppelingen, veilige software die blijft draaien, door één senior developer in Eindhoven.", ft_company: "Bedrijf", ft_about: "Over mij", ft_legal: "Juridisch", ft_privacy: "Privacy", ft_terms: "Terms and conditions", ck_p: "Deze website gebruikt één soort cookie: Google Analytics, om te zien welke pagina's gelezen worden. Er wordt niets gezet voordat je kiest. Weiger en de site werkt precies hetzelfde; ga akkoord en het analytics-script laadt, onder de voorwaarden in de <a href=\"/privacy\">privacyverklaring</a>.", ck_deny: "Weigeren", ck_agree: "Akkoord" }, ph: {} };
  </script>
  @stack('nl')
  @if(config('scuttle-dev.ga_id'))
  <div class="cookie" id="cookie" role="dialog" aria-live="polite" aria-label="Cookies" hidden>
    <p data-i="ck_p">This website uses one cookie set: Google Analytics, to see which pages are read. Nothing is set before you choose. Deny and the site works exactly the same; agree and the analytics script loads, under the conditions in the <a href="{{ route('scuttle.privacy') }}">privacy statement</a>.</p>
    <div class="row"><button type="button" class="btn o" id="ck-deny" data-i="ck_deny">Deny</button><button type="button" class="btn" id="ck-agree" data-i="ck_agree">Agree</button></div>
  </div>
  <script>window.SCUTTLE_GA = @json(config('scuttle-dev.ga_id'));</script>
  @endif
  <script src="{{ secure_asset('vendor/scuttle/site.js') }}" defer></script>
</body>

</html>
