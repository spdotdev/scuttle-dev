#!/usr/bin/env python3
"""Rewrites a qrencode SVG (one <rect> per dark module, about 310 KB for the vCard) as one path of merged runs.
Usage: python3 design/qr-compact.py public/qr/scuttle-vcard.svg   (rewrites the file; the picture does not change)"""
import re, sys
p = sys.argv[1]
s = open(p).read()
vb = re.search(r'viewBox="0 0 (\d+) (\d+)"', s)
w, h = int(vb.group(1)), int(vb.group(2))
tr = re.search(r'translate\((\d+),(\d+)\)', s)
ox, oy = int(tr.group(1)), int(tr.group(2))
colour = re.search(r'<rect x="\d+" y="\d+" width="1" height="1" fill="(#[0-9a-f]+)"', s).group(1)
cells = {(int(x), int(y)) for x, y in re.findall(r'<rect x="(\d+)" y="(\d+)" width="1" height="1"', s)}
d = []
for y in sorted({c[1] for c in cells}):
    xs = sorted(c[0] for c in cells if c[1] == y)
    start = prev = xs[0]
    for x in xs[1:] + [None]:
        if x is not None and x == prev + 1:
            prev = x
            continue
        d.append(f'M{start + ox} {y + oy}h{prev - start + 1}v1h-{prev - start + 1}z')
        if x is not None:
            start = prev = x
open(p, 'w').write(f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}" shape-rendering="crispEdges"><rect width="{w}" height="{h}" fill="#ffffff"/><path fill="{colour}" d="{"".join(d)}"/></svg>\n')
print(len(cells), 'modules')
