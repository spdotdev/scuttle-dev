@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Projects')
@section('description', 'Open source projects and own products from Scuttle Development, each with documentation: a field manual skill for codebases, an inventory product with an MCP server, a WhatsApp MCP server, a Telegram relay for Claude Code, and this site.')
@section('canonical', 'https://scuttle.dev/projects')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Projects</span>
      <h1 data-dec>Open source and own products</h1>
      <p class="lead">What I build for myself and publish. Skills that work on every AI coding platform, products that run in production, and the patterns behind the services. Client projects appear here only with the client's written consent.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\ScuttleDev\Projects::all() as $pr)
      <a class="c rv cg" href="{{ route('scuttle.project', ['slug' => $pr['slug']]) }}">
        <span class="lbl">{{ $pr['kind'] }}</span>
        <h3>{{ $pr['name'] }}</h3>
        <p>{{ $pr['short'] }}</p>
        <div class="tags">@foreach($pr['stack'] as $t)<span>{{ $t }}</span>@endforeach</div>
        <span class="more">{{ count($pr['docs']) ? 'Overview and docs →' : 'Overview →' }}</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>Want one of these patterns in your own system?</h2>
      <p>An MCP server on your API, a skill for a chore your team repeats, a documented codebase. Tell me which and I reply within one working day, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}?about=AI%20Integration">Use the contact form</a><a class="btn o" href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div>
    </div>
  </div></section>
</article>
@endsection
