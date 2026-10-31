# Usage: python3 scripts/nojs-check.py https://example.dev paths.txt
# Loads every page with JavaScript off at 390 px and 1366 px: no text hidden by a reveal animation, the h1 is the real text, the menu links
# can be reached (visible, or a visible burger-less list), and no language or theme control is shown that would do nothing. Exit 1 on any finding.
import sys
from playwright.sync_api import sync_playwright
base = sys.argv[1]; paths = [l.strip() for l in open(sys.argv[2]) if l.strip()]
JS = """()=>{
 const hid=[...document.querySelectorAll('main *')].filter(e=>e.children.length===0&&e.textContent.trim().length>20&&e.offsetParent!==null).filter(e=>{for(let n=e;n&&n!==document.body;n=n.parentElement){if(getComputedStyle(n).opacity==='0')return true}return false}).length;
 const links=[...document.querySelectorAll('#links a')].filter(a=>a.offsetParent!==null).length;
 const dead=[...document.querySelectorAll('.seg button')].filter(b=>b.offsetParent!==null).length;
 return {hid,links,dead,h1:document.querySelector('h1')&&document.querySelector('h1').textContent.trim().length}}"""
bad = []
with sync_playwright() as p:
    b = p.chromium.launch()
    for w in (390, 1366):
        pg = b.new_context(viewport={'width': w, 'height': 800}, java_script_enabled=False, is_mobile=w < 500).new_page()
        for path in paths:
            pg.goto(base + path, wait_until='load'); pg.wait_for_timeout(1500)
            r = pg.evaluate(JS)
            if r['hid']: bad.append(f'{w}px {path}: {r["hid"]} text blocks are hidden')
            if r['links'] < 4: bad.append(f'{w}px {path}: only {r["links"]} menu links reachable')
            if r['dead']: bad.append(f'{w}px {path}: {r["dead"]} language or theme buttons shown that need scripts')
            if not r['h1']: bad.append(f'{w}px {path}: empty h1')
    b.close()
print(len(paths), 'pages at 2 widths,', len(bad), 'findings')
for x in bad: print(' -', x)
sys.exit(1 if bad else 0)
