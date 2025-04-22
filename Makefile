test:
	vendor/bin/phpstan analyse --level 9 src tests
	vendor/bin/phpunit src tests

style:
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
