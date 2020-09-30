FROM php:7.4.4-fpm-alpine

RUN apk update --update-cache && \
    apk upgrade --available && \
    apk --no-cache add \
    openssh

RUN apk add icu-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && composer --version