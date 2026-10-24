@extends('scuttle::layout')

@section('title', 'Phalcon to Laravel, module by module')
@section('description', 'Case study: an incremental migration of a multi-tenant operations platform from Phalcon to Laravel, with no big-bang switch and 60 percent lower latency, using the Strangler Fig pattern.')
@section('canonical', 'https://scuttle.dev/case-studies/phalcon-to-laravel')

@section('content')
<article class="wrap page">
  <span class="lbl dot">Case study · client anonymized</span>
  <h1>Phalcon to Laravel, module by module</h1>
  <p class="lead">An incremental migration of a multi-tenant operations platform: no big-bang switch, no rewrite from scratch, and 60 percent lower latency.</p>

  <div class="facts">
    <div><b>60%</b> lower latency</div>
    <div><b>PHP 8.4</b> up from PHP 7.4</div>
    <div><b>Phalcon 5.9</b> up from Phalcon 3.4</div>
  </div>

  <h2>The situation</h2>
  <p>The platform ran on a legacy Phalcon system. It could not scale, and specialist developers for the framework were hard to find. A rewrite was out of the question: the system runs its customers' daily operations, so a big-bang switch was too risky.</p>

  <h2>The approach</h2>
  <p>I used the Strangler Fig pattern. Instead of replacing the system in one go, I built a thin PHP layer that lets the old Phalcon code and the new Laravel code run side by side. Parts of the application moved over one at a time, and each step was small enough to verify on its own. A wrong assumption could only break one thing, and never the whole platform.</p>
  <p>The platform was also upgraded from PHP 7.4 to PHP 8.4 and from Phalcon 3.4 to Phalcon 5.9, so the runtime stayed supported while the migration went on.</p>

  <h2>The result</h2>
  <ul>
    <li>The customers kept working in the platform during the whole migration. Each release was small and had a rollback.</li>
    <li>60 percent lower latency.</li>
    <li>A supported runtime, and a codebase on a framework that developers actually know.</li>
  </ul>

  <h2>Technology</h2>
  <p>PHP, Laravel, Phalcon, MySQL, Docker, Ansible, GitHub Actions. The client is anonymized and the work is covered by confidentiality. References on request.</p>

  <aside class="callout">
    <p>Does your application look like this? The <a href="{{ route('scuttle.health-check') }}">Health Check</a> is a fixed-price, two-day assessment that ends in a step-by-step plan.</p>
  </aside>
</article>
@endsection
