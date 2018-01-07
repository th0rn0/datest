run:
	docker-compose up -d

dev:
	docker-compose up

down:
	docker-compose down
	
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