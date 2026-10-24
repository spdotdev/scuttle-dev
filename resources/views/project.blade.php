@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $pr['name'])
@section('description', $pr['short'])
@section('canonical', 'https://scuttle.dev/projects/'.$pr['slug'])

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">{{ $pr['kind'] }}</span>
      <h1 data-dec>{{ $pr['name'] }}</h1>
      <p class="lead">{{ $pr['lead'] }}</p>
      <div class="cta">
        @if(count($pr['docs']))<a class="btn" href="{{ route('scuttle.project.docs', ['slug' => $pr['slug']]) }}">Read the docs <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>@endif
        <a class="btn o" href="{{ $pr['repo'] }}" rel="noopener" target="_blank">Repository on GitHub</a>
      </div>
      <p class="price"><b>Status.</b> {{ $pr['status'] }}</p>
    </div>
  </header>

  <div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot">Stack</span>
      <div class="tags big" style="margin-top:16px">@foreach($pr['stack'] as $t)<span>{{ $t }}</span>@endforeach</div>
    </div>
    <div class="plate rv">
      <span class="lbl dot">The services behind it</span>
      <ul class="terms" style="margin-top:16px">
        @foreach($pr['products'] as $slug)<li><a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>: {{ $C::get($slug)['short'] }}</li>@endforeach
      </ul>
    </div>
  </div>

  @if(count($pr['docs']))
  <section class="s tight"><div class="wrap">
    <span class="lbl dot">In the docs</span>
    <div class="cards3" style="margin-top:18px">
      @foreach(array_slice($pr['docs'], 0, 3) as $sec)
      <a class="c rv" href="{{ route('scuttle.project.docs', ['slug' => $pr['slug']]) }}#{{ $sec['id'] }}"><span class="lbl">Docs</span><h3>{{ $sec['title'] }}</h3><p>{{ \Illuminate\Support\Str::limit(strip_tags($sec['body'][0]), 120) }}</p><span class="more">Read →</span></a>
      @endforeach
    </div>
  </div></section>
  @endif

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>Want this pattern on your side?</h2>
      <p>Tell me what you run and I reply within one working day with how {{ $pr['name'] }}'s approach would fit, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($C::get($pr['products'][0])['name']) }}">Use the contact form</a><a class="btn o" href="{{ route('scuttle.projects') }}">All projects</a></div>
    </div>
  </div></section>
</article>
@endsection
