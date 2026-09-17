FROM php:8.4-apache

WORKDIR /var/www/html/slim
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite headers expires

COPY ./composer.json ./
COPY ./ ./

RUN composer install
