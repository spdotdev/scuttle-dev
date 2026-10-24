@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $c['title'])
@section('description', 'Case study: '.$c['lead'])
@section('canonical', 'https://scuttle.dev/case-studies/'.$c['slug'])

@section('content')
<article class="wrap page">
  <span class="lbl dot">Case study · {{ $c['kicker'] }}</span>
  <h1>{{ $c['title'] }}</h1>
  <p class="lead">{{ $c['lead'] }}</p>

  <div class="facts">
    @foreach($c['facts'] as $f)<div><b>{{ $f[0] }}</b>{{ $f[1] }}</div>@endforeach
  </div>

  <h2>The situation</h2>
  <p>{{ $c['situation'] }}</p>

  <h2>The approach</h2>
  <ul>@foreach($c['approach'] as $a)<li>{{ $a }}</li>@endforeach</ul>

  <h2>The result</h2>
  <ul>@foreach($c['result'] as $r)<li>{{ $r }}</li>@endforeach</ul>

  <h2>Technology</h2>
  <p>{{ $c['stack'] }}</p>

  <aside class="callout">
    <p>The products behind this: @foreach($c['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($c['products']) - 1), @endif @endforeach. Want to talk about something like it? <a href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($C::get($c['products'][0])['name']) }}">Use the contact form</a> or <a href="{{ config('scuttle-dev.booking_url') }}">book a call</a>.</p>
  </aside>

  <p class="updated" style="margin-top:32px">More case studies: @foreach(\Spdotdev\ScuttleDev\Cases::all() as $o)@if($o['slug'] !== $c['slug'])<a href="{{ \Spdotdev\ScuttleDev\Cases::url($o['slug']) }}">{{ $o['title'] }}</a> · @endif @endforeach</p>
</article>
@endsection
