#!/bin/sh
# Rebuild the four font files in public/fonts from the Geist release (SIL OFL, github.com/vercel/geist-font).
# Usage: design/make-fonts.sh <folder holding the unzipped release, with geist-font/Geist and geist-font/GeistMono> [output folder]
# Needs fontTools with brotli (pyftsubset). Current files: Geist v1.9.0 (2026-11-01), subset to the unicode-range
# lists of the @font-face rules in public/site.css; the arrows U+2190 to U+2193 and U+2197 are in the latin file.
set -e
src=${1:?folder with the unzipped Geist release}; out=${2:-public/fonts}
LATIN='U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2190-2193,U+2197,U+2212,U+2215,U+FEFF,U+FFFD'
EXT=$(grep -o 'font-family:"Geist";[^}]*latin-ext[^}]*unicode-range:[^;}]*' public/site.css | sed 's/.*unicode-range://')
FEATURES=frac,numr,dnom,pnum,tnum,liga,ccmp,locl
for pair in "Geist/variable/Geist[wght].ttf:geist" "GeistMono/variable/GeistMono[wght].ttf:geist-mono"; do
  f="$src/geist-font/${pair%%:*}"; name=${pair##*:}
  pyftsubset "$f" --unicodes="$LATIN" --flavor=woff2 --layout-features+=$FEATURES --no-hinting --output-file="$out/$name-latin.woff2"
  pyftsubset "$f" --unicodes="$EXT" --flavor=woff2 --layout-features+=$FEATURES --no-hinting --output-file="$out/$name-latin-ext.woff2"
done
