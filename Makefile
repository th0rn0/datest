## Run Detached
run:
	docker-compose up -d

## Run with Debug
dev:
	docker-compose up

## Stop all containers
stop:
	docker-compose down
	
install:
	chmod 777 var/cache/
	chmod 777 var/logs/
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install
	docker-compose build

install-dev:
	chmod 777 var/cache/
	chmod 777 var/logs/
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --dev
	docker-compose build

## PHPUnit
test:
	vendor/bin/phpunit