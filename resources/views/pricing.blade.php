@extends('scuttle::layout')
@php($C = \Spdotdev\ScuttleDev\Catalog::class)
@php($groups = $C::grouped())

@section('title', 'Pricing')
@section('description', 'How Scuttle Development prices its work: EUR 90 per hour excl. VAT, a fixed-price Legacy Health Check from EUR 1,600, projects quoted per step, and a monthly retainer. No plans to pick, no surprises.')
@section('canonical', 'https://scuttle.dev/pricing')

@section('content')
<article class="prod pricing">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Pricing</span>
      <h1 data-dec>One rate, four ways to buy it</h1>
      <p class="lead">I get asked what things cost before anything else, so here it is. One hourly rate, a fixed-price assessment to start with, projects quoted per step, and a retainer when you want the system kept running. Every amount excl. VAT.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="plate rv">
      <span class="lbl dot">Ways to work</span>
      <div class="plans">
        <div class="plan">
          <span class="lbl">01 · Hourly</span>
          <div class="ph2"><h3>Hourly work</h3><div class="amt">€90<small>/hour</small></div></div>
          <p>Small, well-defined tasks and anything that does not fit a fixed scope yet.</p>
          <hr>
          <ul>
            <li>Invoiced monthly, with the hours listed per task.</li>
            <li>Optional cap per month, agreed up front.</li>
            <li>A written task or ticket is all it takes to start.</li>
          </ul>
          <a class="btn o" href="mailto:info@scuttle.dev?subject=Hourly%20work">Ask a question →</a>
        </div>
        <div class="plan hi">
          <span class="lbl">02 · Fixed price</span>
          <div class="ph2"><h3>Legacy Health Check</h3><div class="amt">from €1,600</div></div>
          <p>The place to start when nobody dares to touch the system. Two days, a ranked report, a step-by-step plan.</p>
          <hr>
          <ul>
            <li>Fixed price, known before you say yes.</li>
            <li>A free intro call first, to check it fits.</li>
            <li>Separate from any work that follows: the plan is the product.</li>
          </ul>
          <a class="btn" href="{{ route('scuttle.health-check') }}">Read what is in it →</a>
        </div>
        <div class="plan">
          <span class="lbl">03 · Per step</span>
          <div class="ph2"><h3>Projects in steps</h3><div class="amt">hours × €90</div></div>
          <p>Modernization, builds, integrations and security work: every step gets its own scope and price before it starts.</p>
          <hr>
          <ul>
            <li>The quote shows the math: estimated hours times the rate, as a range.</li>
            <li>Quote valid 14 days. You can stop after any step.</li>
            <li>If a step grows past the estimate, you hear it before the hours are spent.</li>
          </ul>
          <a class="btn o" href="mailto:info@scuttle.dev?subject=Quote">Ask for a quote →</a>
        </div>
        <div class="plan">
          <span class="lbl">04 · Retainer</span>
          <div class="ph2"><h3>Maintenance retainer</h3><div class="amt">block × €90</div></div>
          <p>A monthly block of hours for patches, monitoring, backups, small changes and a report.</p>
          <hr>
          <ul>
            <li>Block size agreed after the first months show the real need.</li>
            <li>Unused hours are reported, never hidden.</li>
            <li>Review every quarter: up, down or stop.</li>
          </ul>
          <a class="btn o" href="{{ route('scuttle.product', ['slug' => 'maintenance-retainer']) }}">Read the retainer →</a>
        </div>
      </div>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot">Included in every price</span><h2>What you always get</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b>The engineer, not an account manager</b><p>You talk to the person who does the work, from the first call to the handover.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Tests before changes</b><p>Nothing is changed without a test that proves the old behaviour first.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>A rollback for every release</b><p>Small releases, each one undoable. No three-month branches.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Documentation in your repository</b><p>Runbook, decisions and architecture notes land next to the code, not in my head.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Your accounts, your code</b><p>Servers, domains and repositories stay in your name. Rights pass to you on full payment.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Plain reporting</b><p>Hours per task on every invoice, and a straight answer when something is not worth its price.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot">How a quote comes about</span>
      <ol class="steps">
        <li><span>01</span><p>A free intro call of about 30 minutes. What you run, what hurts, what must not stop.</p></li>
        <li><span>02</span><p>When the system is unknown territory: the Legacy Health Check, so the plan rests on facts.</p></li>
        <li><span>03</span><p>A written quote per step: deliverables, hours as a range, price as the math, what is not included. Valid 14 days.</p></li>
        <li><span>04</span><p>You accept in writing, the step starts, and you see it running before the next one is quoted.</p></li>
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot">Questions I get</span>
      <dl class="faq">
        <dt>Is VAT included?</dt><dd>No. Every amount on this site is excl. VAT (btw). Invoices show it separately.</dd>
        <dt>When do I pay?</dt><dd>Within 14 days of the invoice. Acceptance of delivered work within 7 days.</dd>
        <dt>Who owns the code?</dt><dd>You do, as soon as the invoice is paid. Everything lives in repositories and accounts in your name from day one.</dd>
        <dt>Do you sign an NDA?</dt><dd>Yes. My mutual NDA is on this site as a PDF, or I sign yours after reading it.</dd>
        <dt>Fixed price for a whole project?</dt><dd>Per step, yes, once the scope is clear. For a whole unknown system, no: that is what the Health Check is for.</dd>
        <dt>Do you work through agencies?</dt><dd>Yes, as a subcontractor on your client's project, under your terms or mine.</dd>
      </dl>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap">
    <span class="lbl dot">Every product and how it is priced</span>
    <div class="pricelist rv">
      @foreach($groups as $key => $items)
      <div class="pg"><small>{{ $C::GROUPS[$key] }}</small>
        @foreach($items as $p)
        <a href="{{ $C::url($p) }}"><b>{{ $p['name'] }}</b><span>{{ $p['price'] }}</span></a>
        @endforeach
      </div>
      @endforeach
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      <img alt="" src="{{ secure_asset('vendor/scuttle/logo.svg') }}" width="46" height="37">
      <h2>Want a number for your situation?</h2>
      <p>Tell me what you are running. I reply within one working day with a first opinion, free.</p>
      <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a><a class="btn o" href="mailto:info@scuttle.dev?subject=Quote">Email info@scuttle.dev</a><a class="btn o" href="tel:+31618233175">+31 6 18 23 31 75</a></div>
    </div>
  </div></section>
</article>
@endsection
