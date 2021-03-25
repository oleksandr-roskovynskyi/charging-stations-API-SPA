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
