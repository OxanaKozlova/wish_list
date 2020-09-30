include .env

SHELL := /bin/bash

start: up info ## Starts the environment

stop: down ## Stops the environment

up:
	@docker-compose up -d

down:
	@docker-compose down

info:
	@echo -e "\n\n"
	@echo "------------------------------------------------"
	@echo "Running on http://localhost:$(NGINX_PORT)"
	@echo "------------------------------------------------"
	@echo -e "\n\n"

setup: ## Setup the environment
	@docker-compose up -d --build
	@docker-compose run app sh -c " \
	composer install && \
	php bin/console doctrine:database:create --if-not-exists && \
	php bin/console doctrine:migration:migrate --no-interaction && \
	php bin/console doctrine:fixtures:load --append \
	"

test: ## run tests
	@docker-compose run app sh -c "composer install && php bin/phpunit"

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
