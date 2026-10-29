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

  <div class="wrap">
    <div class="posts">
      @foreach(\Spdotdev\ScuttleDev\Posts::all() as $post)
      <a class="post-card rv" href="{{ route('scuttle.news.post', ['slug' => $post['slug']]) }}">
        <div class="meta"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time><span>{{ implode(' · ', $post['tags']) }}</span></div>
        <h3 data-i="bl_{{ $loop->index }}_t">{{ $post['title'] }}</h3>
        <p data-i="bl_{{ $loop->index }}_s">{{ $post['summary'] }}</p>
        <span class="more" data-i="bl_more">Read →</span>
      </a>
      @endforeach
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
foreach (array_values(\Spdotdev\ScuttleDev\Posts::all()) as $i => $post) {
    $nl['bl_'.$i.'_t'] = e($post['nl']['title']);
    $nl['bl_'.$i.'_s'] = e($post['nl']['summary']);
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
