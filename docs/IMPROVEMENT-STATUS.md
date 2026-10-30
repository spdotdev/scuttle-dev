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

