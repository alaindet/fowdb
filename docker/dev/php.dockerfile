FROM php:7.4-apache

# # Set www-data to have UID 1000
# RUN usermod -u 1000 www-data;

WORKDIR /var/www/html

COPY ./backend/ /var/www/html/

# Install PHP dependencies
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install app dependencies
# TODO: Optimizations?
RUN composer install

EXPOSE 8080
