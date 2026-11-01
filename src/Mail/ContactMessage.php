<?php

namespace Spdotdev\ScuttleDev\Mail;

use Illuminate\Mail\Mailable;

/**
 * A message from the contact form, delivered as plain text.
 */
class ContactMessage extends Mailable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $about,
        public string $text,
        public string $ip,
        public string $page = '',
    ) {}

    public function build(): static
    {
        $body = "Name: {$this->name}\nEmail: {$this->email}\nAbout: ".($this->about !== '' ? $this->about : '-')."\nPage: ".($this->page !== '' ? $this->page : '-')."\n\n{$this->text}\n\n--\nSent from the scuttle.dev contact form · visitor {$this->ip}";

        $from = (string) config('scuttle-dev.mail_from', '');
        if ($from !== '') {
            $this->from($from, 'Scuttle Development');
        }

        return $this
            ->subject(self::oneLine('scuttle.dev contact: '.($this->about !== '' ? $this->about : 'general').' · '.$this->name))
            ->replyTo($this->email, $this->name)
            ->html('<pre style="font:14px/1.5 monospace;white-space:pre-wrap">'.e($body).'</pre>')
            // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom)
            ->text('scuttle::mail.contact-text');
    }

    /** A header value is one line: control characters (a line break above all) become a space. */
    private static function oneLine(string $v): string
    {
        return trim((string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', $v));
    }
}
