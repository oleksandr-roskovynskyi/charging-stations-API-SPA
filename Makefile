up:
	cp .env.example .env && \
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
