<?php

namespace Spdotdev\ScuttleDev;

/**
 * Blog posts. Plain facts, first person, no client traces (docs/marketing-rules.md).
 * Body paragraphs are plain text; a line starting with "## " is a heading, a line
 * starting with "- " is a list item.
 *
 * @phpstan-type Post array{slug:string, title:string, date:string, summary:string, tags:list<string>, body:list<string>, products:list<string>, nl:array{title:string, summary:string}}
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
                'title' => 'PHP 8.4.27, 8.3.36 and 8.5.12: what the 21 October security release means for you',
                'date' => '2026-10-24',
                'summary' => 'Two critical bugs that allow remote code execution without logging in, fixed in every supported PHP version. What to do today, and what it says about running on an unsupported version.',
                'tags' => ['PHP', 'Security', 'Maintenance'],
                'body' => [
                    'On 21 October 2026 the PHP project released 8.5.12, 8.4.27, 8.3.36 and 8.2.35. All four fix the same two vulnerabilities, CVE-2026-109618 and CVE-2026-109619, rated 9.2 out of 10. Both can let an unauthenticated request run code on the server. The PHP team wrote that there is no complete mitigation and that updating is the only safe choice.',
                    '## What to do today',
                    '- Check the version your site runs: php -v on the server, or the version shown by your hosting panel.',
                    '- If it is 8.2, 8.3, 8.4 or 8.5: update to the patch above. On Debian, Ubuntu and Red Hat the vendor packages were ready on release day.',
                    '- If it is 8.1 or older: the PHP project no longer ships updates for it. Some distributions backport fixes; for this one the security team even published minimal source patches for 7.4, 8.0 and 8.1 because of the severity. That is a courtesy, not a plan.',
                    '- Restart PHP-FPM or the web server after the update, and check the site.',
                    '## What it says about older versions',
                    'A release like this is the moment the difference between a supported and an unsupported runtime becomes real. On a supported version the fix arrives the same day and takes minutes. On an unsupported one you are waiting for a vendor backport that may never come, or building PHP from source. PHP 8.2 reaches end of life on 31 December 2026; if that is what you run, the upgrade to 8.4 belongs on this year\'s list.',
                    'If you do not know what you run, or who would do the update, that is exactly the question the Health Check answers in two days.',
                ],
                'products' => ['maintenance-retainer', 'modernization', 'legacy-health-check'],
                'nl' => ['title' => 'PHP 8.4.27, 8.3.36 en 8.5.12: wat de beveiligingsrelease van 21 oktober voor jou betekent', 'summary' => 'Twee kritieke bugs waarmee zonder inloggen code op afstand kan draaien, verholpen in elke ondersteunde PHP-versie. Wat je vandaag moet doen, en wat het zegt over draaien op een versie zonder support.'],
            ],
            'an-mcp-server-in-front-of-your-api' => [
                'slug' => 'an-mcp-server-in-front-of-your-api',
                'title' => 'Why I put an MCP server in front of an API instead of giving the model the database',
                'date' => '2026-10-24',
                'summary' => 'Customers ask for an AI assistant on their data. The safe way to say yes is a small gateway with named tools, the user\'s own rights and a log of every call.',
                'tags' => ['AI', 'MCP', 'Integrations'],
                'body' => [
                    'The request usually arrives as "can we have ChatGPT on our data". Behind it is a reasonable wish: ask a question in plain language and get an answer from the real system. The quick way is to hand a model a database connection and a prompt. I do not do that, and here is the shape I build instead.',
                    '## Named tools, not raw access',
                    'MCP, the Model Context Protocol, is the open standard assistants use to call tools. An MCP server in front of your existing API exposes a short list of named tools: orders.search, customer.get, stock.low. Each tool does one thing, validates its input, and calls the API the way the app already does. The model never sees a table.',
                    '## The user\'s own rights',
                    'The gateway runs every call with the token of the person asking. Someone who cannot see another customer\'s orders in the app cannot see them through the assistant either. No separate permission model to keep in sync.',
                    '## Read first, write with a human',
                    'Read tools ship first. Write tools exist only where a person approves the action, and the approval is part of the tool, not a promise in a prompt.',
                    '## Every call logged',
                    'Who asked, which tool ran, what came back. The log is what makes the thing auditable and what shows, after a month, which questions people actually ask.',
                    '## The part that pays',
                    'The API did not change. A new assistant, or a new model, is a configuration change. I built this pattern for my own inventory product and it is the one I sell as AI Integration; the case study is on this site.',
                ],
                'products' => ['ai-integration', 'integrations'],
                'nl' => ['title' => 'Waarom ik een MCP-server voor een API zet in plaats van het model de database te geven', 'summary' => 'Klanten vragen om een AI-assistent op hun data. De veilige manier om ja te zeggen is een kleine gateway met benoemde tools, de eigen rechten van de gebruiker en een log van elke aanroep.'],
            ],
            'what-two-days-of-health-check-buy' => [
                'slug' => 'what-two-days-of-health-check-buy',
                'title' => 'What two days of Health Check buy you',
                'date' => '2026-10-24',
                'summary' => 'Twelve hours, read-only, a written report. Here is what I look at, what you get back, and what it costs: EUR 1,080 excl. VAT.',
                'tags' => ['Health Check', 'Pricing'],
                'body' => [
                    'The Health Check is where most of my work starts, so here is what happens in those two days. Nothing is changed; I read, run and write.',
                    '## Day one: how it runs',
                    '- Versions: operating system, PHP, database, framework, the main packages, each against its official end-of-life date.',
                    '- Hosting: how it is deployed, how it is backed up, when a restore was last tested, what is monitored.',
                    '- Security basics: open ports, TLS, headers, dependencies with known advisories, how secrets are stored.',
                    '## Day two: how it is built and used',
                    '- Code: where change is risky, how parts depend on each other, what the tests cover.',
                    '- Hours: the repetitive work in and around the system, and what an agent or an integration would save.',
                    '- The plan: the order of steps, with effort ranges, so that nothing stops while it is done.',
                    '## What you get back',
                    'A report in plain language with findings ranked by value and risk and hours per item, and a walkthrough call. The price is 12 hours at EUR 90, EUR 1,080 excl. VAT, and it is separate from any work that follows. The plan is the product; you can take it to anyone.',
                ],
                'products' => ['legacy-health-check'],
                'nl' => ['title' => 'Wat twee dagen Health Check je opleveren', 'summary' => 'Twaalf uur, alleen-lezen, een schriftelijk rapport. Dit is waar ik naar kijk, wat je terugkrijgt en wat het kost: EUR 1,080 excl. btw.'],
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
