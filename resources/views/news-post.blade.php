@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $post['title'])
@section('description', $post['summary'])
@section('canonical', 'https://scuttle.dev/news/'.$post['slug'])
@section('ogtype', 'article')

@section('head')
  <script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@type' => 'Article', 'headline' => \Illuminate\Support\Str::limit($post['title'], 108, ''), 'description' => $post['summary'], 'datePublished' => $post['date'], 'mainEntityOfPage' => 'https://scuttle.dev/news/'.$post['slug'], 'image' => secure_asset('vendor/scuttle/og-image.png'), 'author' => ['@type' => 'Person', 'name' => 'Stanislav Plotnikov'], 'publisher' => ['@type' => 'Organization', 'name' => 'Scuttle Development', 'url' => 'https://scuttle.dev']], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endsection

@section('content')
<article class="wrap page">
  <span class="lbl dot"><time datetime="{{ $post['date'] }}" data-i="blp_date">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time> · {{ implode(' · ', $post['tags']) }}</span>
  <h1 data-i="blp_h1">{{ $post['title'] }}</h1>
  <p class="lead" data-i="blp_lead">{{ $post['summary'] }}</p>
  <span data-i="blp_note" style="display:block"></span>

  @php($list = false)
  @foreach($post['body'] as $line)
    @if(str_starts_with($line, '- '))
      @if(!$list)<ul>@php($list = true)@endif
      <li>{{ substr($line, 2) }}</li>
    @else
      @if($list)</ul>@php($list = false)@endif
      @if(str_starts_with($line, '## '))
        <h2>{{ substr($line, 3) }}</h2>
      @else
        <p>{{ $line }}</p>
      @endif
    @endif
  @endforeach
  @if($list)</ul>@endif

  @php($sold = $C::get($post['sells']))
  <p class="updated" style="margin-top:32px"><span data-i="blp_about">This article is about:</span> <a href="{{ $C::url($sold) }}">{{ $sold['name'] }}</a></p>

  <aside class="callout">
    <p><span data-i="blp_rel">Related:</span> @foreach($post['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($post['products']) - 1), @endif @endforeach. <span data-i="blp_q">Questions about your own situation go through the <a href="{{ route('scuttle.contact.page') }}">contact form</a>.</span></p>
  </aside>

  <p class="updated" style="margin-top:32px"><span data-i="blp_more">More news:</span> @foreach(\Spdotdev\ScuttleDev\Posts::all() as $o)@if($o['slug'] !== $post['slug'])<a href="{{ route('scuttle.news.post', ['slug' => $o['slug']]) }}">{{ $o['title'] }}</a> · @endif @endforeach <a href="{{ route('scuttle.news') }}" data-i="blp_all">all news</a></p>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'blp_note' => 'Dit artikel is in het Engels.',
    'blp_h1' => e($post['nl']['title']),
    'blp_lead' => e($post['nl']['summary']),
    'blp_date' => \Carbon\Carbon::parse($post['date'])->locale('nl')->translatedFormat('j M Y'),
    'blp_rel' => 'Gerelateerd:',
    'blp_q' => 'Vragen over je eigen situatie lopen via het <a href="'.e(route('scuttle.contact.page')).'">contactformulier</a>.',
    'blp_more' => 'Meer nieuws:',
    'blp_all' => 'al het nieuws',
    'blp_about' => 'Dit artikel gaat over:',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
