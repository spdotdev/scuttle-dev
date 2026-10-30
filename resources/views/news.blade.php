@extends('scuttle::layout')

@section('title', 'News')
@section('description', 'News from Scuttle Development: what changed in the field, what it means for your software, and which service answers it.')
@section('canonical', 'https://scuttle.dev/news')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="bl_lbl">News</span>
      <h1 data-i="bl_h1" data-dec>News from Scuttle Development</h1>
      <p class="lead" data-i="bl_lead">Notes on what changed in the field and what it means for your software. Each one points to the service that answers it, with its price on the page. Written by the person who does the work.</p>
    </div>
  </header>

  @php($A = \Spdotdev\ScuttleDev\Archive::class)
  @php($all = \Spdotdev\ScuttleDev\Posts::all())
  @php($period = preg_match('/^\d{4}(-\d{2}){0,2}$|^\d{4}-W\d{2}$/', (string) request('p')) ? (string) request('p') : '')
  @php([$posts, $page, $pages] = $A::page($A::filter($all, $period), request('page')))
  <div class="wrap arch-grid">
    @include('scuttle::partials.archive', ['A' => $A, 'ns' => 'scuttle', 'base' => route('scuttle.news'), 'tree' => $A::tree($all), 'period' => $period, 'page' => $page, 'pages' => $pages, 'prefix' => 'bl', 'allLabel' => 'All articles'])
    <div class="posts">
      @if($period !== '')<p class="arch-cur"><span data-i="bl_showing">Showing</span> <b>{{ $A::label($period) }}</b> · <a href="{{ route('scuttle.news') }}" data-i="bl_all2">all articles</a></p>@endif
      @if(! count($posts))<p class="arch-empty" data-i="bl_none">Nothing on this page.</p>@endif
      @foreach($posts as $post)
      <a class="post-card rv" href="{{ route('scuttle.news.post', ['slug' => $post['slug']]) }}">
        <div class="meta"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time><span>{{ implode(' · ', $post['tags']) }}</span></div>
        <h3 data-i="bl_t_{{ str_replace('-', '_', $post['slug']) }}">{{ $post['title'] }}</h3>
        <p data-i="bl_s_{{ str_replace('-', '_', $post['slug']) }}">{{ $post['summary'] }}</p>
        <span class="more" data-i="bl_more">Read →</span>
      </a>
      @endforeach
      @include('scuttle::partials.pager', ['base' => route('scuttle.news'), 'period' => $period, 'page' => $page, 'pages' => $pages, 'prefix' => 'bl'])
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="bl_cta_h">A question this news does not answer?</h2>
      <p data-i="bl_cta_p">Ask it. If it needs a proper look before I answer, the first reply says what I will look at.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}" data-i="bl_cta_b1">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}" data-i="bl_cta_b2">Book a call</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'bl_lbl' => 'Nieuws',
    'bl_h1' => 'Nieuws van Scuttle Development',
    'bl_lead' => 'Notities over wat er veranderd is in het vak en wat dat voor je software betekent. Elke notitie wijst naar de dienst die het antwoord is, met de prijs erbij. Geschreven door degene die het werk doet.',
    'bl_more' => 'Lees →',
    'bl_cta_h' => 'Een vraag die dit nieuws niet beantwoordt?',
    'bl_cta_p' => 'Stel hem gerust. Als er eerst goed naar gekeken moet worden voordat ik antwoord, zegt het eerste antwoord waar ik naar ga kijken.',
    'bl_cta_b1' => 'Gebruik het contactformulier',
    'bl_cta_b2' => 'Plan een gesprek',
];
foreach (\Spdotdev\ScuttleDev\Posts::all() as $post) {
    $k = str_replace('-', '_', $post['slug']);
    $nl['bl_t_'.$k] = e($post['nl']['title']);
    $nl['bl_s_'.$k] = e($post['nl']['summary']);
}
foreach (\Spdotdev\ScuttleDev\Archive::tree(\Spdotdev\ScuttleDev\Posts::all()) as $y => $months) {
    foreach (array_keys($months) as $m) {
        $nl['bl_m_'.$y.'_'.$m] = \Carbon\Carbon::create((int) $y, (int) $m, 1)->locale('nl')->translatedFormat('F');
    }
}
foreach (range(1, 12) as $mo) {
    $nl['bl_cm_'.sprintf('%02d', $mo)] = \Carbon\Carbon::create(2026, $mo, 1)->locale('nl')->translatedFormat('F');
}
$nl += ['bl_wk' => 'wk', 'bl_wd0' => 'M', 'bl_wd1' => 'D', 'bl_wd2' => 'W', 'bl_wd3' => 'D', 'bl_wd4' => 'V', 'bl_wd5' => 'Z', 'bl_wd6' => 'Z'];
$nl += ['bl_arch' => 'Archief', 'bl_all' => 'Alle artikelen', 'bl_showing' => 'Je ziet', 'bl_all2' => 'alle artikelen', 'bl_none' => 'Niets op deze pagina.', 'bl_prev' => '← Nieuwer', 'bl_page' => 'Pagina', 'bl_next' => 'Ouder →'];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
