ICONIDS := $(shell sed -n '/id=/p' bookmark/static/icons.svg |grep translate | sed 's/.*id="\([^"]\+\)".*/\1/')

all: icons

icons: bookmark/static/*.png

bookmark/static/*.png: bookmark/static/icons.svg
	tools/create_icons.sh '#e60042'
	tools/create_icons.sh '#ffffff'
	tools/create_icons.sh '#95002b'
	tools/create_icons.sh '#ff9fbc'
	for item in $(ICONIDS); do \
		montage -background transparent -tile 1x7 -geometry 24x24 \
		  bookmark/static/$$item.e60042.png \
		  NULL: \
		  bookmark/static/$$item.ffffff.png \
		  NULL: \
		  bookmark/static/$$item.95002b.png \
		  NULL: \
		  bookmark/static/$$item.ff9fbc.png \
		  bookmark/static/$$item.uncrushed.png; \
		pngcrush -b bookmark/static/$$item.uncrushed.png bookmark/static/$$item.png; \
	done
	rm -f bookmark/static/*.*.png

.PHONY: clean

clean:
	rm -f bookmark/static/*.png
