FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    zlib-dev \
    nodejs \
    npm \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring zip bcmath intl gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --optimize-autoloader \
    && npm install --legacy-peer-deps \
    && npm run build \
    && chmod +x /var/www/html/scripts/railway-start.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["/var/www/html/scripts/railway-start.sh"]
