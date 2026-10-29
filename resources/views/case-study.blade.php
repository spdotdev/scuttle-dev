@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', $c['title'])
@section('description', 'Case study: '.$c['lead'])
@section('canonical', 'https://scuttle.dev/case-studies/'.$c['slug'])

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="csp_lbl">Case study · {{ $c['kicker'] }}</span>
  <h1 data-i="csp_h1">{{ $c['title'] }}</h1>
  <p class="lead" data-i="csp_lead">{{ $c['lead'] }}</p>

  <div class="facts">
    @foreach($c['facts'] as $fi => $f)<div><b>{{ $f[0] }}</b><span data-i="csp_f{{ $fi }}">{{ $f[1] }}</span></div>@endforeach
  </div>

  <h2 data-i="csp_sit_h">The situation</h2>
  <p data-i="csp_sit">{{ $c['situation'] }}</p>

  <h2 data-i="csp_app_h">The approach</h2>
  <ul>@foreach($c['approach'] as $ai => $a)<li data-i="csp_a{{ $ai }}">{{ $a }}</li>@endforeach</ul>

  <h2 data-i="csp_res_h">The result</h2>
  <ul>@foreach($c['result'] as $ri => $r)<li data-i="csp_r{{ $ri }}">{{ $r }}</li>@endforeach</ul>

  <h2 data-i="csp_tech_h">Technology</h2>
  <p data-i="csp_stack">{{ $c['stack'] }}</p>

  <aside class="callout">
    <p data-i="csp_callout">The products behind this: @foreach($c['products'] as $i => $slug)<a href="{{ $C::url($C::get($slug)) }}">{{ $C::get($slug)['name'] }}</a>@if($i < count($c['products']) - 1), @endif @endforeach. Want to talk about something like it? <a href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($C::get($c['products'][0])['name']) }}">Use the contact form</a> or <a href="{{ config('scuttle-dev.booking_url') }}">book a call</a>.</p>
  </aside>

  <p class="updated" style="margin-top:32px" data-i="csp_more">More case studies: @foreach(\Spdotdev\ScuttleDev\Cases::all() as $o)@if($o['slug'] !== $c['slug'])<a href="{{ \Spdotdev\ScuttleDev\Cases::url($o['slug']) }}">{{ $o['title'] }}</a> · @endif @endforeach</p>
</article>
@endsection

@push('nl')
<?php
$nlc = $c['nl'];
$nl = [
    'csp_lbl' => 'Casestudy · '.e($nlc['kicker']),
    'csp_h1' => e($nlc['title']),
    'csp_lead' => e($nlc['lead']),
    'csp_sit_h' => 'De situatie',
    'csp_sit' => e($nlc['situation']),
    'csp_app_h' => 'De aanpak',
    'csp_res_h' => 'Het resultaat',
    'csp_tech_h' => 'Technologie',
    'csp_stack' => e($nlc['stack']),
];
foreach ($nlc['facts'] as $fi => $label) {
    $nl['csp_f'.$fi] = e($label);
}
foreach ($nlc['approach'] as $ai => $t) {
    $nl['csp_a'.$ai] = e($t);
}
foreach ($nlc['result'] as $ri => $t) {
    $nl['csp_r'.$ri] = e($t);
}
$names = array_map(fn ($slug) => '<a href="'.e($C::url($C::get($slug))).'">'.e($C::get($slug)['name']).'</a>', $c['products']);
$nl['csp_callout'] = 'De producten erachter: '.implode(', ', $names).'. Wil je praten over iets vergelijkbaars? <a href="'.e(route('scuttle.contact.page').'?about='.rawurlencode($C::get($c['products'][0])['name'])).'">Gebruik het contactformulier</a> of <a href="'.e(config('scuttle-dev.booking_url')).'">plan een gesprek</a>.';
$others = [];
foreach (\Spdotdev\ScuttleDev\Cases::all() as $o) {
    if ($o['slug'] !== $c['slug']) {
        $others[] = '<a href="'.e(\Spdotdev\ScuttleDev\Cases::url($o['slug'])).'">'.e($o['nl']['title']).'</a> · ';
    }
}
$nl['csp_more'] = 'Meer casestudy\'s: '.implode('', $others);
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
