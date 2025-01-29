#!/usr/bin/env bash

echo "Running composer install..."
composer install --working-dir=/var/ww/html

echo "Caching files..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Generate api docs..."
php artisan scribe:generate
