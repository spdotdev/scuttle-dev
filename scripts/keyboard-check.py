# Usage: python3 scripts/keyboard-check.py https://example.dev paths.txt
# Tabs through every page at 1366 px and counts the keyboard stops that show a visible outline. The skip link must be the first stop,
# Escape must close the mega menu, and no stop may lack a ring or sit hidden. Exit 1 on any finding.
import sys
from playwright.sync_api import sync_playwright
base = sys.argv[1]; paths = [l.strip() for l in open(sys.argv[2]) if l.strip()]
DESC = """()=>{const e=document.activeElement;if(!e||e===document.body)return null;const s=getComputedStyle(e);const r=e.getBoundingClientRect();
 return {tag:e.tagName.toLowerCase(),id:e.id,txt:String(e.innerText||e.getAttribute('aria-label')||e.id||'').trim().slice(0,28),ring:(s.outlineStyle!=='none'&&parseFloat(s.outlineWidth)>0)||s.boxShadow!=='none',shown:r.width>0&&r.height>0&&s.visibility==='visible'}}"""
bad = []; total = 0; ringed = 0
with sync_playwright() as p:
    b = p.chromium.launch(); pg = b.new_context(viewport={'width': 1366, 'height': 800}, reduced_motion='reduce').new_page()
    for path in paths:
        pg.goto(base + path, wait_until='load'); pg.wait_for_timeout(500)
        seen = []
        for _ in range(120):
            pg.keyboard.press('Tab'); pg.wait_for_timeout(60)
            d = pg.evaluate(DESC)
            if d is None or (seen and (d['tag'], d['id'], d['txt']) == seen[0]):
                break
            seen.append((d['tag'], d['id'], d['txt']))
            total += 1; ringed += d['ring']
            if not d['ring'] or not d['shown']:
                bad.append(f"{path}: {d['tag']} '{d['txt']}' ring={d['ring']} shown={d['shown']}")
        if not seen or seen[0][2] != 'Skip to content' and not seen[0][2].startswith('Naar de inhoud'):
            bad.append(f'{path}: the skip link is not the first stop ({seen[0] if seen else None})')
        if pg.query_selector('#mega-btn'):
            pg.focus('#mega-btn'); pg.keyboard.press('Enter'); pg.keyboard.press('Escape'); pg.wait_for_timeout(150)
            if pg.get_attribute('#mega-btn', 'aria-expanded') != 'false':
                bad.append(f'{path}: Escape does not close the mega menu')
    b.close()
print(len(paths), 'pages,', total, 'keyboard stops,', ringed, 'with a visible ring,', len(bad), 'findings')
for x in bad: print(' -', x)
sys.exit(1 if bad else 0)
