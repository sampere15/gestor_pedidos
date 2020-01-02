FROM php:7.1-apache

RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

ADD . /var/www
ADD ./public /var/www/html

RUN chgrp -R www-data /var/www
RUN chmod -R 775 /var/www/storage
