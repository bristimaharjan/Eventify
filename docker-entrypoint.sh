#!/bin/sh
set -eu

port="${PORT:-10000}"
case "$port" in
    ''|*[!0-9]*)
        echo "PORT must be a numeric TCP port" >&2
        exit 1
        ;;
esac

sed -i "s/^Listen 80$/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p /var/data/uploads /var/data/storage-app/private /var/data/storage-app/public
if [ ! -f /var/data/.eventify-seeded ]; then
    cp -a /var/eventify-seed/uploads/. /var/data/uploads/
    touch /var/data/.eventify-seeded
fi

mkdir -p storage
ln -s /var/data/storage-app storage/app
ln -s /var/data/uploads public/uploads

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data /var/data storage bootstrap/cache

if [ ! -e public/storage ] && [ ! -L public/storage ]; then
    php artisan storage:link
fi

exec apache2-foreground