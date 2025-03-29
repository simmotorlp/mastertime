# MasterTime - Beauty Salon Booking Platform

MasterTime is a comprehensive booking platform for beauty salons in Ukraine, supporting both Ukrainian and Russian languages. This repository contains the API and frontend code for the platform.

## Quick Start

### Prerequisites

- Docker and Docker Compose installed
- Git installed

### Setup Instructions

```bash
# Clone the repository
git clone https://github.com/simmotorlp/mastertime.git
cd mastertime

# Copy example environment file and adjust if needed
cp .env.example .env

# Single command setup (recommended)
make install

# Alternatively, you can perform steps manually:
# Start the Docker environment
make up

# Install backend dependencies
make backend-composer-install

# Run database migrations
make backend-migrate

# Seed the database with test data
make backend-seed

# Install frontend dependencies
make frontend-install

# Start frontend development server
make frontend-dev
```

That's it! Your development environment is now ready.

## Accessing the Application

- **Backend API**: http://localhost/api
- **Frontend Development Server**: http://localhost:5173
- **PgAdmin** (Database Admin): http://localhost:5050 (login: admin@example.com / password: admin)
- **Mailhog** (Email Testing): http://localhost:8025
- **Kibana** (Logs): http://localhost:5601

## Project Structure

The project is organized into two main components:

- **Backend**: Laravel API with Sanctum authentication
- **Frontend**: Vue 3 with Element Plus design system

```
mastertime/
├── .env                    # Environment variables
├── Makefile                # Development workflow commands
├── docker-compose.yml      # Docker configuration
├── backend/                # Laravel API (mounted to /var/www/html)
├── frontend/               # Vue 3 frontend (mounted to /app)
└── docker/                 # Docker configuration files
    ├── nginx/
    │   ├── conf.d/         # Nginx configuration
    │   └── ssl/            # SSL certificates
    ├── php/
    │   ├── Dockerfile      # PHP container configuration
    │   ├── php.ini         # PHP configuration
    │   ├── local.ini       # Development PHP settings
    │   ├── supervisord.conf # Process manager configuration
    │   └── cron            # Laravel scheduler cron job
    ├── node/
    │   └── Dockerfile      # Node.js container for frontend
    └── postgres/
        └── init.sql        # Database initialization script
```

All services are containerized using Docker for consistent development:

- **app**: PHP 8.2 with Laravel
- **nginx**: Web server
- **db**: PostgreSQL database
- **redis**: Caching server
- **frontend**: Node.js with Vue
- **pgadmin**: PostgreSQL administration
- **mailhog**: Email testing service
- **elasticsearch/kibana**: Log management

## Working with the Project

### Accessing Containers

To access the backend container:
```bash
make backend
# or directly with Docker
docker-compose exec app bash
```

To access the frontend container:
```bash
make frontend
# or directly with Docker
docker-compose exec frontend sh
```

### Running Artisan Commands

You can run Laravel Artisan commands in two ways:

1. Using the Makefile (recommended):
```bash
# Example: Create a controller
make backend-make-controller NAME=API/UserController

# Example: Run migrations
make backend-migrate

# Example: Clear cache
make backend-clear
```

2. Directly inside the backend container:
```bash
# First access the container
make backend

# Then run artisan commands
php artisan make:controller API/UserController
php artisan migrate
php artisan cache:clear
```

## Available Commands

Run `make help` to see all available commands. Here are the most commonly used ones:

### Installation and Docker Management
- `make install` - Initial project setup (environment, dependencies, database)
- `make up` - Start all containers
- `make down` - Stop all containers
- `make restart` - Restart all containers
- `make ps` - Show running containers

### Backend Development
- `make backend` - Access backend shell
- `make backend-migrate` - Run migrations
- `make backend-seed` - Seed the database
- `make backend-test` - Run tests
- `make backend-make-controller NAME=UserController` - Create a controller
- `make backend-make-model NAME=User` - Create a model
- `make backend-make-migration NAME=create_users_table` - Create a migration
- `make backend-make-request NAME=StoreUserRequest` - Create a form request
- `make backend-make-resource NAME=UserResource` - Create an API resource

### Frontend Development
- `make frontend` - Access frontend shell
- `make frontend-dev` - Start development server
- `make frontend-build` - Build for production

### Database Operations
- `make db-backup` - Create database backup
- `make db-restore BACKUP_FILE=filename.sql` - Restore from backup

### Logs
- `make logs` - View all logs
- `make logs-app` - View backend logs
- `make logs-frontend` - View frontend logs

## Architecture Overview

MasterTime follows an API-first architecture with these key components:

- **RESTful API** built with Laravel
- **PostgreSQL** for relational data with spatial features for location-based queries
- **Versioned API endpoints** via URL path prefixing (/api/v1/...)
- **Multi-language support** built into resources and database schema
- **Vue 3** frontend with state management

## Troubleshooting

### Common Issues

#### "Connection refused" errors
If you see connection errors to the database or other services, ensure all containers are running:
```bash
make ps
```
If a container is not running, try restarting the environment:
```bash
make restart
```

#### Container name resolution issues
If you're getting container name resolution errors, make sure all containers are on the same network:
```bash
docker network ls
# Check if containers are on the same network
docker network inspect mastertime_default
```

#### Database migration issues
If migrations fail, ensure your database connection is properly configured:
```bash
make backend
cat .env | grep DB_
```

#### Frontend not loading
If the frontend doesn't load, check the frontend logs:
```bash
make logs-frontend
```

#### Unable to access container shell
If you can't access a container shell, ensure the container is running first:
```bash
make ps
docker-compose restart [container-name]
```

## API Documentation

The project currently doesn't have automated API documentation. Here are options to add it:

### Option 1: Install Laravel Scribe

```bash
# Access the backend container
make backend

# Install Scribe package
composer require knuckleswtf/scribe

# Publish the configuration
php artisan vendor:publish --provider="Knuckleswtf\Scribe\ScribeServiceProvider" --tag=config

# Generate the documentation
php artisan scribe:generate
```

After setup, documentation will be available at http://localhost/docs

### Option 2: Manual API Documentation

Create a simple documentation page:

1. Add a route in `routes/web.php`:
```php
Route::view('/docs', 'api-docs');
```

2. Create a view file `resources/views/api-docs.blade.php` with your API endpoints documentation

You can then access the docs at http://localhost/docs

## Main Docker Containers

| Container Name | Service | Purpose |
|----------------|---------|---------|
| mastertime-app | PHP/Laravel | Backend API application |
| mastertime-nginx | Nginx | Web server |
| mastertime-db | PostgreSQL | Database |
| mastertime-redis | Redis | Cache |
| mastertime-frontend | Node.js | Frontend development |
| mastertime-pgadmin | PgAdmin | Database administration |
| mastertime-mailhog | Mailhog | Email testing |
| mastertime-elasticsearch | Elasticsearch | Log storage |
| mastertime-kibana | Kibana | Log visualization |

When executing commands directly with Docker, use these container names:
```bash
# Examples
docker-compose exec mastertime-app bash
docker-compose exec mastertime-frontend sh
docker-compose logs -f mastertime-app
```

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue 3 Documentation](https://vuejs.org/guide/introduction.html)
- [Docker Documentation](https://docs.docker.com/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)