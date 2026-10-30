#!/usr/bin/env python3
"""Renders one 1200x630 social image per page into public/og/<slug>.png.
Usage: php design/og-data.php > /tmp/og.json && python3 design/make-og.py /tmp/og.json
The layout picks public/og/<slug>.png by the page address (/services/modernization -> services-modernization.png)
and falls back to public/og-image.png."""
import base64, html, json, os, sys
from playwright.sync_api import sync_playwright

root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
cards = json.load(open(sys.argv[1]))
out = os.path.join(root, 'public', 'og')
os.makedirs(out, exist_ok=True)
fonts = os.path.join(root, 'public', 'fonts')
def b64(path, mime):
    return 'data:%s;base64,%s' % (mime, base64.b64encode(open(path, 'rb').read()).decode())

logo = b64(os.path.join(root, 'public', 'logo.svg'), 'image/svg+xml')
f_sans = b64(os.path.join(fonts, 'geist-latin.woff2'), 'font/woff2')
f_mono = b64(os.path.join(fonts, 'geist-mono-latin.woff2'), 'font/woff2')

TPL = """<!doctype html><html><head><meta charset="utf-8"><style>
@font-face{{font-family:Geist;font-weight:400 600;src:url({f_sans}) format("woff2")}}
@font-face{{font-family:"Geist Mono";font-weight:400 500;src:url({f_mono}) format("woff2")}}
body{{margin:0;width:1200px;height:630px;background:#f5f5f5;color:#0a0a0a;font-family:Geist,sans-serif;display:flex;flex-direction:column;justify-content:space-between;padding:56px 72px;box-sizing:border-box}}
.top{{display:flex;justify-content:space-between;align-items:center;font:500 20px "Geist Mono",monospace;letter-spacing:.14em;text-transform:uppercase}}
.brand{{display:flex;align-items:center;gap:14px}}
.k{{color:#CF5A26}}
h1{{margin:0;font-weight:500;letter-spacing:-.025em;line-height:1.04;font-size:{size}px;text-wrap:balance}}
p{{margin:22px 0 0;font-size:30px;line-height:1.35;color:#555;max-width:900px}}
.foot{{font:500 20px "Geist Mono",monospace;letter-spacing:.1em;text-transform:uppercase;color:#6b6b6b;border-top:2px solid #dadada;padding-top:20px}}
</style></head><body>
<div class="top"><div class="brand"><img src="{logo}" width="60" height="48">Scuttle Development</div><span class="k">{kicker}</span></div>
<div><h1>{title}</h1>{line}</div>
<div class="foot">{foot}</div></body></html>"""

with sync_playwright() as pw:
    b = pw.chromium.launch()
    pg = b.new_page(viewport={'width': 1200, 'height': 630})
    for slug, c in cards.items():
        n = len(c['title'])
        size = 84 if n <= 28 else 68 if n <= 48 else 54 if n <= 80 else 46
        line = '<p>%s</p>' % html.escape(c['line']) if c['line'] else ''
        pg.set_content(TPL.format(f_sans=f_sans, f_mono=f_mono, logo=logo, size=size, kicker=html.escape(c['kicker']), title=html.escape(c['title']), line=line, foot=html.escape(c['foot'])))
        pg.wait_for_timeout(150)
        pg.screenshot(path=os.path.join(out, slug + '.png'))
    b.close()
print(len(cards), 'images in', out)
