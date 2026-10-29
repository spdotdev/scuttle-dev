@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $pr['name'].' docs')
@section('description', 'Documentation for '.$pr['name'].': '.$pr['short'])
@section('canonical', 'https://scuttle.dev/projects/'.$pr['slug'].'/docs')

@section('content')
<div class="wrap docs">
  <aside class="docs-nav">
    <small data-i="pjd_projects">Projects</small>
    @foreach(\Spdotdev\ScuttleDev\Projects::all() as $o)
      @if(count($o['docs']))<a href="{{ route('scuttle.project.docs', ['slug' => $o['slug']]) }}" @class(['cur' => $o['slug'] === $pr['slug']])>{{ $o['name'] }}</a>@endif
    @endforeach
    <small style="margin-top:18px">{{ $pr['name'] }}</small>
    <a href="{{ route('scuttle.project', ['slug' => $pr['slug']]) }}" data-i="pjd_overview">Overview</a>
    @foreach($pr['docs'] as $sec)<a href="#{{ $sec['id'] }}" class="sub">{{ $sec['title'] }}</a>@endforeach
    <a href="{{ $pr['repo'] }}" rel="noopener" target="_blank" data-i="pjd_repo">Repository ↗</a>
  </aside>

  <article class="docs-main">
    <span class="lbl dot" data-i="pjd_kind">Docs · {{ $pr['kind'] }}</span>
    <h1>{{ $pr['name'] }}</h1>
    <p class="lead" data-i="pjd_lead">{{ $pr['lead'] }}</p>
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
      <p data-i="pjd_callout">Services behind this project: @foreach($pr['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($pr['products']) - 1), @endif @endforeach. Found a mistake? <a href="{{ $pr['repo'] }}/issues" rel="noopener" target="_blank">Open an issue</a>.</p>
    </aside>
  </article>

  <aside class="docs-toc">
    <small data-i="pjd_toc">On this page</small>
    @foreach($pr['docs'] as $sec)<a href="#{{ $sec['id'] }}">{{ $sec['title'] }}</a>@endforeach
  </aside>
</div>
@endsection

@push('nl')
<?php
$n = $pr['nl'];
$names = array_map(fn ($slug) => '<a href="'.e($C::url($C::get($slug))).'">'.e($C::get($slug)['name']).'</a>', $pr['products']);
$nl = [
    'pjd_projects' => 'Projecten',
    'pjd_overview' => 'Overzicht',
    'pjd_repo' => 'Repository ↗',
    'pjd_kind' => 'Documentatie · '.e($n['kind']),
    'pjd_lead' => e($n['lead']).' '.e('De documentatie zelf is in het Engels.'),
    'pjd_callout' => 'Diensten achter dit project: '.implode(', ', $names).'. Een fout gevonden? <a href="'.e($pr['repo']).'/issues" rel="noopener" target="_blank">Open een issue</a>.',
    'pjd_toc' => 'Op deze pagina',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
