# 1. Traemos la base para el proyecto (Php, Apache y el sistema operativo)
FROM php:8.2-apache


# 2. Configuramos esa base para que tenga todo lo necesario a la hora de usar mi sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite


# 3. Cambiamos por defecto la ruta de Apache para que sea public (Seguridad)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# 4. Traemos a Composer (el instalador de librerías de PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Copiamos nuestro código al contenedor y descargamos las librerías de producción
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Damos permiso a Apache para que pueda guardar imágenes y archivos temporales
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Exponemos el puerto y damos el botón de encendido al servidor
EXPOSE 10000
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    apache2-foreground