FROM php:7.2

MAINTAINER Ilya Pleshakov <da-eto@ya.ru>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y
RUN apt-get install --no-install-recommends -y \
    curl git ca-certificates zlib1g-dev libpq-dev libxml2-dev

RUN docker-php-ext-install opcache
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install xml

RUN echo 'opcache.memory_consumption=256' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
RUN echo 'opcache.max_accelerated_files=20000' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

WORKDIR /var/www

EXPOSE 8000
