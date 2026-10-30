@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Case studies: work told the way it went')
@section('crumb', 'Case studies')
@section('description', 'Case studies from Scuttle Development: a migration while the platform stayed in daily use, an API that AI assistants can use through an MCP server, a small model trained for a wake word, and a one-person company run by agents.')
@section('canonical', 'https://scuttle.dev/case-studies')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="csi_lbl">Case studies</span>
      <h1 data-i="csi_h1" data-dec>Work, told the way it went</h1>
      <p class="lead" data-i="csi_lead">One client project, anonymized because that is what confidentiality means, and three of my own products, named because they are mine. Each one says what the situation was, what I built and what came out.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\ScuttleDev\Cases::all() as $c)
      <a class="c rv cg" href="{{ \Spdotdev\ScuttleDev\Cases::url($c['slug']) }}">
        <span class="lbl" data-i="csi_{{ $loop->index }}_k">{{ $c['kicker'] }}</span>
        <h2 data-i="csi_{{ $loop->index }}_t">{{ $c['title'] }}</h2>
        <p data-i="csi_{{ $loop->index }}_l">{{ $c['lead'] }}</p>
        <div class="facts3">@foreach($c['facts'] as $fi => $f)<div><b>{{ $f[0] }}</b><span data-i="csi_{{ $loop->parent->index }}_f{{ $fi }}">{{ $f[1] }}</span></div>@endforeach</div>
        <div class="tags">@foreach($c['products'] as $slug)<span>{{ $C::get($slug)['name'] }}</span>@endforeach</div>
        <span class="more" data-i="csi_more">Read the case study →</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="csi_cta_h">Does one of these look like your situation?</h2>
      <p data-i="csi_cta_p">Tell me which one and what is different on your side. I reply within one working day with how I would approach it, free.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}" data-i="csi_cta_b1">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}" data-i="csi_cta_b2">Book a call</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'csi_lbl' => 'Casestudy\'s',
    'csi_h1' => 'Werk, verteld zoals het ging',
    'csi_lead' => 'Eén klantproject, geanonimiseerd omdat dat is wat geheimhouding betekent, en drie eigen producten, bij naam genoemd omdat ze van mij zijn. Elk vertelt wat de situatie was, wat ik bouwde en wat eruit kwam.',
    'csi_more' => 'Lees de casestudy →',
    'csi_cta_h' => 'Lijkt een van deze op jouw situatie?',
    'csi_cta_p' => 'Vertel me welke en wat er bij jou anders is. Ik reageer binnen één werkdag met hoe ik het zou aanpakken, gratis.',
    'csi_cta_b1' => 'Gebruik het contactformulier',
    'csi_cta_b2' => 'Plan een gesprek',
];
foreach (array_values(\Spdotdev\ScuttleDev\Cases::all()) as $i => $c) {
    $nl['csi_'.$i.'_k'] = $c['nl']['kicker'];
    $nl['csi_'.$i.'_t'] = $c['nl']['title'];
    $nl['csi_'.$i.'_l'] = $c['nl']['lead'];
    foreach ($c['nl']['facts'] as $fi => $label) {
        $nl['csi_'.$i.'_f'.$fi] = $label;
    }
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
