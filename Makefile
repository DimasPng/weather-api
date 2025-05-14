init: docker-down-clear docker-pull docker-build docker-up api-init
api-init: composer-install wait-db migrate db-fresh npm-install
up: docker-up
down: docker-down validate
bash: docker-bash

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build

docker-pull:
	docker-compose pull

docker-bash:
	docker-compose exec -it php-fpm bash

composer-install:
	docker-compose run --rm php-fpm composer install

wait-db:
	docker-compose run --rm php-fpm wait-for-it mysql:3306 -t 30

migrate:
	docker-compose run --rm php-fpm php artisan migrate

db-fresh:
	docker-compose run --rm php-fpm php artisan migrate:fresh --seed

clear:
	docker-compose run --rm php-fpm sh -c "php artisan view:clear && php artisan route:clear && php artisan config:clear && php artisan cache:clear"

validate:
	docker-compose run --rm php-fpm composer run validate

npm-install:
	docker-compose run --rm node npm install

npm-dev:
	docker-compose run --rm node npm run dev

npm-build:
	docker-compose run --rm node npm run build
