## Run Detached
run:
	docker-compose up -d

## Run with Debug
dev:
	docker-compose up

## Stop all containers
down:
	docker-compose down
	
install:
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install
	docker-compose build

install-dev:
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --dev
	docker-compose build

## PHPUnit
test:
	vendor/bin/phpunit