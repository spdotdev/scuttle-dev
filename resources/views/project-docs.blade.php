@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $pr['name'].' docs')
@section('description', 'Documentation for '.$pr['name'].': '.$pr['short'])
@section('canonical', 'https://scuttle.dev/projects/'.$pr['slug'].'/docs')

@section('content')
<div class="wrap docs">
  <aside class="docs-nav">
    <small>Projects</small>
    @foreach(\Spdotdev\ScuttleDev\Projects::all() as $o)
      @if(count($o['docs']))<a href="{{ route('scuttle.project.docs', ['slug' => $o['slug']]) }}" @class(['cur' => $o['slug'] === $pr['slug']])>{{ $o['name'] }}</a>@endif
    @endforeach
    <small style="margin-top:18px">{{ $pr['name'] }}</small>
    <a href="{{ route('scuttle.project', ['slug' => $pr['slug']]) }}">Overview</a>
    @foreach($pr['docs'] as $sec)<a href="#{{ $sec['id'] }}" class="sub">{{ $sec['title'] }}</a>@endforeach
    <a href="{{ $pr['repo'] }}" rel="noopener" target="_blank">Repository ↗</a>
  </aside>

  <article class="docs-main">
    <span class="lbl dot">Docs · {{ $pr['kind'] }}</span>
    <h1>{{ $pr['name'] }}</h1>
    <p class="lead">{{ $pr['lead'] }}</p>
    @foreach($pr['docs'] as $sec)
      <section id="{{ $sec['id'] }}">
        <h2>{{ $sec['title'] }} <a class="anchor" href="#{{ $sec['id'] }}" aria-label="Link to this section">#</a></h2>
        @php($list = false)
        @foreach($sec['body'] as $line)
          @if(str_starts_with($line, '- '))
            @if(!$list)<ul>@php($list = true)@endif
            <li>{{ substr($line, 2) }}</li>
          @else
            @if($list)</ul>@php($list = false)@endif
            @if(str_starts_with($line, '```'))
              <pre><code>{{ str_replace('\\n', "\n", trim($line, '`')) }}</code></pre>
            @else
              <p>{{ $line }}</p>
            @endif
          @endif
        @endforeach
        @if($list)</ul>@endif
      </section>
    @endforeach
    <aside class="callout">
      <p>Services behind this project: @foreach($pr['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($pr['products']) - 1), @endif @endforeach. Found a mistake? <a href="{{ $pr['repo'] }}/issues" rel="noopener" target="_blank">Open an issue</a>.</p>
    </aside>
  </article>

  <aside class="docs-toc">
    <small>On this page</small>
    @foreach($pr['docs'] as $sec)<a href="#{{ $sec['id'] }}">{{ $sec['title'] }}</a>@endforeach
  </aside>
</div>
@endsection
