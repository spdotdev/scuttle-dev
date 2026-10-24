@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Work')
@section('description', 'What I deliver: twelve fixed deliverables, each one leaving a document in your repository, and the brands and tools behind them.')
@section('canonical', 'https://scuttle.dev/work')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Work</span>
      <h1 data-dec>What I deliver</h1>
      <p class="lead">Services say what I do; this page says what you hold afterwards. Every engagement ends in something you can point at: a report, a running server, a release pipeline, a document in your repository. Twelve of them, each with a fixed shape.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="tiles">
      @foreach($C::all() as $p)
      <a class="tile rv" href="{{ $C::url($p) }}">
        <div class="img"><span class="k">{{ $C::GROUPS[$p['group']] }}</span>@include('scuttle::partials.illus', ['kind' => $p['illus']])</div>
        <h3>{{ $p['name'] }}</h3><p>{{ $p['short'] }}</p>
      </a>
      @endforeach
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot">Left behind, every time</span><h2>What stays in your repository</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b>A runbook</b><p>Deploy, roll back, restore, rotate a secret, add a user. Tested by following it.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Tests around the change</b><p>The old behaviour pinned before anything moves, the new behaviour proven after.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Decision records</b><p>Why it is built this way, so nobody undoes a good decision by accident.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>A pipeline that blocks red</b><p>Lint, static analysis, tests, security gates. Nothing merges when they fail.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Monitoring that reaches a person</b><p>Uptime, disk, certificates, failed jobs, to one phone.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Accounts in your name</b><p>Servers, domains, repositories, model keys. You own them from day one.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    <div class="plate belts rv">
      <div class="bh"><span class="lbl dot">Worked with</span><h2>Brands I have built for, tools I build with</h2><p>In employment at agencies and product companies before 2024, and as a freelancer since. The tools are the ones in daily use, not a wish list.</p></div>
      <div class="belt" aria-label="Companies"><div class="track">
        @php($cos = ['Jumbo', 'Albert Heijn', 'Mijnwebwinkel', '@Leisure Group', 'Getnoticed', 'New Brand Activators', 'Bek 1 op 1', 'Van Os Beheer'])
        @for($r = 0; $r < 2; $r++)@foreach($cos as $co)<span class="tk"><i></i>{{ $co }}</span>@endforeach @endfor
      </div></div>
      <div class="belt rev" aria-label="Tools"><div class="track">
        @php($tools = ['PHP', 'Laravel', 'Phalcon', 'Filament', 'MySQL', 'Redis', 'Docker', 'Ansible', 'GitHub Actions', 'DigitalOcean', 'Caddy', 'Python', 'Kotlin', 'Claude Code', 'MCP', 'n8n', 'Playwright', 'Linux'])
        @for($r = 0; $r < 2; $r++)@foreach($tools as $t)<span class="tk mono{{ in_array($t, ['Claude Code', 'MCP', 'n8n'], true) ? ' ai' : '' }}"><i></i>{{ $t }}</span>@endforeach @endfor
      </div></div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    <span class="lbl dot">Seen in practice</span>
    <div class="cards3" style="margin-top:18px">
      @foreach(array_slice(\Spdotdev\ScuttleDev\Cases::all(), 0, 3) as $c)
      <a class="c rv" href="{{ \Spdotdev\ScuttleDev\Cases::url($c['slug']) }}"><span class="lbl">{{ $c['kicker'] }}</span><h3>{{ $c['title'] }}</h3><p>{{ $c['quote'] }}</p><span class="more">Read the case study →</span></a>
      @endforeach
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>Which of these is missing on your side?</h2>
      <p>A runbook nobody wrote, a restore nobody tested, a pipeline that does not exist yet. Name it and I reply within one working day with what it would take, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a><a class="btn o" href="{{ route('scuttle.pricing') }}">See the pricing</a></div>
    </div>
  </div></section>
</article>
@endsection
