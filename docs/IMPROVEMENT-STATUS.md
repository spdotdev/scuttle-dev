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
| nginx on the production server (sd-admin repo): text static files gzip, 1 hour cache, fonts 30 days, Google Fonts removed from the CSP | sd-admin | HTML is never compressed (CSRF tokens) |

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
- The contact form cannot send: the live form answers "Mail from this form is not working right now" (checked by a real POST on 2026-10-30; the host's default mailer is `log`). The fix is in the package (v0.7.22): set `SCUTTLE_CONTACT_MAIL_DSN` and `SCUTTLE_CONTACT_MAIL_FROM` in the host `.env` on the production server. Needs SMTP credentials only Stas has.
## Not done
- Eindhoven/Brabant page (council: a line, not a page; the line is on /company and /contact), analytics and UTM links (Stas decides), sample report from a real redacted example (the page says it is illustrative).

# Round 6 (2026-10-31, v0.7.26)
- **Contact leads are never lost (v0.7.24, v0.7.25).** Every valid submission is stored first in `scuttle_contact_leads` (name, email, about, message, language, page, keyed IP hash, mail status, test flag; no raw IP), then mail is tried. The form says "Sent" only when mail went out; when it did not, it says "Received and saved ... I could not send it on by email just now" (EN and NL). Honeypot, signed 3-second minimum and the per-IP limit are unchanged and rejected posts store nothing. `php artisan scuttle:contact-leads` (`--unmailed`, `--id=N`, `--purge-test` for rows whose name starts with [TEST]). The optional own transport (`SCUTTLE_CONTACT_MAIL_DSN`, `_MAIL_FROM`) is kept; without it the log gets one warning per lead with the lead id. The migration runs in the deploy step (`php artisan migrate --force`). The privacy text now says the form data is stored.
- **Security review fixes (v0.7.25).** Failure logs hold only the exception class and, for a database error, the SQLSTATE (never the message or bindings); control characters and invalid UTF-8 are rejected at validation (the message keeps newline and tab); the command strips control characters and escapes console tags before printing. Tests pin all three.
- **Dutch twins of /health-check/sample, /php-check and /nis2 had English titles and descriptions** (found while auditing, live HTML): fixed in v0.7.26 with a test over five pages.
- Lighthouse mobile (lab) on 2026-10-31, accessibility, best practices, SEO and agentic browsing all 100: /health-check, /health-check/sample, /php-check, /nis2, /nl/nis2, /company (scuttle.dev), /cv (splotnikov.dev). The tool does not report performance; round 4 measured 96 to 100.
- Not possible from here: listing the stored leads on the production server. The ssh read was refused by the auto mode classifier (category Production Reads), so the stored row of the live test was not read back.

## Leads nobody sees (round 7)
A lead that is stored but not mailed logs one `critical` line (lead id and the list command, no visitor data), so the existing log alerting can pick it up. The exact commands to list and purge leads on the server are kept in the private operations notes, not in this public repository.

# Round 7 (2026-10-31, v0.7.27)
- Projects: compared with the 16 public GitHub repositories and their READMEs. Added splotnikov.dev (site package) and Bitburner scripts (game automation); Inventory now names its public Android repository (inventory-laravel and inventory-android, both MIT). Checked against the READMEs: manual, whatsapp-mcp, claude-telegram-relay and scuttle.dev descriptions are accurate. Left out on purpose: forks other than whatsapp-mcp and claude-telegram-relay (memU), two archived repositories (the old static sites), the audio EQ notes (bypass), a chat-interface mockup (prompt), the older field-manual (superseded by manual) and scuttlemail-dev (internal outreach site, README of three lines). The inventory README links an inventory-mcp repository that is not public, so it is not linked.
- Related reading on the five money pages (Health Check, Modernization, Hardening, Maintenance Retainer, AI Integration), EN and NL, with natural anchor text; NIS2 and the sample report link onward to Health Check, Hardening, the PHP check and pricing. The articles already linked to their services. Test: `test_money_pages_link_to_their_related_reading_in_both_languages`, plus the existing every-internal-link test.
- A lead that is stored but not mailed now logs at `critical` level.
- Live check after the deploy: 16 page runs of axe (EN and NL, light and dark) with 0 findings; the weekly link crawl found one 502 on /company during the deploy window, three later requests answered 200.

# Round 8 (2026-10-31, v0.7.28)
- **Full sweep, live, 2026-10-31:** Lighthouse mobile (lab, headless Chromium, performance included) on all 82 sitemap addresses of both sites incl. the Dutch twins and the new pages: accessibility, best practices and SEO 100 on every page, performance 95 to 100, CLS at most 0.006, LCP 0.8 to 1.7 s. Two first runs scored low or failed (/nl/health-check/sample 55/89/73, /news no result) while a deploy by another session was running; both scored 100 in all four categories on a rerun. axe-core (WCAG 2.2 AA plus best practice, 390 px, light and dark, reduced motion): 83 pages, 166 runs, 0 findings.
- **Dutch read of the new pages** (/nl/nis2, /nl/php-check, /nl/health-check/sample, the FAQ blocks, the /nl/company blocks, the /nl/cv "At a glance" block). This is an agent read, not a native editor's. Fixed: English month names in the PHP version table ("8 Dec 2022" now "8 dec 2022"), the three English source labels on the NIS2 page, "De Commissie" to "De Europese Commissie", "Midden" to "Gemiddeld" for a risk level, and a sentence in the sample that read as translated. The FAQ, company and CV blocks read as plain Dutch to the agent and were left as they are. Tests: the PHP check table is pinned in both languages.
- **Public repository hygiene:** HEAD and the last 50 commits of both repos searched for IP addresses, hostnames, tokens, keys and home paths. Found no IP address, token or key in HEAD or in history. Removed from HEAD: the server nickname in CLAUDE.md and this file, and home-directory paths in the old plans and one design file. Left on purpose: a made-up lab address range in the demo terminal text of splotnikov.dev (fictional, private range). History is untouched; it holds the nickname and the same home paths in the 2026-06 plan files.

# Round 9 (2026-10-31, autonomous stability and marketing run)
- **Baseline:** phpunit 53 tests, 5,670 assertions, 2.4 s (1 skipped: the dump test without its variable); pint, phpstan and composer audit clean; no direct dependency outdated. **End:** phpunit 91 tests, 7,618 assertions, 2.3 s; pint, phpstan clean. axe at 390 px in light and dark on the 53 dumped pages (106 runs): 0 findings after fixing one my own change had caused; sideways scroll at 360 px: 0 pages.
- **Contact page, measured on the local dump at 390 px:** sideways scroll on /nl/contact 434 px (light) and 445 px (dark) before, none after (cause: the decrypt headline forced one line while random glyphs were wider; it now clips sideways only while it settles). Lead paragraph 39 words and 143 px (Dutch 172 px) before, 22 words and 86 px (Dutch 115 px) after. First field at 450 to 508 px before, 390 to 395 px after. Send button at 847 to 852 px before (below the first screen of an 844 px phone), 765 to 770 px after. Field labels 11 px, now 12 px. Send and "Or book a call" were 34 px tall and side by side, now 44 px tall and full width under 600 px.
- **Contact flow:** validation, bot check and save errors come back in the language of the page (Dutch on /nl/contact, before they were English). A send, an error or a saved-only answer returns to `#cform`, so the message is in view (the anchor clears the sticky bar). The form is empty after a send. Tests: `ContactFormTest` (11): errors, old values and the About choice kept, honeypot, three-second minimum, throttle on the seventh post, mail failure keeps the lead and logs no visitor data, `?about=<script>` escaped and selecting nothing.
- **Real bug found by the robustness tests:** a CMS article with an empty or unknown service (`sells`) made its own page answer 404. It now renders without the "This article is about" line.
- **Robustness tests:** 404 pages for 13 odd addresses, HEAD, POST to read-only routes (404 or 405), 6,000 character query strings, array query values, CMS articles with HTML in the title, a very long title, an empty body and no date (JSON-LD parses, nothing unescaped).
- **Markup tests on every page in both languages:** every image has alt, width and height; target blank links carry noopener; no duplicate ids; one h1 and no skipped level; footer links answer 200 or 301; every page ends with a link to the contact form or the booking page; Dutch twins carry Dutch og tags.
- **Money pages at 390 px:** first button between 454 and 600 px on the 11 services and the Health Check (bar 760 px), no change needed. Pricing had its first button at 1,278 px, now 639 px (Dutch 673 px): one "Book a call" under the three prices, same label as the plan cards.
- **Calls to action:** project documentation ended without a next step; it now ends with the contact button (topic pre-filled), Dutch string included.
- **Search snippets:** 13 descriptions were cut in the middle of a sentence or said too little (pricing, case studies, projects, privacy, PHP check, NIS2, sample report, security test, three case studies and their Dutch twins). Rewritten under 160 characters with the same facts; `Cases` has an optional `meta` line for long leads. Test: no description contains an ellipsis and none is under 90 characters. The social card of the Security Test was rebuilt (only that one changed).
- **Trust block (/company):** every number checked: 16 public repositories (GitHub lists 16), 73 pages and the Lighthouse and axe results are in this file under round 4. No change.
- **CI:** pint, phpstan and phpunit already run on every push; the live link crawl runs weekly. No change.
- **Left out:** long dashes in CLAUDE.md, .env.example and release.yml (issue 6); buttons outside the contact form are 34 px tall on phones (issue 7).

# Round 10 (2026-10-31, leftovers of round 9)
- **Long dashes:** the byte grep over tracked text files went from 41 lines in 8 files to 0 (CLAUDE.md, README.md, .env.example, release.yml, the cutover runbook, the 2026 plan, two design drafts). New test `test_no_tracked_text_file_holds_a_long_dash` walks the package and lists file and line of any offender.
- **Buttons on phones (issue 7):** `.btn` under 600 px is now `min-height:44px;padding:12px 14px` (was 34 px). Measured on the local dump at 390 px, all 53 pages in both languages: smallest visible button 34 px on 46 of the 53 pages before, 44 px or more on all 53 after. First button on the money pages (Health Check, 11 services, pricing, home) at most 691 px before and after (bar 760 px). Sideways scroll at 360 and 390 px: 0 pages. axe at 390 px light and dark: 106 runs, 0 findings. overflow check at 360 px: 53 pages, 0. Guard test added.
- **Errors in both languages:** the contact controller flashes `contact_errors_en` and `contact_errors_nl`; the view renders them with `data-i` keys and the Dutch twin in the map. Tests: Dutch errors server rendered on `/nl/contact` with the English text in the switch map and the reverse on `/contact`; bot check, honeypot, save failure and control characters in both languages; equal counts.
- **Second pass, found fine:** every route has a test (checked by grepping each route against the tests); first button on every money page between 454 and 691 px; contact, pricing and project pages end with a next step.
- **Second pass, left out:** the /services, /company, /case-studies, /news, /projects and sample pages have their first button 2,300 to 4,800 px down; the leads of /projects (61 words) and the AI Agents and Skills page (63 words) are the longest. See the issues.

# Round 11 (2026-11-01, autonomous run, new angles)
- **Baseline:** phpunit 96 tests, 7,640 assertions, 3.2 s; pint, phpstan (level 5) clean. **End:** phpunit 105 tests, 7,754 assertions, 3.3 s, now failing on any deprecation, notice or warning; pint clean; phpstan level 6 clean. axe at 390 px light and dark on the 53 dumped pages (106 runs): 0 findings; sideways scroll at 360 px: 0 of 53 pages. No tag, no deploy.
- **Issue 8, first button on six index pages (390 px, local dump):** one offer line and one button now sit under the lead of /services, /company, /case-studies and the sample report (the Health Check offer of the home page, button to the booking page) and of /news and /projects ("Tell me what you are running", button to the contact form with the topic Something else). First button was 2,300 to 4,800 px down; now 511 (services), 516 (case studies), 602 (company, Dutch 573), 463 (news), 525 (projects), 537 (sample) px, Dutch twins within 40 px of those. Strings reuse the home keys; Dutch in `Dutch::FIRST_STEP`. Tests: `FirstStepTest`.
- **Issue 9, leads:** /projects 61 to 35 words and AI Agents and Skills 63 to 35 words, Dutch too; every part named in the old text is still on the page (the list of parts is in the feature blocks below the lead).
- **Without scripts:** content below the first screen was invisible (the scroll reveal set opacity 0 and only a script sets it back): 10 of the 13 page types I loaded had 3 to 82 hidden text blocks, now 0 on all 53 pages at 390 and 1366 px (the reveal applies only when the head script has set the js class). The home demo window is hidden without scripts (it filled itself with a script and showed an empty window). On a phone the menu sat behind a burger that needs a script; the links now show as a plain list, with a plain Services link. The EN/NL switch on pages without a Dutch twin and the theme buttons need scripts and are hidden without them; paired pages keep real links. The headline shows its real text. The contact form is a plain form post and works. Script: `scripts/nojs-check.py` (53 pages, 2 widths, 0 findings).
- **Keyboard:** 3,142 stops on 53 pages at 1366 px, all with a visible ring (before: the five loop nodes and the hub on the home page had none); skip link first on every page; Escape closes the mega menu. The three modals on the contact page now move focus to the close button, keep Tab inside and return focus to the opener on close. Script: `scripts/keyboard-check.py`.
- **Reduced motion:** no CSS animation runs, the case carousel, the loop and the status lights stay still; found and fixed: smooth scrolling still ran (`scroll-behavior:smooth`). The demo log still fills in, at once, not line by line.
- **Print:** there was no print style: the menu, buttons, grey page, dark panels and the demo printed, and content below the fold printed invisible. Now light tokens in any theme, no menu, buttons, demo or form, everything shown, plans in two columns, outbound addresses written after their link, and one line on top with the name, the address and the page address (`/nl/` pages show the Dutch address). Checked as A4 PDFs of services, pricing, the sample report, a service page and company (3 to 14 pages, nothing cut off).
- **Outbound links:** 26 distinct addresses on 70 live pages, all 200; LinkedIn answers 999 to any script and is listed as unverifiable. `scripts/check-live.py` now checks them every week (HEAD, then GET, 15 s).
- **HTML validity:** html5lib and a strict tag balance on 53 pages: 0 errors. Script: `scripts/html-check.py <dump>`.
- **Performance:** per page 6 requests, CSS 63 KB (14.6 KB gzip), JS 40 KB (14 KB gzip), two fonts 51 KB; the only heavy file was the QR code on /contact, 317 KB as 5,208 rectangles, now one path of 36 KB (7 KB gzip), pixel for pixel the same picture and still scans (`design/qr-compact.py`). No image over 150 KB (test). Lighthouse is not on this laptop, so no new score; rounds 4 and 8 measured 95 to 100.
- **PHP 8.5:** the suite passes with `error_reporting(-1)` and failing on deprecations, notices and warnings; the package raises none. Larastan level 6 clean after two PHPDoc types; level 7 gives 12 errors on sd-cms rows (issue 10).
- **Copy audit:** zeros as claims none (the Dutch "vanaf nul" is the idiom for from scratch); "best" removed in two places (the model for the step, a news summary), "we" removed in seven places; guaranteed/garantie only appear as denials; old scare facts only in the case study as before and after versions. Left: the company names in the "Worked with" belts (issue 11) and the FAQ question "Where do we start?" (issue 12).
- **Feed:** `/feed.xml`, Atom, every article, titles and summaries as plain text, advertised on /news and every article. Tests: `FeedTest`.
- **Loop nodes on the home page:** their accessible names stayed English in Dutch and axe flagged a name that did not match the visible text; both fixed.
