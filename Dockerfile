FROM php:7.4-apache

# Instala dependências básicas + extensões PHP
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    intl \
    zip

# Habilita mod_rewrite
RUN a2enmod rewrite

# Permite listagem de diretórios
RUN sed -i 's/Options -Indexes/Options +Indexes/' /etc/apache2/sites-available/000-default.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia projeto
COPY . /var/www/html

# (Opcional) se usar CodeIgniter ou outro framework
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80