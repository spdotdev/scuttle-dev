{{-- The home page loop (proposal 14, layout B, 2026-10-31): five steps around the Ongoing hub. The ring is drawn by site.js from data-steps; every step's panel is in the HTML (one visible at a time once the script runs, all of them without it), so every service link is real and translatable. --}}
@php($L = $C::loop())
@php($steps = $C::loopSteps())
<div class="loop" id="loop" data-steps="{{ json_encode($steps, JSON_UNESCAPED_UNICODE) }}" data-pinned="Pinned on" data-pinned-nl="Vastgezet op">
  <svg class="loop-svg" xmlns="http://www.w3.org/2000/svg" role="group" aria-label="How I work: research, plan, build, review, deploy, and again; ongoing work in the middle"></svg>
  <div class="loop-steps">
    @foreach($C::STEPS as $key => $st)
    @php($i = $loop->index)
    <section class="lp{{ $loop->first ? ' on' : '' }}" data-step="{{ $key }}" aria-labelledby="lp-{{ $key }}-h">
      <span class="lbl acc" data-i="lp_{{ $key }}_k">{{ $key === 'ongoing' ? 'Between the loops' : sprintf('%02d', $i + 1) }} · {{ $st[0] }}</span>
      <h3 id="lp-{{ $key }}-h" data-i="lp_{{ $key }}_l">{{ $st[2] }}</h3>
      <p class="verb" data-i="lp_{{ $key }}_v">{{ $st[4] }}</p>
      @php($subs = $key === 'build' ? array_keys($C::SUBS) : [null])
      @foreach($subs as $sub)
      @if($sub)<p class="subh" data-i="lp_sub_{{ $sub }}">{{ $C::SUBS[$sub][0] }}</p>@endif
      <ul>
        @foreach($L[$key] as $e)
        @continue($sub && $e['sub'] !== $sub)
        <li><a href="{{ $e['extra'] ? $e['url'].'?about='.rawurlencode($e['name']) : $e['url'] }}"><span class="nm">{{ $e['name'] }}</span><small data-i="t_{{ $e['slug'] }}">{{ $e['short'] }}</small><span class="go" aria-hidden="true">→</span></a></li>
        @endforeach
      </ul>
      @endforeach
    </section>
    @endforeach
  </div>
  <p class="loop-hint" data-i="lp_hint">Click a step to see its services, click a service to open its page. Left alone, the loop walks by itself.</p>
</div>
