<?php

namespace Spdotdev\ScuttleDev;

/**
 * The products Scuttle Development sells, in three groups. One source for the
 * products menu, the home page tiles, the pricing page and the product pages.
 *
 * Prices: only the Legacy Health Check has a set price. Every other product is
 * quoted per step at EUR 90 per hour excl. VAT, after a free intro call.
 *
 * @phpstan-type Product array{slug:string, group:string, name:string, short:string, lead:string, price:string, price_note:string, features:list<array{0:string,1:string}>, steps:list<string>, not:string, illus:string, cta:array{0:string,1:string}, nl:array{lead:string, short:string, price:string, price_note:string, features:list<array{0:string,1:string}>, steps:list<string>, not:string, cta:array{0:string,1:string}}, route?:string}
 */
final class Catalog
{
    public const HOURLY = 'EUR 90 per hour excl. VAT';

    /** Search titles: the service and what it is, so the result says more than the name (council round 2, 2026-10-30). */
    public const SEO_TITLES = [
        'ai-integration' => 'AI Agents and Skills: agents, MCP servers and RAG',
        'integrations' => 'System Integration for bookings, payments, ERP and mail',
        'full-stack-build' => 'Full-stack Build: a web application end to end',
        'modernization' => 'Modernization: PHP, framework and hosting up to date',
        'android-app' => 'Mobile App: Android in Kotlin and Jetpack Compose',
        'security-assessment' => 'Security Test: vulnerability scan or pentest',
        'architecture-review' => 'Architecture Plan: options and a recommendation',
        'hardening' => 'Hardening and Secure Pipeline for your servers',
        'maintenance-retainer' => 'Maintenance Retainer: patches and monitoring monthly',
        'compliance' => 'Compliance Alignment: NIS2, GDPR and DORA',
        'handover-pack' => 'Handover Pack: runbook, architecture, decisions',
        'legacy-health-check' => 'Health Check: two-day assessment, fixed price',
    ];

    /** @var array<string, string> */
    public const SEO_TITLES_NL = [
        'ai-integration' => 'AI Agents and Skills: agents, MCP-servers en RAG',
        'integrations' => 'System Integration: systemen die betrouwbaar praten',
        'full-stack-build' => 'Full-stack Build: een webapplicatie van begin tot eind',
        'modernization' => 'Modernization: PHP, framework en hosting bijgewerkt',
        'android-app' => 'Mobile App: Android in Kotlin en Jetpack Compose',
        'security-assessment' => 'Security Test: kwetsbaarhedenscan of pentest',
        'architecture-review' => 'Architecture Plan: opties en een advies',
        'hardening' => 'Hardening and Secure Pipeline voor je servers',
        'maintenance-retainer' => 'Maintenance Retainer: patches en monitoring per maand',
        'compliance' => 'Compliance Alignment: NIS2, AVG en DORA',
        'handover-pack' => 'Handover Pack: runbook, architectuur, besluiten',
        'legacy-health-check' => 'Health Check: beoordeling in twee dagen, vaste prijs',
    ];

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

    /** @var array<string, string> group key => Dutch label (same words as g_* in the layout) */
    public const GROUPS_NL = [
        'build' => 'Automatiseren',
        'assess' => 'Beoordelen',
        'run' => 'Beheren',
    ];

    /**
     * The development loop on the home page (proposal 14, layout B, Stas 2026-10-31): the
     * steps an engagement goes through, in order; 'ongoing' is the hub in the middle, not
     * a step. Each: [label, label NL, line, line NL, verb, verb NL]. The menu keeps GROUPS.
     *
     * @var array<string, array{0:string,1:string,2:string,3:string,4:string,5:string}>
     */
    public const STEPS = [
        'research' => ['Research', 'Onderzoeken', 'One look at the whole system before anything is decided.', 'Eén blik op het hele systeem voordat iets wordt besloten.', 'Measure, ask, understand', 'Meten, vragen, begrijpen'],
        'plan' => ['Plan', 'Plannen', 'Decide what gets built, in which order, and what it costs.', 'Bepalen wat er gebouwd wordt, in welke volgorde en wat het kost.', 'Options, decisions, roadmap', 'Opties, besluiten, routekaart'],
        'build' => ['Build', 'Bouwen', 'Build what is missing, modernize what runs, connect it, automate it.', 'Bouwen wat ontbreekt, moderniseren wat draait, koppelen, automatiseren.', 'Web, mobile, legacy, integration, AI', 'Web, mobiel, legacy, koppelingen, AI'],
        'review' => ['Review', 'Toetsen', 'Test what was built before anyone depends on it.', 'Testen wat gebouwd is voordat iemand erop vertrouwt.', 'Pentest, code, tests', 'Pentest, code, tests'],
        'deploy' => ['Deploy', 'Uitrollen', 'Ship it locked down, backed up and written down. Then look again.', 'Live brengen: dichtgezet, geback-upt en opgeschreven. Dan opnieuw kijken.', 'Pipeline, hardening, handover', 'Pipeline, hardening, overdracht'],
        'ongoing' => ['Ongoing', 'Doorlopend', 'Not a step: it runs the whole time the system runs.', 'Geen stap: het loopt zolang het systeem draait.', 'Patches, monitoring, evidence', 'Patches, monitoring, bewijs'],
    ];

    /** The two groups inside Build: key => [label, label NL]. @var array<string, array{0:string,1:string}> */
    public const SUBS = [
        'general' => ['General', 'Algemeen'],
        'ai' => ['AI and automation', 'AI en automatisering'],
    ];

    /**
     * Loop entries without a service page yet (they open the contact page): key => [step, sub or null, name, short, short NL].
     * Names stay English, like every service name on the site.
     *
     * @var array<string, array{0:string,1:?string,2:string,3:string,4:string}>
     */
    public const EXTRA = [
        'discovery-session' => ['research', null, 'Discovery Session', 'Half a day with your team: what repeats, what hurts, what to automate first.', 'Een halve dag met je team: wat herhaalt zich, waar wringt het, wat automatiseren we eerst.'],
        'risk-scan' => ['research', null, 'Dependency and Risk Scan', 'Automated check of versions, end-of-life dates and known vulnerabilities. A one-page result within a day.', 'Geautomatiseerde check van versies, end-of-lifedatums en bekende kwetsbaarheden. Eén pagina resultaat binnen een dag.'],
        'proof-of-concept' => ['plan', null, 'Proof of Concept', 'The risky part built small first, so the plan rests on something that runs.', 'Het riskante deel eerst klein gebouwd, zodat het plan rust op iets dat draait.'],
        'specification' => ['plan', null, 'Specification and Estimate', 'A written spec with acceptance criteria, and a fixed quote on it. Spec first, then code.', 'Een geschreven specificatie met acceptatiecriteria, en een vaste offerte daarop. Eerst de spec, dan de code.'],
        'workflow-automation' => ['build', 'ai', 'Workflow Automation', 'Scheduled jobs, pipelines and scripts that replace the manual steps between systems.', 'Geplande taken, pipelines en scripts die de handmatige stappen tussen systemen vervangen.'],
        'code-review' => ['review', null, 'Code and Test Review', 'Code, tests and CI read by a second pair of eyes before go-live, with a written list.', 'Code, tests en CI gelezen door een tweede paar ogen voor de livegang, met een geschreven lijst.'],
        'performance-test' => ['review', null, 'Performance Test', 'Load test and slow-query hunt with real numbers before and after; the fixes included.', 'Belastingtest en jacht op trage query\'s met echte cijfers voor en na; de fixes inbegrepen.'],
        'hosting-cutover' => ['deploy', null, 'Hosting and Cutover', 'Servers, containers and backups set up; the switch-over planned with a rollback, done on a quiet day.', 'Servers, containers en back-ups ingericht; de overgang gepland met een rollback, op een rustige dag.'],
        'monitoring' => ['ongoing', null, 'Monitoring and Alerts', 'Uptime, errors and security advisories watched, so you hear it before your users do.', 'Uptime, fouten en beveiligingsmeldingen in de gaten gehouden, zodat jij het eerder hoort dan je gebruikers.'],
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
                'step' => 'build',
                'sub' => 'ai',
                'name' => 'AI Agents and Skills',
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
                'nl' => [
                    'lead' => 'Eén workflow waar AI meetbaar tijd bespaart, naar productie gebracht: een Claude-agent met eigen skills, een MCP-server die hem benoemde tools geeft op je systemen, retrieval (RAG) over je eigen documenten, en browserautomatisering waar geen API is. De waarde zit in het veilig verbinden van het model met jouw kennis en jouw tools. Ik draai mijn eigen bedrijf op dit patroon.',
                    'short' => 'Agents, eigen skills, MCP-servers, RAG over je data. Eén use case naar productie.',
                    'price' => 'Op offerte per use case, EUR 90 per uur excl. btw',
                    'price_note' => 'Modelkosten zijn voor jou en begrensd. Een evaluatieset bepaalt of het live gaat.',
                    'features' => [
                        ['Eerst één use case', 'Gekozen op meetbaar bespaarde uren, niet op de demo. Facturen inlezen, leads opvolgen, tickets sorteren, rapportage, een vragenbox over je data.'],
                        ['Agents met eigen skills', 'Een Claude-agent die in stappen met je tools werkt en geschreven skills volgt: jouw werkwijze, jouw controles, jouw toon.'],
                        ['MCP-server op je systemen', 'Model Context Protocol: benoemde tools met rechten vóór je API of database, zodat elke assistent ze kan gebruiken en elke aanroep gelogd wordt.'],
                        ['RAG over je documenten', 'Retrieval met bronnen bij elk antwoord; embeddings lokaal gebouwd als de data je servers niet mag verlaten.'],
                        ['Evaluatieset en vangrails', 'Echte voorbeelden met bekende antwoorden, wat de agent nooit mag doen, wat een mens moet doen, een maandelijks kostenplafond en een gedocumenteerde uitschakelknop.'],
                        ['Kleine modellen waar het past', 'Gefinetunede of lokale modellen (Ollama, Whisper, een getraind wake word) voor taken die snel, offline of goedkoop moeten zijn.'],
                    ],
                    'steps' => [
                        'Introgesprek: waar gaan de uren nu heen, hoe ziet een goed antwoord eruit, welke data mag het pand verlaten.',
                        'Evaluatieset en een dun prototype daartegen, met het model gekozen per stap.',
                        'Productiebouw met de MCP-tools, retrieval, vangrails, logging en het kostenplafond.',
                        'Overdracht, met het runbook en de evaluatieset zodat je latere wijzigingen en modelwissels zelf kunt beoordelen.',
                    ],
                    'not' => 'Geen gegarandeerde nauwkeurigheid, geen agent met schrijftoegang tot productie zonder menselijke stap, geen vendor lock-in: het model is per ontwerp verwisselbaar.',
                    'cta' => ['Welke taak geef je als eerste aan een agent?', 'Vertel me waar de uren nu heen gaan en hoe een goed antwoord eruitziet. Binnen één werkdag antwoord ik met de use case waarmee ik zou beginnen, gratis.'],
                ],
            ],

            'integrations' => [
                'slug' => 'integrations',
                'group' => 'build',
                'step' => 'build',
                'sub' => 'ai',
                'name' => 'System Integration',
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
                'nl' => [
                    'lead' => 'De systemen koppelen die je al gebruikt: boekingssystemen, betaalproviders, ERP, mailplatforms, scanners en partner-API\'s. Gebouwd om retries, storingen en een andere kant die van gedachten verandert te overleven.',
                    'short' => 'Systemen die betrouwbaar met elkaar praten: boekingen, betalingen, ERP, mail.',
                    'price' => 'Op offerte per koppeling, EUR 90 per uur excl. btw',
                    'price_note' => 'Bij dunne documentatie gaat eerst een spike van een paar uur tegen de echte API vooraf.',
                    'features' => [
                        ['Idempotent door ontwerp', 'Een webhook die twee keer binnenkomt of een job die opnieuw draait incasseert, mailt of boekt nooit dubbel.'],
                        ['Queues en retries', 'Het werk gebeurt op de achtergrond met backoff, dead letters en alerts.'],
                        ['Mapping op papier', 'Welk veld waarheen gaat, wat de bron van waarheid is, wat er bij een conflict gebeurt.'],
                        ['Monitoring', 'Je ziet de laatste geslaagde sync en de fouten, geen stille gaten.'],
                        ['Eerst sandbox', 'Elke provider getest tegen zijn testomgeving voordat er productiesleutels bestaan.'],
                        ['Runbook', 'Hoe je opnieuw draait, hoe je herhaalt, wie je belt aan de andere kant.'],
                    ],
                    'steps' => [
                        'Introgesprek: de systemen, de richting van de data, de volumes.',
                        'Spike tegen de echte API, daarna een vaste scope.',
                        'Bouwen met tests en een run in de sandbox.',
                        'Overgang naar productie met monitoring en het runbook.',
                    ],
                    'not' => 'Geen scraping van systemen zonder API en zonder akkoord van de eigenaar, geen overdracht met "het werkt bij mij". Partnerkosten en licenties zijn van hen, niet in mijn offerte.',
                    'cta' => ['Welke twee systemen moeten met elkaar praten?', 'Noem ze en de richting van de data. Binnen één werkdag antwoord ik hoe ik ze zou koppelen en waar je op moet letten, gratis.'],
                ],
            ],

            'full-stack-build' => [
                'slug' => 'full-stack-build',
                'group' => 'build',
                'step' => 'build',
                'sub' => 'general',
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
                'nl' => [
                    'lead' => 'Een nieuwe webapplicatie van begin tot eind gebouwd door één persoon: het ontwerp van de schermen, de architectuur, de backend, de front end, de API, de hosting en het releaseproces. Voor een idee, een spreadsheetproces of een product dat zijn eerste versie ontgroeid is.',
                    'short' => 'Ontwerp, architectuur, backend, front end, API, hosting: een nieuwe applicatie van begin tot eind gebouwd.',
                    'price' => 'Op offerte per mijlpaal, EUR 90 per uur excl. btw',
                    'price_note' => 'Een eerste werkende versie is een eigen mijlpaal, zodat je het ziet draaien voordat de rest gepland wordt.',
                    'features' => [
                        ['Ontwerp', 'Eerst samen schermen schetsen: wat elke gebruiker ziet en doet, in de volgorde waarin hij het doet. Gewone, snelle pagina\'s boven decoratie.'],
                        ['Architectuur', 'Datamodel, rollen en tenancy, wat met wat praat, wat nooit mag falen. Vastgelegd als beslisnotities vóór de eerste regel code.'],
                        ['Backend', 'Laravel: authenticatie, rollen, queues, mail, geplande jobs, Filament-beheerschermen, multi-tenant waar het product dat nodig heeft.'],
                        ['Front end', 'Standaard server-rendered Blade met Alpine.js: geen buildpijplijn om te onderhouden, werkt op elk apparaat. Een single-page app alleen als het product er echt een nodig heeft.'],
                        ['API en koppelingen', 'REST-endpoints voor apps en partners, webhooks die veilig tegen retries zijn, een MCP-server als AI-assistenten het moeten kunnen gebruiken.'],
                        ['Hosting, tests en release', 'Docker op een server die van jou is, back-ups, monitoring, TLS; featuretests bij elke wijziging, CI die rode merges blokkeert, deploy en rollback met één commando.'],
                    ],
                    'steps' => [
                        'Introgesprek en een scope van één pagina: wie het gebruikt, wat het eerst moet kunnen.',
                        'Mijlpaal één: de kleinste versie die echt werk doet, live op staging.',
                        'Mijlpaal twee en verder, elk vooraf afgebakend en geprijsd.',
                        'Live, overdrachtspakket, optioneel een retainer.',
                    ],
                    'not' => 'Geen ontwerpbureau ertussen, geen framework om het framework. Ik bouw wat is afgesproken, en ik zeg het als een feature zijn prijs niet waard is.',
                    'cta' => ['Wat moet de eerste versie kunnen?', 'Beschrijf het ene ding dat het op dag één moet doen. Binnen één werkdag antwoord ik wat mijlpaal één zou bevatten en ongeveer kost, gratis.'],
                ],
            ],

            'modernization' => [
                'slug' => 'modernization',
                'group' => 'build',
                'step' => 'build',
                'sub' => 'general',
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
                'nl' => [
                    'lead' => 'Alles onder je applicatie bijgewerkt en toekomstbestendig gemaakt: de PHP-versie, het framework, de database, de webserver, de manier van hosten en de manier van releasen. In stappen, terwijl het systeem de hele tijd in gebruik blijft, zodat niets stilvalt en elke stap teruggedraaid kan worden.',
                    'short' => 'PHP, framework, database, webserver en hosting bijgewerkt, terwijl het systeem blijft draaien.',
                    'price' => 'Op offerte per stap, EUR 90 per uur excl. btw',
                    'price_note' => 'Elke stap krijgt een eigen scope en prijs voordat hij begint. Je kunt na elke stap stoppen.',
                    'features' => [
                        ['PHP-upgrades', '7.x naar 8.x, 8.1 naar 8.4 of 8.5: deprecations opgelost, extensies passend gemaakt, de testsuite die het bewijst vóór de omschakeling.'],
                        ['Framework-upgrades en verhuizingen', 'Oude Laravel naar huidige Laravel, Phalcon of een eigen framework naar Laravel, module voor module, oud en nieuw naast elkaar.'],
                        ['Database en runtime', 'MySQL 5.7 naar 8, MariaDB, Redis, PHP-FPM-tuning; het besturingssysteem weer onder support gebracht.'],
                        ['Webserver en TLS', 'Apache of nginx naar Caddy waar dat loont: automatische certificaten, HTTP/2 en 3, één leesbare config.'],
                        ['Containers en hosting', 'Docker op een server die van jou is, standaard DigitalOcean; staging en productie dezelfde machine; een verhuizing van shared hosting of een oude VPS inbegrepen.'],
                        ['Releaseproces', 'Git, CI met tests en beveiligingscontroles, deploy met één commando, rollback met één commando. Dependencies volgens schema geaudit en bijgewerkt.'],
                    ],
                    'steps' => [
                        'Health Check of een bestaand plan: de volgorde van de stappen, eerst het vangnet.',
                        'Stap één afgebakend, geprijsd en goedgekeurd; tests geschreven rond het huidige gedrag.',
                        'Bouwen, testen, releasen, controleren. Kleine releases, waar mogelijk wekelijks.',
                        'Volgende stap, of stoppen. Het overdrachtspakket wordt na elke stap bijgewerkt.',
                    ],
                    'not' => 'Geen herbouw vanaf nul, geen branch van drie maanden, geen verborgen licentiekosten in de offerte. Als een stap groter blijkt dan geoffreerd, hoor je dat voordat de uren zijn besteed.',
                    'cta' => ['Welke laag in je stack is het oudst?', 'Vertel me de PHP-versie, het framework en waar het gehost wordt. Binnen één werkdag antwoord ik met de eerste stap die ik zou zetten, gratis.'],
                ],
            ],

            'android-app' => [
                'slug' => 'android-app',
                'group' => 'build',
                'step' => 'build',
                'sub' => 'general',
                'name' => 'Mobile App',
                'short' => 'Android now, iOS on request: Kotlin and Jetpack Compose on your API or a new Laravel backend.',
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
                'nl' => [
                    'lead' => 'Een native Android-app in Kotlin met Jetpack Compose, bovenop de API die je hebt of een nieuwe Laravel-backend die ernaast gebouwd wordt. Voor buitendienst, scanners, interne tools en producten die met een slechte verbinding moeten werken.',
                    'short' => 'Android nu, iOS op aanvraag: Kotlin en Jetpack Compose op je API of een nieuwe Laravel-backend.',
                    'price' => 'Op offerte per mijlpaal, EUR 90 per uur excl. btw',
                    'price_note' => 'Interne distributie of Play Store, jouw keuze; de storeaccounts blijven op jouw naam.',
                    'features' => [
                        ['Kotlin en Compose', 'Actuele Android-tooling, geen cross-platformlaag om later te debuggen.'],
                        ['Werkt offline', 'Lokale opslag en sync, zodat een verbroken verbinding geen werk kost.'],
                        ['Camera en scannen', 'Barcodes, foto\'s en documenten rechtstreeks in je systeem.'],
                        ['Backend inbegrepen', 'Een Laravel-API met authenticatie, rollen en een beheerpaneel als je er geen hebt.'],
                        ['Releasepijplijn', 'Ondertekende builds vanuit CI, interne testtrack, versie-releases.'],
                        ['Testen op apparaten', 'Getest op echte apparaten, niet alleen in de emulator.'],
                    ],
                    'steps' => [
                        'Introgesprek: wie het gebruikt, waar, op welke apparaten.',
                        'Mijlpaal één: het hoofdscherm dat echt werk doet met jouw data.',
                        'Verdere mijlpalen, elk afgebakend en geprijsd.',
                        'Release, overdracht, storevermelding als je dat wilt.',
                    ],
                    'not' => 'Geen iOS in dit aanbod, geen app omwille van de app als een responsive webpagina het werk doet. Dat zeg ik in het introgesprek.',
                    'cta' => ['Wie gebruikt de app, en waar?', 'Vertel me welke taak het op de werkvloer of in het veld moet doen. Binnen één werkdag antwoord ik met het eerste scherm dat ik zou bouwen, gratis.'],
                ],
            ],

            'legacy-health-check' => [
                'slug' => 'legacy-health-check',
                'route' => 'scuttle.health-check',
                'group' => 'assess',
                'step' => 'research',
                'name' => 'Health Check',
                'short' => 'Two days: health, security and architecture in one report, with a step-by-step plan.',
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
                'nl' => [
                    'lead' => 'Een beoordeling in twee dagen van je applicatie en de manier waarop die draait. Je krijgt een schriftelijk rapport over waar automatisering uren bespaart, wat risicovol is, welke onderdelen buiten support zijn (de legacy-check) en een plan in stappen dat het bedrijf niet stilzet.',
                    'short' => 'Twee dagen: gezondheid, beveiliging en architectuur in één rapport, met een plan in stappen.',
                    'price' => 'Vaste prijs, EUR 1.080 excl. btw (12 uur)',
                    'price_note' => 'Twee dagen review, 12 uur × EUR 90. Een gratis introgesprek gaat vooraf.',
                    'features' => [
                        ['Schriftelijk rapport', 'In gewone taal, bevindingen gerangschikt op waarde en risico, uren per punt.'],
                        ['Kansen voor automatisering', 'Het repeterende werk in en rond het systeem, met de uren die het nu kost en wat een agent of koppeling zou besparen.'],
                        ['Legacy-check', 'Besturingssysteem, PHP, database, framework en belangrijke dependencies naast hun officiële end-of-lifedatums gelegd.'],
                        ['Snelle beveiligingsscan', 'Configuratie, dependencies met bekende kwetsbaarheden, omgang met secrets, basis van serverhardening.'],
                        ['Architectuurreview', 'Waar wijzigen riskant is, verborgen koppelingen, hoe goed de code door tests gedekt is.'],
                        ['Plan in stappen', 'Volgorde van het werk en bandbreedtes voor de inspanning. Oud en nieuw draaien naast elkaar, niets valt stil.'],
                        ['Doorloopgesprek', 'We lopen het rapport samen door en ik beantwoord elke vraag.'],
                    ],
                    'steps' => [
                        'Een kort introgesprek, gratis, om te checken dat de Health Check bij je situatie past.',
                        'Je geeft me leestoegang tot de code en een korte rondleiding over hoe het gehost wordt.',
                        'Twee dagen review.',
                        'Het rapport, gevolgd door het doorloopgesprek.',
                    ],
                    'not' => 'Het is geen herbouw, het is geen penetratietest en ik breng geen wijzigingen aan in je productiesystemen. Ik heb geen productiedata nodig. Als het rapport laat zien dat er meer nodig is, krijg je een duidelijke scope en prijs voordat iets begint.',
                    'cta' => ['Weet je niet waar je moet beginnen? Begin hier.', 'Twaalf uur, alleen-lezen, een plan waar je me aan kunt houden. Vertel me wat je draait en ik controleer of de Health Check past voordat er iets geboekt wordt.'],
                ],
            ],

            'security-assessment' => [
                'slug' => 'security-assessment',
                'group' => 'assess',
                'step' => 'review',
                'name' => 'Security Test',
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
                'nl' => [
                    'lead' => 'Een kwetsbaarhedenonderzoek of penetratietest van een webapplicatie, API of serveromgeving die van jou is. Bevindingen gerangschikt op ernst, elk met adviezen om te herstellen, en een hertest als de fixes erin zitten.',
                    'short' => 'Kwetsbaarhedenscan of pentest van een applicatie, met een hertest.',
                    'price' => 'Quick scan EUR 720 excl. btw (8 uur); volledig assessment op offerte per fase, EUR 90 per uur excl. btw',
                    'price_note' => 'Scope, testvenster en schriftelijke toestemming zijn afgesproken voordat er iets getest wordt.',
                    'features' => [
                        ['Eerst schriftelijke toestemming', 'Scope, testvenster, noodcontacten en toestemming van derden als je elders host.'],
                        ['Applicatietesten', 'Authenticatie, toegangscontrole, injectie, bedrijfslogica, sessiebeheer, bestandsafhandeling.'],
                        ['Infrastructuurcheck', 'Open poorten, TLS-configuratie, blootgestelde services, verouderde software met bekende kwetsbaarheden.'],
                        ['Gerangschikte bevindingen', 'Ernst, bewijs, impact in bedrijfstermen en de oplossing per punt.'],
                        ['Hersteladvies', 'Concrete wijzigingen voor je developers, of ik voer ze samen met jou door.'],
                        ['Hertest', 'Elke bevinding na de fix opnieuw gecontroleerd, met een sluitrapport.'],
                    ],
                    'steps' => [
                        'Introgesprek: wat je draait, waar je je zorgen over maakt, wat onaangeroerd moet blijven.',
                        'Scope en spelregels op papier, door jou ondertekend.',
                        'Testen binnen het afgesproken venster, alleen-lezen op productie tenzij anders afgesproken.',
                        'Rapport en doorloopgesprek, daarna de hertest na jouw fixes.',
                    ],
                    'not' => 'Geen testen zonder schriftelijke toestemming, geen denial of service, geen social engineering tenzij het in de scope genoemd is. Ik test geen systemen die niet van jou zijn.',
                    'cta' => ['Wat zou het meeste pijn doen als het uitlekt?', 'Vertel me wat het systeem bevat en wie erbij kan. Binnen één werkdag antwoord ik met de scope die ik als eerste zou testen, gratis, en er wordt niets getest zonder schriftelijke toestemming.'],
                ],
            ],

            'architecture-review' => [
                'slug' => 'architecture-review',
                'group' => 'assess',
                'step' => 'plan',
                'name' => 'Architecture Plan',
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
                'nl' => [
                    'lead' => 'Voor een besluit over stack, cloud, koppeling of zelf bouwen versus kopen. Ik vergelijk de opties op kosten, risico en pasvorm met je team, en schrijf een advies op waar je me aan kunt houden.',
                    'short' => 'Opties vergeleken, een schriftelijk advies, een besluitenlogboek.',
                    'price' => 'Vaste prijs, EUR 1.440 excl. btw (16 uur)',
                    'price_note' => 'Twee dagen. Grotere vragen worden na het introgesprek geoffreerd, als de vraag duidelijk is.',
                    'features' => [
                        ['De vraag, opgeschreven', 'Wat er besloten wordt, voor wanneer, en wat een verkeerd antwoord kost.'],
                        ['Opties vergeleken', 'Twee tot vier realistische routes, elk met kosten, risico, teampasvorm en uitweg.'],
                        ['Advies', 'Eén antwoord, met de redenen en de voorwaarden waaronder het verandert.'],
                        ['Roadmap', 'De eerste stappen op volgorde, met bandbreedtes voor de inspanning.'],
                        ['Beslisnotities', 'Korte documenten in je repository, zodat de redenering personeelswisselingen overleeft.'],
                        ['Reviewsessie', 'Eén sessie met je team of leverancier om het resultaat uit te dagen.'],
                    ],
                    'steps' => [
                        'Introgesprek en de opgeschreven vraag.',
                        'Systeemoverzicht, randvoorwaarden en een benoemde beslisser aan jouw kant.',
                        'Analyse en het schriftelijke advies.',
                        'Reviewsessie en de definitieve versie.',
                    ],
                    'not' => 'Ik bouw het gekozen ontwerp niet in deze opdracht en ik onderhandel niet met leveranciers voor je. Beide kunnen als eigen stap volgen.',
                    'cta' => ['Welk besluit ligt er op tafel?', 'Stack, cloud, koppeling, bouwen of kopen: schrijf de vraag in één zin. Binnen één werkdag antwoord ik wat ik zou willen bekijken voordat ik hem beantwoord, gratis.'],
                ],
            ],

            'hardening' => [
                'slug' => 'hardening',
                'group' => 'run',
                'step' => 'deploy',
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
                'not' => 'This is not a penetration test of the application itself; that is the Security Test. No uptime guarantees from one person.',
                'illus' => 'lock',
                'cta' => ['When was the last restore test?', 'If the answer is "never" or "not sure", that is the first thing I would do. Tell me where it runs and I reply within one working day, free.'],
                'nl' => [
                    'lead' => 'De server en de leveringspijplijn veilig gemaakt: poorten dicht, TLS actueel, secrets uit de code, scans op dependencies en secrets bij elke wijziging, en back-ups die echt zijn teruggezet. Een schriftelijke kwaliteitslat die je team kan vasthouden.',
                    'short' => 'Aanvalsoppervlak gesloten, beveiligingscontroles in CI, back-ups die terug te zetten zijn.',
                    'price' => 'Op offerte per omgeving, EUR 90 per uur excl. btw',
                    'price_note' => 'Inbegrepen in elk moderniseringsproject. Los te boeken voor een systeem dat je hebt geërfd.',
                    'features' => [
                        ['Serverhardening', 'Firewall, SSH, TLS, updates, gebruikers en sudo, logging. Gecontroleerd aan de hand van een schriftelijke lijst.'],
                        ['Secrets op orde', 'Uit de repository, naar een vault of omgeving, geroteerd waar ze zijn uitgelekt.'],
                        ['Beveiligingscontroles in CI', 'Dependency-audit, secretscan en statische analyse die de build laten falen.'],
                        ['Back-ups die terug te zetten zijn', 'Een restore uitgevoerd en getimed, opgeschreven met de hersteltijd.'],
                        ['Monitoring en alerts', 'Uptime, schijf, certificaten en mislukte jobs bereiken een mens voordat een klant het doet.'],
                        ['Kwaliteitslat op papier', 'Cijfers en de commando\'s die ze controleren, zodat niemand de lat ongemerkt verlaagt.'],
                    ],
                    'steps' => [
                        'Introgesprek en leestoegang tot de server en de pijplijn.',
                        'Lijst met bevindingen, gerangschikt, met de uren per punt.',
                        'Fixes in afgesproken volgorde, elk gecontroleerd.',
                        'Schriftelijke kwaliteitslat en het runbook overgedragen.',
                    ],
                    'not' => 'Dit is geen penetratietest van de applicatie zelf; dat is de Security assessment. Geen uptimegaranties van één persoon.',
                    'cta' => ['Wanneer was de laatste restoretest?', 'Als het antwoord "nooit" of "weet ik niet" is, is dat het eerste wat ik zou doen. Vertel me waar het draait en ik antwoord binnen één werkdag, gratis.'],
                ],
            ],

            'maintenance-retainer' => [
                'slug' => 'maintenance-retainer',
                'group' => 'run',
                'step' => 'ongoing',
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
                'nl' => [
                    'lead' => 'Voor een systeem dat je wilt laten draaien door de persoon die het kent. Een maandelijks blok uren voor beveiligingspatches, monitoring, back-ups, kleine wijzigingen en de vragen ertussendoor, met elke maand een kort rapport.',
                    'short' => 'Een maandelijks urenblok: patches, monitoring, kleine wijzigingen, een rapport.',
                    'price' => 'Vanaf EUR 720 per maand excl. btw (8 uur)',
                    'price_note' => 'Blokken van 8, 16 of 24 uur per maand à EUR 90. Ongebruikte uren worden gerapporteerd, niet verstopt.',
                    'features' => [
                        ['Patchen', 'Besturingssysteem, PHP, framework en dependencies onder support gehouden.'],
                        ['Monitoring', 'Uptime, certificaten, schijf, queues en mislukte jobs bewaakt, met een reactiedoel.'],
                        ['Back-ups', 'Gecontroleerd, en volgens schema echt teruggezet.'],
                        ['Kleine wijzigingen', 'Tickets uit het blok gepakt, in de volgorde die jij bepaalt.'],
                        ['Maandrapport', 'Gebruikte uren, wat er veranderd is, wat eraan komt, wat ik adviseer.'],
                        ['Noodpad', 'Hoe je me bereikt, en wat er gebeurt als ik niet beschikbaar ben.'],
                    ],
                    'steps' => [
                        'Introgesprek en een blik op het systeem, of direct na een project van mij.',
                        'Blokgrootte en reactiedoelen schriftelijk afgesproken.',
                        'Maandelijkse cyclus: patches, monitoring, tickets, rapport.',
                        'Elk kwartaal een review: groter, kleiner of stoppen.',
                    ],
                    'not' => 'Nieuwe features buiten het blok worden apart geoffreerd. Reactietijden zijn doelen, geen garanties: ik ben één persoon en ik zeg dat eerlijk.',
                    'cta' => ['Wie houdt het draaiend nadat de developer weg is?', 'Vertel me wat het is en hoe vaak het verandert. Binnen één werkdag antwoord ik met de blokgrootte waarmee ik zou beginnen, gratis.'],
                ],
            ],

            'compliance' => [
                'slug' => 'compliance',
                'group' => 'run',
                'step' => 'ongoing',
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
                'nl' => [
                    'lead' => 'Voor een bedrijf dat zijn technische maatregelen moet aantonen aan een toezichthouder, een auditor of een grotere klant. Een gap-analyse tegen NIS2, AVG of DORA, een herstelplan, en de maatregelen ingevoerd met bewijs dat je kunt overhandigen.',
                    'short' => 'NIS2, AVG en DORA: gap-analyse, herstel, bewijs.',
                    'price' => 'Op offerte per regime, EUR 90 per uur excl. btw',
                    'price_note' => 'De gap-analyse is een vast aantal dagen; herstel wordt per punt geoffreerd op basis van de bevindingen.',
                    'features' => [
                        ['Scope op papier', 'Welk regime, welke systemen, welke eigenaren, welk bewijs door wie nodig is.'],
                        ['Gap-analyse', 'Technische maatregelen getoetst aan de eis, in een tabel die je aan de auditor kunt geven.'],
                        ['Herstelplan', 'Gerangschikt op risico en inspanning, met uren per punt.'],
                        ['Maatregelen ingevoerd', 'Toegangsbeheer, logging, back-ups, versleuteling, patchen, incidentafhandeling.'],
                        ['Bewijspakket', 'Screenshots, configuratie-exports en procedures, gedateerd en opgeslagen waar jij kiest.'],
                        ['Verwerkersovereenkomst', 'Aanwezig als ik persoonsgegevens voor je verwerk, zoals de AVG vereist.'],
                    ],
                    'steps' => [
                        'Introgesprek: wie wat vraagt, voor wanneer.',
                        'Gap-analyse, vaste dagen.',
                        'Herstel in afgesproken volgorde.',
                        'Bewijspakket en een overdracht aan wie de auditor te woord staat.',
                    ],
                    'not' => 'Dit is technisch werk, geen juridisch advies en geen certificering. Boetes, advocaten en de auditor zelf zitten niet in de offerte.',
                    'cta' => ['Wie vraagt om het bewijs, en voor wanneer?', 'Noem het regime en de deadline. Binnen één werkdag antwoord ik wat de gap-analyse zou omvatten, gratis.'],
                ],
            ],

            'handover-pack' => [
                'slug' => 'handover-pack',
                'group' => 'run',
                'step' => 'deploy',
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
                'nl' => [
                    'lead' => 'Alles wat de volgende developer nodig heeft om het systeem zonder mij te draaien: hoe het werkt, hoe je deployt en terugdraait, hoe je een back-up terugzet, welke accounts er zijn en van wie, de genomen beslissingen en de bekende risico\'s. Inbegrepen in elk project, en los te boeken voor een systeem dat je hebt geërfd.',
                    'short' => 'Runbook, architectuur, beslissingen, accounts, risico\'s. Blijft van jou.',
                    'price' => 'Inbegrepen in elk project. Los: op offerte, EUR 90 per uur excl. btw',
                    'price_note' => 'Een systeem van gebruikelijke omvang vergt een paar dagen om goed te documenteren.',
                    'features' => [
                        ['Runbook', 'Deployen, terugdraaien, restoren, een secret roteren, een gebruiker toevoegen. Stap voor stap, getest door het te volgen.'],
                        ['Architectuurnotities', 'De onderdelen, hoe ze praten, waar de data staat, met een diagram dat klopt met de werkelijkheid.'],
                        ['Accounts en eigenaarschap', 'Elk account en domein, op wiens naam, hoe je erin komt zonder mij. Geen secrets in het document.'],
                        ['Beslisnotities', 'Waarom dingen zijn zoals ze zijn, zodat niemand per ongeluk een goede beslissing terugdraait.'],
                        ['Bekende risico\'s', 'Wat het eerst breekt, wat de oplossing kost, waar je op moet letten.'],
                        ['Contacten', 'Leveranciers, hosting, domein, de mensen die kunnen helpen.'],
                    ],
                    'steps' => [
                        'Leestoegang en een rondleiding door wat je hebt.',
                        'Opstellen terwijl de procedures echt gevolgd worden.',
                        'Review met je team of de volgende developer.',
                        'Definitieve versie in je repository.',
                    ],
                    'not' => 'Nooit secrets in het pakket zelf. Het wijst naar de vault, het vervangt de vault niet.',
                    'cta' => ['Kan iemand anders het vanavond draaien met alleen de documentatie?', 'Als die vraag je zenuwachtig maakt, stuur me de naam van het systeem. Binnen één werkdag antwoord ik wat het pakket zou bevatten, gratis.'],
                ],
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
     * The home page loop: step key => list of entries, services first (with their real page), then
     * the EXTRA entries (contact page). Every entry: slug, name, short, short NL, url, sub (Build only), extra.
     *
     * @return array<string, list<array{slug:string, name:string, short:string, short_nl:string, url:string, sub:?string, extra:bool}>>
     */
    public static function loop(): array
    {
        $out = [];
        foreach (array_keys(self::STEPS) as $key) {
            $out[$key] = [];
        }
        foreach (self::all() as $p) {
            /** @var array<string, mixed> $p */
            $out[$p['step']][] = ['slug' => $p['slug'], 'name' => $p['name'], 'short' => $p['short'], 'short_nl' => $p['nl']['short'], 'url' => self::url($p), 'sub' => $p['sub'] ?? null, 'extra' => false];
        }
        foreach (self::EXTRA as $slug => $e) {
            $out[$e[0]][] = ['slug' => $slug, 'name' => $e[2], 'short' => $e[3], 'short_nl' => $e[4], 'url' => route('scuttle.contact.page'), 'sub' => $e[1], 'extra' => true];
        }
        foreach ($out as $key => $items) {
            /** @var list<array{slug:string, name:string, short:string, short_nl:string, url:string, sub:?string, extra:bool}> $items */
            if ($key === 'build') {
                usort($items, fn ($a, $b) => strcmp($a['sub'], $b['sub']) ?: 0);
                $out[$key] = array_merge(array_values(array_filter($items, fn ($i) => $i['sub'] === 'general')), array_values(array_filter($items, fn ($i) => $i['sub'] === 'ai')));
            }
        }

        return $out;
    }

    /**
     * The steps for the ring drawn by site.js: key, hub flag, number, labels in both languages.
     *
     * @return list<array{key:string, hub:bool, num:string, en:string, nl:string, tag:array{en:string, nl:string}}>
     */
    public static function loopSteps(): array
    {
        $out = [];
        $i = 0;
        foreach (self::STEPS as $k => $s) {
            $hub = $k === 'ongoing';
            $out[] = ['key' => $k, 'hub' => $hub, 'num' => $hub ? '' : sprintf('%02d', ++$i), 'en' => $s[0], 'nl' => $s[1], 'tag' => ['en' => 'between the loops', 'nl' => 'tussen de rondes']];
        }

        return $out;
    }

    /**
     * The Dutch strings of the home page loop, keyed by its data-i keys (t_<slug> one-liners, lp_* step texts).
     *
     * @return array<string, string>
     */
    public static function nlLoopStrings(): array
    {
        $out = [];
        foreach (self::all() as $p) {
            $out['t_'.$p['slug']] = $p['nl']['short'];
        }
        foreach (self::EXTRA as $k => $e) {
            $out['t_'.$k] = $e[4];
        }
        $i = 0;
        foreach (self::STEPS as $k => $s) {
            $out['lp_'.$k.'_k'] = ($k === 'ongoing' ? 'Tussen de rondes' : sprintf('%02d', ++$i)).' · '.$s[1];
            $out['lp_'.$k.'_l'] = $s[3];
            $out['lp_'.$k.'_v'] = $s[5];
        }
        foreach (self::SUBS as $k => $s) {
            $out['lp_sub_'.$k] = $s[1];
        }
        $out['lp_hint'] = 'Klik op een stap voor haar diensten, klik op een dienst voor haar pagina. Met rust gelaten loopt de ronde vanzelf.';

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

    /**
     * The Dutch strings of one product page, keyed by the data-i keys the product view uses.
     *
     * @param  Product  $p
     * @return array<string, string>
     */
    public static function nl(array $p): array
    {
        $n = $p['nl'];
        $out = [
            'p_lead' => $n['lead'],
            'p_price' => $n['price'].'.',
            'p_pn' => $n['price_note'],
            'p_not' => $n['not'],
            'p_cta1' => $n['cta'][0],
            'p_cta2' => $n['cta'][1],
        ];
        foreach ($n['features'] as $i => $f) {
            $out['p_f'.$i.'t'] = $f[0];
            $out['p_f'.$i.'d'] = $f[1];
            if ($i < 4) {
                $out['p_fm'.$i] = $f[0];
            }
        }
        foreach ($n['steps'] as $i => $st) {
            $out['p_s'.$i] = $st;
        }

        return $out;
    }
}
