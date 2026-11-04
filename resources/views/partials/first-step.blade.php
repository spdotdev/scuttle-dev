{{-- One line and one button under the lead of an index page, so the first button is on the first screen of a phone.
     $ask false: the Health Plan offer of the home page, button to the booking page.
     $ask true: "Tell me what you are running", button to the contact form with the topic $about. --}}
@php($ask = $ask ?? false)
@php($about = $about ?? 'Something else')
<p class="offer">@if($ask)<b data-i="fs_ask_b">Tell me what you are running.</b> <span data-i="fs_ask_t">I reply within one working day with a first opinion, free.</span>@else<b data-i="of_b">Start with a Health Plan.</b> <span data-i="of_t">Two days, read-only, a written plan. Fixed price: EUR 1,440 excl. VAT.</span>@endif</p>
<div class="cta">@if($ask)<a class="btn" href="{{ route('scuttle.contact.page') }}?about={{ rawurlencode($about) }}" data-i="ct_b2" data-sda-event="Contact button click">Use the contact form</a>@else<a class="btn" href="{{ config('scuttle-dev.booking_url') }}" data-i="cta1" data-sda-event="Contact button click">Book a call about a Health Plan</a>@endif</div>
@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {!! json_encode(\Spdotdev\ScuttleDev\Dutch::FIRST_STEP, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!});
</script>
@endpush
