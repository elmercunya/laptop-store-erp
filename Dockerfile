FROM php:8.2-apache

# 1. CORRECCIÓN LIBRERÍAS: Agregamos libcurl4-openssl-dev para que compile la extensión de PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libcurl4-openssl-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip curl \
    && rm -rf /var/lib/apt/lists/*

# 2. Habilitar mod_rewrite (Rutas de Laravel)
RUN a2enmod rewrite

# 3. CORRECCIÓN PUERTO: Forzar a Apache a escuchar en 10000 (Render lo exige)
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf

# 4. CORRECCIÓN SEGURIDAD: Apuntar a public/ Y DAR PERMISOS (El 403 se arregla aquí)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Agregamos la directiva que faltaba para evitar el 403
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf

# 5. Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos de carpetas
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000

# 6. CORRECCIÓN SEED: migrate:fresh --seed --force para crear usuario admin
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate:fresh --seed --force && \
    apache2-foreground