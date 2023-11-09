up:
	cd docker && docker-compose up -d --build
	cd docker && docker-compose exec php_foreign composer install
	cd docker && docker-compose exec php_foreign php bin/console doctrine:database:create --if-not-exists
	cd docker && docker-compose exec php_foreign php bin/console doctrine:migrations:migrate --no-interaction

