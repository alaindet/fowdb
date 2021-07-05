FROM php:7.4-apache

WORKDIR /var/www/html

COPY src/ /var/www/html/

# Install PHP dependencies
# RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install app dependencies
# TODO: Optimizations?
RUN composer install

# ARG USERNAME=ciaone

# Create a new user
# RUN useradd --system --create-home --home-dir /home/ciaone --shell /bin/bash --gid root --groups sudo --uid 1001 ciaone
# USER ciaone

EXPOSE 8080
