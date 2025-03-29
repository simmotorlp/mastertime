# Инструкция по использованию Docker-конфигурации

## Структура проекта

Для корректной работы с Docker, проект должен иметь следующую структуру:

```
beauty-platform/
├── .env                    # Переменные окружения
├── docker-compose.yml      # Основной файл Docker Compose
├── backend/                # Laravel проект
│   └── ...
├── frontend/               # Vue.js проект
│   └── ...
└── docker/                 # Docker конфигурационные файлы
    ├── nginx/
    │   ├── conf.d/         # Nginx конфигурации
    │   └── ssl/            # SSL сертификаты
    ├── php/
    │   ├── Dockerfile      # Dockerfile для PHP
    │   ├── php.ini         # PHP настройки
    │   ├── local.ini       # Локальные PHP настройки
    │   ├── supervisord.conf # Supervisor конфигурация
    │   └── cron            # Cron задачи
    ├── node/
    │   └── Dockerfile      # Dockerfile для Node.js
    └── postgres/
        └── init.sql        # Инициализация БД
```

## Переменные окружения

Создайте файл `.env` в корне проекта со следующими параметрами:

```
# PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=beauty_platform
DB_USERNAME=beauty
DB_PASSWORD=secret

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=secret
REDIS_PORT=6379

# PgAdmin
PGADMIN_DEFAULT_EMAIL=admin@example.com
PGADMIN_DEFAULT_PASSWORD=admin

# Mailhog
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=noreply@beauty-platform.local
```

## Разворачивание проекта

### 1. Подготовка структуры проекта

Создайте необходимые директории:

```bash
mkdir -p beauty-platform
cd beauty-platform
mkdir -p backend frontend
mkdir -p docker/nginx/conf.d docker/nginx/ssl docker/php docker/node docker/postgres
```

### 2. Создание конфигурационных файлов

Поместите все предоставленные файлы (docker-compose.yml, Dockerfile для PHP и Node.js, конфигурацию Nginx) в соответствующие директории.

### 3. Создание проектов Laravel и Vue

#### Laravel (бэкенд):

```bash
cd backend
composer create-project laravel/laravel .
```

#### Vue 3 (фронтенд):

```bash
cd ../frontend
npm create vite@latest . -- --template vue
```

### 4. Запуск Docker-контейнеров

Вернитесь в корневую директорию проекта и запустите контейнеры:

```bash
cd ..
docker-compose up -d
```

### 5. Настройка Laravel

```bash
# Войдите в контейнер
docker-compose exec app bash

# Установите зависимости
composer install

# Сгенерируйте ключ приложения
php artisan key:generate

# Выполните миграции
php artisan migrate:fresh --seed

# Создайте символическую ссылку для хранилища
php artisan storage:link
```

### 6. Настройка Vue.js

```bash
# Войдите в контейнер
docker-compose exec frontend sh

# Установите зависимости
npm install

# Запустите сервер разработки
npm run dev
```

## Доступ к сервисам

После запуска вы можете получить доступ к следующим сервисам:

- **Веб-приложение**: http://localhost
- **Фронтенд (Vite)**: http://localhost:3000 или http://localhost:5173
- **PgAdmin**: http://localhost:5050 (email: admin@example.com, пароль: admin)
- **Mailhog**: http://localhost:8025
- **Kibana**: http://localhost:5601

## Команды для работы с Docker

### Управление контейнерами

```bash
# Запуск контейнеров
docker-compose up -d

# Просмотр логов
docker-compose logs -f

# Остановка контейнеров
docker-compose down

# Перезапуск определенного сервиса
docker-compose restart app

# Вход в контейнер
docker-compose exec app bash
docker-compose exec frontend sh
```

### Работа с Laravel

```bash
# Выполнение Artisan-команд
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:controller Api/ServiceController
docker-compose exec app php artisan make:migration create_appointments_table

# Запуск тестов
docker-compose exec app php artisan test
```

### Работа с фронтендом

```bash
# Установка зависимостей
docker-compose exec frontend npm install

# Сборка для production
docker-compose exec frontend npm run build
```

## Советы по использованию

1. **Производительность Docker на Windows/Mac**: Используйте WSL2 на Windows и опции кеширования томов для улучшения производительности.

2. **Многоязычность**: Для тестирования разных языков (украинский/русский), используйте переключение в UI или задайте язык в URL или заголовке `Accept-Language`.

3. **Отладка**: Для отладки PHP используйте Xdebug (необходимо дополнительно настроить в php.ini) или `dd()` / `dump()` функции.

4. **Мониторинг**: Используйте Kibana для мониторинга логов и анализа ошибок.

5. **Безопасность**: Для production окружения рекомендуется настроить дополнительные меры безопасности: SSL сертификаты, WAF, регулярное обновление зависимостей.

6. **CI/CD**: Интегрируйте Docker с вашей CI/CD системой (GitHub Actions, GitLab CI, Jenkins) для автоматизации тестирования и деплоя.