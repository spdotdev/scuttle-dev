@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $p['name'])
@section('description', $p['short'].' '.$p['price'].'.')
@section('canonical', $C::canonical($p))

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">{{ $C::GROUPS[$p['group']] }} · {{ sprintf('%02d', array_search($p['slug'], array_keys($C::all()), true) + 1) }}</span>
      <h1 data-dec>{{ $p['name'] }}</h1>
      <p class="lead">{{ $p['lead'] }}</p>
      <div class="cta">
        <a class="btn" href="mailto:info@scuttle.dev?subject={{ rawurlencode($p['name']) }}">Ask about {{ $p['name'] }} <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <a class="btn o" href="{{ route('scuttle.pricing') }}">How I price</a>
      </div>
      <p class="price"><b>{{ $p['price'] }}.</b> {{ $p['price_note'] }}</p>
    </div>
  </header>

  <div class="wrap">
    <div class="plate hero-plate rv">
      <div class="mock">
        <div class="bar"><i></i><i></i><i></i><span>scuttle · {{ \Illuminate\Support\Str::slug($p['name']) }}</span></div>
        <div class="body">
          <div class="art">@include('scuttle::partials.illus', ['kind' => $p['illus']])</div>
          <ul class="feat-mini">
            @foreach(array_slice($p['features'], 0, 4) as $f)
            <li><i class="lt"></i><span>{{ $f[0] }}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot">What you get</span><h2>One engagement. Clear deliverables.</h2></div>
      <div class="feats">
        @foreach($p['features'] as $f)
        <div class="f"><i class="lt"></i><div><b>{{ $f[0] }}</b><p>{{ $f[1] }}</p></div></div>
        @endforeach
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot">How it works</span>
      <ol class="steps">
        @foreach($p['steps'] as $i => $st)
        <li><span>{{ sprintf('%02d', $i + 1) }}</span><p>{{ $st }}</p></li>
        @endforeach
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot">What it is not</span>
      <p class="not">{{ $p['not'] }}</p>
      <span class="lbl dot" style="margin-top:28px">Terms in short</span>
      <ul class="terms">
        <li>Amounts excl. VAT, quote valid 14 days.</li>
        <li>Payment within 14 days, acceptance within 7 days.</li>
        <li>Rights pass to you on full payment.</li>
        <li><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}">General terms</a> and <a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">NDA</a> as PDF.</li>
      </ul>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    @php($cs = collect(\Spdotdev\ScuttleDev\Cases::all())->first(fn ($c) => in_array($p['slug'], $c['products'], true)) ?? \Spdotdev\ScuttleDev\Cases::get('phalcon-to-laravel'))
    <figure class="quote rv">
      <blockquote>{{ $cs['quote'] }}</blockquote>
      <figcaption><b>{{ $cs['kicker'] }}</b><span><a href="{{ \Spdotdev\ScuttleDev\Cases::url($cs['slug']) }}">Read the case study: {{ $cs['title'] }}</a></span></figcaption>
    </figure>
  </div></section>

  @if($p['slug'] === 'ai-integration')
    @include('scuttle::partials.ai-extra')
  @endif

  @if(count($related))
  <section class="s tight"><div class="wrap">
    <span class="lbl dot">Also in {{ $C::GROUPS[$p['group']] }}</span>
    <div class="cards3" style="margin-top:18px">
      @foreach($related as $r)
      <a class="c rv" href="{{ $C::url($r) }}"><span class="lbl">{{ $C::GROUPS[$r['group']] }}</span><h3>{{ $r['name'] }}</h3><p>{{ $r['short'] }}</p><span class="more">Read more →</span></a>
      @endforeach
    </div>
  </div></section>
  @endif

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>{{ $p['cta'][0] }}</h2>
      <p>{{ $p['cta'][1] }}</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($p['name']) }}">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev?subject={{ rawurlencode($p['name']) }}">Email info@scuttle.dev</a></div>
    </div>
  </div></section>
</article>
@endsection
