@extends('scuttle::layout')

@section('title', 'Nieuws van Scuttle Development')
@section('crumb', 'Nieuws')
@section('description', 'Nieuws van Scuttle Development: wat er in het veld veranderde, wat het voor je software betekent en welke dienst het beantwoordt.')
@section('canonical', 'https://scuttle.dev/news')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Nieuws</span>
      <h1>Nieuws van Scuttle Development</h1>
      <p class="lead">Notities over wat er in het veld veranderde en wat het voor je software betekent. Artikelen die nog alleen in het Engels bestaan, verwijzen naar de Engelse tekst.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(collect(\Spdotdev\ScuttleDev\Posts::all())->sortByDesc('date') as $post)
      @php
        $hasNl = ($post['title_nl'] ?? '') !== '';
        $titleNl = $hasNl ? $post['title_nl'] : $post['title'];
        $summaryNl = ($post['summary_nl'] ?? '') !== '' ? $post['summary_nl'] : $post['summary'];
      @endphp
      <a class="c rv cg" href="{{ url('/news/'.$post['slug']) }}">
        <span class="lbl">{{ $post['date'] }}{{ $hasNl ? '' : ' · Engels' }}</span>
        <h2>{{ $titleNl }}</h2>
        <p>{{ $summaryNl }}</p>
      </a>
      @endforeach
    </div>
  </div>
  <div class="wrap" style="margin-top:32px">
    <p><a class="btn" href="{{ url('/contact') }}">Vertel me wat je draait</a></p>
  </div>
</article>
@endsection
