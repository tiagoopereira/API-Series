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
bash:
	docker exec -it api_series /bin/bash
run: up composer env sqlite migrations