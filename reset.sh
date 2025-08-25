#!/bin/bash

echo "🔄 Running: php artisan migrate:fresh"
php artisan migrate:fresh

echo "🌱 Seeding: PermissionTableSeeder"
php artisan db:seed --class=PermissionTableSeeder

echo "👤 Seeding: CreateAdminUserSeeder"
php artisan db:seed --class=CreateAdminUserSeeder

echo "🔁 Running: php artisan migrate --seed"
php artisan migrate --seed

echo "✅ All done!"
