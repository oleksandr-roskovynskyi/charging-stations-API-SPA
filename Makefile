up:
	cp .env.example .env && \
	cp docker-compose.yml.example docker-compose.yml && \
	docker-compose up -d && \
	docker-compose exec app composer install && \
 	docker-compose exec app php artisan key:generate && \
 	docker-compose exec app php artisan migrate && \
 	docker-compose exec app php artisan db:seed && \
	docker-compose run frontend npm install && \
	docker-compose run frontend npm run prod
watch:
	docker-compose run frontend npm run watch
d:
	docker-compose up -d && \
	docker-compose ps
down:
	docker-compose down
res:
	docker-compose stop
	docker-compose up -d
o:
	docker-compose exec app composer dump-autoload -o

test:
	docker-compose exec app vendor/bin/phpunit

test-feature:
	docker-compose exec app vendor/bin/phpunit --filter=Feature

test-suite:
	docker-compose exec app vendor/bin/phpunit --testsuite=Feature

test-group:
	docker-compose exec app vendor/bin/phpunit --group=start,example

test-unit-coverage:
	docker-compose exec app vendor/bin/phpunit --testsuite=Unit --coverage-html var/coverage/unit

test-functional-coverage:
	docker-compose exec app vendor/bin/phpunit --testsuite=functional --coverage-html var/coverage/functional

test-coverage:
	docker-compose exec app vendor/bin/phpunit --coverage-html var/coverage/all
