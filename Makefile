.PHONY: test coverage min-coverage clean cs cs-fix dev coveralls verify-readme
.PHONY: mnd psalm phpmetrics infection verify require phpmd sonar benchmark
.PHONY: benchmark-trace no-leaks

vendor: composer.json composer.lock
	composer install

verify: coverage min-coverage cs phpstan phpmd psalm require mnd no-leaks phpmetrics

dev: cs-fix verify

infection: vendor
	vendor/bin/infection --threads=8

coveralls: logs/phpunit
	mkdir -p build/logs && \
	cp logs/phpunit/clover.xml build/logs/ && \
	vendor/bin/php-coveralls -v

sonar: logs/phpunit
	./sonar.sh

benchmark: vendor
	vendor/bin/phpbench run  --revs=50 --iterations=10 --report=aggregate

benchmark-trace: vendor
	vendor/bin/phpbench xdebug:trace --extension="PhpBench\\Extensions\\XDebug\\XDebugExtension"

clean:
	rm -rf logs vendor

test: vendor
	vendor/bin/phpunit

coverage: vendor
	vendor/bin/phpunit \
		--coverage-html logs/phpunit/coverage-report \
		--coverage-clover logs/phpunit/clover.xml \
		--log-junit logs/phpunit/test-report.xml

min-coverage: vendor coverage
	vendor/bin/min-coverage --min-coverage 95 --clover-file logs/phpunit/clover.xml

cs: vendor
	vendor/bin/phpcs -s src test

cs-fix: vendor
	vendor/bin/phpcbf --report=diff src test || :

phpstan: vendor
	vendor/bin/phpstan analyse --level 7 src

mnd: vendor
	vendor/bin/phpmnd --non-zero-exit-on-violation src

psalm: vendor
	vendor/bin/psalm

phpmetrics: vendor logs/phpunit
	vendor/bin/phpmetrics \
		--report-html=logs/phpmetrics \
		--junit=logs/phpunit/test-report.xml \
		.

require: vendor
	vendor/bin/composer-require-checker check ./composer.json

phpmd: vendor
	vendor/bin/phpmd src/ text phpmd.xml

verify-readme: vendor README.md
	./verify-readme

no-leaks: vendor
	vendor/bin/roave-no-leaks
