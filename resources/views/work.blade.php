@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Work')
@section('description', 'What I deliver: twelve fixed deliverables, each one leaving a document in your repository, and the brands and tools behind them.')
@section('canonical', 'https://scuttle.dev/work')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="wk_l">Work</span>
      <h1 data-dec data-i="wk_h1">What I deliver</h1>
      <p class="lead" data-i="wk_lead">Services say what I do; this page says what you hold afterwards. Every engagement ends in something you can point at: a report, a running server, a release pipeline, a document in your repository. Twelve of them, each with a fixed shape.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="tiles">
      @foreach($C::all() as $p)
      <a class="tile rv" href="{{ $C::url($p) }}">
        <div class="img"><span class="k" data-i="g_{{ $p['group'] }}">{{ $C::GROUPS[$p['group']] }}</span>@include('scuttle::partials.illus', ['kind' => $p['illus']])</div>
        <h3>{{ $p['name'] }}</h3><p>{{ $p['short'] }}</p>
      </a>
      @endforeach
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot" data-i="wk_d_l">Left behind, every time</span><h2 data-i="wk_d_h">What stays in your repository</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b data-i="wk_f1b">A runbook</b><p data-i="wk_f1p">Deploy, roll back, restore, rotate a secret, add a user. Tested by following it.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="wk_f2b">Tests around the change</b><p data-i="wk_f2p">The old behaviour pinned before anything moves, the new behaviour proven after.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="wk_f3b">Decision records</b><p data-i="wk_f3p">Why it is built this way, so nobody undoes a good decision by accident.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="wk_f4b">A pipeline that blocks red</b><p data-i="wk_f4p">Lint, static analysis, tests, security gates. Nothing merges when they fail.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="wk_f5b">Monitoring that reaches a person</b><p data-i="wk_f5p">Uptime, disk, certificates, failed jobs, to one phone.</p></div></div>
        <div class="f"><i class="lt"></i><div><b data-i="wk_f6b">Accounts in your name</b><p data-i="wk_f6p">Servers, domains, repositories, model keys. You own them from day one.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    <div class="plate belts rv">
      <div class="bh"><span class="lbl dot" data-i="wk_w_l">Worked with</span><h2 data-i="wk_w_h">Brands I have built for, tools I build with</h2><p data-i="wk_w_p">In employment at agencies and product companies before 2024, and as a freelancer since. The tools are the ones in daily use, not a wish list.</p></div>
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
    <span class="lbl dot" data-i="wk_s_l">Seen in practice</span>
    <div class="cards3" style="margin-top:18px">
      @foreach(array_slice(\Spdotdev\ScuttleDev\Cases::all(), 0, 3) as $c)
      <a class="c rv" href="{{ \Spdotdev\ScuttleDev\Cases::url($c['slug']) }}"><span class="lbl">{{ $c['kicker'] }}</span><h3>{{ $c['title'] }}</h3><p>{{ $c['quote'] }}</p><span class="more" data-i="wk_more">Read the case study →</span></a>
      @endforeach
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="wk_e_h">Which of these is missing on your side?</h2>
      <p data-i="wk_e_p">A runbook nobody wrote, a restore nobody tested, a pipeline that does not exist yet. Name it and I reply within one working day with what it would take, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}"><span data-i="wk_e_b1">Use the contact form</span></a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}"><span data-i="wk_e_b2">Book a call</span></a><a class="btn o" href="{{ route('scuttle.pricing') }}"><span data-i="wk_e_b3">See the pricing</span></a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
 "wk_l": "Werk",
 "wk_h1": "Wat ik oplever",
 "wk_lead": "Diensten zeggen wat ik doe; deze pagina zegt wat je daarna in handen hebt. Elke opdracht eindigt in iets waar je naar kunt wijzen: een rapport, een draaiende server, een releasepipeline, een document in je repository. Twaalf stuks, elk met een vaste vorm.",
 "wk_d_l": "Elke keer achtergelaten",
 "wk_f1b": "Een runbook",
 "wk_f2b": "Tests rond de wijziging",
 "wk_f3b": "Besluitenlogboek",
 "wk_f4b": "Een pipeline die op rood blokkeert",
 "wk_f5b": "Monitoring die een mens bereikt",
 "wk_f6b": "Accounts op jouw naam",
 "wk_w_l": "Gewerkt met",
 "wk_s_l": "In de praktijk gezien",
 "wk_more": "Lees de casestudy →",
 "wk_e_h": "Welke hiervan ontbreekt bij jou?",
 "wk_e_p": "Een runbook dat niemand schreef, een restore die niemand testte, een pipeline die nog niet bestaat. Noem het en ik reageer binnen één werkdag met wat het kost, gratis.",
 "wk_e_b1": "Gebruik het contactformulier",
 "wk_e_b2": "Plan een gesprek",
 "wk_e_b3": "Bekijk de prijzen",
 "wk_d_h": "Wat er in je repository blijft",
 "wk_f1p": "Deployen, terugdraaien, herstellen, een secret roteren, een gebruiker toevoegen. Getest door het te volgen.",
 "wk_f2p": "Het oude gedrag vastgelegd voordat er iets beweegt, het nieuwe gedrag bewezen erna.",
 "wk_f3p": "Waarom het zo is gebouwd, zodat niemand per ongeluk een goed besluit terugdraait.",
 "wk_f4p": "Lint, statische analyse, tests, securitychecks. Niets wordt gemerged als ze falen.",
 "wk_f5p": "Uptime, schijf, certificaten, mislukte jobs, naar één telefoon.",
 "wk_f6p": "Servers, domeinen, repositories, modelsleutels. Je bent er vanaf dag één eigenaar van.",
 "wk_w_h": "Merken waarvoor ik heb gebouwd, tools waarmee ik bouw",
 "wk_w_p": "In loondienst bij bureaus en productbedrijven vóór 2024, en sindsdien als freelancer. De tools zijn die van dagelijks gebruik, geen wensenlijst."
});
</script>
@endpush
