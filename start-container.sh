#!/bin/bash
# Container start script for Railway (Railpack picks up this file instead of
# its default). Same steps as Railpack's Laravel default, plus a one-time demo
# seed. It runs inside the app container, so seeded product photos are written
# to the volume mounted at /app/storage/app/public (pre-deploy commands cannot
# see volumes).
set -e

if [ "$IS_LARAVEL" = "true" ]; then
  # Drop any config cached at build time so runtime variables are used.
  php artisan optimize:clear

  if [ "$RAILPACK_SKIP_MIGRATIONS" != "true" ]; then
    echo "Running migrations ..."
    php artisan migrate --force
  fi

  # Roles, staff accounts, settings and demo catalogue: first deploy only.
  php artisan store:seed-if-empty

  php artisan storage:link || true
  php artisan optimize

  echo "Starting Laravel server ..."
fi

# Start the FrankenPHP server
docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
