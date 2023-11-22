FROM php:8.1.17-apache-buster

RUN a2enmod rewrite && a2enmod headers

ENV APACHE_DOCUMENT_ROOT /var/www/html/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update -y && apt-get install -y

RUN apt-get install -y git zip

RUN apt-get install -y libpq-dev zlib1g-dev libzip-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev libgd-dev libicu-dev

RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/

RUN docker-php-ext-install mysqli pdo pdo_mysql gd
RUN docker-php-ext-enable mysqli pdo pdo_mysql

RUN docker-php-ext-configure intl && docker-php-ext-install intl

COPY --from=composer /usr/bin/composer /usr/bin/composer
