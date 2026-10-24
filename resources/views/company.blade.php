@extends('scuttle::layout')

@section('title', 'Company')
@section('description', 'Scuttle Development: one senior developer and solution architect in Eindhoven, founded 2024. How I work, what I believe about software, the facts, and how to work with me as an agency or a peer.')
@section('canonical', 'https://scuttle.dev/company')

@section('content')
<article class="prod company">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Company</span>
      <h1 data-dec>One person, the whole stack</h1>
      <p class="lead">Scuttle Development is Stanislav Plotnikov, senior developer and solution architect in Eindhoven, founded in 2024. I automate the repetitive work in and around business software, keep that software secure and in support, and build what is missing. You talk to the person who does the work.</p>
    </div>
  </header>

  <div class="wrap two">
    <div class="plate rv">
      <span class="lbl dot">How I work</span>
      <ol class="steps">
        <li><span>01</span><p>Assess before touching. A written plan with the order of steps, so nothing stops while it is done.</p></li>
        <li><span>02</span><p>Small releases, each with a rollback. Tests around the old behaviour before it changes.</p></li>
        <li><span>03</span><p>Automate what repeats, by hand what is judgment. A person taps before anything leaves the building.</p></li>
        <li><span>04</span><p>Everything written down in your repository: runbook, decisions, architecture. You own the accounts from day one.</p></li>
      </ol>
    </div>
    <div class="plate rv">
      <span class="lbl dot">What I believe about software</span>
      <ul class="terms beliefs">
        <li><b>Return on investment over trends.</b> A stack is chosen on the people who can run it and its total cost, not on what is popular this year.</li>
        <li><b>A modular monolith over microservices</b> for a team of one to ten. Services when they earn their cost, not before.</li>
        <li><b>Old and new side by side.</b> The Strangler Fig pattern: move one part at a time, keep the business running.</li>
        <li><b>Security first, as a habit.</b> Ports closed, secrets out of the code, backups that have been restored, a pipeline that blocks red.</li>
        <li><b>AI is a tool with guardrails.</b> Agents with evaluation sets, cost caps and a human step, never a model with a database connection.</li>
        <li><b>Open prices and straight answers.</b> Every service and how it is priced is on this site. A hard question gets "I need to look before I answer", not a dodge.</li>
      </ul>
    </div>
  </div>

  <section class="s"><div class="wrap">
    <div class="dark-panel rv">
      <div class="dh"><span class="lbl dot">The facts</span><h2>Scuttle Development</h2></div>
      <div class="feats">
        <div class="f"><i class="lt"></i><div><b>Founded</b><p>December 2024, Eindhoven, the Netherlands. Sole proprietorship (eenmanszaak).</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Registered</b><p><a href="https://www.kvk.nl/zoeken/?source=all&amp;q=96040947" rel="noopener" target="_blank">KvK 96040947</a> · BTW NL005184584B85</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Address</b><p>Dirigentplein 144, 5642 RK Eindhoven. On site in the Eindhoven region, remote everywhere else.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Languages</b><p>Dutch, English, Russian. Documents in Dutch or English, your choice.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Terms</b><p><a href="{{ route('scuttle.legal', ['name' => 'TERMS_AND_CONDITIONS']) }}">General terms</a>, <a href="{{ route('scuttle.legal', ['name' => 'NDA']) }}">mutual NDA</a>, <a href="{{ route('scuttle.privacy') }}">privacy statement</a>. 14 days payment, 7 days acceptance, rights pass on full payment.</p></div></div>
        <div class="f"><i class="lt"></i><div><b>Background</b><p>Programming since the 2000s, PHP first, then Python, Kotlin and AI tooling. Agencies and product companies in employment, freelance since 2024. More on <a href="https://splotnikov.dev" rel="noopener" target="_blank">splotnikov.dev</a>.</p></div></div>
      </div>
    </div>
  </div></section>

  <section class="s tight"><div class="wrap two">
    <div class="plate rv" id="careers">
      <span class="lbl dot">Working with me</span>
      <p class="not">There are no vacancies; this is a one-person company by design. Three ways we can still work together:</p>
      <ul class="terms">
        <li><b>Agencies:</b> senior hands on your client's project at a day rate, under your terms or mine, NDA first. See <a href="{{ route('scuttle.pricing') }}">pricing</a>.</li>
        <li><b>Peers:</b> a second pair of eyes on an architecture or security question, or an overflow partner when you are full.</li>
        <li><b>Open source:</b> the skills and tools on the <a href="{{ route('scuttle.projects') }}">projects page</a> take issues and pull requests.</li>
      </ul>
    </div>
    <div class="plate rv">
      <span class="lbl dot">Where to go next</span>
      <ul class="terms">
        <li><a href="{{ route('scuttle.home') }}#services">Services</a>: the twelve things I sell, in three groups.</li>
        <li><a href="{{ route('scuttle.pricing') }}">Pricing</a>: one rate, two ways to buy it, every price on the page.</li>
        <li><a href="{{ route('scuttle.cases') }}">Case studies</a>: work, told the way it went.</li>
        <li><a href="{{ route('scuttle.projects') }}">Projects</a>: open source and own products, with documentation.</li>
        <li><a href="{{ route('scuttle.blog') }}">Blog</a>: short notes from the work.</li>
      </ul>
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>Want to know if we fit?</h2>
      <p>A thirty-minute call is free and ends with a straight answer: yes, no, or what I need to look at first.</p>
      <div class="row"><a class="btn" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a><a class="btn o" href="{{ route('scuttle.contact.page') }}">Use the contact form</a></div>
    </div>
  </div></section>
</article>
@endsection
