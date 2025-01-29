#!/usr/bin/env bash

echo "Running composer install..."
composer install --working-dir=/var/www/html

echo "Caching files..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Generating api docs..."
php artisan scribe:generate
