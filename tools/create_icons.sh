#!/bin/bash

WIDTH=24
MOD=""
COLOR=black
if [[ $1 == --half ]]; then
  WIDTH=12
  MOD=.small
  shift
fi
if [[ $1 ]]; then
  COLOR="$1"
  MOD=".${COLOR#\#}"
fi

cd $(dirname $0)
SOURCE_FILE=../static/icons.svg
TARGET=../static/
PARAMS="--export-width=$WIDTH --export-height=$WIDTH"

SOURCE=$(sed -e 's/opacity="\.3"/opacity="0"/' -e "s/black/$COLOR/" "$SOURCE_FILE")

i=0
for item in new list cancel logout; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:704:${i}96:800 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in help save check login; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:604:${i}96:700 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in excl 'export' pencil on-off; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:504:${i}96:600 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in tag plus minus refresh; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:404:${i}96:500 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in document magnifier folder cog; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:304:${i}96:400 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in cut; do
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:204:${i}96:300 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

