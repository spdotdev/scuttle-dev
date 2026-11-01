{!! '<'.'?xml version="1.0" encoding="utf-8"?'.'>' !!}
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en">
  <title>Scuttle Development news</title>
  <subtitle>What changed in the field and what it means for your software.</subtitle>
  <link rel="self" type="application/atom+xml" href="https://scuttle.dev/feed.xml"/>
  <link rel="alternate" type="text/html" href="https://scuttle.dev/news"/>
  <id>https://scuttle.dev/news</id>
  <updated>{{ $updated }}</updated>
  <author><name>Stanislav Plotnikov</name></author>
@foreach($entries as $e)
  <entry>
    <title>{{ $e['title'] }}</title>
    <link rel="alternate" type="text/html" href="{{ $e['url'] }}"/>
    <id>{{ $e['url'] }}</id>
    <updated>{{ $e['updated'] }}</updated>
    <summary>{{ $e['summary'] }}</summary>
  </entry>
@endforeach
</feed>
