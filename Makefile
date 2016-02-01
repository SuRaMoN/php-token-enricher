
.PHONY: test vendor composer
SHELL=bash

test: vendor
	vendor/bin/phpunit

vendor: composer
	bin/composer.phar install

composer:
	mkdir -p bin
	@if ! md5sum -c <(echo "488ca7972e447e0e1b2988f66d8e01e6  bin/composer.phar") 2> /dev/null; then \
		curl "https://getcomposer.org/download/1.0.0-alpha11/composer.phar" -o bin/composer.phar && \
		chmod a+x bin/composer.phar; \
	fi
	md5sum -c <(echo "488ca7972e447e0e1b2988f66d8e01e6  bin/composer.phar")

