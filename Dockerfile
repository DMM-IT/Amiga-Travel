FROM php:8.3-fpm-alpine

# System dependencies
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
    unzip

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip bcmath intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy dependency manifests first for better caching
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./
COPY .env.example .env

# Install PHP and Node dependencies, build assets
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
RUN npm install --legacy-peer-deps
RUN npm run build

# Copy remaining project files
COPY . .

# Generate app key and cache config
RUN php artisan key:generate --ansi
RUN php artisan config:cache

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
