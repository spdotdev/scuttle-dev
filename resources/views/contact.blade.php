@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Contact')
@section('description', 'Contact Scuttle Development in Eindhoven: the form, info@scuttle.dev, +31 6 18 23 31 75, a vCard and QR code, the address and the Chamber of Commerce number.')
@section('canonical', 'https://scuttle.dev/contact')

@section('content')
<article class="prod contact-page">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Contact</span>
      <h1 data-dec>Tell me what you are running</h1>
      <p class="lead">What it does, where the hours go, what must not stop. I reply within one working day with a first opinion, free. Hard question? Then the first reply says what I need to look at before I answer it properly.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="cta-box rv with-form">
      <div>
        <img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="46" height="37">
        <h2>Three ways, pick the one you like</h2>
        <p>The form lands in my inbox with your address as reply-to. A call is thirty minutes, free, through the booking page. Or just email.</p>
        <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev">Email info@scuttle.dev</a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
      </div>
      <form class="cform" method="post" action="{{ route('scuttle.contact') }}" id="cform">
        @csrf
        <input type="hidden" name="t" value="{{ \Spdotdev\ScuttleDev\Http\Controllers\ContactController::token() }}">
        <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        @if(session('contact_sent'))
          <div class="sent" role="status"><b>Sent. I reply within one working day.</b></div>
        @endif
        @if($errors->any())
          <div class="err" role="alert">{{ $errors->first() }}</div>
        @endif
        <div class="two-f">
          <label><span>Name</span><input type="text" name="name" required maxlength="120" value="{{ old('name') }}" autocomplete="name"></label>
          <label><span>Email</span><input type="email" name="email" required maxlength="160" value="{{ old('email') }}" autocomplete="email"></label>
        </div>
        <label><span>About</span><select name="about" id="cf-about"><option value="">Not sure yet</option>@foreach($C::all() as $cp)<option value="{{ $cp['name'] }}" @selected(old('about', request('about')) === $cp['name'])>{{ $cp['name'] }}</option>@endforeach<option value="Something else" @selected(old('about', request('about')) === 'Something else')>Something else</option></select></label>
        <label><span>What are you running, and where do the hours go?</span><textarea name="message" required minlength="10" maxlength="4000" rows="6">{{ old('message') }}</textarea></label>
        @if(config('scuttle-dev.turnstile_site_key'))
          <div class="cf-turnstile" data-sitekey="{{ config('scuttle-dev.turnstile_site_key') }}" data-theme="dark"></div>
          <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif
        <div class="cf-foot"><button class="btn" type="submit">Send</button><small>No tracking, no newsletter. Your message goes to info@scuttle.dev and nowhere else.</small></div>
      </form>
    </div>
  </div>

  <div class="wrap contact" id="about">
    <div class="cc rv">
      <span class="lbl">Company</span>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Scuttle Development<br><button class="lnk" id="open-map" type="button" title="Show on the map">Dirigentplein 144<br>5642 RK Eindhoven<br>The Netherlands</button></span></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:info@scuttle.dev">info@scuttle.dev</a></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 2z"/></svg><a href="tel:+31618233175">+31 (0) 6 18 23 31 75</a></div>
      <div class="line mono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span><a href="https://www.kvk.nl/zoeken/?source=all&amp;q=96040947" rel="noopener" target="_blank" title="Chamber of Commerce register">KvK 96040947</a><br>BTW NL005184584B85</span></div>
    </div>
    <div class="cc rv">
      <span class="lbl">Save my contact details</span>
      <div class="qr">
        <button id="open-qr" type="button" aria-label="Show the QR code larger"><img src="{{ secure_asset('vendor/scuttle/qr/scuttle-vcard.svg') }}" alt="QR code with my contact details (vCard)" width="104" height="104"></button>
        <div><p>Scan the code with your phone camera and it offers to add me as a contact, or download the vCard.</p><a class="btn o" href="{{ route('scuttle.vcard') }}">Save vCard</a></div>
      </div>
    </div>
    <div class="cc rv">
      <span class="lbl">About</span>
      <div class="about">
        <button id="open-pic" type="button" aria-label="Show the photo larger"><img src="{{ secure_asset('vendor/scuttle/portrait.jpg') }}" alt="Stanislav Plotnikov" width="96" height="96" loading="lazy"></button>
        <div><p>Stanislav Plotnikov. Senior developer and solution architect, PHP since the 2000s, Laravel and Phalcon in production, Python for automation and AI, Kotlin for Android. One person, one point of contact, from the first call to the handover. More about me, my CV and my writing: <a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a>.</p>
          <div class="soc"><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a><a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a></div></div>
      </div>
    </div>
  </div>

  <div id="qr-modal" class="modal" aria-hidden="true" role="dialog" aria-label="QR code with my contact details">
    <div class="ov" data-close="qr-modal"></div>
    <div class="box">
      <button class="x" data-close="qr-modal" type="button" aria-label="Close">&times;</button>
      <img src="{{ secure_asset('vendor/scuttle/qr/scuttle-vcard.svg') }}" alt="QR code with my contact details (vCard), large" width="360" height="360">
      <div class="cap">scan with your camera · adds me as a contact · Scuttle Development</div>
    </div>
  </div>
  <div id="pic-modal" class="modal" aria-hidden="true" role="dialog" aria-label="Photo of Stanislav Plotnikov">
    <div class="ov" data-close="pic-modal"></div>
    <div class="box pic">
      <button class="x" data-close="pic-modal" type="button" aria-label="Close">&times;</button>
      <img src="{{ secure_asset('vendor/scuttle/portrait.jpg') }}" alt="Stanislav Plotnikov" width="480" height="480">
      <div class="cap">Stanislav Plotnikov · Scuttle Development, Eindhoven</div>
    </div>
  </div>
  <div id="map-modal" class="modal" aria-hidden="true" role="dialog" aria-label="Map">
    <div class="ov" data-close="map-modal"></div>
    <div class="box map">
      <button class="x" data-close="map-modal" type="button" aria-label="Close">&times;</button>
      <div class="frame" id="map-frame" data-src="https://www.google.com/maps?q=Dirigentplein+144,+5642+RK+Eindhoven&amp;output=embed"></div>
      <div class="cap">Dirigentplein 144, 5642 RK Eindhoven · <a href="https://www.google.com/maps/search/?api=1&amp;query=Dirigentplein+144,+5642+RK+Eindhoven" rel="noopener" target="_blank">open in Google Maps</a> · the map loads only when you open it</div>
    </div>
  </div>
</article>
@endsection
