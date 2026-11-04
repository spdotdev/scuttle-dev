# Usage: python3 scripts/ui-check.py https://example.dev paths.txt  (one path per line)
# Two checks at 390 px, light and dark, in both languages as the paths list gives them:
#  1. every visible .btn is at least 44 px tall (a thumb target);
#  2. on the money pages (/health-plan, /pricing, /services/<slug>, also under /nl) the first visible button inside <main> starts
#     above 760 px from the top of the page, so the first screen of a phone shows what the page is and one next step.
# Needs: pip install playwright; playwright install chromium. Exit 1 on any finding.
import re, sys
from playwright.sync_api import sync_playwright

base = sys.argv[1].rstrip('/')
paths = [l.strip() for l in open(sys.argv[2]) if l.strip()]
MONEY = re.compile(r'^(/nl)?/(health-plan|pricing|services/[a-z0-9-]+)/?$')
JS = """() => {
  const vis = e => { const r = e.getBoundingClientRect(); const s = getComputedStyle(e);
    return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none' && e.offsetParent !== null; };
  const small = [...document.querySelectorAll('.btn')].filter(vis).map(e => ({t: e.textContent.trim().slice(0, 30), h: Math.round(e.getBoundingClientRect().height)})).filter(x => x.h < 44);
  const first = [...document.querySelectorAll('main .btn')].filter(vis)[0];
  return {small, firstTop: first ? Math.round(first.getBoundingClientRect().top + window.scrollY) : null, firstText: first ? first.textContent.trim().slice(0, 30) : null};
}"""
bad = []
runs = 0
money = 0
with sync_playwright() as p:
    b = p.chromium.launch()
    for scheme in ('light', 'dark'):
        pg = b.new_context(viewport={'width': 390, 'height': 844}, color_scheme=scheme, reduced_motion='reduce', is_mobile=True).new_page()
        for path in paths:
            try:
                pg.goto(base + path, wait_until='load', timeout=45000)
                pg.wait_for_timeout(500)
                r = pg.evaluate(JS)
            except Exception as e:
                bad.append(f'{scheme} {path}: could not be checked ({str(e)[:80]})')
                continue
            runs += 1
            for x in r['small']:
                bad.append(f"{scheme} {path}: button '{x['t']}' is {x['h']} px tall, under 44")
            if MONEY.match(path):
                money += 1
                if r['firstTop'] is None:
                    bad.append(f'{scheme} {path}: no button in main')
                elif r['firstTop'] > 760:
                    bad.append(f"{scheme} {path}: first button '{r['firstText']}' starts at {r['firstTop']} px, below 760")
    b.close()
print(runs, 'page runs,', money, 'money page runs,', len(bad), 'findings')
for x in bad:
    print(' -', x)
sys.exit(1 if bad else 0)
