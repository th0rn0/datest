run:
	docker-compose up

install:
	composer install
	chmod 777 /var/cache/*
	chmod 777 /var/log/*
	docker-composer build

install-dev:
	composer install --dev
	chmod 777 /var/cache/*
	chmod 777 /var/log/*
	docker-composer build