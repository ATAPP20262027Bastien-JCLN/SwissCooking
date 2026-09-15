.PHONY: test test-unit test-feature

test:
	./vendor/bin/pest

test-unit:
	./vendor/bin/pest Tests/Unit

test-feature:
	./vendor/bin/pest Tests/Feature