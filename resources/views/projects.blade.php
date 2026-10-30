@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Projects: open source and own products')
@section('crumb', 'Projects')
@section('description', 'Open source projects and own products from Scuttle Development, each with documentation: a field manual skill for codebases, an inventory product with an MCP server, a WhatsApp MCP server, a Telegram relay for Claude Code, and this site.')
@section('canonical', 'https://scuttle.dev/projects')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="pj_lbl">Projects</span>
      <h1 data-i="pj_h1" data-dec>Open source, and the products behind the services</h1>
      <p class="lead" data-i="pj_lead">Everything here is open source or runs on open source I publish: skills that work on every AI coding platform, MCP servers, an inventory product, this site. Each page is an overview with documentation, so you can see how I build before you hire me. Client projects appear here only with the client's written consent; more personal projects are on <a href="https://splotnikov.dev/projects" rel="noopener" target="_blank">splotnikov.dev</a>.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\ScuttleDev\Projects::all() as $pr)
      <a class="c rv cg" href="{{ route('scuttle.project', ['slug' => $pr['slug']]) }}">
        <span class="lbl" data-i="pj_{{ $loop->index }}_k">{{ $pr['kind'] }}</span>
        <h2>{{ $pr['name'] }}</h2>
        <p data-i="pj_{{ $loop->index }}_s">{{ $pr['short'] }}</p>
        <div class="tags">@foreach($pr['stack'] as $t)<span>{{ $t }}</span>@endforeach</div>
        <span class="more" data-i="{{ count($pr['docs']) ? 'pj_more_d' : 'pj_more' }}">{{ count($pr['docs']) ? 'Overview and docs →' : 'Overview →' }}</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="pj_cta_h">Want one of these patterns in your own system?</h2>
      <p data-i="pj_cta_p">An MCP server on your API, a skill for a chore your team repeats, a documented codebase. Tell me which and I reply within one working day, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}?about=AI%20Integration" data-i="pj_cta_b1">Use the contact form</a><a class="btn o" href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'pj_lbl' => 'Projecten',
    'pj_h1' => 'Open source, en de producten achter de diensten',
    'pj_lead' => 'Alles hier is open source of draait op open source die ik publiceer: skills die op elk AI-codeerplatform werken, MCP-servers, een voorraadproduct, deze site. Elke pagina is een overzicht met documentatie, zodat je ziet hoe ik bouw voordat je me inhuurt. Klantprojecten staan hier alleen met schriftelijke toestemming van de klant; meer persoonlijke projecten staan op <a href="https://splotnikov.dev/projects" rel="noopener" target="_blank">splotnikov.dev</a>.',
    'pj_more_d' => 'Overzicht en documentatie →',
    'pj_more' => 'Overzicht →',
    'pj_cta_h' => 'Wil je een van deze patronen in je eigen systeem?',
    'pj_cta_p' => 'Een MCP-server op je API, een skill voor een klus die je team herhaalt, een gedocumenteerde codebase. Vertel me welke en ik reageer binnen één werkdag, gratis.',
    'pj_cta_b1' => 'Gebruik het contactformulier',
];
foreach (array_values(\Spdotdev\ScuttleDev\Projects::all()) as $i => $pr) {
    $nl['pj_'.$i.'_k'] = $pr['nl']['kind'];
    $nl['pj_'.$i.'_s'] = $pr['nl']['short'];
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
