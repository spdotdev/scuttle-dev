@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $post['title'])
@section('description', $post['summary'])
@section('canonical', 'https://scuttle.dev/blog/'.$post['slug'])

@section('content')
<article class="wrap page">
  <span class="lbl dot"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time> · {{ implode(' · ', $post['tags']) }}</span>
  <h1>{{ $post['title'] }}</h1>
  <p class="lead">{{ $post['summary'] }}</p>

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
    <p>Related: @foreach($post['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($post['products']) - 1), @endif @endforeach. Questions about your own situation go through the <a href="{{ route('scuttle.contact.page') }}">contact form</a>.</p>
  </aside>

  <p class="updated" style="margin-top:32px">More notes: @foreach(\Spdotdev\ScuttleDev\Posts::all() as $o)@if($o['slug'] !== $post['slug'])<a href="{{ route('scuttle.post', ['slug' => $o['slug']]) }}">{{ $o['title'] }}</a> · @endif @endforeach <a href="{{ route('scuttle.blog') }}">all posts</a></p>
</article>
@endsection
