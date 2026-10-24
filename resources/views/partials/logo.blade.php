{{-- The mark as inline SVG so the three squares can fade in one after the other. $w/$h set the box. --}}
@php($w = $w ?? 30)
@php($h = $h ?? 24)
<svg class="lg {{ $cls ?? '' }}" width="{{ $w }}" height="{{ $h }}" viewBox="22 8 92 74" aria-hidden="true" focusable="false"><defs><linearGradient id="lg-{{ $id ?? 'g' }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" class="s1"/><stop offset="1" class="s2"/></linearGradient></defs><rect class="r1" x="30" y="38" width="36" height="36" rx="8" fill="none" stroke-width="5.5"/><rect class="r2" x="50" y="27" width="36" height="36" rx="8" fill="none" stroke-width="5.5"/><rect class="r3" x="70" y="16" width="36" height="36" rx="8" fill="none" stroke="url(#lg-{{ $id ?? 'g' }})" stroke-width="5.5"/></svg>
