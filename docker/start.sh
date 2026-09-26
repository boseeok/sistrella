#!/bin/sh
# Container start script for the Docker demo image (see Dockerfile / render.yaml).
set -e
cd /var/www/html

# A self-resetting demo needs no fixed key: generate one per start if none is set.
if [ -z "$APP_KEY" ]; then
  APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
  export APP_KEY
fi

# Use the public URL Render assigns when APP_URL isn't set explicitly.
if [ -z "$APP_URL" ] && [ -n "$RENDER_EXTERNAL_URL" ]; then
  APP_URL="$RENDER_EXTERNAL_URL"
  export APP_URL
fi

# Only drop cached config (a file); the database may not have its tables yet.
php artisan config:clear

if [ "$DEMO_MODE" = "true" ] && [ "$DB_CONNECTION" = "sqlite" ]; then
  # Fresh demo store on every start (photos are already in the image, so this is quick).
  # Only ever done for the demo's own SQLite file, never for a real MySQL database.
  touch "$DB_DATABASE"
  php artisan migrate:fresh --seed --force --no-interaction
else
  php artisan migrate --force --no-interaction
fi

php artisan storage:link || true
php artisan optimize
chown -R www-data:www-data storage bootstrap/cache database

exec apache2-foreground
