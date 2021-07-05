FROM php:7.4-apache

# # Set www-data to have UID 1000
# RUN usermod -u 1000 www-data;

WORKDIR /var/www/html

COPY ./backend/ .

# RUN apt-get install -y \
#   libzip-dev \
#   zip

RUN apt-get update && \
  apt-get install -y \
  zlib1g-dev

# Install PHP dependencies
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install app dependencies
# TODO: Optimizations?
RUN composer install

# Production
# RUN composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --classmap-authoritative

EXPOSE 8080
