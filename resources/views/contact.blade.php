@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)

@section('title', 'Contact')
@section('description', 'Contact Scuttle Development in Eindhoven: the form, info@scuttle.dev, +31 6 18 23 31 75, a vCard and QR code, the address and the Chamber of Commerce number.')
@section('canonical', 'https://scuttle.dev/contact')

@section('content')
<article class="prod contact-page">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="ctp_l">Contact</span>
      <h1 data-dec data-i="ctp_h">Tell me what you are running</h1>
      <p class="lead" data-i="ctp_lead">What it does, where the hours go, what must not stop. I reply within one working day with a first opinion, free. A hard question gets what I need to look at before I answer it, not a dodge.</p>
    </div>
  </header>

  <div class="wrap">
    <form class="cform light" method="post" action="{{ route('scuttle.contact') }}" id="cform">
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
        <label><span data-i="ctp_n">Name</span><input type="text" name="name" required maxlength="120" value="{{ old('name') }}" autocomplete="name"></label>
        <label><span data-i="ctp_e">Email</span><input type="email" name="email" required maxlength="160" value="{{ old('email') }}" autocomplete="email"></label>
      </div>
      <label><span data-i="ctp_a">About</span><select name="about" id="cf-about"><option value="">Not sure yet</option>@foreach($C::all() as $cp)<option value="{{ $cp['name'] }}" @selected(old('about', request('about')) === $cp['name'])>{{ $cp['name'] }}</option>@endforeach<option value="Something else" @selected(old('about', request('about')) === 'Something else')>Something else</option></select></label>
      <label><span data-i="ctp_m">What are you running, and where do the hours go?</span><textarea name="message" required minlength="10" maxlength="4000" rows="7">{{ old('message') }}</textarea></label>
      @if(config('scuttle-dev.turnstile_site_key'))
        <div class="cf-turnstile" data-sitekey="{{ config('scuttle-dev.turnstile_site_key') }}"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      @endif
      <div class="cf-foot"><button class="btn" type="submit" data-i="ctp_send">Send</button><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}"><span data-i="ctp_book">Or book a call</span></a><small data-i="ctp_note">No tracking, no newsletter. Your message goes to info@scuttle.dev and nowhere else.</small></div>
    </form>
  </div>

  <div class="wrap contact two-c" id="about">
    <div class="cc rv">
      <span class="lbl" data-i="ctp_c">Company</span>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Scuttle Development<br><button class="lnk" id="open-map" type="button" title="Show on the map" data-i="ctp_addr">Dirigentplein 144<br>5642 RK Eindhoven<br>The Netherlands</button></span></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:info@scuttle.dev">info@scuttle.dev</a></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 2z"/></svg><a href="tel:+31618233175">+31 (0) 6 18 23 31 75</a></div>
      <div class="line mono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span><a href="https://www.kvk.nl/zoeken/#/96040947000061394009/" rel="noopener" target="_blank" title="Chamber of Commerce register">KvK 96040947</a><br>BTW NL005184584B85</span></div>
      <span class="lbl" style="margin-top:8px" data-i="ctp_save">Save my contact details</span>
      <div class="qr">
        <button id="open-qr" type="button" aria-label="Show the QR code larger"><img src="{{ secure_asset('vendor/scuttle/qr/scuttle-vcard.svg') }}" alt="QR code with my contact details (vCard)" width="104" height="104"></button>
        <div><p data-i="ctp_qr">Scan the code with your phone camera and it offers to add me as a contact, or download the vCard.</p><a class="btn o" href="{{ route('scuttle.vcard') }}"><span data-i="ctp_vc">Save vCard</span></a></div>
      </div>
    </div>
    <div class="cc rv">
      <span class="lbl" data-i="ctp_ab">About</span>
      <div class="about">
        <button id="open-pic" type="button" aria-label="Show the photo larger"><img src="{{ secure_asset('vendor/scuttle/portrait.jpg') }}" alt="Stanislav Plotnikov" width="96" height="96" loading="lazy"></button>
        <div><p data-i="ctp_abp">Stanislav Plotnikov. Senior developer and solution architect, PHP since the 2000s, Laravel and Phalcon in production, Python for automation and AI, Kotlin for Android. One person, one point of contact, from the first call to the handover. More about me, my CV and my writing: <a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a>.</p>
          <div class="soc"><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a><a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a></div></div>
      </div>
    </div>
  </div>

  <div id="qr-modal" class="modal" aria-hidden="true" role="dialog" aria-label="QR code with my contact details">
    <div class="ov" data-close="qr-modal"></div>
    <div class="box">
      <button class="x" data-close="qr-modal" type="button" aria-label="Close">&times;</button>
      <img src="{{ secure_asset('vendor/scuttle/qr/scuttle-vcard.svg') }}" alt="QR code with my contact details (vCard), large" width="360" height="360">
      <div class="cap" data-i="ctp_qcap">scan with your camera · adds me as a contact · Scuttle Development</div>
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
      <div class="cap" data-i="ctp_mcap">Dirigentplein 144, 5642 RK Eindhoven · <a href="https://www.google.com/maps/search/?api=1&amp;query=Dirigentplein+144,+5642+RK+Eindhoven" rel="noopener" target="_blank">open in Google Maps</a> · the map loads only when you open it</div>
    </div>
  </div>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
 "ctp_l": "Contact",
 "ctp_h": "Vertel me wat je draait",
 "ctp_lead": "Wat het doet, waar de uren heen gaan, wat niet mag stilvallen. Ik reageer binnen één werkdag met een eerste mening, gratis. Een lastige vraag krijgt wat ik eerst moet bekijken voordat ik antwoord, geen ontwijking.",
 "ctp_n": "Naam",
 "ctp_e": "E-mail",
 "ctp_a": "Waarover",
 "ctp_m": "Wat draai je, en waar gaan de uren heen?",
 "ctp_send": "Versturen",
 "ctp_book": "Of plan een gesprek",
 "ctp_note": "Geen tracking, geen nieuwsbrief. Je bericht gaat naar info@scuttle.dev en nergens anders heen.",
 "ctp_c": "Bedrijf",
 "ctp_addr": "Dirigentplein 144<br>5642 RK Eindhoven<br>Nederland",
 "ctp_save": "Bewaar mijn contactgegevens",
 "ctp_qr": "Scan de code met de camera van je telefoon en die biedt aan mij als contact toe te voegen, of download de vCard.",
 "ctp_vc": "Bewaar vCard",
 "ctp_ab": "Over mij",
 "ctp_abp": "Stanislav Plotnikov. Senior developer en solution architect, PHP sinds de jaren 2000, Laravel en Phalcon in productie, Python voor automatisering en AI, Kotlin voor Android. Eén persoon, één aanspreekpunt, van het eerste gesprek tot de overdracht. Meer over mij, mijn cv en mijn teksten: <a href=\"https://splotnikov.dev\" rel=\"noopener\" target=\"_blank\">splotnikov.dev</a>.",
 "ctp_qcap": "scan met je camera · voegt mij toe als contact · Scuttle Development",
 "ctp_mcap": "Dirigentplein 144, 5642 RK Eindhoven · <a href=\"https://www.google.com/maps/search/?api=1&amp;query=Dirigentplein+144,+5642+RK+Eindhoven\" rel=\"noopener\" target=\"_blank\">open in Google Maps</a> · de kaart wordt pas geladen als je hem opent"
});
</script>
@endpush
