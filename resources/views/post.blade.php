@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $post['title'])
@section('description', $post['summary'])
@section('canonical', 'https://scuttle.dev/blog/'.$post['slug'])

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

  <aside class="callout">
    <p><span data-i="blp_rel">Related:</span> @foreach($post['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($post['products']) - 1), @endif @endforeach. <span data-i="blp_q">Questions about your own situation go through the <a href="{{ route('scuttle.contact.page') }}">contact form</a>.</span></p>
  </aside>

  <p class="updated" style="margin-top:32px"><span data-i="blp_more">More notes:</span> @foreach(\Spdotdev\ScuttleDev\Posts::all() as $o)@if($o['slug'] !== $post['slug'])<a href="{{ route('scuttle.post', ['slug' => $o['slug']]) }}">{{ $o['title'] }}</a> · @endif @endforeach <a href="{{ route('scuttle.blog') }}" data-i="blp_all">all posts</a></p>
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
    'blp_more' => 'Meer notities:',
    'blp_all' => 'alle berichten',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
