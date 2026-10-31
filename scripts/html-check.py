import glob,collections,sys
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
for f in sorted(glob.glob('../r11dump/*.html')):
    src=open(f).read()
    p=P(); p.feed(src); p.close()
    errs=list(p.errs)+[f'unclosed <{t}> from line {l}' for t,l in p.stack]
    parser=html5lib.HTMLParser(strict=False); parser.parse(src)
    h5=[(pos,code,data) for pos,code,data in parser.errors if code not in ('expected-doctype-but-got-start-tag',)]
    name=f.split('/')[-1]
    if errs or h5:
        print(name,'mine:',errs[:4],'| html5lib:',len(h5),[ (c,d) for _,c,d in h5][:4])
    tot['pages']+=1; tot['mine']+=len(errs); tot['h5']+=len(h5)
print(dict(tot))
