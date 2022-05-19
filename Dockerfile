ARG PHP_VERSION=8.0
FROM php:$PHP_VERSION-cli-alpine

RUN apk add git zip unzip autoconf make g++

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /package

COPY composer.json ./

ARG LARAVEL=8
RUN composer require illuminate/support ^$LARAVEL.0

COPY src src
COPY config config
COPY tests tests
COPY phpunit.xml ./

RUN composer test
