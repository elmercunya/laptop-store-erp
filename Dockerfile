# BASE: PHP 8.2 + Apache (necesario para Laravel)
FROM php:8.2-apache

# EXTENSIONES: Instala solo lo que Laravel necesita para funcionar
# pdo_mysql → conexión a MySQL
# mbstring, xml, zip, curl → dependencias core de Laravel
RUN apt-get update && apt-get install -y \
    git curl libzip-dev zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring xml curl zip \
    && rm -rf /var/lib/apt/lists/*

# REWRITE: Necesario para que Laravel maneje las rutas (sin esto, 404 en todas las rutas)
RUN a2enmod rewrite

# APACHE: Permite que Apache sirva la carpeta public/ de Laravel
# Sin esto, Apache deniega acceso por seguridad (error 403 Forbidden)
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf

# COMPOSER: Copia el instalador de dependencias de PHP
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# DIRECTORIO: Define dónde estará el código dentro del contenedor
WORKDIR /var/www/html

# CÓDIGO: Copia todo tu proyecto Laravel al contenedor
COPY . /var/www/html

# PERMISOS: Laravel necesita escribir en storage/ y bootstrap/cache/
# Sin esto, error 500 al subir imágenes o cachear config
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# DEPENDENCIAS: Instala solo librerías de producción (más rápido, menos peso)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# PUERTO: Render requiere que la app escuche en 10000 (no en 80)
EXPOSE 10000

# ARRANQUE: Prepara Laravel y levanta Apache
# config:cache, route:cache, view:cache → optimización para producción
# migrate:fresh --seed → crea tablas y crea usuario admin
# apache2-foreground → mantiene el contenedor vivo
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate:fresh --seed --force && \
    apache2-foreground