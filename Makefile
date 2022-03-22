up:
	docker-compose up -d
down:
	docker-compose down
composer:
	composer install --ignore-platform-reqs
env:
	cp .env.example .env
sqlite:
	rm -rf database/database.sqlite
	touch database/database.sqlite
migrations:
	docker exec -it api_series php artisan migrate:fresh --seed
storage:
	mkdir storage
passport_install:
	docker exec -it api_series php artisan passport:install --force
test:
	docker exec -it api_series php vendor/bin/phpunit
bash:
	docker exec -it api_series /bin/bash
run: up composer env sqlite migrations storage passport_install test