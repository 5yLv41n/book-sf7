#Setup automatically docker compose variables
include .env
-include .env.local

COMPOSE			:= docker-compose.exe
COMPOSE_EXEC	:= $(COMPOSE) exec
PHP 			:= $(COMPOSE_EXEC) php
COMPOSER 		:= $(PHP) composer

.php-cs-fixer.php: .php-cs-fixer.dist.php ## Create ".php-cs-fixer.php" file
	@cp .php-cs-fixer.dist.php $@

### Code style targets ###
php_cs_fixer: ## Run PHP Code standards and fixer
	$(PHP) vendor/bin/php-cs-fixer fix

phpstan: ## Run phpstan
	@$(PHP) vendor/bin/phpstan analyse src

### Common targets ###
setup: .env.local docker-compose.yaml .php-cs-fixer.php ## Setup project before that all containers is up

up: ## Start all containers
	@$(COMPOSE) pull --ignore-pull-failures --quiet &>/dev/null
	@$(COMPOSE) up -d --remove-orphans --build

down: ## Stop all containers
	@$(COMPOSE) down -v

### Databases targets ###
reset_dbs: reset_mariadb reset_postgres ## Rest all databases

reset_mariadb: ## Reset mariadb database
	@$(PHP) bin/console doctrine:database:drop --force --if-exists -c mariadb
	@$(PHP) bin/console doctrine:database:create -c mariadb
	@$(PHP) bin/console doctrine:schema:create --em mariadb

reset_postgres: ## Reset postgres database
	@$(PHP) bin/console doctrine:database:drop --force --if-exists -c postgres
	@$(PHP) bin/console doctrine:database:create -c postgres
	@$(PHP) bin/console doctrine:schema:create --em postgres
