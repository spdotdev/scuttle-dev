#!/bin/sh
# Regenerate every logo asset of scuttle-dev from the layered-squares mark in a given colour.
# usage: make_logo.sh <accent hex> <accent light hex> [outdir]   (danger: low, writes only into outdir)
set -eu
A="$1"; B="$2"; OUT="${3:-$HOME/sd/scuttle-dev/public}"
mkdir -p "$OUT/mail"
logo() { # $1 viewBox, $2 stroke width, $3 opacities "a b"
  set -- "$1" "$2" $3
  printf '<svg xmlns="http://www.w3.org/2000/svg" viewBox="%s"><defs><linearGradient id="g1" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient></defs><rect x="30" y="38" width="36" height="36" rx="8" fill="none" stroke="%s" stroke-width="%s" opacity="%s"/><rect x="50" y="27" width="36" height="36" rx="8" fill="none" stroke="%s" stroke-width="%s" opacity="%s"/><rect x="70" y="16" width="36" height="36" rx="8" fill="none" stroke="url(#g1)" stroke-width="%s"/></svg>' \
    "$1" "$A" "$B" "$A" "$2" "$3" "$A" "$2" "$4" "$2"
}
logo "22 8 92 74" 5.5 "0.25 0.55" > "$OUT/logo.svg"
logo "22 -1 92 92" 8 "0.35 0.65" > "$OUT/icon.svg"
rsvg-convert -w 16 -h 16 "$OUT/icon.svg" -o "$OUT/favicon-16x16.png"
rsvg-convert -w 32 -h 32 "$OUT/icon.svg" -o "$OUT/favicon-32x32.png"
rsvg-convert -w 192 -h 192 "$OUT/icon.svg" -o "$OUT/android-chrome-192x192.png"
rsvg-convert -w 512 -h 512 "$OUT/icon.svg" -o "$OUT/android-chrome-512x512.png"
# touch icon: light background behind the mark
rsvg-convert -w 140 -h 140 "$OUT/icon.svg" -o /tmp/_touch.png
magick -size 180x180 xc:'#f5f5f5' /tmp/_touch.png -gravity center -composite "$OUT/apple-touch-icon.png"
# mail signature logo: white circle, 96 px
rsvg-convert -w 60 -h 60 "$OUT/icon.svg" -o /tmp/_sig.png
magick -size 96x96 xc:none -fill white -draw 'circle 48,48 48,1' /tmp/_sig.png -gravity center -composite "$OUT/mail/signature-logo.png"
rm -f /tmp/_touch.png /tmp/_sig.png
echo "logo assets written to $OUT in $A -> $B"
