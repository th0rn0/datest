run:
	docker-compose up -d

dev:
	docker-compose up

down:
	docker-compose down
	
install:
	composer install
	sudo chmod 777 /var/cache/*
	sudo chmod 777 /var/log/*
	docker-compose build

install-dev:
	composer install --dev
	sudo chmod 777 /var/cache/*
	sudo chmod 777 /var/log/*
	docker-compose build