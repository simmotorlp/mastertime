#!/bin/bash

echo "===== Container Status ====="
docker-compose ps

echo -e "\n===== Laravel Environment ====="
docker-compose exec app php -v
docker-compose exec app php artisan --version

echo -e "\n===== Backend Environment File ====="
docker-compose exec app cat .env | grep -v PASSWORD | grep -v KEY

echo -e "\n===== Database Status ====="
docker-compose exec db pg_isready -U mastertime -d mastertime_platform

echo -e "\n===== Laravel Logs ====="
docker-compose exec app tail -n 50 storage/logs/laravel.log

echo -e "\n===== API Routes ====="
docker-compose exec app php artisan route:list --path=api

echo -e "\n===== API Test ====="
curl -v http://localhost/api/v1/salons

echo -e "\n===== Storage Permissions ====="
docker-compose exec app ls -la storage/
docker-compose exec app ls -la bootstrap/cache/

echo -e "\n===== Nginx Config ====="
docker-compose exec nginx cat /etc/nginx/conf.d/default.conf