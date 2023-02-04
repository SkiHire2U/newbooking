#!/bin/bash

echo "Making /storage writeable..."
chmod -R 775 /var/app/current/storage

if [ ! -f /var/app/current/storage/logs/laravel.log ]; then
    echo "Creating /storage/logs/laravel.log..."
    touch /var/app/current/storage/logs/laravel.log
    chown webapp:webapp /var/app/current/storage/logs/laravel.log
fi

if [ ! -d /var/app/current/public/storage ]; then
    echo "Creating /public/storage symlink..."
    cd /var/app/current/
    php artisan storage:link --quiet
fi
