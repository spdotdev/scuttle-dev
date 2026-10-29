<?php

namespace Spdotdev\ScuttleDev;

/**
 * News articles. Scuttle Development is the company side of scuttle.dev, so every article
 * is written to sell one service on the site (the `sells` key, a key of Catalog::all()),
 * while staying useful and honest. Plain facts, first person, no client traces
 * (docs/marketing-rules.md). Body paragraphs are plain text; a line starting with "## "
 * is a heading, a line starting with "- " is a list item.
 *
 * @phpstan-type Post array{slug:string, title:string, date:string, summary:string, tags:list<string>, body:list<string>, sells:string, products:list<string>, nl:array{title:string, summary:string}}
 */
final class Posts
{
    /**
     * @return array<string, Post>
     */
    public static function all(): array
    {
        return [
            'php-security-release-october-2026' => [
                'slug' => 'php-security-release-october-2026',
                'title' => 'PHP security release of 21 October fixes two critical remote code execution bugs',
                'date' => '2026-10-24',
                'summary' => 'PHP 8.5.12, 8.4.27, 8.3.36 and 8.2.35 close two flaws rated 9.2 that let anyone run code on a server without logging in. What changed, and why a patch on the day depends on who is watching.',
                'tags' => ['PHP', 'Security', 'Maintenance'],
                'sells' => 'maintenance-retainer',
                'body' => [
                    'On 21 October 2026 the PHP project released 8.5.12, 8.4.27, 8.3.36 and 8.2.35. All four fix the same two vulnerabilities, CVE-2026-109618 and CVE-2026-109619, rated 9.2 out of 10. Both allow remote code execution without logging in: a request from the internet can make the server run the attacker\'s code. The PHP team wrote that there is no complete mitigation and that updating is the only safe choice.',
                    'Notice what that sentence leaves out. It does not say who updates your server. The release is public the moment it is announced, and so is the description of the flaw. From that hour, the only question is how long your site stays exposed.',
                    '## The same day, or whenever someone notices',
                    'Picture two companies running the same PHP 8.4. In the first, someone who knows the system read the announcement on release day, checked the version on the server, updated the package, restarted PHP-FPM, tested the main pages and wrote down what was done. That takes well under an hour, and nobody else in the company hears about it.',
                    'In the second, the site was built by a developer who moved on, the server is looked after by nobody in particular, and the update is "something IT does". The announcement does not reach anyone with the access to act on it. The server keeps running, the site keeps working, and nothing looks wrong, because an unpatched flaw does not announce itself. The first sign of trouble is the trouble.',
                    'The software is identical in both cases. What differs is whether anyone is watching.',
                    '## What to do today',
                    '- Check the version: php -v on the server, or the version your hosting panel shows.',
                    '- On 8.2, 8.3, 8.4 or 8.5: update to the patch above. Debian, Ubuntu and Red Hat packages were ready on release day.',
                    '- On 8.1 or older: the PHP project no longer ships updates for it. For this release the security team published minimal source patches for 7.4, 8.0 and 8.1, but that is a courtesy, not a plan.',
                    '- Restart PHP-FPM or the web server, then check the site.',
                    '## Unsupported versions make the gap wider',
                    'On a supported version the fix arrives the same day and installs in minutes. On an unsupported one you wait for a vendor backport that may never come, or you build PHP from source and hope you did it right. PHP 8.2 reaches end of life on 31 December 2026. If that is what you run, the move to 8.4 belongs on this year\'s list.',
                    '## Why a retainer exists',
                    'This is the situation the Maintenance Retainer is for. You buy a block of hours each month, from EUR 720 for 8 hours, excl. VAT. In those hours I watch the releases for the stack you run, patch it, check that backups restore, keep an eye on uptime and certificates, and send a short report each month. When a release like this one lands, the first step is mine, not yours.',
                    'If you do not know which PHP version you run, or who would update it, start with the security quick scan: 8 hours, EUR 720 excl. VAT, written report. It tells you what is exposed today.',
                    'The retainer is at /services/maintenance-retainer and the quick scan is at /services/security-assessment. Write to me through the contact form and I reply within one working day.',
                ],
                'products' => ['maintenance-retainer', 'security-assessment', 'modernization'],
                'nl' => ['title' => 'PHP-beveiligingsrelease van 21 oktober verhelpt twee kritieke bugs voor code op afstand', 'summary' => 'PHP 8.5.12, 8.4.27, 8.3.36 en 8.2.35 sluiten twee lekken met cijfer 9.2 waarmee iedereen zonder inloggen code op een server kan draaien. Wat er veranderde, en waarom een patch op dezelfde dag afhangt van wie meekijkt.'],
            ],
            'an-mcp-server-in-front-of-your-api' => [
                'slug' => 'an-mcp-server-in-front-of-your-api',
                'title' => 'Customers now ask for AI on their own data: an MCP server in front of the API is the safe answer',
                'date' => '2026-10-24',
                'summary' => 'The request is "ChatGPT on our data". The safe way to say yes is an MCP server with named read-only tools in front of your API, never a model on the database. What it looks like, and where to start.',
                'tags' => ['AI', 'MCP', 'Integrations'],
                'sells' => 'ai-integration',
                'body' => [
                    'More and more customers ask me the same thing: can we have an AI assistant on our own data. Behind it is a reasonable wish. Someone wants to ask a question in plain language and get an answer from the real system, orders, stock, customers, tickets, instead of clicking through five screens.',
                    'The quick way to say yes is to give a model a database connection and a prompt. I do not do that. A model with a connection string can read every table, including the ones nobody meant it to see, and nothing in a prompt reliably stops it. A prompt is a request, not a rule.',
                    '## Named tools, read-only',
                    'MCP, the Model Context Protocol, is the open standard assistants use to call tools. An MCP server in front of your existing API exposes a short list of named tools, for example orders.search, customer.get and stock.low. Each tool does one thing, validates its input and calls the API the way your app already does. The model never sees a table. It sees a menu of questions it is allowed to ask.',
                    'The first version has read tools only. Anything that changes data comes later, and only where a person approves the action. That approval is built into the tool, not promised in a prompt.',
                    '## The user\'s own rights',
                    'The server runs every call with the token of the person asking. If someone cannot see another customer\'s orders in the app, they cannot see them through the assistant either. There is no second permission model to keep in sync, and no way for a clever question to reach further than the person asking could reach.',
                    '## A log of every call',
                    'Each call is logged: who asked, which tool ran, what came back. That makes the setup auditable. After a month it also shows which questions people actually ask, which is the best guide to what to build next.',
                    '## What stays the same',
                    'Your API does not change. A new assistant, or a new model next year, is a configuration change in front of it, not a rebuild. I use this pattern in my own inventory product, and the case study on this site shows it running.',
                    '## Where to start',
                    'Start with one workflow, not with "AI for the whole company". Pick a question that people ask every week and that costs real hours, write down what a good answer looks like, and build the two or three tools that answer it. That is the scope of AI Integration: quoted per use case at EUR 90 per hour excl. VAT, model costs capped and visible, and an evaluation set that decides whether it goes live. Details are at /services/ai-integration.',
                    'If the real question is bigger, such as which assistant, which hosting, whether to build or buy, that is a decision before it is a project. The Architecture Review is made for it: two days, 16 hours, EUR 1,440 excl. VAT, with a written recommendation and the conditions under which it would change. Details are at /services/architecture-review.',
                    'Tell me which question your team asks most often. I reply within one working day with the workflow I would start with.',
                ],
                'products' => ['ai-integration', 'architecture-review', 'integrations'],
                'nl' => ['title' => 'Klanten vragen nu om AI op hun eigen data: een MCP-server voor de API is het veilige antwoord', 'summary' => 'De vraag is "ChatGPT op onze data". De veilige manier om ja te zeggen is een MCP-server met benoemde, alleen-lezen tools voor je API, nooit een model op de database. Hoe dat eruitziet, en waar je begint.'],
            ],
            'what-two-days-of-health-check-buy' => [
                'slug' => 'what-two-days-of-health-check-buy',
                'title' => 'PHP 8.2 reaches end of life on 31 December: a two-day Health Check shows where your system stands',
                'date' => '2026-10-24',
                'summary' => 'Support for PHP 8.2 ends on 31 December 2026. Many owners do not know which version they run or what depends on it. What a read-only Health Check looks at in two days, what you get back, and what it costs.',
                'tags' => ['Health Check', 'PHP', 'Pricing'],
                'sells' => 'legacy-health-check',
                'body' => [
                    'PHP 8.2 stops receiving security fixes on 31 December 2026. For the owner of a system built on it, that date is not an abstract milestone: from January, a flaw like the ones fixed in this month\'s PHP release gets no patch for that version. The question I hear most before a date like this is simple. What do I actually run, and how bad is it?',
                    'Most owners cannot answer it. The developer who built the system moved on, the server is looked after by whoever has time, and the only documentation is the system itself. That is the situation the Health Check is made for.',
                    '## What the Health Check is',
                    'It is a fixed-price review of two days, 12 hours at EUR 90, so EUR 1,080 excl. VAT. It is read-only: I do not change anything on your system. I read, run and write, and a free intro call comes first so we agree what is in scope.',
                    '## Day one: how it runs',
                    '- Versions: operating system, PHP, database, framework and main packages, each checked against its official end-of-life date.',
                    '- Hosting: how it is deployed, how it is backed up, when a restore was last tested, what is monitored.',
                    '- Security basics: open ports, TLS, headers, dependencies with known advisories, how secrets are stored.',
                    '## Day two: how it is built and used',
                    '- Code: where change is risky, how parts depend on each other, what the tests cover.',
                    '- Hours: the repetitive work in and around the system, and what an agent or an integration could save.',
                    '- The plan: the order of steps, with effort ranges, so that nothing stops while the work is done.',
                    '## What you get back',
                    'A report in plain language, with findings ranked by value and risk and an hours estimate per item. After that comes a walkthrough call where you can challenge anything in it. The report is yours, whether or not you ask me to do the work. Nothing in the price is a down payment on a project.',
                    '## What it does not do',
                    'It does not fix anything, and it is not a penetration test. If the report shows that a deeper security look is warranted, the security quick scan is the next step: EUR 720 excl. VAT for 8 hours. If it shows a version past its end of life, the upgrade is quoted separately, per step, so you can stop after any of them.',
                    '## Why before the end of the year',
                    'An upgrade from PHP 8.2 to 8.4 is usually small when the system has tests and current packages, and large when it does not. You find out which one you have from the review, not from the upgrade. Two days now cost EUR 1,080. Finding the same facts in the middle of an incident costs more, and you do not get to choose the order of the work.',
                    'The Health Check is at /health-check. Tell me what the system is, which version it runs if you know it, and where it is hosted. I reply within one working day with what I would look at first.',
                ],
                'products' => ['legacy-health-check', 'security-assessment', 'modernization'],
                'nl' => ['title' => 'PHP 8.2 bereikt op 31 december het einde van de support: een Health Check van twee dagen laat zien waar je systeem staat', 'summary' => 'De support voor PHP 8.2 eindigt op 31 december 2026. Veel eigenaren weten niet welke versie ze draaien of wat ervan afhangt. Waar een alleen-lezen Health Check in twee dagen naar kijkt, wat je terugkrijgt en wat het kost.'],
            ],
        ];
    }

    /**
     * @return Post
     */
    public static function get(string $slug): array
    {
        $all = self::all();
        abort_unless(isset($all[$slug]), 404);

        return $all[$slug];
    }
}
