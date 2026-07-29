#!/usr/bin/env sh
set -e

# Set defaults for Railway deployment
# These should be set in Railway Variables, but provide sensible defaults
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export APP_URL="${APP_URL:-https://amiga-travel-production.up.railway.app}"
export APP_NAME="${APP_NAME:-Amiga Travel}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE="${CACHE_STORE:-database}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

# Database - fallback to Railway MYSQL env vars if DB_* not set explicitly
export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-${MYSQLHOST:-${MYSQL_HOST:-sakura.proxy.rlwy.net}}}"
export DB_PORT="${DB_PORT:-${MYSQLPORT:-${MYSQL_PORT:-43993}}}"
export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-${MYSQL_DATABASE:-railway}}}"
export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-${MYSQL_USER:-root}}}"
export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-${MYSQL_ROOT_PASSWORD:-BIMPMSZRxyaizrljoaKdBoAixcTWShuP}}}"

# Mail settings
export MAIL_MAILER="${MAIL_MAILER:-smtp}"
export MAIL_HOST="${MAIL_HOST:-smtp.gmail.com}"
export MAIL_PORT="${MAIL_PORT:-587}"
export MAIL_ENCRYPTION="${MAIL_ENCRYPTION:-tls}"
export MAIL_USERNAME="${MAIL_USERNAME}"
export MAIL_PASSWORD="${MAIL_PASSWORD}"
export MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS}"
export RESEND_API_KEY="${RESEND_API_KEY}"

export NOCAPTCHA_SITEKEY="${NOCAPTCHA_SITEKEY}"
export NOCAPTCHA_SECRET="${NOCAPTCHA_SECRET}"
export MAIL_FROM_NAME="${MAIL_FROM_NAME}"
export MAIL_SCHEME="${MAIL_SCHEME}"

# Handle Firebase Credentials safely to avoid .env parsing errors
if [ -n "$FIREBASE_CREDENTIALS" ]; then
    echo "$FIREBASE_CREDENTIALS" > /var/www/html/storage/firebase-auth.json
    export FIREBASE_CREDENTIALS_PATH="/var/www/html/storage/firebase-auth.json"
else
    export FIREBASE_CREDENTIALS_PATH=""
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force 2>&1 || true
  # After generation, read it from the generated .env
  if [ -f /var/www/html/.env ]; then
    APP_KEY=$(grep "^APP_KEY=" /var/www/html/.env | cut -d= -f2)
  fi
fi

# Create .env file in container from Railway environment variables
# This overrides the local .env that was copied into the image
cat > /var/www/html/.env <<EOF
APP_NAME="Amiga Travel"
APP_ENV="$APP_ENV"
APP_DEBUG="$APP_DEBUG"
APP_KEY="$APP_KEY"
APP_URL="$APP_URL"
APP_LOCALE="en"
APP_FALLBACK_LOCALE="en"
APP_FAKER_LOCALE="en_US"
APP_MAINTENANCE_DRIVER="file"

BCRYPT_ROUNDS="12"
LOG_CHANNEL="stack"
LOG_LEVEL="debug"

DB_CONNECTION="$DB_CONNECTION"
DB_HOST="$DB_HOST"
DB_PORT="$DB_PORT"
DB_DATABASE="$DB_DATABASE"
DB_USERNAME="$DB_USERNAME"
DB_PASSWORD="$DB_PASSWORD"

SESSION_DRIVER="$SESSION_DRIVER"
CACHE_STORE="$CACHE_STORE"
QUEUE_CONNECTION="$QUEUE_CONNECTION"

MAIL_MAILER="$MAIL_MAILER"
MAIL_HOST="$MAIL_HOST"
MAIL_PORT="$MAIL_PORT"
MAIL_USERNAME="$MAIL_USERNAME"
MAIL_PASSWORD="$MAIL_PASSWORD"
MAIL_ENCRYPTION="$MAIL_ENCRYPTION"
MAIL_FROM_ADDRESS="$MAIL_FROM_ADDRESS"
RESEND_API_KEY="$RESEND_API_KEY"

NOCAPTCHA_SITEKEY="$NOCAPTCHA_SITEKEY"
NOCAPTCHA_SECRET="$NOCAPTCHA_SECRET"
FIREBASE_CREDENTIALS="$FIREBASE_CREDENTIALS_PATH"
MAIL_FROM_ADDRESS="$MAIL_FROM_ADDRESS"
MAIL_FROM_NAME="$MAIL_FROM_NAME"
MAIL_SCHEME="$MAIL_SCHEME"
SENDGRID_API_KEY="$SENDGRID_API_KEY"

FILESYSTEM_DISK="local"
BROADCAST_CONNECTION="log"
EOF

# Dynamically configure Nginx to listen on Railway's assigned $PORT
PORT="${PORT:-10000}"
echo "=== Configuring Nginx port to $PORT ==="
sed -i "s/listen [0-9]*;/listen ${PORT};/g" /etc/nginx/http.d/default.conf 2>/dev/null || true

# Run migrations and setup
timeout 60 php artisan migrate --force --no-interaction || echo "Migrations skipped or timed out"
php artisan storage:link || true

echo "=== Reached config cache step ==="
php artisan config:clear || true
php artisan config:cache || true
php artisan route:clear || true
php artisan view:cache || true

echo "=== Starting Supervisor (Nginx + PHP-FPM + Queue Worker) ==="
exec supervisord -c /var/www/html/supervisord.conf
