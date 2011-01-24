ICONIDS := $(shell sed -n '/id=/p' static/icons.svg |grep translate | sed 's/.*id="\([^"]\+\)".*/\1/')

all: icons

icons: static/*.png

static/*.png: static/icons.svg
	tools/create_icons.sh '#e60042'
	tools/create_icons.sh '#ffffff'
	tools/create_icons.sh '#95002b'
	tools/create_icons.sh '#ff9fbc'
	for item in $(ICONIDS); do \
		montage -background transparent -tile 1x4 -geometry +0+2 \
		  static/$$item.e60042.png static/$$item.ffffff.png static/$$item.95002b.png static/$$item.ff9fbc.png \
		  static/$$item.uncrushed.png; \
		pngcrush -b static/$$item.uncrushed.png static/$$item.png; \
	done
	rm -f static/*.*.png

.PHONY: clean

clean:
	rm -f static/*.png
