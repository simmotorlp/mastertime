FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user=www-data
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libgmp-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql gmp gd opcache intl zip bcmath exif pcntl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure www-data user (already exists in the base image)
RUN mkdir -p /home/www-data && \
    chown -R www-data:www-data /home/www-data && \
    usermod -u $uid www-data && \
    mkdir -p /home/www-data/.composer && \
    chown -R www-data:www-data /home/www-data

# Set working directory
WORKDIR /var/www/html

# Add custom PHP config
COPY php.ini /usr/local/etc/php/php.ini

# Install Supervisor
RUN apt-get update && apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install cron
RUN apt-get update && apt-get install -y cron
COPY cron /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron

USER $user

EXPOSE 9000
CMD ["php-fpm"]
