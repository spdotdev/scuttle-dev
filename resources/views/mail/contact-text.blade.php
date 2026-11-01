Name: {!! $name !!}
Email: {!! $email !!}
About: {!! $about !== '' ? $about : '-' !!}
Page: {!! ($page ?? '') !== '' ? $page : '-' !!}

{!! $text !!}

--
Sent from the scuttle.dev contact form · visitor {!! $ip !!}
