# Usage: python3 scripts/switch-check.py http://127.0.0.1:PORT/dump/ : after the NL button on pages without a Dutch twin, the labels, the NDA link and lang are Dutch, and English again after EN.
import sys
from playwright.sync_api import sync_playwright
base = sys.argv[1]
pages = ['news.html', 'news-post.html', 'projects.html', 'project.html', 'docs.html']
bad = []
with sync_playwright() as p:
    b = p.chromium.launch()
    for f in pages:
        ctx = b.new_context(viewport={'width': 1366, 'height': 900}, reduced_motion='reduce')
        pg = ctx.new_page()
        pg.goto(base + f, wait_until='load')
        snap = "()=>({lang:document.documentElement.lang,nda:[...document.querySelectorAll('footer a')].find(a=>a.textContent.trim()==='NDA').getAttribute('href'),nav:document.querySelector('nav.top').getAttribute('aria-label'),burger:document.getElementById('burger').getAttribute('aria-label'),dark:document.querySelector('[data-th=dark]').getAttribute('title')})"
        en0 = pg.evaluate(snap)
        pg.click('#nl'); pg.wait_for_timeout(300)
        nl = pg.evaluate(snap)
        pg.click('#en'); pg.wait_for_timeout(300)
        en1 = pg.evaluate(snap)
        ok = (nl['lang'] == 'nl' and 'GEHEIMHOUDINGSOVEREENKOMST' in nl['nda'] and nl['nav'] == 'Hoofdmenu' and nl['dark'] == 'Donker'
              and en1 == en0 and en0['lang'] == 'en' and en0['nav'] == 'Main navigation' and 'NDA' in en0['nda'] and 'GEHEIM' not in en0['nda'])
        print(f, 'OK' if ok else 'FAIL', nl if not ok else '', en1 if not ok else '')
        if not ok: bad.append(f)
        ctx.close()
    b.close()
sys.exit(1 if bad else 0)
