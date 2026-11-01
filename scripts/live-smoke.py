#!/usr/bin/env python3
"""Live smoke watch: usage live-smoke.py <base url> [list file].

For every line of scripts/smoke-urls.txt (status, path, marker, tab separated) do a GET with a 15 second timeout, up to
three tries 20 seconds apart, and require the expected status, the marker text in the body and an answer under 3 seconds.
Prints one line per address; exits 1 and lists every failing address when any fails. No browser, standard library only."""
import sys
import time
import urllib.error
import urllib.request

TRIES, PAUSE, TIMEOUT, SLOW = 3, 20, 15, 3.0


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


def load(path):
    rows = []
    for line in open(path, encoding='utf-8').read().splitlines():
        if not line.strip() or line.startswith('#'):
            continue
        status, url, marker = line.split('\t')
        rows.append((int(status), url, marker))
    return rows


def fetch(url):
    opener = urllib.request.build_opener(NoRedirect)
    req = urllib.request.Request(url, headers={'User-Agent': 'scuttle-live-smoke'})
    start = time.monotonic()
    try:
        with opener.open(req, timeout=TIMEOUT) as r:
            code, body = r.status, r.read()
    except urllib.error.HTTPError as e:
        code, body = e.code, e.read()
    return code, body.decode('utf-8', 'replace'), time.monotonic() - start


def check(base, status, path, marker):
    problem = 'no answer'
    for attempt in range(TRIES):
        if attempt:
            time.sleep(PAUSE)
        try:
            code, body, secs = fetch(base + path)
        except Exception as e:  # network error, timeout
            problem = type(e).__name__ + ' ' + str(e)[:80]
            continue
        problems = []
        if code != status:
            problems.append(f'status {code}, expected {status}')
        if marker not in body:
            problems.append(f'marker "{marker}" missing')
        if secs > SLOW:
            problems.append(f'slow {secs:.1f}s')
        if not problems:
            return None, f'{code} {secs:.2f}s'
        problem = ', '.join(problems)
    return problem, ''


def main():
    base = sys.argv[1].rstrip('/')
    rows = load(sys.argv[2] if len(sys.argv) > 2 else 'scripts/smoke-urls.txt')
    failed = []
    for status, path, marker in rows:
        problem, info = check(base, status, path, marker)
        if problem:
            failed.append((path, problem))
            print(f'FAIL {path}: {problem}')
        else:
            print(f'ok   {path} {info}')
    print(f'{len(rows) - len(failed)} of {len(rows)} addresses fine')
    if failed:
        print('Failing addresses:')
        for path, problem in failed:
            print(f'  {base}{path}: {problem}')
        sys.exit(1)


main()
