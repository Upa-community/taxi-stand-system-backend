docker_up:
	docker compose -f docker-compose.yml up -d --build
	docker compose -f docker-compose.yml exec app composer install
	docker compose -f docker-compose.yml exec app php artisan migrate
	docker compose -f docker-compose.yml exec app php artisan octane:start --host=0.0.0.0 --port=8000
