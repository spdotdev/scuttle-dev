@extends('scuttle::layout')

@section('title', 'Blog')
@section('description', 'Short, practical notes from Scuttle Development on automation, AI integration, PHP security releases, pricing and keeping software in support.')
@section('canonical', 'https://scuttle.dev/blog')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Blog</span>
      <h1 data-dec>Notes from the work</h1>
      <p class="lead">Short and practical: what a security release means for you, why a gateway beats a database connection, what two days of assessment contain. Written by the person who does the work.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="posts">
      @foreach(\Spdotdev\ScuttleDev\Posts::all() as $post)
      <a class="post-card rv" href="{{ route('scuttle.post', ['slug' => $post['slug']]) }}">
        <div class="meta"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time><span>{{ implode(' · ', $post['tags']) }}</span></div>
        <h3>{{ $post['title'] }}</h3>
        <p>{{ $post['summary'] }}</p>
        <span class="more">Read →</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('scuttle::partials.logo', ['id' => 'cta', 'w' => 46, 'h' => 37])
      <h2>A question these notes do not answer?</h2>
      <p>Ask it. If it needs a proper look before I answer, the first reply says what I will look at.</p>
      <div class="row"><a class="btn" href="{{ route('scuttle.contact.page') }}">Use the contact form</a><a class="btn o" href="{{ config('scuttle-dev.booking_url') }}">Book a call</a></div>
    </div>
  </div></section>
</article>
@endsection
