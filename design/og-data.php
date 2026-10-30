<?php

// Prints the cards for the per-page social images as JSON: php design/og-data.php > /tmp/og.json, then python3 design/make-og.py /tmp/og.json
require __DIR__.'/../vendor/autoload.php';

use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Projects;

$cards = [];
foreach (Catalog::all() as $p) {
    $slug = $p['slug'] === 'legacy-health-check' ? 'health-check' : 'services-'.$p['slug'];
    $cards[$slug] = ['kicker' => Catalog::GROUPS[$p['group']], 'title' => $p['name'], 'line' => $p['short'], 'foot' => preg_replace('/\.$/', '', $p['price'])];
}
$cards['pricing'] = ['kicker' => 'Pricing', 'title' => 'One rate, two ways to buy it', 'line' => 'EUR 90 per hour, and fixed prices for the Health Check, the quick scan and the architecture review.', 'foot' => 'Every amount excl. VAT'];
$cards['services'] = ['kicker' => 'Services', 'title' => 'Twelve services, automation first', 'line' => 'Each one leaves a document in your repository, not only a result on a server.', 'foot' => 'Open prices'];
$cards['company'] = ['kicker' => 'Company', 'title' => 'One person, the whole stack', 'line' => 'Senior developer and solution architect in Eindhoven, founded 2024.', 'foot' => 'KvK 96040947'];
$cards['contact'] = ['kicker' => 'Contact', 'title' => 'Tell me what you are running', 'line' => 'A first opinion within one working day, free.', 'foot' => 'info@scuttle.dev'];
$cards['case-studies'] = ['kicker' => 'Case studies', 'title' => 'Work, told the way it went', 'line' => 'One anonymized client project and three own products.', 'foot' => 'scuttle.dev'];
$cards['php-check'] = ['kicker' => 'PHP version check', 'title' => 'Which PHP version are you running?', 'line' => 'A ten-minute check with the dates from php.net, and what a Health Check does with the answer.', 'foot' => 'Health Check: EUR 1,080 fixed'];
$cards['health-check-sample'] = ['kicker' => 'Sample report', 'title' => 'What a Health Check report looks like', 'line' => 'The structure of the written report, with an invented example system.', 'foot' => 'Two days, read-only'];
$cards['nis2'] = ['kicker' => 'NIS2 and CRA', 'title' => 'What applies to a Dutch company', 'line' => 'The Cyberbeveiligingswet since 15 August 2026, the CRA reporting duty since 11 September 2026.', 'foot' => 'Compliance Alignment'];
foreach (Cases::all() as $slug => $c) {
    $cards['case-studies-'.$slug] = ['kicker' => 'Case study', 'title' => $c['title'], 'line' => $c['kicker'], 'foot' => 'scuttle.dev'];
}
foreach (Projects::all() as $pr) {
    $cards['projects-'.$pr['slug']] = ['kicker' => 'Project', 'title' => $pr['name'], 'line' => $pr['short'], 'foot' => $pr['kind']];
}
$cards['projects'] = ['kicker' => 'Projects', 'title' => 'Open source and own products', 'line' => 'Skills, MCP servers, an inventory product and this site, each with documentation.', 'foot' => 'github.com/spdotdev'];
$cards['news'] = ['kicker' => 'News', 'title' => 'What changed and what it means', 'line' => 'Notes from the person who does the work, each pointing to the service that answers it.', 'foot' => 'scuttle.dev'];
foreach (Posts::builtIn() as $post) {
    $cards['news-'.$post['slug']] = ['kicker' => 'News · '.$post['date'], 'title' => $post['title'], 'line' => '', 'foot' => 'scuttle.dev'];
}
echo json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
