{{-- Small line illustrations, one per product. Uses currentColor for strokes and the accent token for the highlighted part. --}}
@php($kind = $kind ?? 'report')
<svg class="il" viewBox="0 0 220 150" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($kind)
  @case('report')
    <rect x="30" y="18" width="160" height="114" rx="6"/>
    <path d="M46 36h58M46 48h36"/>
    <g class="ac"><rect x="46" y="64" width="110" height="6" rx="3" fill="currentColor" stroke="none"/><rect x="46" y="80" width="84" height="6" rx="3" fill="currentColor" stroke="none"/></g>
    <rect x="46" y="96" width="60" height="6" rx="3" fill="currentColor" stroke="none" opacity=".25"/>
    <rect x="46" y="112" width="40" height="6" rx="3" fill="currentColor" stroke="none" opacity=".25"/>
    <rect x="150" y="34" width="28" height="12" rx="3" class="ac"/><path d="M158 40h12" class="ac"/>
    @break
  @case('shield')
    <path d="M110 20l56 20v34c0 30-24 50-56 60-32-10-56-30-56-60V40z"/>
    <path d="M110 34l40 14v26c0 22-17 37-40 46-23-9-40-24-40-46V48z" opacity=".3"/>
    <path d="M92 76l12 12 26-28" class="ac" stroke-width="3"/>
    <path d="M24 130h28M168 130h28M30 118h12M178 118h12" opacity=".4"/>
    @break
  @case('options')
    <rect x="18" y="30" width="54" height="90" rx="6"/><rect x="83" y="30" width="54" height="90" rx="6" class="ac" stroke-width="2.5"/><rect x="148" y="30" width="54" height="90" rx="6"/>
    <path d="M30 50h30M30 62h22M30 74h26M95 50h30M95 62h22M95 74h26M160 50h30M160 62h22M160 74h26"/>
    <circle cx="110" cy="100" r="7" class="ac" fill="currentColor" stroke="none"/><circle cx="45" cy="100" r="7" opacity=".3"/><circle cx="175" cy="100" r="7" opacity=".3"/>
    @break
  @case('migration')
    <rect x="18" y="24" width="78" height="102" rx="6"/><rect x="124" y="24" width="78" height="102" rx="6"/>
    <rect x="28" y="36" width="58" height="18" rx="3" opacity=".3"/><rect x="28" y="60" width="58" height="18" rx="3" opacity=".3"/><rect x="28" y="84" width="58" height="18" rx="3" opacity=".3"/>
    <rect x="134" y="36" width="58" height="18" rx="3" class="ac" fill="currentColor" fill-opacity=".15"/><rect x="134" y="60" width="58" height="18" rx="3" class="ac" fill="currentColor" fill-opacity=".15"/><rect x="134" y="84" width="58" height="18" rx="3" stroke-dasharray="3 4"/>
    <path d="M98 45h22M114 39l6 6-6 6M98 69h22M114 63l6 6-6 6" class="ac"/><path d="M98 93h22M114 87l6 6-6 6" opacity=".35"/>
    @break
  @case('build')
    <rect x="22" y="20" width="176" height="110" rx="6"/><path d="M22 40h176"/>
    <circle cx="34" cy="30" r="2.5" fill="currentColor" stroke="none"/><circle cx="44" cy="30" r="2.5" fill="currentColor" stroke="none"/><circle cx="54" cy="30" r="2.5" fill="currentColor" stroke="none"/>
    <rect x="22" y="40" width="52" height="90"/><path d="M32 54h30M32 66h22M32 78h26M32 90h18" opacity=".5"/>
    <rect x="86" y="52" width="48" height="30" rx="3" class="ac"/><rect x="142" y="52" width="44" height="30" rx="3"/><rect x="86" y="92" width="100" height="26" rx="3"/>
    <path d="M94 104h60" opacity=".5"/>
    @break
  @case('integrations')
    <rect x="86" y="56" width="48" height="38" rx="6" class="ac" stroke-width="2.5"/>
    <rect x="18" y="22" width="44" height="26" rx="4"/><rect x="158" y="22" width="44" height="26" rx="4"/><rect x="18" y="102" width="44" height="26" rx="4"/><rect x="158" y="102" width="44" height="26" rx="4"/>
    <path d="M62 35h14c6 0 10 4 10 10v11M158 35h-14c-6 0-10 4-10 10v11M62 115h14c6 0 10-4 10-10V94M158 115h-14c-6 0-10-4-10-10V94"/>
    <circle cx="86" cy="56" r="3" fill="currentColor" stroke="none" class="ac"/><circle cx="134" cy="56" r="3" fill="currentColor" stroke="none" class="ac"/><circle cx="86" cy="94" r="3" fill="currentColor" stroke="none" class="ac"/><circle cx="134" cy="94" r="3" fill="currentColor" stroke="none" class="ac"/>
    <path d="M100 75h20" opacity=".5"/>
    @break
  @case('agent')
    <rect x="80" y="54" width="60" height="42" rx="8" class="ac" stroke-width="2.5"/>
    <circle cx="98" cy="75" r="4" fill="currentColor" stroke="none" class="ac"/><circle cx="122" cy="75" r="4" fill="currentColor" stroke="none" class="ac"/>
    <rect x="18" y="20" width="46" height="24" rx="4"/><rect x="156" y="20" width="46" height="24" rx="4"/><rect x="18" y="106" width="46" height="24" rx="4"/><rect x="156" y="106" width="46" height="24" rx="4"/>
    <path d="M64 32h20l10 22M156 32h-20l-10 22M64 118h20l10-22M156 118h-20l-10-22" opacity=".6"/>
    <path d="M28 32h26M28 118h26M166 32h26M166 118h26" opacity=".35"/>
    @break
  @case('phone')
    <rect x="76" y="14" width="68" height="122" rx="10"/><path d="M100 24h20"/><rect x="86" y="36" width="48" height="30" rx="3" class="ac"/>
    <rect x="86" y="74" width="48" height="8" rx="2" opacity=".4"/><rect x="86" y="88" width="34" height="8" rx="2" opacity=".4"/><rect x="86" y="102" width="42" height="8" rx="2" opacity=".4"/>
    <path d="M28 60h30M28 76h22M162 60h30M162 76h22" opacity=".3"/><path d="M58 68h12M150 68h12" opacity=".5"/>
    @break
  @case('lock')
    <rect x="70" y="66" width="80" height="60" rx="8"/><path d="M86 66V50a24 24 0 0 1 48 0v16"/>
    <circle cx="110" cy="92" r="6" class="ac" fill="currentColor" stroke="none"/><path d="M110 98v12" class="ac" stroke-width="3"/>
    <path d="M22 40h30M22 56h22M22 72h26M168 40h30M176 56h22M172 72h26" opacity=".3"/>
    <rect x="22" y="104" width="36" height="12" rx="3" class="ac"/><rect x="162" y="104" width="36" height="12" rx="3" opacity=".4"/>
    @break
  @case('retainer')
    <circle cx="110" cy="75" r="50"/><circle cx="110" cy="75" r="36" opacity=".3"/>
    <path d="M110 75V45M110 75l22 14" class="ac" stroke-width="3"/><circle cx="110" cy="75" r="4" class="ac" fill="currentColor" stroke="none"/>
    <path d="M110 25v6M160 75h-6M110 125v-6M60 75h6"/>
    <rect x="170" y="28" width="30" height="8" rx="2" opacity=".35"/><rect x="170" y="44" width="22" height="8" rx="2" opacity=".35"/><rect x="20" y="104" width="30" height="8" rx="2" opacity=".35"/>
    @break
  @case('checklist')
    <rect x="40" y="18" width="140" height="114" rx="6"/>
    <rect x="54" y="34" width="14" height="14" rx="3" class="ac"/><path d="M57 41l4 4 7-8" class="ac"/><path d="M78 41h70"/>
    <rect x="54" y="60" width="14" height="14" rx="3" class="ac"/><path d="M57 67l4 4 7-8" class="ac"/><path d="M78 67h56"/>
    <rect x="54" y="86" width="14" height="14" rx="3"/><path d="M78 93h64" opacity=".5"/>
    <rect x="54" y="112" width="14" height="14" rx="3"/><path d="M78 119h44" opacity=".5"/>
    @break
  @default
    <path d="M48 24h74l28 28v78a6 6 0 0 1-6 6H48a6 6 0 0 1-6-6V30a6 6 0 0 1 6-6z"/><path d="M122 24v28h28"/>
    <path d="M62 70h70M62 86h54M62 102h62M62 118h40" opacity=".5"/>
    <path d="M62 54h30" class="ac" stroke-width="3"/>
@endswitch
</svg>
