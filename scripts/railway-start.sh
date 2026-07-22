#!/usr/bin/env sh
set -e

# Set defaults for Railway deployment
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export APP_URL="${APP_URL:-https://amiga-travel-production.up.railway.app}"
export APP_NAME="${APP_NAME:-Amiga Travel}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE="${CACHE_STORE:-database}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

# Create .env file in container from Railway environment variables
# This overrides the local .env that was copied into the image
cat > /var/www/html/.env <<EOF
APP_NAME="Amiga Travel"
APP_ENV=production
APP_DEBUG=false
APP_URL=$APP_URL
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD

SESSION_DRIVER=$SESSION_DRIVER
CACHE_STORE=$CACHE_STORE
QUEUE_CONNECTION=$QUEUE_CONNECTION

MAIL_MAILER=$MAIL_MAILER
MAIL_HOST=$MAIL_HOST
MAIL_PORT=$MAIL_PORT
MAIL_USERNAME=$MAIL_USERNAME
MAIL_PASSWORD=$MAIL_PASSWORD
MAIL_ENCRYPTION=$MAIL_ENCRYPTION
MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS

FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log
EOF

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
else
  # Append APP_KEY to .env
  echo "APP_KEY=$APP_KEY" >> /app/.env
fi

# Run migrations and setup
php artisan migrate --force --no-interaction 2>/dev/null || true
php artisan storage:link --quiet 2>/dev/null || true

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
