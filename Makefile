up:
	cd docker && docker-compose up -d --build
	cd docker && docker-compose exec php_foreign composer install
	cd docker && docker-compose exec php_foreign php bin/console doctrine:database:create --if-not-exists
	cd docker && docker-compose exec php_foreign php bin/console doctrine:migrations:migrate --no-interaction
up-stage:
	cp docker/.env.stage-example .env
	cp docker/.env.stage-example docker/.env
	cd docker && docker-compose -f docker-compose.stage.yml up -d --build #--scale message_consumer_spf=3
	cd docker && docker-compose exec php_foreign composer install
	cd docker && docker-compose exec php_foreign php bin/console doctrine:database:create --if-not-exists
	cd docker && docker-compose exec php_foreign php bin/console doctrine:migrations:migrate --no-interaction

down-stage:
	cd docker && docker-compose -f docker-compose.stage.yml down