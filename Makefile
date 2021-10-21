# Commands
CONSOLE	= $(SYMFONY) console
DOCKER = docker
DOCKER-COMPOSE = $(DOCKER)-compose
RUN = run
SYMFONY = symfony
TESTS = $(SYMFONY) $(RUN) vendor/bin/phpunit

# Default values
CONTAINER-NAME ?= php
ENV ?= dev

# Database
.PHONY: db-create db-drop db-fixtures db-migrations
db-create:				## Create Database
						$(CONSOLE) doctrine:database:create --if-not-exists --env=$(ENV)

db-drop:				## Delete Database
						$(CONSOLE) doctrine:database:drop --if-exists --force --env=$(ENV)

db-fixtures:			## Launch fixtures
						$(CONSOLE) hautelook:fixtures:load --no-interaction --env=$(ENV)

db-migrations:			## Execute Doctrine migrations
						$(CONSOLE) doctrine:migrations:migrate --no-interaction --env=$(ENV)

db-reload:				## Delete database, create it, launch migrations and load fixtures
						ENV=$(ENV) make db-drop
						ENV=$(ENV) make db-create
						ENV=$(ENV) make db-migrations
						ENV=$(ENV) make db-fixtures

# Docker Compose commands
.PHONY: dc-build dc-down dc-exec dc-start dc-stop dc-up
dc-build:				## Build docker images
						$(DOCKER-COMPOSE) build --pull --no-cache

dc-down:				## Delete containers and volumes
						$(DOCKER-COMPOSE) down --remove-orphans --volumes

dc-exec:				## Interact with a container
						$(DOCKER) exec -it $(CONTAINER-NAME) sh

dc-start:				## Start docker containers
						$(DOCKER-COMPOSE) start

dc-stop:				## Stop docker containers
						$(DOCKER-COMPOSE) stop

dc-up:					## Initialize the project with Docker
						$(DOCKER-COMPOSE) up -d

# Symfony Commands
.PHONY: sf-cc sf-cw
sf-cc:					## Clear Symfony cache
						$(CONSOLE) cache:clear --env=$(ENV)

sf-cw:					## Warmup Symfony cache
						$(CONSOLE) cache:warmup --env=$(ENV)

# Tests
.PHONY: coverage tests reset-tests
coverage: 				## Run the tests with the Code coverage report
						ENV=test XDEBUG_MODE=coverage $(TESTS) --coverage-html var/data

tests: 					## Run the tests
						ENV=test $(TESTS)

reset-coverage:			## Recreate database, launch migrations, load fixtures and execute tests with code coverage
						ENV=test make db-drop
						ENV=test make db-create
						ENV=test make db-migrations
						ENV=test make db-fixtures
						make coverage

reset-tests: 			## Recreate database, launch migrations, load fixtures and execute tests
						ENV=test make db-drop
						ENV=test make db-create
						ENV=test make db-migrations
						ENV=test make db-fixtures
						make tests

# Help
.PHONY: help

help:					## Display help
						@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.DEFAULT_GOAL := 	help
