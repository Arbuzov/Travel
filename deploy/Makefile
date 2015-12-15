projectName=travel-php-lib
fakeRoot=/tmp/$(projectName)
BUILD_NUMBER = 1

clean:
	sudo rm -rf $(fakeRoot)
	sudo rm -rf /tmp/$(projectName).deb
	sudo rm -rf *.deb
	
deb: clean
	install -d $(fakeRoot)
	install -d $(fakeRoot)/DEBIAN/
	install -d $(fakeRoot)/usr/share/doc/$(projectName)/
	install -d -m 777 $(fakeRoot)/usr/share/pear

	cp ./DEBIAN/* $(fakeRoot)/DEBIAN/
	cp -R ../src/Travel $(fakeRoot)/usr/share/pear/Travel/
	
	cp ./DEBIAN/copyright $(fakeRoot)/usr/share/doc/$(projectName)/
	gzip -c -9 ./DEBIAN/changelog > $(fakeRoot)/usr/share/doc/$(projectName)/changelog.Debian.gz
	sed -i 's/#build/$(BUILD_NUMBER)/' $(fakeRoot)/DEBIAN/control
	fakeroot dpkg-deb --build $(fakeRoot)
	cp /tmp/$(projectName).deb ../build/$(projectName).$(BUILD_NUMBER)~noarch.deb
	
check: deb
	lintian --allow-root -L ">=important" /tmp/$(projectName).deb

test: 
	cd ../tests; phpunit
	cd ../; phpcs --extensions=php --standard=Zend --report=checkstyle --report-file=./tests/log/check_style.xml ./src
	cd ../; phpmd ./src xml codesize,unusedcode,naming,design --reportfile ./tests/log/pmd.xml