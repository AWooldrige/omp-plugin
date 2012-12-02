PACKAGE = omp-plugin
VERSION = 0.1.0

ARCHIVE = $(PACKAGE)-$(VERSION)
BUILDDIR = ./BUILD/$(ARCHIVE)/
DESTDIR = /var/www/wp_ompcouk/wp-content/plugins/

.PHONY: test

all: build

##############################################################################
# INSTALL TO DESTDIR AS IF FINAL PRODUCT - This may require sudo
##############################################################################
install:
	cp -R $(BUILDDIR) $(DESTDIR)
	chown -R www-data:www-data $(DESTDIR)$(ARCHIVE)

distclean:
	rm -rf $(DESTDIR)$(ARCHIVE)


##############################################################################
# INSTALL TO DESTDIR WITH -dev PREPENDED - This may require sudo
##############################################################################
devinstall:
	cp -R $(BUILDDIR) $(DESTDIR)$(ARCHIVE)-dev/
	chown -R www-data:www-data $(DESTDIR)$(ARCHIVE)-dev

devclean:
	rm -rf $(DESTDIR)$(ARCHIVE)-dev


##############################################################################
# BUILDING OF THE PLUGIN
##############################################################################
# Build should build the plugin into BUILD asif BUILD was the root of the plugin
build: clean test prep
	cp -R src/* $(BUILDDIR)
	# Replace {{VERSION}} with the version of the build in all files
	find $(BUILDDIR) -type f | xargs perl -pi -e 's/{{VERSION}}/$(VERSION)/g'
	find $(BUILDDIR) -type f | xargs perl -pi -e 's/{{ARCHIVE}}/$(ARCHIVE)/g'

dist: build
	tar -cjf ./SOURCES/$(ARCHIVE).tar.bz2 -C ./BUILD/ .
	cd ./BUILD/ && zip -9 -r ../SOURCES/$(ARCHIVE).zip ${ARCHIVE} && cd -

clean:
	rm -rf BUILD SOURCES

prep:
	mkdir -p $(BUILDDIR) SOURCES

test:
	phpunit -c test/phpunit.xml
