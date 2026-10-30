<?php

namespace Spdotdev\ScuttleDev;

/**
 * Questions and answers shown on the money pages, English and Dutch. Every answer restates a fact that is already on
 * the page or in the catalog (price, steps, what the service is not); nothing here is a new claim. The same text is
 * the visible FAQ block and the FAQPage data in the page head, word for word.
 */
class Faq
{
    /**
     * @return list<array{q: string, a: string, qn: string, an: string}>
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
        foreach (self::for($slug) as $i => $f) {
            $out['p_q'.$i] = $f['qn'];
            $out['p_a'.$i] = $f['an'];
        }

        return $out;
    }

    /**
     * @return array<string, list<array{q: string, a: string, qn: string, an: string}>>
     */
    public static function all(): array
    {
        return [
            'legacy-health-check' => [
                [
                    'q' => 'What does the Health Check cost?',
                    'a' => 'A fixed EUR 1,080 excl. VAT: two days of review, 12 hours at EUR 90. A free intro call comes first, so you know it fits before anything is booked.',
                    'qn' => 'Wat kost de Health Check?',
                    'an' => 'Een vaste prijs van EUR 1.080 excl. btw: twee dagen review, 12 uur à EUR 90. Een gratis introgesprek gaat vooraf, zodat je weet dat het past voordat er iets geboekt wordt.',
                ],
                [
                    'q' => 'Do you need access to my server or my production data?',
                    'a' => 'I need read-only access to the code and a short tour of how the system is hosted. I do not need production data, and I make no changes to your production systems.',
                    'qn' => 'Heb je toegang nodig tot mijn server of mijn productiedata?',
                    'an' => 'Ik heb leestoegang nodig tot de code en een korte rondleiding over hoe het systeem gehost wordt. Ik heb geen productiedata nodig en ik breng geen wijzigingen aan in je productiesystemen.',
                ],
                [
                    'q' => 'What do I get at the end?',
                    'a' => 'A written report in plain language, with findings ranked by value and risk and the hours per item. It covers automation opportunities, a legacy check against official end-of-life dates, a security quick scan, an architecture review and a step-by-step plan. We then go through it together in a walkthrough call.',
                    'qn' => 'Wat krijg ik aan het eind?',
                    'an' => 'Een schriftelijk rapport in gewone taal, met bevindingen gerangschikt op waarde en risico en de uren per punt. Het omvat kansen voor automatisering, een legacy-check naast de officiële end-of-lifedatums, een snelle beveiligingsscan, een architectuurreview en een plan in stappen. Daarna lopen we het samen door in een doorloopgesprek.',
                ],
                [
                    'q' => 'Is the Health Check a penetration test or a rewrite?',
                    'a' => 'No. It is not a rewrite and it is not a penetration test. It includes a security quick scan of configuration, dependencies and secrets handling. A test of the application itself is the separate Security Assessment.',
                    'qn' => 'Is de Health Check een penetratietest of een herbouw?',
                    'an' => 'Nee. Het is geen herbouw en geen penetratietest. Het bevat wel een snelle beveiligingsscan van configuratie, dependencies en omgang met secrets. Een test van de applicatie zelf is de aparte Security Assessment.',
                ],
                [
                    'q' => 'What happens if the report shows that more work is needed?',
                    'a' => 'You get a clear scope and price before anything starts. Larger work is done in steps, each with its own price, and you can stop after any step.',
                    'qn' => 'Wat als uit het rapport blijkt dat er meer werk nodig is?',
                    'an' => 'Je krijgt een duidelijke scope en prijs voordat iets begint. Groter werk gaat in stappen, elk met een eigen prijs, en je kunt na elke stap stoppen.',
                ],
                [
                    'q' => 'Can I sign an NDA first, and who does the work?',
                    'a' => 'Yes, a mutual NDA is available as a PDF on this page and I sign it before we go into details if you want. I am one person and I do the work myself, so there is no handover to a junior.',
                    'qn' => 'Kan ik eerst een NDA tekenen, en wie doet het werk?',
                    'an' => 'Ja, een wederzijdse NDA staat als pdf op deze pagina en ik teken die desgewenst voordat we op details ingaan. Ik ben één persoon en doe het werk zelf, dus er is geen overdracht aan een junior.',
                ],
            ],
            'modernization' => [
                [
                    'q' => 'Do you rewrite my application from scratch?',
                    'a' => 'No. There is no rewrite from scratch and no three-month branch. The work is done in small steps with the system in use the whole time, old and new side by side.',
                    'qn' => 'Herbouw je mijn applicatie vanaf nul?',
                    'an' => 'Nee. Er is geen herbouw vanaf nul en geen branch van drie maanden. Het werk gaat in kleine stappen terwijl het systeem de hele tijd in gebruik blijft, oud en nieuw naast elkaar.',
                ],
                [
                    'q' => 'How is Modernization priced?',
                    'a' => 'Each step is quoted separately at EUR 90 per hour excl. VAT, with its own scope and price before it starts. If a step turns out bigger than quoted, you hear it before the hours are spent.',
                    'qn' => 'Hoe wordt Modernization geprijsd?',
                    'an' => 'Elke stap krijgt een eigen offerte à EUR 90 per uur excl. btw, met eigen scope en prijs voordat hij begint. Blijkt een stap groter dan geoffreerd, dan hoor je dat voordat de uren zijn besteed.',
                ],
                [
                    'q' => 'Can I stop after the first step?',
                    'a' => 'Yes. You can stop after any step, and the handover pack is updated after every step so the work is usable without me.',
                    'qn' => 'Kan ik na de eerste stap stoppen?',
                    'an' => 'Ja. Je kunt na elke stap stoppen, en het overdrachtspakket wordt na elke stap bijgewerkt zodat het werk ook zonder mij bruikbaar is.',
                ],
                [
                    'q' => 'Where do we start?',
                    'a' => 'With a Health Check or an existing plan, to fix the order of steps with the safety net first. Tests are written around the current behaviour before anything changes.',
                    'qn' => 'Waar beginnen we?',
                    'an' => 'Met een Health Check of een bestaand plan, om de volgorde van de stappen vast te leggen met het vangnet eerst. Vóór er iets verandert worden tests geschreven rond het huidige gedrag.',
                ],
            ],
            'hardening' => [
                [
                    'q' => 'Is Hardening a penetration test?',
                    'a' => 'No. It makes the server and the delivery pipeline safe. A test of the application itself is the Security Assessment.',
                    'qn' => 'Is Hardening een penetratietest?',
                    'an' => 'Nee. Het maakt de server en de leveringspijplijn veilig. Een test van de applicatie zelf is de Security Assessment.',
                ],
                [
                    'q' => 'What does Hardening cost?',
                    'a' => 'It is quoted per environment at EUR 90 per hour excl. VAT. It is included in every modernization project and can be booked on its own for a system you inherited.',
                    'qn' => 'Wat kost Hardening?',
                    'an' => 'Het wordt geoffreerd per omgeving à EUR 90 per uur excl. btw. Het zit in elk moderniseringsproject en is los te boeken voor een systeem dat je hebt geërfd.',
                ],
                [
                    'q' => 'What do I have afterwards?',
                    'a' => 'A ranked findings list with the hours per item, the agreed fixes each verified, and a written quality bar with a runbook that your team can keep. Backups are restored for real and the recovery time is written down.',
                    'qn' => 'Wat heb ik daarna?',
                    'an' => 'Een gerangschikte bevindingenlijst met de uren per punt, de afgesproken fixes elk geverifieerd, en een schriftelijke kwaliteitslat met een runbook die je team kan vasthouden. Back-ups worden echt teruggezet en de hersteltijd wordt opgeschreven.',
                ],
            ],
            'maintenance-retainer' => [
                [
                    'q' => 'What does the Maintenance Retainer cost?',
                    'a' => 'From EUR 720 a month excl. VAT for a block of 8 hours at EUR 90. Blocks of 16 or 24 hours are available. Unused hours are reported, not hidden.',
                    'qn' => 'Wat kost de Maintenance Retainer?',
                    'an' => 'Vanaf EUR 720 per maand excl. btw voor een blok van 8 uur à EUR 90. Blokken van 16 of 24 uur zijn mogelijk. Ongebruikte uren worden gerapporteerd, niet verstopt.',
                ],
                [
                    'q' => 'Are response times guaranteed?',
                    'a' => 'No. Response times are agreed in writing as targets, not guarantees: I am one person and I say so. The emergency path says how to reach me and what happens when I am not available.',
                    'qn' => 'Zijn reactietijden gegarandeerd?',
                    'an' => 'Nee. Reactietijden leggen we schriftelijk vast als streefwaarden, niet als garantie: ik ben één persoon en dat zeg ik. Het noodpad beschrijft hoe je me bereikt en wat er gebeurt als ik er niet ben.',
                ],
                [
                    'q' => 'Can I change the size of the block or stop?',
                    'a' => 'Yes. The block is reviewed every quarter: size up, size down or stop.',
                    'qn' => 'Kan ik het blok aanpassen of stoppen?',
                    'an' => 'Ja. Het blok wordt elk kwartaal beoordeeld: groter, kleiner of stoppen.',
                ],
            ],
        ];
    }
}
