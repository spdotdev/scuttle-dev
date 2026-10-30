<?php

namespace Spdotdev\ScuttleDev;

/**
 * Case studies. Client work is anonymized: no client names, no platform or
 * repository names, nothing that ties a story to a client. Own products are
 * named as such. No zeros, no superlatives: see docs/marketing-rules.md.
 *
 * @phpstan-type CaseNl array{kicker:string, title:string, quote:string, lead:string, facts:list<string>, situation:string, approach:list<string>, result:list<string>, stack:string}
 * @phpstan-type CaseStudy array{slug:string, title:string, kicker:string, quote:string, lead:string, facts:list<array{0:string,1:string}>, situation:string, approach:list<string>, result:list<string>, stack:string, products:list<string>, nl:CaseNl}
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
                'nl' => [
                    'kicker' => 'Multi-tenant operatieplatform · klant geanonimiseerd',
                    'title' => 'Van Phalcon naar Laravel, module voor module',
                    'quote' => 'Van Phalcon naar Laravel, module voor module, terwijl het platform dagelijks in gebruik bleef. Geen big-bang-overstap, geen herbouw vanaf nul.',
                    'lead' => 'Een stapsgewijze migratie van een multi-tenant operatieplatform: geen big-bang-overstap, geen herbouw vanaf nul, en 60 procent lagere latency.',
                    'facts' => ['lagere latency', 'van PHP 7.4', 'van Phalcon 3.4'],
                    'situation' => 'Het platform draaide op een verouderd Phalcon-systeem. Het kon niet schalen en specialisten voor het framework waren moeilijk te vinden. Opnieuw bouwen was uitgesloten: het systeem draagt de dagelijkse operatie van de klanten, dus een big-bang-overstap was te riskant.',
                    'approach' => [
                        'Het Strangler Fig-patroon: een dunne PHP-laag laat de oude Phalcon-code en de nieuwe Laravel-code naast elkaar draaien. Onderdelen van de applicatie gaan één voor één over, elke stap klein genoeg om los te controleren.',
                        'De runtime ging mee: PHP 7.4 naar 8.4 en Phalcon 3.4 naar 5.9, zodat het platform in support bleef tijdens de migratie.',
                        'Elke release klein, elke release met een rollback. Een verkeerde aanname kon maar één ding breken, nooit het hele platform.',
                    ],
                    'result' => [
                        'De klanten bleven tijdens de hele migratie in het platform werken.',
                        '60 procent lagere latency.',
                        'Een ondersteunde runtime en een codebase op een framework dat ontwikkelaars echt kennen.',
                    ],
                    'stack' => 'PHP, Laravel, Phalcon, MySQL, Docker, Ansible, GitHub Actions. De klant is geanonimiseerd en het werk valt onder geheimhouding.',
                ],
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
                'nl' => [
                    'kicker' => 'Eigen product · huishoudvoorraad',
                    'title' => 'Een API die AI-assistenten kunnen gebruiken: Laravel, Android en een MCP-server',
                    'quote' => 'Dezelfde API bedient de Android-app, het beheerpaneel en elke AI-assistent, via één MCP-server met benoemde tools.',
                    'lead' => 'Een multi-tenant voorraadproduct in drie delen: een Laravel-API, een Kotlin Android-app en een MCP-server, zodat een assistent vragen over de voorraad kan beantwoorden zonder de database aan te raken.',
                    'facts' => ['API, Android-app, MCP-server', 'achter app, beheer en assistent', 'de open standaard voor tools'],
                    'situation' => 'Een huishouden wil weten wat er op voorraad is, waar het ligt en wanneer het op is. De telefoon is het natuurlijke apparaat, en de vraag wordt vaak gesproken of in gewone taal getypt. Ik bouwde het voor mijn eigen gezin, en het is het patroon dat ik verkoop: één API, meerdere gezichten.',
                    'approach' => [
                        'Laravel-API met authenticatie, rollen en een Filament-beheerpaneel; multi-tenant vanaf de eerste migratie.',
                        'Kotlin- en Jetpack Compose-app voor de telefoon: barcodes scannen, foto\'s, werkt met een slechte verbinding en synchroniseert later.',
                        'Een MCP-server (Model Context Protocol) vóór de API: benoemde tools met rechten, zoals "wat is bijna op" en "waar ligt X", zodat Claude of een andere assistent antwoordt uit de echte data, alleen-lezen, elke aanroep gelogd.',
                        'Uitgerold met Docker op een server in Amsterdam, back-ups en monitoring vanaf dag één.',
                    ],
                    'result' => [
                        'Eén codebase bedient drie clients: app, beheer, assistent. Een nieuw gezicht is een dunne laag, geen herbouw.',
                        'De assistent ziet de database nooit; hij ziet tools met de rechten van de gebruiker zelf.',
                        'Hetzelfde gateway-patroon past op elk product dat al een API heeft.',
                    ],
                    'stack' => 'Laravel, Filament, MySQL, Kotlin, Jetpack Compose, TypeScript MCP-server, Docker, DigitalOcean.',
                ],
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
                'nl' => [
                    'kicker' => 'Eigen product · spraakassistent',
                    'title' => 'Een klein model getraind voor één taak: een wake word',
                    'quote' => 'Een groot model is het verkeerde gereedschap om de hele dag te luisteren. Een klein model, getraind op één woord, draait op de telefoon en kost niets per uur.',
                    'lead' => 'Voor mijn eigen spraakassistent had ik een wake word nodig dat lokaal draait, de hele dag, op een telefoon. Geen taak voor een groot model: een klein model getraind op opnames van die ene zin, getoetst op de fouten die ertoe doen.',
                    'facts' => ['geen audio verlaat de telefoon', 'getraind voor één zin', 'bepaalt elke hertraining'],
                    'situation' => 'Een assistent die luistert naar zijn naam moet altijd aan staan, offline en goedkoop, en mag niet wakker worden op het verkeerde woord. De hele dag audio naar een cloudmodel sturen is niet privé en niet betaalbaar.',
                    'approach' => [
                        'Opnames van de wake-zin en van alles wat genegeerd moet worden, in verschillende stemmen, ruimtes en met verschillende ruis.',
                        'Een klein model getraind voor die ene taak, geëxporteerd voor de telefoon.',
                        'Een evaluatieset met de twee fouten die ertoe doen: het woord missen, en wakker worden op het verkeerde. Elke hertraining wordt eraan gemeten voordat hij live gaat.',
                        'Android-integratie in Kotlin; het zware werk gaat pas na het wake word naar een groter model.',
                    ],
                    'result' => [
                        'Altijd luisteren op het apparaat, zonder dat audio het verlaat.',
                        'Modelkeuze per taak: klein en lokaal om te luisteren, groot en gehost om te begrijpen.',
                        'Een patroon dat past op elke smalle taak met veel volume: classificeren, routeren, extraheren.',
                    ],
                    'stack' => 'Python, PyTorch, Kotlin, Android, Claude voor de begripsstap.',
                ],
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
                'nl' => [
                    'kicker' => 'Eigen bedrijf · Scuttle Development',
                    'title' => 'Een eenmansbedrijf gerund door agents',
                    'quote' => 'Claude is het brein, een browser zijn de handen, een database is het geheugen. Hetzelfde patroon, voor elke klus die zich herhaalt.',
                    'lead' => 'Scuttle Development is één persoon. Bedrijven vinden, outreach schrijven en versturen, posts opstellen, reacties bijhouden, plannen en boekhouden draaien als agents op één patroon, met een menselijke tik overal waar iets het huis verlaat.',
                    'facts' => ['brein, handen, geheugen', 'voordat iets wordt verstuurd', 'en een noodstop'],
                    'situation' => 'Verkopen kost uren die een freelancer niet heeft. De repetitieve delen, de juiste bedrijven vinden, eerste contact, follow-ups, rapportage, zijn precies wat een agent goed doet, zolang er niets de deur uitgaat zonder dat een mens ernaar kijkt.',
                    'approach' => [
                        'Laravel-pakketten op één host, elk een taak: bedrijven zoeken en verrijken, een CRM met opgestelde pitches, LinkedIn-outreach via een browser die de agent bestuurt, posts opstellen, planning.',
                        'Claude als brein met geschreven skills per taak; Playwright of een browserextensie als handen waar geen API is; SQLite en MySQL als geheugen.',
                        'Vangrails in code, niet in goede bedoelingen: dagelijkse limieten, een noodstop, menselijke goedkeuring voordat een bericht wordt verstuurd, een auditlog van elke actie.',
                        'Kosten per taak bewaakt, met een maandelijks plafond.',
                    ],
                    'result' => [
                        'De pipeline draait dagelijks; ik controleer en tik, ik typ niet.',
                        'Elk onderdeel is een product dat ik voor een klant kan nabouwen: het CRM, de browserhanden, de skills, de gateway.',
                        'De cijfers die ik aan klanten noem komen uit mijn eigen gebruik.',
                    ],
                    'stack' => 'Laravel, Filament, MySQL, SQLite, Python, Claude Code, eigen skills, MCP-servers, Playwright, Docker, DigitalOcean.',
                ],
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

    /**
     * The Dutch strings for the places where a case is shown outside its own page (home carousel,
     * services page, product pages), keyed by slug so every view can use the same keys.
     *
     * @return array<string, string>
     */
    public static function nlStrings(): array
    {
        $labels = ['phalcon-to-laravel' => 'Phalcon → Laravel', 'api-meets-ai' => 'API → MCP → AI', 'wake-word-model' => 'audio → klein model', 'business-on-agents' => 'agents → pipeline'];
        $out = [];
        foreach (self::all() as $slug => $c) {
            $out['cxk_'.$slug] = $c['nl']['kicker'];
            $out['cxq_'.$slug] = $c['nl']['quote'];
            $out['cxt_'.$slug] = $c['nl']['title'];
            $out['cxl_'.$slug] = $labels[$slug] ?? $c['nl']['title'];
            foreach ([0, 1] as $i) {
                $out['cxf'.$i.'_'.$slug] = $c['facts'][$i][0].' · '.$c['nl']['facts'][$i];
            }
        }

        return $out;
    }

    public static function url(string $slug): string
    {
        return route('scuttle.case', ['slug' => $slug]);
    }
}
