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
SOURCE_FILE=../bookmark/static/icons.svg
TARGET=../bookmark/static/
PARAMS="--export-width=$WIDTH --export-height=$WIDTH"

SOURCE=$(sed -e 's/opacity="\.3"/opacity="0"/' -e "s/black/$COLOR/" "$SOURCE_FILE")

ITEMS=$(sed -n '/id=/p' $SOURCE_FILE |grep translate | sed 's/.*id="\([^"]\+\)".\+transform="translate(\([0-9]\+\),\([0-9]\+\))".*$/\1:\2:\3/g')

for item in $ITEMS; do
  X1=${item#*:}
  X1=${X1%%:*}
  let "X2 = $X1 + 24"
  Y0=${item##*:}
  let "Y2 = 200 - Y0"
  let "Y1 = 200 - Y0 - 24"
  item=${item%%:*}
  echo $SOURCE | inkscape --export-png=${TARGET}$item$MOD.png \
                 -a $X1:$Y1:$X2:$Y2 $PARAMS /proc/self/fd/0
done
