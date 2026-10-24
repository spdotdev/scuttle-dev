<?php

namespace Spdotdev\ScuttleDev;

/**
 * Case studies. Client work is anonymized: no client names, no platform or
 * repository names, nothing that ties a story to a client. Own products are
 * named as such. No zeros, no superlatives: see docs/marketing-rules.md.
 *
 * @phpstan-type CaseStudy array{slug:string, title:string, kicker:string, quote:string, lead:string, facts:list<array{0:string,1:string}>, situation:string, approach:list<string>, result:list<string>, stack:string, products:list<string>}
 */
final class Cases
{
    /**
     * @return array<string, CaseStudy>
     */
    public static function all(): array
    {
        return [
            'phalcon-to-laravel' => [
                'slug' => 'phalcon-to-laravel',
                'title' => 'Phalcon to Laravel, module by module',
                'kicker' => 'Multi-tenant operations platform · client anonymized',
                'quote' => 'Phalcon to Laravel, module by module, while the platform stayed in daily use. No big-bang switch, no rewrite from scratch.',
                'lead' => 'An incremental migration of a multi-tenant operations platform: no big-bang switch, no rewrite from scratch, and 60 percent lower latency.',
                'facts' => [['60%', 'lower latency'], ['PHP 8.4', 'up from PHP 7.4'], ['Phalcon 5.9', 'up from Phalcon 3.4']],
                'situation' => 'The platform ran on a legacy Phalcon system. It could not scale, and specialist developers for the framework were hard to find. A rewrite was out of the question: the system runs its customers\' daily operations, so a big-bang switch was too risky.',
                'approach' => [
                    'The Strangler Fig pattern: a thin PHP layer lets the old Phalcon code and the new Laravel code run side by side. Parts of the application move over one at a time, each step small enough to verify on its own.',
                    'The runtime came along: PHP 7.4 to 8.4 and Phalcon 3.4 to 5.9, so the platform stayed in support while the migration went on.',
                    'Every release small, every release with a rollback. A wrong assumption could only break one thing, never the whole platform.',
                ],
                'result' => [
                    'The customers kept working in the platform during the whole migration.',
                    '60 percent lower latency.',
                    'A supported runtime, and a codebase on a framework that developers actually know.',
                ],
                'stack' => 'PHP, Laravel, Phalcon, MySQL, Docker, Ansible, GitHub Actions. The client is anonymized and the work is covered by confidentiality.',
                'products' => ['modernization', 'hardening', 'maintenance-retainer'],
            ],
            'api-meets-ai' => [
                'slug' => 'api-meets-ai',
                'title' => 'An API that AI assistants can use: Laravel, Android and an MCP server',
                'kicker' => 'Own product · household inventory',
                'quote' => 'The same API serves the Android app, the admin panel and any AI assistant, through one MCP server with named tools.',
                'lead' => 'A multi-tenant inventory product built as three parts: a Laravel API, a Kotlin Android app, and an MCP server so an assistant can answer questions about the stock without touching the database.',
                'facts' => [['3 repos', 'API, Android app, MCP server'], ['1 API', 'behind app, admin and assistant'], ['MCP', 'the open standard for tools']],
                'situation' => 'A household needs to know what is in stock, where it is and when it runs out. The phone is the natural device, and the question is often spoken or typed in plain language. I built it for my own family, and it is the pattern I sell: one API, several faces.',
                'approach' => [
                    'Laravel API with authentication, roles and a Filament admin panel; multi-tenant from the first migration.',
                    'Kotlin and Jetpack Compose app for the phone: barcode scanning, photos, works with a bad connection and syncs later.',
                    'An MCP server (Model Context Protocol) in front of the API: named, permissioned tools such as "what is low", "where is X", so Claude or another assistant answers from the real data, read-only, every call logged.',
                    'Deployed with Docker on a server in Amsterdam, backups and monitoring from day one.',
                ],
                'result' => [
                    'One codebase answers three clients: app, admin, assistant. A new face is a thin layer, not a rewrite.',
                    'The assistant never sees the database; it sees tools with the user\'s own rights.',
                    'The same gateway pattern applies to any product that already has an API.',
                ],
                'stack' => 'Laravel, Filament, MySQL, Kotlin, Jetpack Compose, TypeScript MCP server, Docker, DigitalOcean.',
                'products' => ['ai-integration', 'full-stack-build', 'android-app'],
            ],
            'wake-word-model' => [
                'slug' => 'wake-word-model',
                'title' => 'A small model trained for one job: a wake word',
                'kicker' => 'Own product · voice assistant',
                'quote' => 'A large model is the wrong tool for listening all day. A small one, trained for a single word, runs on the phone and costs nothing per hour.',
                'lead' => 'For my own voice assistant I needed a wake word that runs locally, all day, on a phone. Not a job for a large model: a small model trained on recordings of that one phrase, evaluated against the mistakes that matter.',
                'facts' => [['On device', 'no audio leaves the phone'], ['Small model', 'trained for one phrase'], ['Evaluation set', 'decides every retrain']],
                'situation' => 'An assistant that listens for its name has to run always on, offline, cheap, and must not wake up at the wrong word. Sending audio to a cloud model all day is neither private nor affordable.',
                'approach' => [
                    'Recordings of the wake phrase and of everything it must ignore, in different voices, rooms and noise.',
                    'A small model trained for that one job, exported for the phone.',
                    'An evaluation set with the two mistakes that matter: missing the word, and waking on the wrong one. Every retrain is measured against it before it ships.',
                    'Android integration in Kotlin, the heavy work handed to a larger model only after the wake word.',
                ],
                'result' => [
                    'Always-on listening on the device, with no audio leaving it.',
                    'Model choice by job: small and local for listening, large and hosted for understanding.',
                    'A pattern that applies to any narrow, high-volume task: classification, routing, extraction.',
                ],
                'stack' => 'Python, PyTorch, Kotlin, Android, Claude for the understanding step.',
                'products' => ['ai-integration', 'android-app'],
            ],
            'business-on-agents' => [
                'slug' => 'business-on-agents',
                'title' => 'A one-person company run by agents',
                'kicker' => 'Own business · Scuttle Development',
                'quote' => 'Claude is the brain, a browser is the hands, a database is the memory. The same pattern, for every chore that repeats.',
                'lead' => 'Scuttle Development is one person. Finding companies, writing and sending outreach, drafting posts, tracking replies, planning and bookkeeping run as agents on one pattern, with a human tap wherever something leaves the building.',
                'facts' => [['One pattern', 'brain, hands, memory'], ['Human tap', 'before anything is sent'], ['Daily caps', 'and a kill switch']],
                'situation' => 'Selling takes hours a freelancer does not have. The repetitive parts, finding the right companies, first contact, follow-ups, reporting, are exactly what an agent does well, as long as nothing goes out without a person looking at it.',
                'approach' => [
                    'Laravel packages on one host, each one a job: company discovery and enrichment, a CRM with drafted pitches, LinkedIn outreach through a browser the agent controls, post drafting, planning.',
                    'Claude as the brain with written skills per job; Playwright or a browser extension as the hands where there is no API; SQLite and MySQL as the memory.',
                    'Guardrails in code, not in good intentions: daily caps, a kill switch, a human approval before a message is sent, an audit log of every action.',
                    'Costs watched per job, with a monthly ceiling.',
                ],
                'result' => [
                    'The pipeline runs daily; I review and tap, I do not type.',
                    'Every part is a product I can rebuild for a client: the CRM, the browser hands, the skills, the gateway.',
                    'The numbers I quote to clients come from running this myself.',
                ],
                'stack' => 'Laravel, Filament, MySQL, SQLite, Python, Claude Code, custom skills, MCP servers, Playwright, Docker, DigitalOcean.',
                'products' => ['ai-integration', 'integrations', 'maintenance-retainer'],
            ],
        ];
    }

    /**
     * @return CaseStudy
     */
    public static function get(string $slug): array
    {
        $all = self::all();
        abort_unless(isset($all[$slug]), 404);

        return $all[$slug];
    }

    public static function url(string $slug): string
    {
        return route('scuttle.case', ['slug' => $slug]);
    }
}
