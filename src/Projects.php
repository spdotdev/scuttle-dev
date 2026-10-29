<?php

namespace Spdotdev\ScuttleDev;

/**
 * Open-source and own projects shown at /projects, each with an optional docs page.
 * Client projects appear here only with the client's written consent (see GitHub issue #1).
 *
 * @phpstan-type Section array{id:string, title:string, body:list<string>}
 * @phpstan-type ProjectNl array{kind:string, short:string, lead:string, status:string}
 * @phpstan-type Project array{slug:string, name:string, kind:string, short:string, lead:string, repo:string, stack:list<string>, status:string, products:list<string>, docs:list<Section>, nl:ProjectNl}
 */
final class Projects
{
    /**
     * @return array<string, Project>
     */
    public static function all(): array
    {
        return [
            'manual' => [
                'slug' => 'manual',
                'name' => 'Manual',
                'kind' => 'Open source · Claude Code skill',
                'short' => 'Generates a detailed, visually designed field manual for any codebase.',
                'lead' => 'A skill for AI coding agents that reads a codebase and writes a field manual: architecture, state machines, flow diagrams, procedures. Every run produces a published HTML page and a Markdown file committed to the project; optionally a standalone HTML file and a PDF.',
                'repo' => 'https://github.com/spdotdev/skill-manual',
                'stack' => ['Claude Code', 'Markdown', 'Mermaid', 'HTML', 'PDF'],
                'status' => 'Public, MIT licence, README in seven languages',
                'products' => ['handover-pack', 'ai-integration'],
                'nl' => ['kind' => 'Open source · Claude Code-skill', 'short' => 'Genereert een uitgebreid, visueel vormgegeven handboek voor elke codebase.', 'lead' => 'Een skill voor AI-codeeragents die een codebase leest en er een handboek van schrijft: architectuur, toestandsdiagrammen, stroomdiagrammen, procedures. Elke run levert een gepubliceerde HTML-pagina en een Markdown-bestand dat in het project wordt vastgelegd; optioneel een los HTML-bestand en een PDF.', 'status' => 'Openbaar, MIT-licentie, README in zeven talen'],
                'docs' => [
                    ['id' => 'install', 'title' => 'Install', 'body' => ['Clone the repository into your Claude Code skills directory:', '```git clone https://github.com/spdotdev/skill-manual.git ~/.claude/skills/manual```', 'Then run /manual inside a project, or ask Claude for detailed project documentation. The README describes the setup for other hosts (Claude on the web, Codex, Gemini).']],
                    ['id' => 'usage', 'title' => 'Usage', 'body' => ['```/manual                          # writes MANUAL.md and publishes a page\n/manual docs/ARCHITECTURE.md     # custom output path\n/manual --html                   # also writes a standalone MANUAL.html\n/manual --pdf                    # also renders MANUAL.pdf```', 'Standing defaults go in the project\'s CLAUDE.md: manual output, manual html, manual pdf.']],
                    ['id' => 'output', 'title' => 'What a run produces', 'body' => ['- A published HTML page, theme aware, with Mermaid diagrams and one fixed design system, so every project\'s manual looks like part of the same family.', '- A Markdown file in the repository as the version-controlled source of truth.', '- Optionally a standalone HTML file and a PDF for sharing outside a terminal or browser.']],
                    ['id' => 'why', 'title' => 'Why it exists', 'body' => ['The handover pack I deliver with every project needs an architecture document that matches reality. Writing it by hand goes stale; a skill that reads the code and redraws the diagrams does not.']],
                ],
            ],
            'inventory' => [
                'slug' => 'inventory',
                'name' => 'Inventory',
                'kind' => 'Own product · Laravel, Android, MCP',
                'short' => 'A private, multi-household home-stock manager: API, Android app, admin and an MCP server.',
                'lead' => 'A multi-tenant inventory product built as three parts: a Laravel backend shipped as a Composer package with a versioned API, a Kotlin Android client, and an MCP server so an AI assistant can answer questions about the stock without touching the database. Live at inventory.scuttle.dev.',
                'repo' => 'https://github.com/spdotdev/inventory-laravel',
                'stack' => ['Laravel', 'Sanctum', 'Reverb', 'MySQL', 'Kotlin', 'Jetpack Compose', 'MCP', 'Docker'],
                'status' => 'Live in production, functionally complete MVP plus phase 2',
                'products' => ['full-stack-build', 'android-app', 'ai-integration'],
                'nl' => ['kind' => 'Eigen product · Laravel, Android, MCP', 'short' => 'Een privé voorraadbeheerder voor meerdere huishoudens: API, Android-app, beheer en een MCP-server.', 'lead' => 'Een multi-tenant voorraadproduct in drie delen: een Laravel-backend als Composer-pakket met een versioned API, een Kotlin Android-client en een MCP-server, zodat een AI-assistent vragen over de voorraad kan beantwoorden zonder de database aan te raken. Live op inventory.scuttle.dev.', 'status' => 'Live in productie, functioneel complete MVP plus fase 2'],
                'docs' => [
                    ['id' => 'surfaces', 'title' => 'What is mounted', 'body' => ['Everything is host-based routed on one domain:', '- Web app: login, register, password reset, join a household by code, the inventory UI.', '- API: /api/v1, headless REST and JSON with Sanctum bearer tokens; the contract lives in docs/specs/api-contract.md.', '- Live updates: a private channel per household over Reverb.', '- App-version feed: the latest published Android release, so the app can prompt for updates.', '- Admin API and an HTTP MCP server with the same operator tools.', '- Health: a JSON liveness, version and database probe.']],
                    ['id' => 'install', 'title' => 'Install into a host app', 'body' => ['```composer require spdotdev/inventory```', 'The service provider is auto-discovered; migrations run with the host\'s php artisan migrate. All tables carry the inventory_ prefix.']],
                    ['id' => 'config', 'title' => 'Configuration', 'body' => ['Everything is env-driven and documented in config/inventory.php: the domain the package answers on, the Google OAuth client ids accepted for sign-in, the static admin token that protects the admin API and the MCP server (empty disables both), and per-minute rate limits on authentication, join codes and client error intake.']],
                    ['id' => 'quality', 'title' => 'Quality gates', 'body' => ['Pint, Larastan and PHPUnit on every change, with a SQLite job and a real MySQL job in CI. ROADMAP.md says what is next, BACKLOG.md what shipped.']],
                ],
            ],
            'whatsapp-mcp' => [
                'slug' => 'whatsapp-mcp',
                'name' => 'WhatsApp MCP',
                'kind' => 'Open source · fork, in daily use',
                'short' => 'An MCP server that lets an assistant search, read and send WhatsApp messages through a personal account.',
                'lead' => 'A Model Context Protocol server for WhatsApp: a Go bridge speaks the WhatsApp web multi-device protocol and stores messages locally, a Python MCP server exposes search, read and send tools to Claude. My fork runs the alerts and reminders of my own business.',
                'repo' => 'https://github.com/spdotdev/whatsapp-mcp',
                'stack' => ['Go', 'Python', 'MCP', 'SQLite'],
                'status' => 'Fork of lharries/whatsapp-mcp with my own operations fixes',
                'products' => ['ai-integration', 'integrations'],
                'nl' => ['kind' => 'Open source · fork, dagelijks in gebruik', 'short' => 'Een MCP-server waarmee een assistent WhatsApp-berichten kan zoeken, lezen en versturen via een persoonlijk account.', 'lead' => 'Een Model Context Protocol-server voor WhatsApp: een Go-bridge spreekt het WhatsApp-web multi-device-protocol en slaat berichten lokaal op, een Python MCP-server biedt zoek-, lees- en verstuurtools aan Claude. Mijn fork draait de meldingen en herinneringen van mijn eigen bedrijf.', 'status' => 'Fork van lharries/whatsapp-mcp met mijn eigen fixes voor de dagelijkse praktijk'],
                'docs' => [
                    ['id' => 'parts', 'title' => 'The two parts', 'body' => ['- The bridge (Go): connects to WhatsApp as a linked device, scans a QR code once, stores chats and messages in SQLite.', '- The MCP server (Python): tools to list chats, search messages and contacts, download media and send text, files or voice messages.']],
                    ['id' => 'caution', 'title' => 'Read before running', 'body' => ['Like many MCP servers this one combines private data, untrusted content and the ability to act. Treat every message the model reads as untrusted input, and keep sending behind a human approval. That is how I run it.']],
                ],
            ],
            'claude-telegram-relay' => [
                'slug' => 'claude-telegram-relay',
                'name' => 'Claude Telegram Relay',
                'kind' => 'Open source · reference pattern',
                'short' => 'A minimal pattern for running Claude Code as an always-on Telegram bot.',
                'lead' => 'You send a message on Telegram, the relay spawns the Claude Code CLI, and sends the answer back. The simplest way to reach the full tool set of Claude Code, including MCP servers, from a phone. A reference implementation to adapt, not a product.',
                'repo' => 'https://github.com/spdotdev/claude-telegram-relay',
                'stack' => ['Bun', 'TypeScript', 'Claude Code CLI', 'Telegram Bot API'],
                'status' => 'Reference implementation, cross-platform daemon setup included',
                'products' => ['ai-integration'],
                'nl' => ['kind' => 'Open source · referentiepatroon', 'short' => 'Een minimaal patroon om Claude Code als altijd-aan Telegram-bot te draaien.', 'lead' => 'Je stuurt een bericht op Telegram, de relay start de Claude Code CLI en stuurt het antwoord terug. De eenvoudigste manier om vanaf een telefoon bij alle tools van Claude Code te komen, inclusief MCP-servers. Een referentie-implementatie om aan te passen, geen product.', 'status' => 'Referentie-implementatie, inclusief daemon-setup voor meerdere platformen'],
                'docs' => [],
            ],
            'scuttle-dev' => [
                'slug' => 'scuttle-dev',
                'name' => 'scuttle.dev',
                'kind' => 'Open source · this website',
                'short' => 'This site as a Laravel package: one shell, services, pricing, cases, news, contact, tests.',
                'lead' => 'The site you are reading, shipped as a Composer package that mounts into a host Laravel application through host-based routing. Everything is data in PHP classes (services, cases, posts, projects), one stylesheet, one script, no tracking unless you agree to it, and a test suite that fails on the marketing rules.',
                'repo' => 'https://github.com/spdotdev/scuttle-dev',
                'stack' => ['Laravel', 'Blade', 'Geist', 'PHPUnit', 'Playwright'],
                'status' => 'Public repository, versioned by git tag, deployed through the host app',
                'products' => ['full-stack-build'],
                'nl' => ['kind' => 'Open source · deze website', 'short' => 'Deze site als Laravel-pakket: één shell, diensten, prijzen, casestudy\'s, nieuws, contact, tests.', 'lead' => 'De site die je nu leest, geleverd als Composer-pakket dat via host-gebaseerde routing in een Laravel-hostapplicatie wordt gemount. Alles is data in PHP-klassen (diensten, casestudy\'s, posts, projecten), één stylesheet, één script, geen tracking tenzij je ermee instemt, en een testsuite die faalt op de marketingregels.', 'status' => 'Openbare repository, versies via git-tags, uitgerold via de hostapplicatie'],
                'docs' => [
                    ['id' => 'shape', 'title' => 'The shape', 'body' => ['- src/Catalog.php: the twelve services in three groups, one source for the menu, the home page, pricing and the service pages.', '- src/Cases.php, src/Posts.php, src/Projects.php: case studies, news articles, projects with docs.', '- resources/views/layout.blade.php: the shell; public/site.css and public/site.js: the only stylesheet and script.', '- tests/Feature/SiteTest.php: renders every page and fails on client traces, zeros as claims and missing links.']],
                    ['id' => 'ship', 'title' => 'How a change ships', 'body' => ['Change, test, push to main, tag. The host app pins the package by tag, bumps its lock, and its CI deploys. A static dump of every page can be written with one test and screenshotted headlessly at desktop and phone width.']],
                ],
            ],
        ];
    }

    /**
     * @return Project
     */
    public static function get(string $slug): array
    {
        $all = self::all();
        abort_unless(isset($all[$slug]), 404);

        return $all[$slug];
    }
}
