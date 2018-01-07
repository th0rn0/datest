run:
	docker-compose up -d

dev:
	docker-compose up

down:
	docker-compose down
	
install:
	docker run --rm --interactive --tty \
    --volume $PWD:/app \
    --user $(id -u):$(id -g) \
    composer install

	sudo chmod 777 /var/cache/*
	sudo chmod 777 /var/log/*
	docker-compose build

install-dev:
	composer install --dev
	sudo chmod 777 /var/cache/*
	sudo chmod 777 /var/log/*
	docker-compose build