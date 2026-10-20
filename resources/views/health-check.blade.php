@extends('scuttle::layout')

@section('title', 'Legacy Health Check')
@section('description', 'A fixed-price, two-day assessment of your PHP application: risks, end-of-life versions, a security quick scan and a step-by-step modernization plan.')
@section('canonical', 'https://scuttle.dev/legacy-health-check')

@section('content')
  <p class="page-eyebrow">Fixed-price assessment</p>
  <h1>Legacy Health Check</h1>
  <p class="page-lead">A two-day assessment of your PHP application. You get a written report on what is risky, what leaving it as it is will cost you, and a step-by-step plan to modernize it without downtime.</p>
  <p class="page-cta">
    <a class="cta-button" href="mailto:info@scuttle.dev?subject=Legacy%20Health%20Check">Ask for a Health Check</a>
    <span class="page-price">Fixed price, from EUR 1,600 excl. VAT.</span>
  </p>

  <h2>Who it is for</h2>
  <p>Dutch SMEs and agencies with a PHP application that works, but that nobody dares to touch: a Phalcon, Symfony or older Laravel system, a custom framework, or a codebase that has outlived the team that wrote it.</p>
  <p>There is also a date to plan around. PHP 8.1 reached end of life on 31 December 2025, and security support for PHP 8.2 ends on 31 December 2026. After that date an unpatched runtime becomes a risk you carry yourself.</p>

  <h2>What you get</h2>
  <ul class="page-list">
    <li><strong>A written report</strong> in plain language, with the findings ranked by risk.</li>
    <li><strong>A version and end-of-life overview</strong> of PHP, the framework and the key dependencies.</li>
    <li><strong>A security quick scan</strong>: configuration, dependencies with known vulnerabilities, how secrets are handled and the basics of server hardening.</li>
    <li><strong>An architecture review</strong>: where change is risky, hidden coupling between parts, and how well the code is covered by tests.</li>
    <li><strong>A step-by-step plan</strong> with the order of work and effort ranges, built on the Strangler Fig pattern, so old and new run side by side and the business never stops.</li>
    <li><strong>A walkthrough call</strong> to go through the report and answer questions.</li>
  </ul>

  <h2>How it works</h2>
  <ol class="page-list">
    <li>A short intro call, free, to check that the Health Check fits your situation.</li>
    <li>You give me read-only access to the code and a short tour of how it is hosted.</li>
    <li>Two days of review.</li>
    <li>The report, followed by the walkthrough call.</li>
  </ol>

  <h2>What it is not</h2>
  <p>It is not a rewrite, it is not a penetration test, and I make no changes to your production systems. I do not need production data. If the report shows that more is needed, such as a full security test or the migration itself, you get a clear scope and price before anything starts.</p>

  <h2>Why one person</h2>
  <p>You talk to the engineer who does the work. There are no account managers and no handover between a salesperson and a developer.</p>

  <aside class="page-callout">
    <p>See what this looks like in practice: <a href="{{ route('scuttle.case-study') }}">how a Phalcon platform moved to Laravel with zero downtime</a>.</p>
  </aside>
@endsection
