WIDTH=24
MOD=""
if [[ $1 == half ]]; then
  WIDTH=12
  MOD=.small
fi

SOURCE=../static/icons.svg
TARGET=../static/
PARAMS="--export-width=$WIDTH --export-height=$WIDTH"

i=0
for item in new list cancel logout; do
  inkscape --export-png=${TARGET}$item$MOD.png --export-area=${i}00:104:${i}96:200 $PARAMS "$SOURCE" && identify ${TARGET}$item$MOD.png
  let "i = $i + 1"
done

i=0
for item in help save check login; do
  inkscape --export-png=${TARGET}$item$MOD.png --export-area=${i}00:004:${i}96:100 $PARAMS "$SOURCE" && identify ${TARGET}$item$MOD.png
  let "i = $i + 1"
done

