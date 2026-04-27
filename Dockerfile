FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip \
    libpng-dev libonig-dev libxml2-dev libicu-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel-money

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
