# MasterTime.ua Project Makefile
# Main commands for development workflow

# Default command
.PHONY: help
help:
	@echo "MasterTime.ua Commands:"
	@echo "-------------------"
	@echo "make install        # Initial project setup"
	@echo "make up             # Start all containers"
	@echo "make down           # Stop all containers"
	@echo "make restart        # Restart all containers"
	@echo ""
	@echo "make frontend       # Access frontend shell"
	@echo "make frontend-build # Build frontend for production"
	@echo "make frontend-lint  # Run linting on frontend code"
	@echo ""
	@echo "make backend        # Access backend shell"
	@echo "make backend-migrate # Run database migrations"
	@echo "make backend-seed   # Run database seeders"
	@echo "make backend-test   # Run backend tests"
	@echo ""
	@echo "make logs           # View all logs"
	@echo "make logs-app       # View backend logs"
	@echo "make logs-frontend  # View frontend logs"
	@echo ""
	@echo "make db-backup      # Backup database"
	@echo "make db-restore     # Restore database"
	@echo ""
	@echo "make clean          # Remove all containers and volumes"

# Initial setup
.PHONY: install
install:
	@echo "Setting up MasterTime.ua project..."
	mkdir -p backend frontend
	mkdir -p docker/nginx/conf.d docker/nginx/ssl docker/php docker/node docker/postgres
	cp -n .env.example .env || true
	docker-compose up -d
	make backend-install
	make frontend-install
	@echo "Setup complete! The application is running at http://localhost"

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

# Frontend commands
.PHONY: frontend
frontend:
	docker-compose exec frontend sh

.PHONY: frontend-install
frontend-install:
	@if [ ! -f "frontend/package.json" ]; then \
		echo "Creating new Vue project..."; \
		docker-compose exec frontend sh -c "cd /app && npm create vite@latest . -- --template vue"; \
	else \
		echo "Installing frontend dependencies..."; \
		docker-compose exec frontend npm install; \
	fi

.PHONY: frontend-build
frontend-build:
	docker-compose exec frontend npm run build

.PHONY: frontend-lint
frontend-lint:
	docker-compose exec frontend npm run lint

.PHONY: frontend-dev
frontend-dev:
	docker-compose exec frontend npm run dev -- --host 0.0.0.0

# Backend commands
.PHONY: backend
backend:
	docker-compose exec app bash

.PHONY: backend-install
backend-install:
	@if [ ! -f "backend/composer.json" ]; then \
		echo "Creating new Laravel project..."; \
		docker-compose exec app composer create-project laravel/laravel .; \
	else \
		echo "Installing composer dependencies..."; \
		docker-compose exec app composer install; \
	fi
	docker-compose exec app php artisan key:generate --no-interaction || true
	docker-compose exec app php artisan storage:link --no-interaction || true

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

# Logs
.PHONY: logs
logs:
	docker-compose logs -f

.PHONY: logs-app
logs-app:
	docker-compose logs -f app

.PHONY: logs-frontend
logs-frontend:
	docker-compose logs -f frontend

.PHONY: logs-nginx
logs-nginx:
	docker-compose logs -f nginx

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

# Create a new Laravel controller
.PHONY: make-controller
make-controller:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make make-controller NAME=UserController"; \
	else \
		docker-compose exec app php artisan make:controller $(NAME); \
	fi

# Create a new Laravel model
.PHONY: make-model
make-model:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make make-model NAME=User"; \
	else \
		docker-compose exec app php artisan make:model $(NAME) $(if $(MIGRATION),-m,); \
	fi

# Create a new Laravel migration
.PHONY: make-migration
make-migration:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make make-migration NAME=create_users_table"; \
	else \
		docker-compose exec app php artisan make:migration $(NAME); \
	fi

# Create new Vue component
.PHONY: make-component
make-component:
	@if [ -z "$(NAME)" ]; then \
		echo "Please specify a name: make make-component NAME=Appointment"; \
	else \
		echo "Creating Vue component $(NAME)..."; \
		mkdir -p frontend/src/components/$(NAME); \
		echo "<template>\n  <div>\n    <!-- $(NAME) Component -->\n  </div>\n</template>\n\n<script setup>\n// Component logic\n</script>\n\n<style scoped>\n/* Component styles */\n</style>" > frontend/src/components/$(NAME)/$(NAME).vue; \
		echo "Component created at frontend/src/components/$(NAME)/$(NAME).vue"; \
	fi

# Clean up everything
.PHONY: clean
clean:
	docker-compose down -v
	docker system prune -f
	rm -rf node_modules vendor
	@echo "Environment cleaned successfully"