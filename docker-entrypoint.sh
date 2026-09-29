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

sqlite_database="${DB_DATABASE:-/var/www/database/database.sqlite}"
case "$sqlite_database" in
    /*) ;;
    *) sqlite_database="$(pwd)/$sqlite_database" ;;
esac
export DB_DATABASE="$sqlite_database"
sqlite_directory="$(dirname "$sqlite_database")"
mkdir -p "$sqlite_directory"
touch "$sqlite_database"
chown www-data:www-data "$sqlite_directory" "$sqlite_database"
chmod 775 "$sqlite_directory"
chmod 664 "$sqlite_database"

if [ ! -e public/storage ] && [ ! -L public/storage ]; then
    php artisan storage:link
fi

php artisan migrate --force

exec apache2-foreground