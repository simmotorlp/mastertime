# MasterTime.ua Project Makefile
# Main commands for development workflow

# Default command
.PHONY: help
help:
	@echo "MasterTime.ua Commands:"
	@echo "-------------------"
	@echo "make install        # Initial project setup (environment, dependencies, database)"
	@echo "make up             # Start all containers"
	@echo "make down           # Stop all containers"
	@echo "make restart        # Restart all containers"
	@echo "make rebuild        # Rebuild containers (use after Dockerfile changes)"
	@echo ""
	@echo "make backend        # Access backend shell"
	@echo "make backend-composer-install # Install backend dependencies"
	@echo "make backend-migrate # Run database migrations"
	@echo "make backend-seed   # Run database seeders"
	@echo "make backend-test   # Run backend tests"
	@echo "make backend-fix-permissions # Fix storage permissions"
	@echo "make backend-key-generate # Generate application key"
	@echo ""
	@echo "make frontend       # Access frontend shell"
	@echo "make frontend-install # Install frontend dependencies"
	@echo "make frontend-dev   # Start frontend development server"
	@echo "make frontend-build # Build frontend for production"
	@echo ""
	@echo "make logs           # View all logs"
	@echo "make logs-app       # View Laravel app logs"
	@echo "make logs-nginx     # View Nginx logs"
	@echo "make logs-db        # View database logs"
	@echo "make ps             # Show running containers"
	@echo ""
	@echo "make db-backup      # Backup database"
	@echo "make db-restore     # Restore database (make db-restore BACKUP_FILE=filename.sql)"
	@echo ""
	@echo "make clean          # Remove all containers and volumes"

# Installation command
.PHONY: install
install:
	@echo "Installing MasterTime.ua..."
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "Created .env file from example"; \
	fi
	@if [ ! -f backend/.env ]; then \
		cp backend/.env.example backend/.env; \
		echo "Created backend .env file from example"; \
	fi
	@echo "Starting Docker containers..."
	docker-compose up -d --build
	@echo "Giving containers time to initialize..."
	sleep 5
	@echo "Setting permissions and generating application key..."
	docker-compose exec app /usr/local/bin/fix-permissions.sh
	@echo "Running database migrations..."
	docker-compose exec app php artisan migrate --force
	@echo "Running database seeders..."
	docker-compose exec app php artisan db:seed --force
	@echo "MasterTime.ua installation complete!"

# Docker commands
.PHONY: up
up:
	docker-compose up -d

.PHONY: down
down:
	docker-compose down

.PHONY: restart
restart:
	docker-compose restart

.PHONY: ps
ps:
	docker-compose ps

.PHONY: rebuild
rebuild:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

# Frontend commands
.PHONY: frontend
frontend:
	docker-compose exec frontend sh

.PHONY: frontend-install
frontend-install:
	docker-compose exec frontend npm install

.PHONY: frontend-build
frontend-build:
	docker-compose exec frontend npm run build

.PHONY: frontend-dev
frontend-dev:
	docker-compose exec frontend npm run dev -- --host 0.0.0.0

# Backend commands
.PHONY: backend
backend:
	docker-compose exec app bash

.PHONY: backend-composer-install
backend-composer-install:
	docker-compose exec app composer install

.PHONY: backend-migrate
backend-migrate:
	docker-compose exec app php artisan migrate

.PHONY: backend-fresh
backend-fresh:
	docker-compose exec app php artisan migrate:fresh --seed

.PHONY: backend-seed
backend-seed:
	docker-compose exec app php artisan db:seed

.PHONY: backend-test
backend-test:
	docker-compose exec app php artisan test

.PHONY: backend-clear
backend-clear:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

.PHONY: backend-fix-permissions
backend-fix-permissions:
	docker-compose exec app /usr/local/bin/fix-permissions.sh

.PHONY: backend-key-generate
backend-key-generate:
	docker-compose exec app php artisan key:generate

.PHONY: backend-storage-link
backend-storage-link:
	docker-compose exec app php artisan storage:link

.PHONY: backend-routes
backend-routes:
	docker-compose exec app php artisan route:list

# Laravel development commands
.PHONY: backend-make-controller
backend-make-controller:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-controller NAME=UserController"; \
	else \
		docker-compose exec app php artisan make:controller $(NAME) $(ARGS); \
	fi

.PHONY: backend-make-model
backend-make-model:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-model NAME=User"; \
	else \
		docker-compose exec app php artisan make:model $(NAME) $(if $(MIGRATION),-m,); \
	fi

.PHONY: backend-make-migration
backend-make-migration:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-migration NAME=create_users_table"; \
	else \
		docker-compose exec app php artisan make:migration $(NAME); \
	fi

.PHONY: backend-make-request
backend-make-request:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-request NAME=StoreUserRequest"; \
	else \
		docker-compose exec app php artisan make:request $(NAME); \
	fi

.PHONY: backend-make-resource
backend-make-resource:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-resource NAME=UserResource"; \
	else \
		docker-compose exec app php artisan make:resource $(NAME); \
	fi

.PHONY: backend-make-seeder
backend-make-seeder:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make backend-make-seeder NAME=UsersSeeder"; \
	else \
		docker-compose exec app php artisan make:seeder $(NAME); \
	fi

# Logs
.PHONY: logs
logs:
	docker-compose logs -f

.PHONY: logs-app
logs-app:
	docker-compose logs -f app

.PHONY: logs-nginx
logs-nginx:
	docker-compose logs -f nginx

.PHONY: logs-db
logs-db:
	docker-compose logs -f db

.PHONY: logs-frontend
logs-frontend:
	docker-compose logs -f frontend

# Database operations
.PHONY: db-backup
db-backup:
	@echo "Creating database backup..."
	@mkdir -p backups
	docker-compose exec db pg_dump -U $(shell grep DB_USERNAME .env | cut -d '=' -f2) $(shell grep DB_DATABASE .env | cut -d '=' -f2) > backups/db_backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "Backup saved to backups/ directory"

.PHONY: db-restore
db-restore:
	@if [ -z "$(BACKUP_FILE)" ]; then \
		echo "Please specify a backup file: make db-restore BACKUP_FILE=filename.sql"; \
	else \
		echo "Restoring database from $(BACKUP_FILE)..."; \
		docker-compose exec -T db psql -U $(shell grep DB_USERNAME .env | cut -d '=' -f2) $(shell grep DB_DATABASE .env | cut -d '=' -f2) < $(BACKUP_FILE); \
		echo "Database restored successfully"; \
	fi

# Clean up everything
.PHONY: clean
clean:
	docker-compose down -v
	docker system prune -f
	@echo "Environment cleaned successfully"

# Debug API routes
.PHONY: debug-api
debug-api:
	@echo "Checking API routes..."
	docker-compose exec app php artisan route:list --path=api
	@echo "\nChecking Laravel configuration..."
	docker-compose exec app php artisan config:get app.url
	docker-compose exec app php artisan config:get sanctum
	@echo "\nChecking API connectivity..."
	curl -v http://localhost/api/v1/salons