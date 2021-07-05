FROM phpdockerio/php74-fpm:latest

WORKDIR /application

RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.4-gd php7.4-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
