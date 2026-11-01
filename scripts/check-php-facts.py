#!/usr/bin/env python3
"""Weekly freshness check: usage check-php-facts.py [live base url].

Reads the latest release of each PHP series from php.net (https://www.php.net/releases/index.php?json&version=8.4) and
fails when src/Facts.php (PHP_LATEST) is behind, printing the exact string to change. With a base url it also checks
that the live home page names the 8.4 version. Standard library only."""
import json
import re
import sys
import urllib.request

facts = open('src/Facts.php', encoding='utf-8').read()
m = re.search(r"PHP_LATEST = \[([^\]]*)\]", facts)
site = dict(re.findall(r"'(\d\.\d)' => '(\d\.\d\.\d+)'", m.group(1)))
prev = re.search(r"PHP_HERO_PREVIOUS = '([\d.]+)'", facts).group(1)


def get(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'scuttle-facts-check'})
    return urllib.request.urlopen(req, timeout=30).read().decode('utf-8')


def vt(v):
    return tuple(int(x) for x in v.split('.'))


problems = []
for series, mine in site.items():
    real = json.loads(get(f'https://www.php.net/releases/index.php?json&version={series}'))['version']
    print(f'PHP {series}: php.net {real}, site {mine}')
    if vt(real) > vt(mine):
        fix = f"in src/Facts.php change PHP_LATEST['{series}'] from '{mine}' to '{real}'"
        if series == '8.4':
            fix += f", set PHP_HERO_PREVIOUS to '{mine}' and PHP_HERO_DATE and PHP_HERO_DATE_NL to the release date of {real}; check the hero line wording still fits ('security release')"
        problems.append(f'PHP {series} is behind: {fix}')
    elif vt(real) < vt(mine):
        problems.append(f"PHP {series}: the site names {mine}, php.net lists {real} as the latest: check PHP_LATEST['{series}'] for a typo")
if len(sys.argv) > 1:
    home = get(sys.argv[1].rstrip('/') + '/')
    if f"PHP {site['8.4']}</span>" not in home:
        problems.append(f"the live home page does not show 'PHP {site['8.4']}': the deploy is behind the repository")
print('previous release in the hero line:', prev)
if problems:
    print('\n'.join('FAIL ' + p for p in problems))
    sys.exit(1)
print('PHP facts are current')
