env:
	cp .env.example .env
up:
	docker-compose up -d
down:
	docker-compose down
composer:
	composer install --ignore-platform-reqs
sqlite:
	rm -rf database/database.sqlite
	touch database/database.sqlite
migrations:
	docker exec -it api_series php artisan migrate:fresh --seed
run: up composer env sqlite migrations