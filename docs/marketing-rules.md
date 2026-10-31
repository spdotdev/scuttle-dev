# What goes on scuttle.dev and what never does

Rules from Stas for the words, numbers and structure of the site. Read before writing or changing any page, product text, case study or call to action. Linked from CLAUDE.md. Add a dated line when he gives a new rule.

## Never
- **No zeros as a claim.** "0 rewrites", "0 downtime", "0 stops" reads as arrogant and overconfident and does not sell (Stas, 2026-10-24). Say what happened in words: "the customers kept working during the migration".
- **No overconfidence, no superlatives.** No "best", "guaranteed", "zero risk". Competent and specific beats bold.
- **Nothing that points at a client.** No client names, no platform names (hotel-platform, rental-platform), no repository or branch names, no sector detail that identifies one company. Anonymized client work is described in words that fit many companies ("multi-tenant operations platform"). Own products are named as own products. A test fails on hotel, hosintra, rental-platform (2026-10-24).
- **No old scare facts.** Not "PHP 7.4 end of life since 2022". Show something recent and real, for example the latest PHP security release with its version numbers, and refresh it. Address the general case, not the edge case (2026-10-24).
- **No "legacy" in product names.** The product is "Health Check"; the legacy check is one of its parts (2026-10-24). Modernization is "Modernization", not "Modernization in Steps": no overcomplicated names.
- **No invented client results, testimonials, logos or numbers.** A worked example with illustrative numbers is allowed when the page says so in the same block.
- **No long dashes anywhere.** Periods, commas, colons.
- **No generic closing block repeated on every page.** The closing call to action matches the page it closes (2026-10-24).
- **No "modernization first".** Lead with automation and AI; modernization stays a product but is not the headline (2026-10-24).

## Always
- **Be open about prices.** Every product and how it is priced, the rate, the math (hours × EUR 90), amounts excl. VAT with the VAT note small and quiet. Openness is the marketing (Stas, 2026-10-24: "I do like the confidence and openness of every product and how it's priced").
- **Answer every question, including the hard ones.** Never dodge. When the honest answer needs research, say that the question needs a proper look before it is answered, and say what will be looked at. Competent, not overconfident (2026-10-24).
- **Sell what a product stands for, not its name.** Each product page spells out the parts of the work (for a build: design, architecture, backend, frontend, API, infrastructure) and what is usually done there (2026-10-24).
- **The value is in the knowledge, not in the model.** AI pages name the real parts (agents, skills, MCP, RAG, embeddings, evaluation sets, small models) and show how they connect, with a workflow chart and a "which model for what" table instead of a wall of words (2026-10-24).
- **Interactive beats static.** The demo panel is clickable on both sides; every run links to the product behind it; times look like real working hours, not midnight.
- **Promote what should be bought most.** Projects in steps and the maintenance retainer carry the badge; the Health Check is "where most start".
- **Plain words, "I" not "we".** One person does the work and says so.

## Structure of the site, round 2 (2026-10-30, council)
Menu: Services (hover menu, three groups, plus an "All services" link to `/services`, which replaced Work), Pricing, Case studies, Company, Contact, plus the Book a call button. News and Projects are in the footer. The sectors block is gone from the home page. The home leads with the automation headline, then one offer (Health Check, two days, EUR 1,080 excl. VAT) and one button; proof (cases) comes before the tiles and the demo panel moves below them. Pages that exist in both languages have real Dutch twins under `/nl/` with hreflang; send Dutch outreach links there. News bodies and project docs stay English only.

## Structure of the site (2026-10-24, superseded in part by the round above)
Menu: Services (hover menu, three groups), Pricing, Work, Case studies, Projects, News, Company, Contact, plus the Book a call button. Careers sits inside Company, never in the menu ("too many clicks for no reason"). Sectors is a home block, not a menu item. The word is services, not products: "we don't sell products, we sell services".
Home: hero, interactive demo panel, three product groups, belts, 12 tiles, case study carousel, sectors, closing call to action that links to /contact. Products from `src/Catalog.php`, cases from `src/Cases.php`, pricing with two blocks of four, contact as its own page with the form, the vCard, the QR code, the address and the Chamber of Commerce link.
- **Service names (2026-10-31, proposal 14).** AI Agents and Skills, System Integration, Mobile App, Security Test, Architecture Plan; the Health Check keeps its name (it is the one main assessment: health, security and architecture in one report). Two names that sound alike (Integrations next to AI Integration) never again.
