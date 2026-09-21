.PHONY: help run-no-vhost install update dump-autoload test test-unit test-feature

help:
	@echo "Available commands:"
	@echo "  run-no-vhost      - Run the PHP built-in server without a virtual host"
	@echo "  install           - Install project dependencies using Composer"
	@echo "  update            - Update project dependencies using Composer"
	@echo "  dump-autoload     - Regenerate the Composer autoloader"
	@echo "  test              - Run all tests using Pest"
	@echo "  test-unit         - Run unit tests using Pest"
	@echo "  test-feature      - Run feature tests using Pest"

run-no-vhost:install
	php -S localhost:8000 -t public

install:
	composer install

update:install
	composer update

dump-autoload:install
	composer dump-autoload

test:install
	./vendor/bin/pest

test-unit:install
	./vendor/bin/pest tests/Unit

test-feature:install
	./vendor/bin/pest tests/Feature