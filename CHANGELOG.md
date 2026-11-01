# Changelog

One line per tag, in plain words, for the tags of 31 October and 1 November 2026. Older changes are in the git
history (`git log`).

## v0.7.36 (2026-11-01)
- Atom feed of the news at /feed.xml, advertised on the news pages.
- Pages work without scripts, with the keyboard, with reduced motion and in print.
- Index pages carry one offer line and one button under the lead; shorter leads on projects and AI Agents and Skills.
- The QR code on the contact page is one path: 317 KB down to 36 KB, same picture.
- Home loop nodes have an accessible name that matches the visible text and follows the language switch.
- Copy: "I" instead of "we", no superlatives. Tests now fail on deprecations, notices and warnings.

## v0.7.35 (2026-11-01)
- Contact form errors travel in both languages, so the EN/NL switch flips them.
- Phone buttons are 44 px tall and long labels wrap.
- No long dash in any tracked text file, with a test that keeps it so.

## v0.7.34 (2026-10-31)
- Contact page: no sideways scroll on a phone, shorter lead, Dutch errors, the form is the scroll target after a send.
- Search snippets that said too little or were cut mid-sentence now fit and say what the page gives, in both languages.
- Pricing has one button on the first phone screen; project docs end with a contact step.
- A news article from the CMS that names no valid service no longer answers 404.
- Tests: markup checks (images, noopener, ids, headings, footer links) and Dutch social tags on every page.

## v0.7.33 (2026-10-31)
- Same release as v0.7.32 with the CI fixes (Pint style, Larastan types) that kept the pipeline red.

## v0.7.32 (2026-10-31)
- Mail and phone links only on the contact page; every other contact button opens the contact form.

## v0.7.31 (2026-10-31)
- Home: the twelve services as one loop you can click, with Ongoing in the middle.

## v0.7.30 (2026-10-31)
- Five services renamed (AI Agents and Skills, System Integration, Mobile App, Security Test, Architecture Plan); every service has a step for the home loop. Addresses unchanged.

## v0.7.29 (2026-10-31)
- Visit counting with sd-analytics behind SCUTTLE_ANALYTICS_KEY; the privacy statement describes it in both languages.
