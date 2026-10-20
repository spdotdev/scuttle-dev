<!doctype html>
<html lang="en" data-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | Scuttle Development</title>
  <meta name="description" content="@yield('description')" />
  <meta name="author" content="Scuttle Development" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="@yield('canonical')" />
  <meta name="theme-color" content="#5454D4" />

  <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('vendor/scuttle/favicon-32x32.png') }}" />
  <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('vendor/scuttle/favicon-16x16.png') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('vendor/scuttle/apple-touch-icon.png') }}" />

  <meta property="og:type" content="article" />
  <meta property="og:url" content="@yield('canonical')" />
  <meta property="og:title" content="@yield('title') | Scuttle Development" />
  <meta property="og:description" content="@yield('description')" />
  <meta property="og:image" content="{{ secure_asset('vendor/scuttle/og-image.png') }}" />
  <meta property="og:site_name" content="Scuttle Development" />
  <meta name="twitter:card" content="summary_large_image" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="{{ secure_asset('vendor/scuttle/style.css') }}" />
  <link rel="stylesheet" href="{{ secure_asset('vendor/scuttle/page.css') }}" />
</head>

<body>
  <header role="banner">
    <a href="/" class="logo" aria-label="Scuttle Development Home">
      <img src="{{ secure_asset('vendor/scuttle/logo-bw.png') }}" alt="Scuttle Development Logo" width="40" height="40">
    </a>
    <nav role="navigation" aria-label="Main navigation">
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="{{ route('scuttle.health-check') }}">Health Check</a></li>
        <li><a href="{{ route('scuttle.case-study') }}">Case study</a></li>
        <li><a href="mailto:info@scuttle.dev">Contact</a></li>
      </ul>
      <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode" type="button"></button>
    </nav>
  </header>

  <main class="page">
    @yield('content')
  </main>

  <footer class="page-footer">
    <p>&copy; 2026 Scuttle Development, Eindhoven.
      <a href="mailto:info@scuttle.dev">info@scuttle.dev</a>
      <a href="{{ route('scuttle.privacy') }}">Privacy</a></p>
  </footer>

  <script src="{{ secure_asset('vendor/scuttle/page.js') }}" defer></script>
</body>

</html>
