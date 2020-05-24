# SPA "Charging stations"
### API for a service which does the following:  
 - creates / updates / deletes E-stations;
 - displays stations in the specified city (Kyiv, Odessa, Lviv…);
 - displays stations in the specified city that are currently open;
 - displays the closest E-station that is currently open.

## Installation. Unix (Linux)
```bash
make up
```
#### or follow sequentially:

```bash
cp docker-compose.yml.example docker-compose.yml
cp .env.example .env

docker-compose up -d

docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose run frontend npm install
docker-compose run frontend npm run prod
```

Open browser:

`https://localhost:8001`


### Tests
```bash
docker-compose exec app vendor/bin/phpunit
```
