# Improvement status, scuttle.dev (page by page)

Started 2026-10-30. Audit tools: live crawl of every sitemap URL (title, description, canonical, headings, schema, links), Lighthouse mobile (accessibility, SEO, best practices), layout-shift trace, screenshots.

Legend: done = changed, tested, live. checked = audited, nothing to change. open = still to do.

| Page | State | Notes |
|---|---|---|
| / (home) | done v0.7.12 | description shortened to fit, headline pinned during decrypt (layout shift), carousel dots 24 px touch targets, demo heading no longer skips levels |
| /pricing | done v0.7.12 | plan titles are h2 (heading order), badge contrast fixed |
| /health-check, /services/* (11 pages) | done v0.7.12 | Service structured data, breadcrumbs, descriptions cut cleanly under 160 |
| /contact | done v0.7.12 | button contrast fixed, self-hosted fonts |
| /case-studies, /case-studies/* | done v0.7.12 | titles fit search results, breadcrumbs |
| /news, /news/* | done v0.7.12 | Article structured data, og:type article, titles cut under 70 |
| /projects, /projects/*, docs | done v0.7.12 | breadcrumbs, og:type website |
| /work, /company, /privacy | done v0.7.12 | breadcrumbs, shared head changes |
| All pages | done v0.7.12 | fonts self-hosted (no Google Fonts request), twitter tags, og:locale |

## Later in the same pass
| Change | Version | Notes |
|---|---|---|
| Skip link on every page (EN and NL) | v0.7.13 | first tab stop, visible on focus |
| Project pages: description from the lead sentence | v0.7.13 | was a 70 character one-liner |
| Card titles after the page title are h2 | v0.7.14 | /work, /case-studies, /news, /projects, project pages: no skipped heading level (test added) |
| News archive: out-of-month calendar days readable, year and month links 24 px high | v0.7.15 | Lighthouse accessibility 91 to 100 |
| Decrypt headline pins width as well as height | v0.7.16 | splotnikov.dev home layout shift 0.238 to passing |
| nginx on d051 (sd-admin repo): text static files gzip, 1 hour cache, fonts 30 days, Google Fonts removed from the CSP | sd-admin | HTML is never compressed (CSRF tokens) |

## Measured (Lighthouse mobile, live, before to after)
| Page | Accessibility | Layout shift |
|---|---|---|
| / | 94 to 100 | 0.222 to passing |
| /pricing | 95 to 100 | 0.275 to passing |
| /contact | 96 to 100 | 0.66 to passing |
| /services/ai-integration | 100 | passing |
| /work | 98 (heading order), fixed in v0.7.14 | passing |
| /news | 91 (contrast, touch size), fixed in v0.7.15 | passing |

## Not changed, noted
- The host sets a session cookie on every page (`sd-admin-session`). The privacy page says so ("the only cookie is the technically necessary session cookie"), so nothing to fix on the site.
- No hreflang: Dutch is a switch on the same address, not a separate URL, so there is nothing to point at. Separate Dutch URLs would be a bigger decision.

Final live check 2026-10-30: Lighthouse mobile 100 in all four categories on /, /pricing, /contact, /services/ai-integration, /news, /company, /projects/manual/docs, /case-studies/api-meets-ai (scuttle.dev) and /, /projects, /blog, /cv (splotnikov.dev). site.css 50 KB to 15 KB and site.js 34 KB to 13 KB over the wire.

# Round 2 (2026-10-30)

## Council, small version
Question: Dutch URLs versus the JS switch, what the home leads with, page order and what to cut. Advisors run: contrarian, first principles, outsider, executor (four small agents, sonnet, low effort). Left out: the expansionist, the anonymous peer-review round (chairman synthesis done in the session instead). Verdict followed:
- Dutch: 3 of 4 for real `/nl/` pages with hreflang on the pages that can rank, news bodies and project docs stay English without a twin; the executor wanted to wait for search data. Done as the majority said, with the executor's cheap part too (a stored choice sends a returning Dutch visitor to the twin).
- Home: 4 of 4 lead with one concrete offer and one price (Health Check, two days, EUR 1,080 excl. VAT) and one action (book a call), automation headline kept above it, demo and logos lower.
- Menu and order: 4 of 4 cut the menu to about five, merge Work into Services, move News and Projects to the footer, cut the sectors block.

## Pages
| Page | State | What changed in round 2 |
|---|---|---|
| / | done v0.7.17 | offer line and one button under the headline, order: hero, foundation, cases, tiles, trust, demo, belt, closing call; sectors block removed; tile and case texts translate |
| /nl and the Dutch twins | done v0.7.17 | `/nl`, `/nl/pricing`, `/nl/health-check`, `/nl/services`, `/nl/services/*` (12), `/nl/case-studies`, `/nl/case-studies/*` (4), `/nl/company`, `/nl/contact`, `/nl/privacy`: server-rendered Dutch, Dutch title and description, hreflang en/nl/x-default, sitemap alternates |
| /services (was /work) | done v0.7.17 | the services index; `/work` and `/nl/work` redirect 301; menu and mega menu link to it |
| /pricing, /contact, /company, /case-studies, /news, /projects | done v0.7.17 | search titles that say what the page is; company description 92 to 150 characters |
| /health-check and the 11 service pages | done v0.7.17 | search titles per service (for example "Modernization: PHP, framework and hosting up to date"); Dutch titles |
| /projects/* | done v0.7.17 | descriptions from the lead sentence, hand-written for Inventory and WhatsApp MCP |
| All pages | done v0.7.17 to v0.7.19 | 31 own social cards in `public/og/` (1200x630, per service, case, project, news post), own 404 page with noindex, menu Services, Pricing, Case studies, Company, Contact |

## Audit of what round 1 skipped (Lighthouse mobile, live)
100 in all four categories: /health-check, /privacy, /news/what-two-days-of-health-check-buy, /projects/inventory, /nl, /, /nl/services/hardening (scuttle.dev); /toolkit, /blog/strangler-fig-what-the-bridge-taught-me (splotnikov.dev). One failure found and fixed: splotnikov.dev contact buttons (white on teal, ratio 1.9 in dark mode).
Checks without Lighthouse: every internal link on every page type answers 200 or 301 (test), every social image file exists (test), both sitemaps parse as XML (test; the first splotnikov.dev version with alternates did not, found on the live site and fixed in v0.3.24), robots.txt unchanged and valid, http to https 308, www to apex 301, old addresses (`/legacy-health-check`, `/products/*`, `/blog`, `/work`) redirect 301, 404 pages are the site's own and noindex.

## Noted, not changed
- `/pricing/` and `/index.php` also answer 200 (canonical points at the clean address); a redirect would be a host (nginx) rule.
- Outreach messages in the CRM that link to scuttle.dev should link Dutch recipients to `/nl/...` (sd-crm templates, not this repo).
- The hero of splotnikov.dev still leads with "I turn legacy technical debt into infrastructure that scales"; only the line under the name was reordered to automation first. The hero sentence is Stas's wording, so it is his call.

# Round 3 (2026-10-30)
- Dutch read in the browser (21 of the 24 Dutch pages at 360 px (not opened: two of the four case studies), language and layout): one overflow found and fixed (the button "Vraag naar Hardening and Secure Pipeline" ran 21 px off a 360 px screen; buttons now wrap under 600 px). Wording fixes in the Dutch: thousands separators (EUR 1.080, EUR 1.440), "verholpen" instead of "gedicht", "besluitenlogboek", "Blijft van jou", "getraind wake word", "Er ligt iets plat", "het assessment", "herbouw" for rewrite, the case title on product pages in Dutch, the contact select options and the English fact heads of the case carousel ("Op het apparaat", "Klein model", "Eén patroon", "Eén tik van mij").
- Duplicate addresses: trailing slash and index.php now 301 to the clean address on both sites (nginx in sd-admin); uppercase paths, `//`, `.html` and `/home` answer 404, not 200, so no other variants exist.
- Lighthouse mobile, live, 100 in all four categories on the Dutch twins /nl/services/modernization, /nl/services/hardening, /nl/contact, /nl/pricing, /nl and on splotnikov.dev /nl, /nl/cv (one splotnikov.dev /nl run scored 92 SEO and 67 agentic because robots.txt timed out while the server was busy; the rerun passed).
- Deploy window measured and shortened (sd-admin, see its CLAUDE.md and issue #17): from about 15 s of 502 per deploy to a handful of failed samples and no 502. Lock overwrite: CI check and `scripts/bump-package.sh` in sd-admin.
- Six article drafts in sd-cms, status review, none published: scuttle (NIS2 and the Cyber Resilience Act, measuring my own two websites and the Health Check, a lock file that went backwards and a measured 502); splotnikov (the headline layout shift, why the Dutch pages are real URLs, what reading the Dutch pages in a browser found).



# Round 4 (2026-10-30)
- **Every page, both sites, both languages, measured:** Lighthouse mobile (lab) on all 73 sitemap addresses (60 on scuttle.dev incl. the 12 services and 11 service twins in Dutch, 13 on splotnikov.dev): accessibility 100, best practices 100, SEO 100 on every page; performance 96 to 100 (lowest: /contact 96); CLS 0 on every page; LCP 1.4 to 1.7 s. axe-core (WCAG 2.2 AA plus best practice) at 390 px in light and dark, reduced motion: Lighthouse had missed light-theme contrast; after the fixes 0 findings on all pages of both sites in both themes. Sideways scroll at 360 px: none on any of the 73 pages.
- **Fixed from axe:** darker accent for text in the light theme, `--muted` darker, white text on the coloured buttons in light, scrollable tables and code blocks reachable by keyboard, no `<aside>` inside `<main>`, no link inside a `summary` in the archive.
- **No cookie on content pages** (session, CSRF and queued-cookie middleware off every route except the contact page and its POST). Live check: 0 Set-Cookie on /pricing, /cv and the stylesheet; the contact page still sets its session and a form POST without a token answers 419, one with a token answers 302.
- **Link health:** test over every sitemap address (self-canonical, indexable), hreflang reciprocity and every internal link; the same crawl against the live site runs weekly (`.github/workflows/live-links.yml`, `scripts/check-live.py`). Live run today: 60 addresses and 99 links, 0 problems (scuttle.dev); 13 and 23, 0 problems (splotnikov.dev).
- **Headers:** Cross-Origin-Opener-Policy same-origin and a longer Permissions-Policy added in nginx (sd-admin). HSTS stays at one year without includeSubDomains or preload (other subdomains not verified). CSP keeps 'unsafe-inline' for scripts and styles (inline scripts carry the Dutch strings and JSON-LD; a nonce would need a middleware and an nginx change, not done). The preview host sends `X-Robots-Tag: noindex, nofollow, noarchive`.
- **Buyer on a phone:** the price now sits above the buttons on every service page (Health Check: EUR 1,080 before "Ask about Health Check"); the pricing page shows the three prices a buyer looks for (EUR 90 per hour, Health Check EUR 1,080, retainer from EUR 720) as a row under the headline in both languages.
- **CI on both repos was red since round 2** (Larastan error in the 404 handler); found and fixed in this round, green now. Lesson: read the CI result before calling a release done.
- **Not done:** Core Web Vitals field data from the CrUX API (using the Google key from KeePass was blocked by the permission classifier; lab data only; the sites are small, field data may not exist for them anyway).

# Round 5 (2026-10-30, v0.7.23)
## Council, small version
Question: which one set of changes brings the most qualified leads in 3 months. Advisors: contrarian, first principles, outsider (a Brabant factory owner), executor (four small sonnet agents, low effort). Left out: expansionist, the anonymous peer round; no CRM reply report was readable, so no reply data went in. Agreed by all four: (1) the contact flow is the gate, (2) the About / how I work answer belongs on the existing Company page, not a new page, (3) proof only from public facts, (4) FAQ is cheap and useful for the Health Check but no rich-result win (Google shows FAQ results for almost no sites), (5) Eindhoven is a line, not a page (a town page is doorway work). The contrarian also wanted analytics and UTM links; that is Stas's decision (privacy rule, GitHub issue #5), not built.
## Done
- FAQ (visible text and FAQPage data word for word, EN and NL) on /health-check, /services/modernization, /services/hardening, /services/maintenance-retainer; every answer restates a fact already on the page or in the catalog (`src/Faq.php`); tests: `test_faq_blocks_match_their_faqpage_data_in_both_languages`, `test_faq_prices_match_the_catalog`.
- /company: "What you can check yourself" (16 public repositories, Lighthouse and axe numbers of 30 Oct 2026 from round 4, the PHP check and the sample report, why client work has no names) and "What happens when you write" (free thirty-minute call, NDA, quote valid 14 days, runbook handover).
- Released earlier today and now live: /health-check/sample, /php-check, /nis2 and their /nl twins (commit 789bcc9).
- axe on the live site, 390 px, light and dark: 0 findings on 10 scuttle.dev pages and 2 splotnikov.dev pages (EN and NL).
## Blocked
- The contact form cannot send: the live form answers "Mail from this form is not working right now" (checked by a real POST on 2026-10-30; the host's default mailer is `log`). The fix is in the package (v0.7.22): set `SCUTTLE_CONTACT_MAIL_DSN` and `SCUTTLE_CONTACT_MAIL_FROM` in the host `.env` on d051. Needs SMTP credentials only Stas has.
## Not done
- Eindhoven/Brabant page (council: a line, not a page; the line is on /company and /contact), analytics and UTM links (Stas decides), sample report from a real redacted example (the page says it is illustrative).

# Round 6 (2026-10-31, v0.7.26)
- **Contact leads are never lost (v0.7.24, v0.7.25).** Every valid submission is stored first in `scuttle_contact_leads` (name, email, about, message, language, page, keyed IP hash, mail status, test flag; no raw IP), then mail is tried. The form says "Sent" only when mail went out; when it did not, it says "Received and saved ... I could not send it on by email just now" (EN and NL). Honeypot, signed 3-second minimum and the per-IP limit are unchanged and rejected posts store nothing. `php artisan scuttle:contact-leads` (`--unmailed`, `--id=N`, `--purge-test` for rows whose name starts with [TEST]). The optional own transport (`SCUTTLE_CONTACT_MAIL_DSN`, `_MAIL_FROM`) is kept; without it the log gets one warning per lead with the lead id. The migration runs in the deploy step (`php artisan migrate --force`). The privacy text now says the form data is stored.
- **Security review fixes (v0.7.25).** Failure logs hold only the exception class and, for a database error, the SQLSTATE (never the message or bindings); control characters and invalid UTF-8 are rejected at validation (the message keeps newline and tab); the command strips control characters and escapes console tags before printing. Tests pin all three.
- **Dutch twins of /health-check/sample, /php-check and /nis2 had English titles and descriptions** (found while auditing, live HTML): fixed in v0.7.26 with a test over five pages.
- Lighthouse mobile (lab) on 2026-10-31, accessibility, best practices, SEO and agentic browsing all 100: /health-check, /health-check/sample, /php-check, /nis2, /nl/nis2, /company (scuttle.dev), /cv (splotnikov.dev). The tool does not report performance; round 4 measured 96 to 100.
- Not possible from here: listing the stored leads on d051. The ssh read was refused by the auto mode classifier (category Production Reads), so the stored row of the live test was not read back.
