# MasterTime.ua Installation Guide

This guide will help you set up the MasterTime.ua development environment.

## Prerequisites

- Docker and Docker Compose installed
- Git installed

## Step 1: Clone the Repository

```bash
git clone https://github.com/simmotorlp/mastertime.git
cd mastertime
```

## Step 2: Set Up Environment Files

```bash
# Copy example environment files
cp .env.example .env
cp backend/.env.example backend/.env
```

## Step 3: Build and Start Docker Containers

```bash
# Build and start all containers
make rebuild

# Or use Docker Compose directly
docker-compose up -d --build
```

## Step 4: Fix Permissions and Run Migrations

```bash
# Fix storage directory permissions
make backend-fix-permissions

# Generate application key
make backend-key-generate

# Run migrations
make backend-migrate

# Seed the database
make backend-seed
```

## Step 5: Verify the Installation

1. **Backend API**: Visit http://localhost/api/test
2. **Frontend**: Visit http://localhost:5173
3. **PgAdmin**: Visit http://localhost:5050 (login: admin@example.com / password: admin)
4. **Mailhog**: Visit http://localhost:8025

## Troubleshooting Common Issues

### API Not Working

If the API is not responding:

1. Check container status:
   ```bash
   make ps
   ```

2. Check logs:
   ```bash
   make logs-app
   make logs-nginx
   ```

3. Restart containers:
   ```bash
   make restart
   ```

4. Rebuild containers (if necessary):
   ```bash
   make rebuild
   ```

5. Verify API routes:
   ```bash
   make backend-routes
   ```

6. Debug API:
   ```bash
   make debug-api
   ```

### Database Connection Issues

If you experience database connection issues:

1. Check database container is running:
   ```bash
   make ps
   ```

2. Check database logs:
   ```bash
   make logs-db
   ```

3. Verify database credentials in backend/.env match docker-compose.yml

### Frontend Connection Issues

If the frontend can't connect to the API:

1. Make sure the CORS middleware is registered correctly
2. Verify that the API URL in frontend configuration is correct
3. Check browser console for any errors

## Using the Development Environment

### Backend Development

```bash
# Access backend shell
make backend

# Run artisan commands
php artisan ...

# Create a controller
make backend-make-controller NAME=Api/ExampleController

# Run tests
make backend-test
```

### Frontend Development

```bash
# Access frontend shell
make frontend

# Install dependencies
make frontend-install

# Start development server
make frontend-dev
```

## Complete Reset

If you need to completely reset the environment:

```bash
# Remove all containers and volumes
make clean

# Rebuild everything
make install
```

## Additional Resources

- For database administration, use PgAdmin at http://localhost:5050
- For email testing, use Mailhog at http://localhost:8025
- For log visualization, use Kibana at http://localhost:5601