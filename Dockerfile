ARG PHP_VERSION=7.3
FROM php:$PHP_VERSION-cli-alpine

RUN apk add git zip unzip autoconf make g++

RUN if [ -z "${PHP_VERSION##7\.1*}" ]; then \
    pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug; \
else \
    pecl install xdebug && docker-php-ext-enable xdebug; \
fi;

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /package

COPY composer.json ./

ARG LARAVEL=7
RUN composer require illuminate/support ^$LARAVEL.0

COPY src src
COPY config config
COPY tests tests
COPY phpunit.xml phpstan.neon ./

RUN composer test
