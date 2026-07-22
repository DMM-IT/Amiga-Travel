#!/usr/bin/env sh
set -e

# Set APP_URL FIRST, before any artisan commands (fixes malformed host error)
# Handle both unset and empty APP_URL variables from Railway
if [ -z "$APP_URL" ]; then
  export APP_URL="https://amiga-travel-production.up.railway.app"
fi
export APP_NAME="${APP_NAME:-Amiga Travel}"

if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
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
