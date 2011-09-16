ICONIDS := $(shell sed -n '/id=/p' bookmarks/static/icons.svg |grep translate | sed 's/.*id="\([^"]\+\)".*/\1/')

all: icons

icons: bookmarks/static/*.png

bookmarks/static/*.png: bookmarks/static/icons.svg
	tools/create_icons.sh --half '#e60042'
	tools/create_icons.sh --half '#ffffff'
	tools/create_icons.sh --half '#95002b'
	tools/create_icons.sh --half '#ff9fbc'
	for item in $(ICONIDS); do \
		montage -background transparent -tile 1x7 -geometry 24x24 \
		  bookmarks/static/$$item.e60042.png \
		  NULL: \
		  bookmarks/static/$$item.ffffff.png \
		  NULL: \
		  bookmarks/static/$$item.95002b.png \
		  NULL: \
		  bookmarks/static/$$item.ff9fbc.png \
		  bookmarks/static/$$item.png; \
		optipng -o7 bookmarks/static/$$item.png; \
	done
	rm -f bookmarks/static/*.*.png

.PHONY: clean

clean:
	rm -f bookmarks/static/*.png
