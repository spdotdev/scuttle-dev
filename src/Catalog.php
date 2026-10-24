<?php

namespace Spdotdev\ScuttleDev;

/**
 * The products Scuttle Development sells, in three groups. One source for the
 * products menu, the home page tiles, the pricing page and the product pages.
 *
 * Prices: only the Legacy Health Check has a set price. Every other product is
 * quoted per step at EUR 90 per hour excl. VAT, after a free intro call.
 *
 * @phpstan-type Product array{slug:string, group:string, name:string, short:string, lead:string, price:string, price_note:string, features:list<array{0:string,1:string}>, steps:list<string>, not:string, illus:string, cta:array{0:string,1:string}, route?:string}
 */
final class Catalog
{
    public const HOURLY = 'EUR 90 per hour excl. VAT';

    /** @var array<string, string> group key => label */
    public const GROUPS = [
        'build' => 'Automate',
        'assess' => 'Assess',
        'run' => 'Run',
    ];

    /** @var array<string, string> group key => one line */
    public const GROUP_LINES = [
        'build' => 'Automate the repetitive work, build what is missing.',
        'assess' => 'Know where you stand before anything is touched.',
        'run' => 'Keep it secure, backed up and documented.',
    ];

    /**
     * @return array<string, Product>
     */
    public static function all(): array
    {
        return [
            'ai-integration' => [
                'slug' => 'ai-integration',
                'group' => 'build',
                'name' => 'AI Integration',
                'short' => 'Agents, custom skills, MCP servers, RAG over your data. One use case to production.',
                'lead' => 'One workflow where AI saves measurable time, taken to production: a Claude agent with custom skills, an MCP server that gives it named tools on your systems, retrieval (RAG) over your own documents, and browser automation where there is no API. The value is in connecting the model to your knowledge and your tools, safely. I run my own business on this pattern.',
                'price' => 'Quoted per use case at '.self::HOURLY,
                'price_note' => 'Model costs are yours and capped. An evaluation set decides whether it ships.',
                'features' => [
                    ['One use case first', 'Picked for measurable hours saved, not for the demo. Invoice intake, lead follow-up, ticket triage, reporting, a question box over your data.'],
                    ['Agents with custom skills', 'A Claude agent that works in steps with your tools, following written skills: your procedure, your checks, your tone.'],
                    ['MCP server on your systems', 'Model Context Protocol: named, permissioned tools in front of your API or database, so every assistant can use them and every call is logged.'],
                    ['RAG over your documents', 'Retrieval with sources shown in every answer; embeddings built locally when the data may not leave your servers.'],
                    ['Evaluation set and guardrails', 'Real examples with known answers, what the agent may never do, what needs a person, a monthly cost cap and a documented switch-off.'],
                    ['Small models where it fits', 'Fine-tuned or local models (Ollama, Whisper, a trained wake word) for jobs that must be fast, offline or cheap.'],
                ],
                'steps' => [
                    'Intro call: where the hours go today, what a good answer looks like, what data may leave the building.',
                    'Evaluation set and a thin prototype against it, with the model picked per step.',
                    'Production build with the MCP tools, retrieval, guardrails, logging and the cost cap.',
                    'Handover, with the runbook and the evaluation set so you can judge later changes and model swaps.',
                ],
                'not' => 'No guaranteed accuracy, no agent with write access to production without a human step, no vendor lock-in: the model is swappable by design.',
                'illus' => 'agent',
                'cta' => ['Which task would you hand to an agent first?', 'Tell me where the hours go today and what a good answer looks like. I reply within one working day with the use case I would start with, free.'],
            ],

            'integrations' => [
                'slug' => 'integrations',
                'group' => 'build',
                'name' => 'Integrations',
                'short' => 'Systems that talk to each other reliably: bookings, payments, ERP, mail.',
                'lead' => 'Connecting the systems you already run: booking systems, payment providers, ERP, mail platforms, scanners and partner APIs. Built to survive retries, outages and the other side changing its mind.',
                'price' => 'Quoted per integration at '.self::HOURLY,
                'price_note' => 'A spike of a few hours against the real API comes first when the documentation is thin.',
                'features' => [
                    ['Idempotent by design', 'A webhook delivered twice or a job retried never charges, mails or books twice.'],
                    ['Queues and retries', 'Work happens in the background with backoff, dead letters and alerts.'],
                    ['Mapping on paper', 'Which field goes where, what is the source of truth, what happens on conflict.'],
                    ['Monitoring', 'You see the last successful sync and the failures, not a silent gap.'],
                    ['Sandbox first', 'Every provider tested against its test environment before production keys exist.'],
                    ['Runbook', 'How to re-run, how to replay, who to call at the other side.'],
                ],
                'steps' => [
                    'Intro call: the systems, the direction of the data, the volumes.',
                    'Spike against the real API, then a fixed scope.',
                    'Build with tests and a sandbox run.',
                    'Production switch with monitoring and the runbook.',
                ],
                'not' => 'No scraping of systems that offer no API without the owner agreeing, no "it works on my machine" switches. Partner fees and licences are theirs, not in my quote.',
                'illus' => 'integrations',
                'cta' => ['Which two systems should talk?', 'Name them and the direction of the data. I reply within one working day with how I would connect them and what to watch, free.'],
            ],

            'full-stack-build' => [
                'slug' => 'full-stack-build',
                'group' => 'build',
                'name' => 'Full-stack Build',
                'short' => 'Design, architecture, backend, front end, API, hosting: a new application built end to end.',
                'lead' => 'A new web application built end to end by one person: the design of the screens, the architecture, the backend, the front end, the API, the hosting and the release process. For an idea, a spreadsheet process or a product that has outgrown its first version.',
                'price' => 'Quoted per milestone at '.self::HOURLY,
                'price_note' => 'A first working version is a milestone of its own, so you see it running before the rest is planned.',
                'features' => [
                    ['Design', 'Screens sketched with you first: what each user sees and does, in the order they do it. Plain, fast pages over decoration.'],
                    ['Architecture', 'Data model, roles and tenancy, what talks to what, what must never fail. Written down as decision records before the first line of code.'],
                    ['Backend', 'Laravel: authentication, roles, queues, mail, scheduled jobs, Filament admin screens, multi-tenant where the product needs it.'],
                    ['Front end', 'Server-rendered Blade with Alpine.js by default: no build pipeline to maintain, works on every device. A single-page app only when the product truly needs one.'],
                    ['API and integrations', 'REST endpoints for apps and partners, webhooks handled safely for retries, an MCP server when AI assistants should use it.'],
                    ['Hosting, tests and release', 'Docker on a server you own, backups, monitoring, TLS; feature tests on every change, CI that blocks red merges, deploy and rollback in one command.'],
                ],
                'steps' => [
                    'Intro call and a one-page scope: who uses it, what it must do first.',
                    'Milestone one: the smallest version that does real work, live on staging.',
                    'Milestones two and on, each scoped and priced before it starts.',
                    'Go live, handover pack, optional retainer.',
                ],
                'not' => 'No design agency in the middle, no framework for the sake of it. I build what is agreed, and I say so when a feature is not worth its price.',
                'illus' => 'build',
                'cta' => ['What should the first version do?', 'Describe the one thing it must do on day one. I reply within one working day with what milestone one would contain and roughly cost, free.'],
            ],

            'modernization' => [
                'slug' => 'modernization',
                'group' => 'build',
                'name' => 'Modernization',
                'short' => 'PHP, framework, database, web server and hosting brought up to date, while the system keeps running.',
                'lead' => 'Everything under your application brought up to date and made future-proof: the PHP version, the framework, the database, the web server, the way it is hosted and the way it is released. Done in steps, with the system in use the whole time, so nothing stops and every step can be undone.',
                'price' => 'Quoted per step at '.self::HOURLY,
                'price_note' => 'Each step gets its own scope and price before it starts. You can stop after any step.',
                'features' => [
                    ['PHP upgrades', '7.x to 8.x, 8.1 to 8.4 or 8.5: deprecations fixed, extensions matched, the test suite proving it before the switch.'],
                    ['Framework upgrades and moves', 'Old Laravel to current Laravel, Phalcon or a custom framework to Laravel, module by module, old and new side by side.'],
                    ['Database and runtime', 'MySQL 5.7 to 8, MariaDB, Redis, PHP-FPM tuning; the operating system brought back into support.'],
                    ['Web server and TLS', 'Apache or nginx to Caddy where it pays: automatic certificates, HTTP/2 and 3, one readable config.'],
                    ['Containers and hosting', 'Docker on a server you own, DigitalOcean by default; staging and production the same machine; a move from a shared host or an old VPS included.'],
                    ['Release process', 'Git, CI with tests and security gates, deploy in one command, rollback in one command. Dependencies audited and updated on a schedule.'],
                ],
                'steps' => [
                    'Health Check or an existing plan: the order of steps, safety net first.',
                    'Step one scoped, priced and approved; tests written around the current behaviour.',
                    'Build, test, release, verify. Small releases, weekly where possible.',
                    'Next step, or stop. The handover pack is updated after every step.',
                ],
                'not' => 'No rewrite from scratch, no three-month branch, no licence costs hidden in the quote. If a step turns out bigger than quoted, you hear it before the hours are spent.',
                'illus' => 'migration',
                'cta' => ['Which layer is the oldest in your stack?', 'Tell me the PHP version, the framework and where it is hosted. I reply within one working day with the first step I would take, free.'],
            ],

            'android-app' => [
                'slug' => 'android-app',
                'group' => 'build',
                'name' => 'Android App',
                'short' => 'Kotlin and Jetpack Compose, on your API or a new Laravel backend.',
                'lead' => 'A native Android app in Kotlin with Jetpack Compose, on top of the API you have or a new Laravel backend built alongside it. For field staff, scanners, internal tools and products that need to work with a bad connection.',
                'price' => 'Quoted per milestone at '.self::HOURLY,
                'price_note' => 'Internal distribution or Play Store, your choice; store accounts stay in your name.',
                'features' => [
                    ['Kotlin and Compose', 'Current Android tooling, no cross-platform layer to debug later.'],
                    ['Works offline', 'Local storage and sync, so a lost connection does not lose work.'],
                    ['Camera and scanning', 'Barcodes, photos and documents straight into your system.'],
                    ['Backend included', 'A Laravel API with authentication, roles and an admin panel when you do not have one.'],
                    ['Release pipeline', 'Signed builds from CI, internal test track, versioned releases.'],
                    ['Device testing', 'Tested on real devices, not only the emulator.'],
                ],
                'steps' => [
                    'Intro call: who uses it, where, on what devices.',
                    'Milestone one: the main screen doing real work against your data.',
                    'Further milestones, each scoped and priced.',
                    'Release, handover, store listing if wanted.',
                ],
                'not' => 'No iOS in this offer, no app for the sake of an app when a responsive web page does the job. I say so in the intro call.',
                'illus' => 'phone',
                'cta' => ['Who would use the app, and where?', 'Tell me the job it must do on the floor or in the field. I reply within one working day with the first screen I would build, free.'],
            ],

            'legacy-health-check' => [
                'slug' => 'legacy-health-check',
                'route' => 'scuttle.health-check',
                'group' => 'assess',
                'name' => 'Health Check',
                'short' => 'Two-day assessment: automation opportunities, risks, a step-by-step plan.',
                'lead' => 'A two-day assessment of your application and the way it is run. You get a written report on where automation saves hours, what is risky, which parts are out of support (the legacy check), and a step-by-step plan that does not stop the business.',
                'price' => 'Fixed price, EUR 1,080 excl. VAT (12 hours)',
                'price_note' => 'Two days of review, 12 hours × EUR 90. A free intro call comes first.',
                'features' => [
                    ['Written report', 'Plain language, findings ranked by value and risk, hours per item.'],
                    ['Automation opportunities', 'The repetitive work in and around the system, with the hours it costs today and what an agent or integration would save.'],
                    ['Legacy check', 'Operating system, PHP, database, framework and key dependencies against their official end-of-life dates.'],
                    ['Security quick scan', 'Configuration, dependencies with known vulnerabilities, secrets handling, server hardening basics.'],
                    ['Architecture review', 'Where change is risky, hidden coupling, how well the code is covered by tests.'],
                    ['Step-by-step plan', 'Order of work and effort ranges. Old and new run side by side, nothing stops.'],
                    ['Walkthrough call', 'We go through the report together and answer every question.'],
                ],
                'steps' => [
                    'A short intro call, free, to check that the Health Check fits your situation.',
                    'You give me read-only access to the code and a short tour of how it is hosted.',
                    'Two days of review.',
                    'The report, followed by the walkthrough call.',
                ],
                'not' => 'It is not a rewrite, it is not a penetration test, and I make no changes to your production systems. I do not need production data. If the report shows that more is needed, you get a clear scope and price before anything starts.',
                'illus' => 'report',
                'cta' => ['Not sure where to start? Start here.', 'Twelve hours, read-only, a plan you can hold me to. Tell me what you run and I check that the Health Check fits before anything is booked.'],
            ],

            'security-assessment' => [
                'slug' => 'security-assessment',
                'group' => 'assess',
                'name' => 'Security Assessment',
                'short' => 'Vulnerability assessment or application pentest, with a retest.',
                'lead' => 'A vulnerability assessment or application penetration test of a web application, API or server environment you own. Findings ranked by severity, each with fix advice, and a retest when the fixes are in.',
                'price' => 'Quick scan EUR 720 excl. VAT (8 hours); full assessment quoted per phase at '.self::HOURLY,
                'price_note' => 'Scope, test window and written authorization are agreed before anything is tested.',
                'features' => [
                    ['Written authorization first', 'Scope, test window, emergency contacts and third-party permission when you host elsewhere.'],
                    ['Application testing', 'Authentication, access control, injection, business logic, session handling, file handling.'],
                    ['Infrastructure check', 'Open ports, TLS configuration, exposed services, outdated software with known vulnerabilities.'],
                    ['Ranked findings', 'Severity, proof, impact in business terms, and the fix for each item.'],
                    ['Fix advice', 'Concrete changes for your developers, or I make them with you.'],
                    ['Retest', 'Every finding checked again after the fix, with a closing report.'],
                ],
                'steps' => [
                    'Intro call: what you run, what worries you, what must stay untouched.',
                    'Scope and rules of engagement on paper, signed by you.',
                    'Testing inside the agreed window, read-only on production unless agreed otherwise.',
                    'Report and walkthrough, then the retest after your fixes.',
                ],
                'not' => 'No testing without written authorization, no denial of service, no social engineering unless it is named in the scope. I do not test systems you do not own.',
                'illus' => 'shield',
                'cta' => ['What would hurt most if it leaked?', 'Tell me what the system holds and who can reach it. I reply within one working day with the scope I would test first, free, and nothing is tested without written authorization.'],
            ],

            'architecture-review' => [
                'slug' => 'architecture-review',
                'group' => 'assess',
                'name' => 'Architecture Review',
                'short' => 'Options compared, a written recommendation, decision records.',
                'lead' => 'For a decision on stack, cloud, integration or build versus buy. I compare the options on cost, risk and fit with your team, and write down a recommendation you can hold me to.',
                'price' => 'Fixed price, EUR 1,440 excl. VAT (16 hours)',
                'price_note' => 'Two days. Bigger questions are quoted after the intro call, once the question is clear.',
                'features' => [
                    ['The question, written down', 'What is being decided, by when, and what a wrong answer costs.'],
                    ['Options compared', 'Two to four realistic routes, each with cost, risk, team fit and exit path.'],
                    ['Recommendation', 'One answer, with the reasons and the conditions under which it changes.'],
                    ['Roadmap', 'The first steps in order, with effort ranges.'],
                    ['Decision records', 'Short documents in your repository, so the reasoning survives staff changes.'],
                    ['Review session', 'One session with your team or your supplier to challenge the result.'],
                ],
                'steps' => [
                    'Intro call and the written question.',
                    'System overview, constraints and a named decision owner on your side.',
                    'Analysis and the written recommendation.',
                    'Review session and the final version.',
                ],
                'not' => 'I do not build the chosen design in this engagement and I do not negotiate with vendors for you. Both can follow as their own step.',
                'illus' => 'options',
                'cta' => ['What is the decision on the table?', 'Stack, cloud, integration, build or buy: write the question in one sentence. I reply within one working day with what I would need to look at before I answer it, free.'],
            ],

            'hardening' => [
                'slug' => 'hardening',
                'group' => 'run',
                'name' => 'Hardening and Secure Pipeline',
                'short' => 'Attack surface closed, security gates in CI, backups that restore.',
                'lead' => 'The server and the delivery pipeline made safe: ports closed, TLS current, secrets out of the code, dependency and secret scans on every change, and backups that have actually been restored. A written quality bar your team can keep.',
                'price' => 'Quoted per environment at '.self::HOURLY,
                'price_note' => 'Included in every modernization project. Bookable on its own for a system you inherited.',
                'features' => [
                    ['Server hardening', 'Firewall, SSH, TLS, updates, users and sudo, logging. Checked against a written list.'],
                    ['Secrets handled', 'Out of the repository, into a vault or environment, rotated where they leaked.'],
                    ['Security gates in CI', 'Dependency audit, secret scan and static analysis that fail the build.'],
                    ['Backups that restore', 'A restore performed and timed, written down with the recovery time.'],
                    ['Monitoring and alerts', 'Uptime, disk, certificates and failed jobs reach a person before a customer does.'],
                    ['Quality bar on paper', 'Numbers and the commands that check them, so nobody lowers it quietly.'],
                ],
                'steps' => [
                    'Intro call and read access to the server and the pipeline.',
                    'Findings list, ranked, with the hours per item.',
                    'Fixes in agreed order, each verified.',
                    'Written quality bar and the runbook handed over.',
                ],
                'not' => 'This is not a penetration test of the application itself; that is the Security Assessment. No uptime guarantees from one person.',
                'illus' => 'lock',
                'cta' => ['When was the last restore test?', 'If the answer is "never" or "not sure", that is the first thing I would do. Tell me where it runs and I reply within one working day, free.'],
            ],

            'maintenance-retainer' => [
                'slug' => 'maintenance-retainer',
                'group' => 'run',
                'name' => 'Maintenance Retainer',
                'short' => 'A monthly hours block: patches, monitoring, small changes, a report.',
                'lead' => 'For a system you want kept running by the person who knows it. A monthly block of hours for security patches, monitoring, backups, small changes and the questions in between, with a short report every month.',
                'price' => 'From EUR 720 a month excl. VAT (8 hours)',
                'price_note' => 'Blocks of 8, 16 or 24 hours a month at EUR 90. Unused hours are reported, not hidden.',
                'features' => [
                    ['Patching', 'Operating system, PHP, framework and dependencies kept in support.'],
                    ['Monitoring', 'Uptime, certificates, disk, queues and failed jobs watched, with a response target.'],
                    ['Backups', 'Checked, and restored for real on a schedule.'],
                    ['Small changes', 'Tickets picked from the block, in the order you set.'],
                    ['Monthly report', 'Hours used, what changed, what is coming up, what I recommend.'],
                    ['Emergency path', 'How to reach me, and what happens when I am not available.'],
                ],
                'steps' => [
                    'Intro call and a look at the system, or straight after a project of mine.',
                    'Block size and response targets agreed in writing.',
                    'Monthly cycle: patches, monitoring, tickets, report.',
                    'Review every quarter: size up, size down or stop.',
                ],
                'not' => 'New features beyond the block are quoted separately. Response times are targets, not guarantees: I am one person and I say so.',
                'illus' => 'retainer',
                'cta' => ['Who keeps it running after the developer leaves?', 'Tell me what it is and how often it changes. I reply within one working day with the block size I would start with, free.'],
            ],

            'compliance' => [
                'slug' => 'compliance',
                'group' => 'run',
                'name' => 'Compliance Alignment',
                'short' => 'NIS2, GDPR and DORA: gap analysis, remediation, evidence.',
                'lead' => 'For a company that has to show its technical controls to a regulator, an auditor or a bigger customer. A gap analysis against NIS2, GDPR or DORA, a remediation plan, and the controls implemented with evidence you can hand over.',
                'price' => 'Quoted per regime at '.self::HOURLY,
                'price_note' => 'The gap analysis is a fixed number of days; remediation is quoted per item from its findings.',
                'features' => [
                    ['Scope on paper', 'Which regime, which systems, which owners, which evidence is needed by whom.'],
                    ['Gap analysis', 'Technical controls checked against the requirement, in a table you can give to the auditor.'],
                    ['Remediation plan', 'Ranked by risk and effort, with hours per item.'],
                    ['Controls implemented', 'Access control, logging, backups, encryption, patching, incident handling.'],
                    ['Evidence pack', 'Screenshots, configuration exports and procedures, dated and stored where you choose.'],
                    ['Processor agreement', 'In place when I handle personal data for you, as the GDPR requires.'],
                ],
                'steps' => [
                    'Intro call: who is asking for what, by when.',
                    'Gap analysis, fixed days.',
                    'Remediation in agreed order.',
                    'Evidence pack and a handover to whoever faces the auditor.',
                ],
                'not' => 'This is technical work, not legal advice and not a certification. Fines, lawyers and the auditor itself are not in the quote.',
                'illus' => 'checklist',
                'cta' => ['Who is asking for the evidence, and by when?', 'Name the regime and the deadline. I reply within one working day with what the gap analysis would cover, free.'],
            ],

            'handover-pack' => [
                'slug' => 'handover-pack',
                'group' => 'run',
                'name' => 'Handover Pack',
                'short' => 'Runbook, architecture, decisions, accounts, risks. Yours to keep.',
                'lead' => 'Everything the next developer needs to run the system without me: how it works, how to deploy and roll back, how to restore a backup, which accounts exist and who owns them, the decisions made and the known risks. Included in every project, and bookable on its own for a system you inherited.',
                'price' => 'Included in every project. On its own: quoted at '.self::HOURLY,
                'price_note' => 'A system of typical size takes a few days to document properly.',
                'features' => [
                    ['Runbook', 'Deploy, roll back, restore, rotate a secret, add a user. Step by step, tested by following it.'],
                    ['Architecture notes', 'The parts, how they talk, where the data lives, with a diagram that matches reality.'],
                    ['Accounts and ownership', 'Every account and domain, in whose name, how to get in without me. No secrets in the document.'],
                    ['Decision records', 'Why things are the way they are, so nobody undoes a good decision by accident.'],
                    ['Known risks', 'What will break first, what it costs to fix, what to watch.'],
                    ['Contacts', 'Suppliers, hosting, domain, the people who can help.'],
                ],
                'steps' => [
                    'Read access and a walkthrough of what you have.',
                    'Drafting while following the procedures for real.',
                    'Review with your team or the next developer.',
                    'Final version in your repository.',
                ],
                'not' => 'No secrets in the pack itself, ever. It points to the vault, it does not replace it.',
                'illus' => 'docs',
                'cta' => ['Could someone else run it from the documentation tonight?', 'If that question makes you nervous, send me the name of the system. I reply within one working day with what the pack would contain, free.'],
            ],

        ];
    }

    /**
     * @return Product
     */
    public static function get(string $slug): array
    {
        $all = self::all();
        abort_unless(isset($all[$slug]), 404);

        return $all[$slug];
    }

    /**
     * @return array<string, list<Product>>
     */
    public static function grouped(): array
    {
        $out = [];
        foreach (array_keys(self::GROUPS) as $key) {
            $out[$key] = [];
        }
        foreach (self::all() as $p) {
            $out[$p['group']][] = $p;
        }

        return $out;
    }

    /**
     * @param  Product  $p
     */
    public static function url(array $p): string
    {
        return isset($p['route']) ? route($p['route']) : route('scuttle.product', ['slug' => $p['slug']]);
    }

    /**
     * The public https address, for canonical tags and the sitemap.
     *
     * @param  Product  $p
     */
    public static function canonical(array $p): string
    {
        return 'https://scuttle.dev'.(string) parse_url(self::url($p), PHP_URL_PATH);
    }
}
