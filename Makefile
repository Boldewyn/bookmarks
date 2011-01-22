all: icons

icons: static/*.png

static/*.png: static/icons.svg
	tools/create_icons.sh '#e60042'
	tools/create_icons.sh '#ffffff'
	tools/create_icons.sh '#95002b'
	tools/create_icons.sh '#ff9fbc'
	montage -background transparent -tile 1x4 -geometry +0+2 static/new.e60042.png static/new.ffffff.png static/new.95002b.png static/new.ff9fbc.png static/new.uncrushed.png
	pngcrush -b static/new.uncrushed.png static/new.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/login.e60042.png static/login.ffffff.png static/login.95002b.png static/login.ff9fbc.png static/login.uncrushed.png
	pngcrush -b static/login.uncrushed.png static/login.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/logout.e60042.png static/logout.ffffff.png static/logout.95002b.png static/logout.ff9fbc.png static/logout.uncrushed.png
	pngcrush -b static/logout.uncrushed.png static/logout.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/help.e60042.png static/help.ffffff.png static/help.95002b.png static/help.ff9fbc.png static/help.uncrushed.png
	pngcrush -b static/help.uncrushed.png static/help.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/list.e60042.png static/list.ffffff.png static/list.95002b.png static/list.ff9fbc.png static/list.uncrushed.png
	pngcrush -b static/list.uncrushed.png static/list.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/cancel.e60042.png static/cancel.ffffff.png static/cancel.95002b.png static/cancel.ff9fbc.png static/cancel.uncrushed.png
	pngcrush -b static/cancel.uncrushed.png static/cancel.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/check.e60042.png static/check.ffffff.png static/check.95002b.png static/check.ff9fbc.png static/check.uncrushed.png
	pngcrush -b static/check.uncrushed.png static/check.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/save.e60042.png static/save.ffffff.png static/save.95002b.png static/save.ff9fbc.png static/save.uncrushed.png
	pngcrush -b static/save.uncrushed.png static/save.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/excl.e60042.png static/excl.ffffff.png static/excl.95002b.png static/excl.ff9fbc.png static/excl.uncrushed.png
	pngcrush -b static/excl.uncrushed.png static/excl.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/export.e60042.png static/export.ffffff.png static/export.95002b.png static/export.ff9fbc.png static/export.uncrushed.png
	pngcrush -b static/export.uncrushed.png static/export.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/pencil.e60042.png static/pencil.ffffff.png static/pencil.95002b.png static/pencil.ff9fbc.png static/pencil.uncrushed.png
	pngcrush -b static/pencil.uncrushed.png static/pencil.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/on-off.e60042.png static/on-off.ffffff.png static/on-off.95002b.png static/on-off.ff9fbc.png static/on-off.uncrushed.png
	pngcrush -b static/on-off.uncrushed.png static/on-off.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/tag.e60042.png static/tag.ffffff.png static/tag.95002b.png static/tag.ff9fbc.png static/tag.uncrushed.png
	pngcrush -b static/tag.uncrushed.png static/tag.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/plus.e60042.png static/plus.ffffff.png static/plus.95002b.png static/plus.ff9fbc.png static/plus.uncrushed.png
	pngcrush -b static/plus.uncrushed.png static/plus.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/minus.e60042.png static/minus.ffffff.png static/minus.95002b.png static/minus.ff9fbc.png static/minus.uncrushed.png
	pngcrush -b static/minus.uncrushed.png static/minus.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/refresh.e60042.png static/refresh.ffffff.png static/refresh.95002b.png static/refresh.ff9fbc.png static/refresh.uncrushed.png
	pngcrush -b static/refresh.uncrushed.png static/refresh.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/document.e60042.png static/document.ffffff.png static/document.95002b.png static/document.ff9fbc.png static/document.uncrushed.png
	pngcrush -b static/document.uncrushed.png static/document.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/folder.e60042.png static/folder.ffffff.png static/folder.95002b.png static/folder.ff9fbc.png static/folder.uncrushed.png
	pngcrush -b static/folder.uncrushed.png static/folder.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/cog.e60042.png static/cog.ffffff.png static/cog.95002b.png static/cog.ff9fbc.png static/cog.uncrushed.png
	pngcrush -b static/cog.uncrushed.png static/cog.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/cut.e60042.png static/cut.ffffff.png static/cut.95002b.png static/cut.ff9fbc.png static/cut.uncrushed.png
	pngcrush -b static/cut.uncrushed.png static/cut.png
	montage -background transparent -tile 1x4 -geometry +0+2 static/magnifier.e60042.png static/magnifier.ffffff.png static/magnifier.95002b.png static/magnifier.ff9fbc.png static/magnifier.uncrushed.png
	pngcrush -b static/magnifier.uncrushed.png static/magnifier.png
	rm -f static/*.*.png

.PHONY: clean

clean:
	rm -f static/*.png
