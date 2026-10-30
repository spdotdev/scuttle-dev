@extends('scuttle::layout')
@php
$C = \Spdotdev\ScuttleDev\Catalog::class;
$idx = sprintf('%02d', array_search($p['slug'], array_keys($C::all()), true) + 1);
$nl = $C::nl($p) + [
    'p_grp' => $C::GROUPS_NL[$p['group']].' · '.$idx,
    'p_ask' => 'Vraag naar '.$p['name'],
    'p_samp' => 'Zo ziet het rapport eruit',
    'p_phpc' => 'Check zelf je PHP-versie',
    'p_price_btn' => 'Zo reken ik',
    'p_what' => 'Wat je krijgt',
    'p_h2' => 'Eén opdracht. Duidelijke opleveringen.',
    'p_how' => 'Hoe het werkt',
    'p_notl' => 'Wat het niet is',
    'p_terms' => 'Voorwaarden in het kort',
    'p_t1' => 'Bedragen excl. btw, offerte 14 dagen geldig.',
    'p_t2' => 'Betaling binnen 14 dagen, acceptatie binnen 7 dagen.',
    'p_t3' => 'De rechten gaan na volledige betaling naar jou over.',
    'p_t4a' => 'Algemene voorwaarden',
    'p_t4b' => 'en',
    'p_t4c' => 'als pdf.',
    'p_form' => 'Gebruik het contactformulier',
    'p_book' => 'Plan een gesprek',
    'p_mail' => 'Mail info@scuttle.dev',
    'p_also' => 'Ook bij '.$C::GROUPS_NL[$p['group']],
    'p_more' => 'Lees meer →',
];
$faq = \Spdotdev\ScuttleDev\Faq::for($p['slug']);
$nl += ['p_faq_l' => 'Vragen', 'p_faq_h' => 'Vragen die ik krijg'] + \Spdotdev\ScuttleDev\Faq::nl($p['slug']);
foreach ($related as $r) {
    $nl['p_rs_'.str_replace('-', '_', $r['slug'])] = $r['nl']['short'];
}
@endphp

@section('title', $C::SEO_TITLES[$p['slug']] ?? $p['name'])
@section('crumb', $p['name'])
@section('description', $p['short'].' '.$p['price'].'.')
@section('canonical', $C::canonical($p))

@section('head')
  <script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@type' => 'Service', 'name' => $p['name'], 'description' => $p['short'], 'url' => $C::canonical($p), 'serviceType' => $p['name'], 'provider' => ['@type' => 'ProfessionalService', 'name' => 'Scuttle Development', 'url' => 'https://scuttle.dev']], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
  @if(count($faq))
  @php($faqNl = request()->is('nl') || request()->is('nl/*'))
  <script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $faqNl ? $f['qn'] : $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faqNl ? $f['an'] : $f['a']]], $faq)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
  @endif
@endsection

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="p_grp">{{ $C::GROUPS[$p['group']] }} · {{ $idx }}</span>
      <h1 data-dec>{{ $p['name'] }}</h1>
      <p class="lead" data-i="p_lead">{{ $p['lead'] }}</p>
      <p class="price"><b data-i="p_price">{{ $p['price'] }}.</b> <span data-i="p_pn">{{ $p['price_note'] }}</span></p>
      <div class="cta">
        <a class="btn" href="mailto:info@scuttle.dev?subject={{ rawurlencode($p['name']) }}"><span data-i="p_ask">Ask about {{ $p['name'] }}</span> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <a class="btn o" href="{{ route('scuttle.pricing') }}" data-i="p_price_btn">How I price</a>
      </div>
      @if(in_array($p['slug'], ['legacy-health-check', 'modernization'], true))
      <p class="price">@if($p['slug'] === 'legacy-health-check')<a href="{{ route('scuttle.health-check.sample') }}" data-i="p_samp">See what the report looks like</a> · @endif<a href="{{ route('scuttle.php-check') }}" data-i="p_phpc">Check your PHP version yourself</a></p>
      @endif
    </div>
  </header>

  <div class="wrap">
    <div class="plate hero-plate rv">
      <div class="mock">
        <div class="bar"><i></i><i></i><i></i><span>scuttle · {{ \Illuminate\Support\Str::slug($p['name']) }}</span></div>
        <div class="body">
          <div class="art">@include('scuttle::partials.illus', ['kind' => $p['illus']])</div>
          <ul class="feat-mini">
            @foreach(array_slice($p['features'], 0, 4) as $f)
            <li><i class="lt"></i><span data-i="p_fm{{ $loop->index }}">{{ $f[0] }}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot" data-i="p_what">What you get</span><h2 data-i="p_h2">One engagement. Clear deliverables.</h2></div>
      <div class="feats">
        @foreach($p['features'] as $f)
        <div class="f"><i class="lt"></i><div><b data-i="p_f{{ $loop->index }}t">{{ $f[0] }}</b><p data-i="p_f{{ $loop->index }}d">{{ $f[1] }}</p></div></div>
        @endforeach
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot" data-i="p_how">How it works</span>
      <ol class="steps">
        @foreach($p['steps'] as $i => $st)
        <li><span>{{ sprintf('%02d', $i + 1) }}</span><p data-i="p_s{{ $i }}">{{ $st }}</p></li>
        @endforeach
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot" data-i="p_notl">What it is not</span>
      <p class="not" data-i="p_not">{{ $p['not'] }}</p>
      <span class="lbl dot" style="margin-top:28px" data-i="p_terms">Terms in short</span>
      <ul class="terms">
        <li data-i="p_t1">Amounts excl. VAT, quote valid 14 days.</li>
        <li data-i="p_t2">Payment within 14 days, acceptance within 7 days.</li>
        <li data-i="p_t3">Rights pass to you on full payment.</li>
        <li><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}" data-i="p_t4a">General terms</a> <span data-i="p_t4b">and</span> <a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">NDA</a> <span data-i="p_t4c">as PDF.</span></li>
      </ul>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    @php($cs = collect(\Spdotdev\ScuttleDev\Cases::all())->first(fn ($c) => in_array($p['slug'], $c['products'], true)) ?? \Spdotdev\ScuttleDev\Cases::get('phalcon-to-laravel'))
    <figure class="quote rv">
      <blockquote data-i="cxq_{{ $cs['slug'] }}">{{ $cs['quote'] }}</blockquote>
      <figcaption><b data-i="cxk_{{ $cs['slug'] }}">{{ $cs['kicker'] }}</b><span><a data-i="p_case" href="{{ \Spdotdev\ScuttleDev\Cases::url($cs['slug']) }}">Read the case study: {{ $cs['title'] }}</a></span></figcaption>
    </figure>
  </div></section>

  @if(count($faq))
  <section class="s tight"><div class="wrap">
    <div class="plate rv faq">
      <span class="lbl dot" data-i="p_faq_l">Questions</span>
      <h2 data-i="p_faq_h">Questions I get</h2>
      <dl>
        @foreach($faq as $i => $f)
        <dt data-i="p_q{{ $i }}">{{ $f['q'] }}</dt>
        <dd data-i="p_a{{ $i }}">{{ $f['a'] }}</dd>
        @endforeach
      </dl>
    </div>
  </div></section>
  @endif

  @if($p['slug'] === 'ai-integration')
    @include('scuttle::partials.ai-extra')
  @endif

  @if(count($related))
  <section class="s tight"><div class="wrap">
    <span class="lbl dot" data-i="p_also">Also in {{ $C::GROUPS[$p['group']] }}</span>
    <div class="cards3" style="margin-top:18px">
      @foreach($related as $r)
      <a class="c rv" href="{{ $C::url($r) }}"><span class="lbl">{{ $C::GROUPS[$r['group']] }}</span><h3>{{ $r['name'] }}</h3><p data-i="p_rs_{{ str_replace('-', '_', $r['slug']) }}">{{ $r['short'] }}</p><span class="more" data-i="p_more">Read more →</span></a>
      @endforeach
    </div>
  </div></section>
  @endif

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2 data-i="p_cta1">{{ $p['cta'][0] }}</h2>
      <p data-i="p_cta2">{{ $p['cta'][1] }}</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($p['name']) }}" data-i="p_form">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}" data-i="p_book">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev?subject={{ rawurlencode($p['name']) }}" data-i="p_mail">Email info@scuttle.dev</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json(\Spdotdev\ScuttleDev\Cases::nlStrings() + $nl + ['p_case' => 'Lees de casestudy: '.$cs['nl']['title']]));
</script>
@endpush
