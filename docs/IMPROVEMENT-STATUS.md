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
- Dutch read in the browser (all 23 Dutch pages at 360 px, language and layout): one overflow found and fixed (the button "Vraag naar Hardening and Secure Pipeline" ran 21 px off a 360 px screen; buttons now wrap under 600 px). Wording fixes in the Dutch: thousands separators (EUR 1.080, EUR 1.440), "verholpen" instead of "gedicht", "besluitenlogboek", "Blijft van jou", "getraind wake word", "Er ligt iets plat", "het assessment", "herbouw" for rewrite, the case title on product pages in Dutch, the contact select options and the English fact heads of the case carousel ("Op het apparaat", "Klein model", "Eén patroon", "Eén tik van mij").
- Duplicate addresses: trailing slash and index.php now 301 to the clean address on both sites (nginx in sd-admin); uppercase paths, `//`, `.html` and `/home` answer 404, not 200, so no other variants exist.

