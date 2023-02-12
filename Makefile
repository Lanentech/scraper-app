# Variables
DOCKER_COMPOSE=docker-compose
EXEC=docker exec -it scrape_app bash

# Ignore the following files/directories, so it does not get confused when running similarly named commands.
.PHONY: tests

up:
	${DOCKER_COMPOSE} up -d

down:
	${DOCKER_COMPOSE} down

build:
	${DOCKER_COMPOSE} up -d --build

scrape:
	${EXEC} -c "php bin/console app:scrape"

run-tests:
	${EXEC} -c "./vendor/bin/simple-phpunit"

run-unit-tests:
	${EXEC} -c "./vendor/bin/simple-phpunit --testsuite=Unit"

run-unit-tests-with-filter:
	${EXEC} -c "./vendor/bin/simple-phpunit --testsuite=Unit --filter=${filter}"

run-functional-tests:
	${EXEC} -c "./vendor/bin/simple-phpunit --testsuite=Functional"

run-functional-tests-with-filter:
	${EXEC} -c "./vendor/bin/simple-phpunit --testsuite=Functional --filter=${filter}"
