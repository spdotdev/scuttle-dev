<?php

namespace Spdotdev\ScuttleDev;

/**
 * Related reading for the money pages: a few natural links to the article, the sample report, the check or the
 * neighbouring service that answers the next question a reader has. The wording says why the link is worth a click.
 * Both directions exist: the articles already link to their services.
 */
class Links
{
    /**
     * @return list<array{path: string, en: string, nl: string}>
     */
    public static function for(string $slug): array
    {
        return self::all()[$slug] ?? [];
    }

    /**
     * @return array<string, string>
     */
    public static function nl(string $slug): array
    {
        $out = [];
        foreach (self::for($slug) as $i => $l) {
            $out['p_rl'.$i] = $l['nl'];
        }

        return $out;
    }

    /**
     * @return array<string, list<array{path: string, en: string, nl: string}>>
     */
    public static function all(): array
    {
        return [
            'health-plan' => [
                ['path' => '/news/what-two-days-of-health-check-buy', 'en' => 'Read what two days of a Health Plan buy', 'nl' => 'Lees wat twee dagen Health Plan opleveren'],
                ['path' => '/nis2', 'en' => 'Questions about NIS2 or the Cyber Resilience Act: what applies to a Dutch company', 'nl' => 'Vragen over NIS2 of de Cyber Resilience Act: wat geldt voor een Nederlands bedrijf'],
                ['path' => '/services/modernization', 'en' => 'When the report says the stack is out of support: Modernization, in steps', 'nl' => 'Als het rapport zegt dat de stack buiten support is: Modernization, in stappen'],
            ],
            'modernization' => [
                ['path' => '/health-plan', 'en' => 'Not sure where to start? A Health Plan fixes the order of the steps first', 'nl' => 'Weet je niet waar je moet beginnen? Een Health Plan legt eerst de volgorde van de stappen vast'],
                ['path' => '/health-plan/sample', 'en' => 'See what the written report looks like', 'nl' => 'Bekijk hoe het schriftelijke rapport eruitziet'],
                ['path' => '/news/php-security-release-october-2026', 'en' => 'Why PHP versions matter: the security release of 21 October', 'nl' => 'Waarom PHP-versies ertoe doen: de beveiligingsrelease van 21 oktober'],
            ],
            'hardening' => [
                ['path' => '/services/security-assessment', 'en' => 'Need the application itself tested? That is the Security Scan', 'nl' => 'Moet de applicatie zelf getest worden? Dat is de Security Scan'],
                ['path' => '/nis2', 'en' => 'NIS2 and the Cyber Resilience Act: who is in scope and what they must do', 'nl' => 'NIS2 en de Cyber Resilience Act: wie eronder valt en wat ze moeten doen'],
                ['path' => '/health-plan', 'en' => 'Want a first overview of risks across the whole system? The Health Plan', 'nl' => 'Een eerste overzicht van de risico\'s in het hele systeem? De Health Plan'],
            ],
            'maintenance-plan' => [
                ['path' => '/news/php-security-release-october-2026', 'en' => 'A recent example of what patching covers: the PHP release of 21 October', 'nl' => 'Een recent voorbeeld van wat patchen omvat: de PHP-release van 21 oktober'],
                ['path' => '/php-check', 'en' => 'Check which PHP version you run and until when it gets security fixes', 'nl' => 'Check welke PHP-versie je draait en tot wanneer die beveiligingsfixes krijgt'],
                ['path' => '/health-plan', 'en' => 'No review of the system yet? Start with a Health Plan', 'nl' => 'Nog geen beoordeling van het systeem? Begin met een Health Plan'],
            ],
            'automation-scan' => [
                ['path' => '/services/ai-integration', 'en' => 'When the report points at an agent: AI Agents and Skills, one use case to production', 'nl' => 'Als het rapport naar een agent wijst: AI Agents and Skills, één use case naar productie'],
                ['path' => '/services/integrations', 'en' => 'When the report points at two systems that should talk: System Integration', 'nl' => 'Als het rapport wijst op twee systemen die met elkaar moeten praten: System Integration'],
                ['path' => '/health-plan', 'en' => 'Want the whole system looked at, not only the manual work? The Health Plan', 'nl' => 'Wil je het hele systeem bekeken hebben, niet alleen het handwerk? Het Health Plan'],
            ],
            'ai-integration' => [
                ['path' => '/news/an-mcp-server-in-front-of-your-api', 'en' => 'Why an MCP server in front of the API is the safe way to let AI use your data', 'nl' => 'Waarom een MCP-server voor de API de veilige manier is om AI je data te laten gebruiken'],
                ['path' => '/services/architecture-review', 'en' => 'Before the build: an Architecture Plan of the system the agent will touch', 'nl' => 'Vóór de bouw: een Architecture Plan van het systeem waar de agent bij komt'],
            ],
        ];
    }
}
