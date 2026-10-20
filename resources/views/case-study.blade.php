@extends('scuttle::layout')

@section('title', 'Phalcon to Laravel without downtime')
@section('description', 'Case study: an incremental migration of a hotel software platform from Phalcon to Laravel with zero downtime and 60 percent lower latency, using the Strangler Fig pattern.')
@section('canonical', 'https://scuttle.dev/case-studies/phalcon-to-laravel')

@section('content')
  <p class="page-eyebrow">Case study, client anonymized</p>
  <h1>Phalcon to Laravel without downtime</h1>
  <p class="page-lead">An incremental migration of a hotel software platform: zero downtime, and 60 percent lower latency.</p>

  <div class="page-facts">
    <div><span class="page-fact">0</span> minutes of downtime</div>
    <div><span class="page-fact">60%</span> lower latency</div>
    <div><span class="page-fact">PHP 8.4</span> up from PHP 7.4</div>
  </div>

  <h2>The situation</h2>
  <p>The platform ran on a legacy Phalcon system. It could not scale, and specialist developers for the framework were hard to find. A rewrite was out of the question: the system runs hotel operations every day, so a big-bang switch was too risky.</p>

  <h2>The approach</h2>
  <p>I used the Strangler Fig pattern. Instead of replacing the system in one go, I built a thin PHP layer that lets the old Phalcon code and the new Laravel code run side by side. Parts of the application moved over one at a time, and each step was small enough to verify on its own. A wrong assumption could only break one thing, and never the whole platform.</p>
  <p>The platform was also upgraded from PHP 7.4 to PHP 8.4 and from Phalcon 3.4 to Phalcon 5.9, so the runtime stayed supported.</p>

  <h2>The result</h2>
  <ul class="page-list">
    <li>Zero downtime during the migration.</li>
    <li>60 percent lower latency.</li>
    <li>A supported runtime, and a codebase on a framework that developers actually know.</li>
  </ul>

  <h2>Technology</h2>
  <p>PHP, Laravel, Phalcon, MySQL, Docker, Ansible, GitHub Actions. The client is anonymized and the work is covered by confidentiality.</p>

  <aside class="page-callout">
    <p>Does your application look like this? The <a href="{{ route('scuttle.health-check') }}">Legacy Health Check</a> is a fixed-price, two-day assessment that ends in a step-by-step plan.</p>
  </aside>
@endsection
