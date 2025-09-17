# MT5 Manager API - Docker Makefile
# Convenient commands for Docker operations

.PHONY: help build up down restart logs shell test examples clean

# Default target
help: ## Show this help message
	@echo "MT5 Manager API Docker Commands"
	@echo "================================"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Docker Operations
build: ## Build Docker images
	@echo "Building Docker images..."
	docker-compose build

build-simple: ## Build with simplified Dockerfile (for build issues)
	@echo "Building with simplified Dockerfile..."
	docker-compose -f docker-compose.simple.yml build

up: ## Start containers in detached mode
	@echo "Starting containers..."
	docker-compose up -d
	@echo "Containers started! Visit http://localhost:8080"

up-simple: ## Start with simplified build (for build issues)
	@echo "Starting containers with simplified build..."
	docker-compose -f docker-compose.simple.yml up -d
	@echo "Containers started! Visit http://localhost:8080"

up-dev: ## Start development containers with Xdebug
	@echo "Starting development containers..."
	docker-compose --profile dev up -d
	@echo "Development containers started! Visit http://localhost:8081"

down: ## Stop and remove containers
	@echo "Stopping containers..."
	docker-compose down

restart: down up ## Restart containers

# Logs and Monitoring
logs: ## View container logs
	docker-compose logs -f mt5-sdk

logs-dev: ## View development container logs
	docker-compose logs -f mt5-sdk-dev

status: ## Show container status
	docker-compose ps

# Shell Access
shell: ## Access container shell
	docker-compose exec mt5-sdk bash

shell-dev: ## Access development container shell
	docker-compose exec mt5-sdk-dev bash

# Development and Testing
test: ## Run PHPUnit tests
	@echo "Running tests..."
	docker-compose exec mt5-sdk ./vendor/bin/phpunit

test-dev: ## Run tests in development container
	@echo "Running tests in development container..."
	docker-compose exec mt5-sdk-dev ./vendor/bin/phpunit

lint: ## Check code style
	docker-compose exec mt5-sdk-dev ./vendor/bin/php-cs-fixer fix --dry-run

lint-fix: ## Fix code style issues
	docker-compose exec mt5-sdk-dev ./vendor/bin/php-cs-fixer fix

# Examples
examples: ## Run all examples
	@echo "Running all examples..."
	./examples/docker_run_examples.sh all

example-basic: ## Run basic usage example
	@echo "Running basic usage example..."
	./examples/docker_run_examples.sh basic

example-user: ## Run user management example
	@echo "Running user management example..."
	./examples/docker_run_examples.sh user

example-trading: ## Run trading operations example
	@echo "Running trading operations example..."
	./examples/docker_run_examples.sh trading

# Utilities
composer-install: ## Install composer dependencies
	docker-compose exec mt5-sdk composer install

composer-update: ## Update composer dependencies
	docker-compose exec mt5-sdk composer update

env-setup: ## Copy .env.example to .env
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "Created .env file from .env.example"; \
		echo "Please edit .env with your MT5 server details"; \
	else \
		echo ".env file already exists"; \
	fi

# Cleanup
clean: ## Remove containers, networks, and volumes
	@echo "Cleaning up Docker resources..."
	docker-compose down -v --remove-orphans
	docker system prune -f

clean-all: ## Remove everything including images
	@echo "Removing all Docker resources..."
	docker-compose down -v --remove-orphans --rmi all
	docker system prune -a -f

# Health Checks
health: ## Check container health
	@echo "Checking container health..."
	@docker-compose ps
	@echo ""
	@echo "Testing HTTP endpoint..."
	@curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" http://localhost:8080 || echo "Service not accessible"

# Quick Setup
setup: env-setup build up ## Complete setup (env + build + start)
	@echo ""
	@echo "Setup complete!"
	@echo "- Visit http://localhost:8080 to see the SDK dashboard"
	@echo "- Run 'make examples' to test the API examples"
	@echo "- Run 'make help' to see all available commands"

# Development Setup
dev-setup: env-setup build up-dev ## Setup for development
	@echo ""
	@echo "Development setup complete!"
	@echo "- Visit http://localhost:8081 for development environment"
	@echo "- Xdebug is enabled for debugging"
	@echo "- Source code is mounted for live editing"