#!/usr/bin/env sh
set -e

if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Set a safe APP_URL for migrations and app startup
if [ -z "$APP_URL" ]; then
  export APP_URL="http://localhost:10000"
fi

if [ -z "$SESSION_DRIVER" ]; then
  export SESSION_DRIVER=database
fi

if [ -z "$CACHE_STORE" ]; then
  export CACHE_STORE=database
fi

if [ -z "$QUEUE_CONNECTION" ]; then
  export QUEUE_CONNECTION=database
fi

# Run only essential migrations without triggering URL validation
php artisan migrate --force --no-interaction 2>/dev/null || true
php artisan storage:link --quiet 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
