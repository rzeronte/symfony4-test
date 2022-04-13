-include .env.local

COLOR_RESET = \033[0m
COLOR_INFO = \033[32m
COLOR_COMMENT = \033[33m

DOCKER_NAMESPACE := $(shell basename $(CURDIR) | tr A-Z a-z)

DATE := $(shell date +%Y)

UNAME := $(shell uname)
UID := $(shell id -u)
GID := $(shell id -g)

ifeq ($(UNAME), Linux)
    OS = "Linux"
else ifeq ($(UNAME), Darwin)
    OS = "OSX"
else
    OS = "Windows"
endif

ifndef DISABLE_INTERACTIVE
	INTERACTIVE_FLAG =
else
	INTERACTIVE_FLAG = -T
endif

DOCKER_COMPOSE = docker-compose
EXEC_APP = $(DOCKER_COMPOSE) run ${INTERACTIVE_FLAG} php-fpm sh -c
EXEC_TRIVY = docker run --rm -v /var/run/docker.sock:/var/run/docker.sock -v /tmp/Caches:/root/.cache/ aquasec/trivy
DOCKER_IMAGES = $(shell docker ps --format '{{.Image}}' -a --no-trunc --filter name=^/ms-integration)

.DEFAULT_GOAL := help

##@ Helpers

.PHONY: help
help: ## Display help
	@awk 'BEGIN {FS = ":.*##"; printf "\n\033[1;34m${DOCKER_NAMESPACE}\033[0m\nCopyright (c) ${DATE} Docline Development\n \nUsage:\n  make \033[1;34m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[1;34m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Main commands
.PHONY: initialize
initialize: copy-environment start composer-install db-init ## Initialize this project from scratch

.PHONY: start
start: ## Start this project
	docker-compose up --force-recreate -d --remove-orphans

.PHONY: restart
restart: ## Restart this project
	docker-compose restart

.PHONY: stop
stop: ## Stop this project
	docker-compose stop

.PHONY: bash
bash: ## Takes you inside the API container
	docker exec -it php-fpm bash

.PHONY: copy-environment
copy-environment: ## Copy environment files
	cp .application.env ./application/.env

.PHONY: composer-install
composer-install: ## Install Composer dependencies
	$(EXEC_APP) "composer install"

.PHONY: composer-update
composer-update: ## Update Composer dependencies
	$(EXEC_APP) "composer update"

.PHONY: composer-validate
composer-validate: ## Validate composer.json and composer.lock
	$(EXEC_APP) "composer validate --no-check-lock --strict composer.json"

##@ Database
.PHONY: db-init
db-init: db-fresh  ## Initialize database

.PHONY: db-fresh
db-fresh: ## Drop the database and create a new one with all migrations
	$(EXEC_APP) "./bin/console doctrine:database:drop --force --if-exists"
	$(EXEC_APP) "./bin/console doctrine:database:create"
	$(EXEC_APP) "./bin/console doctrine:migrations:migrate --no-interaction"

.PHONY: db-migrate
db-migrate: ## Launch database migrations
	$(EXEC_APP) "./bin/console doctrine:migrations:migrate --no-interaction"

.PHONY: db-diff
db-diff: db-init ## Generate a migration by comparing your current database to your mapping information
	$(EXEC_APP) "./bin/console doctrine:migration:diff"

.PHONY: db-validate
db-validate: ## Validate Doctrine mapping files
	$(EXEC_APP) "php bin/console doctrine:schema:validate"

.PHONY: lint-check
lint-check:  ## Analyse code and show errors
	$(EXEC_APP) "php -d memory_limit=-1 ./vendor/bin/phpcs --standard=PSR2 -s src tests"
	$(EXEC_APP) "php -d memory_limit=-1 ./vendor/bin/php-cs-fixer fix --dry-run --diff --no-interaction"

.PHONY: lint-fix
lint-fix: ## Analyse code and fix errors
	$(EXEC_APP) "php -d memory_limit=-1 ./vendor/bin/phpcbf --standard=PSR2 src tests"
	$(EXEC_APP) "php -d memory_limit=-1 ./vendor/bin/php-cs-fixer fix --diff --no-interaction"

PHPSTAN_PATH := src

.PHONY: phpstan
phpstan: ## Run PHPStan
	$(EXEC_APP) "php ./bin/console debug:container --quiet" # Required by phpstan/phpstan-symfony
	$(EXEC_APP) "php ./bin/phpstan analyse -l max -c phpstan.neon --memory-limit=-1 $(PHPSTAN_PATH)"

.PHONY: test
mutant-test: mutant-test ## Execute all tests
	$(EXEC_APP) "php ./bin/phpunit --coverage-xml=./report"
	$(EXEC_APP) "php -d memory_limit=-1 ./vendor/bin/infection --filter=src --threads=4 --show-mutations"

.PHONY: unit-test
unit-test: ## Execute unit tests with no coverage
	$(EXEC_APP) "php ./bin/phpunit --no-coverage"

.PHONY: unit-test-coverage
unit-test-coverage: unit-test-coverage ## Execute unit tests with coverage
	$(EXEC_APP) "php ./bin/phpunit --coverage-html=./report"

.PHONY: bdd-test
bdd-test: ## Execute unit tests with no coverage
	$(EXEC_APP) "php ./vendor/bin/behat"
