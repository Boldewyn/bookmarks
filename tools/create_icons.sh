WIDTH=24
MOD=""
COLOR=black
if [[ $1 == half ]]; then
  WIDTH=12
  MOD=.small
else
  COLOR=$1
fi

SOURCE=../static/icons.svg
TARGET=../static/
PARAMS="--export-width=$WIDTH --export-height=$WIDTH"

i=0
for item in new list cancel logout; do
  sed "s/black/$COLOR/g" "$SOURCE" | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:104:${i}96:200 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

i=0
for item in help save check login; do
  sed "s/black/$COLOR/g" "$SOURCE" | inkscape --export-png=${TARGET}$item$MOD.png -a ${i}00:004:${i}96:100 $PARAMS /proc/self/fd/0
  let "i = $i + 1"
done

