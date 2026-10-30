@extends('scuttle::layout')

@section('title', 'Page not found')
@section('description', 'This page does not exist on scuttle.dev. The services, the pricing and the contact form are one click away.')
@section('canonical', 'https://scuttle.dev/404')
@section('robots', 'noindex, follow')

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="e4_l">404</span>
  <h1 data-i="e4_h">That page does not exist</h1>
  <p class="lead" data-i="e4_lead">The address is wrong or the page moved. Everything I sell and what it costs is a click away.</p>
  <div class="cta" style="margin-top:28px">
    <a class="btn" href="{{ route('scuttle.work') }}" data-i="e4_b1">See the services</a>
    <a class="btn o" href="{{ route('scuttle.pricing') }}" data-i="e4_b2">See the pricing</a>
    <a class="btn o" href="{{ route('scuttle.contact.page') }}" data-i="e4_b3">Tell me what you are looking for</a>
  </div>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
 "e4_h": "Die pagina bestaat niet",
 "e4_lead": "Het adres klopt niet of de pagina is verhuisd. Alles wat ik verkoop en wat het kost is een klik verderop.",
 "e4_b1": "Bekijk de diensten",
 "e4_b2": "Bekijk de prijzen",
 "e4_b3": "Vertel me wat je zoekt"
});
</script>
@endpush
