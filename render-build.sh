#!/usr/bin/env bash
# Salir si algún comando falla
set -o errexit

# Instalar solo dependencias de producción (optimizado)
composer install --no-dev --optimize-autoloader

# Cachear configuraciones para velocidad
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
php artisan migrate --force

php artisan db:seed --force
