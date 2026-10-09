#!/bin/sh
set -e

# Buat storage link jika belum ada
php artisan storage:link --no-interaction --quiet || true

# Optimasi cache Laravel jika di production
if [ "$APP_ENV" = "production" ]; then
    php artisan optimize || true
fi

# Pastikan permission folder storage dan bootstrap/cache aman
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
