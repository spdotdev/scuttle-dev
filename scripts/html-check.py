# Usage: python3 scripts/html-check.py https://example.dev paths.txt  (needs: pip install html5lib)
# Fetches every page and reports duplicate attributes, unbalanced tags and html5lib parse errors. Exit 1 on any finding.
import collections,sys,urllib.request
import html5lib
from html.parser import HTMLParser
VOID={'area','base','br','col','embed','hr','img','input','link','meta','source','track','wbr'}
class P(HTMLParser):
    def __init__(s):
        super().__init__(convert_charrefs=True); s.stack=[]; s.errs=[]
    def handle_starttag(s,t,a):
        names=[k for k,_ in a]
        for k in set(names):
            if names.count(k)>1: s.errs.append(f'duplicate attribute {k} on <{t}> line {s.getpos()[0]}')
        if t not in VOID: s.stack.append((t,s.getpos()[0]))
    def handle_startendtag(s,t,a):
        names=[k for k,_ in a]
        for k in set(names):
            if names.count(k)>1: s.errs.append(f'duplicate attribute {k} on <{t}> line {s.getpos()[0]}')
    def handle_endtag(s,t):
        if t in VOID: return
        if not s.stack or s.stack[-1][0]!=t:
            s.errs.append(f'unexpected </{t}> line {s.getpos()[0]} (open: {s.stack[-1] if s.stack else None})')
            for i in range(len(s.stack)-1,-1,-1):
                if s.stack[i][0]==t: del s.stack[i:]; break
        else: s.stack.pop()
tot=collections.Counter()
base=sys.argv[1].rstrip('/'); paths=[l.strip() for l in open(sys.argv[2]) if l.strip()]
for path in paths:
    src=urllib.request.urlopen(urllib.request.Request(base+path,headers={'User-Agent':'html-check'}),timeout=30).read().decode('utf8','replace')
    p=P(); p.feed(src); p.close()
    errs=list(p.errs)+[f'unclosed <{t}> from line {l}' for t,l in p.stack]
    parser=html5lib.HTMLParser(strict=False); parser.parse(src)
    h5=[(pos,code,data) for pos,code,data in parser.errors if code not in ('expected-doctype-but-got-start-tag',)]
    name=path
    if errs or h5:
        print(name,'mine:',errs[:4],'| html5lib:',len(h5),[ (c,d) for _,c,d in h5][:4])
    tot['pages']+=1; tot['mine']+=len(errs); tot['h5']+=len(h5)
print(dict(tot))
sys.exit(1 if tot['mine'] or tot['h5'] else 0)
