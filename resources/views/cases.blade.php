@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Case studies')
@section('description', 'Case studies from Scuttle Development: a migration while the platform stayed in daily use, an API that AI assistants can use through an MCP server, a small model trained for a wake word, and a one-person company run by agents.')
@section('canonical', 'https://scuttle.dev/case-studies')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Case studies</span>
      <h1 data-dec>Work, told the way it went</h1>
      <p class="lead">One client project, anonymized because that is what confidentiality means, and three of my own products, named because they are mine. Each one says what the situation was, what I built and what came out.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\ScuttleDev\Cases::all() as $c)
      <a class="c rv cg" href="{{ \Spdotdev\ScuttleDev\Cases::url($c['slug']) }}">
        <span class="lbl">{{ $c['kicker'] }}</span>
        <h3>{{ $c['title'] }}</h3>
        <p>{{ $c['lead'] }}</p>
        <div class="facts3">@foreach($c['facts'] as $f)<div><b>{{ $f[0] }}</b><span>{{ $f[1] }}</span></div>@endforeach</div>
        <div class="tags">@foreach($c['products'] as $slug)<span>{{ $C::get($slug)['name'] }}</span>@endforeach</div>
        <span class="more">Read the case study →</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>Does one of these look like your situation?</h2>
      <p>Tell me which one and what is different on your side. I reply within one working day with how I would approach it, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a></div>
    </div>
  </div></section>
</article>
@endsection
