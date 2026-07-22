#!/usr/bin/env sh
set -e

if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

if [ -z "$APP_URL" ] || [ "$APP_URL" = "http://localhost" ]; then
  if [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
    export APP_URL="https://$RAILWAY_PUBLIC_DOMAIN"
  elif [ -n "$RAILWAY_ENVIRONMENT_NAME" ]; then
    export APP_URL="https://$RAILWAY_ENVIRONMENT_NAME.railway.app"
  else
    export APP_URL="http://localhost:10000"
  fi
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

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan package:discover --ansi
php artisan filament:upgrade
php artisan migrate --force
php artisan storage:link --quiet || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
