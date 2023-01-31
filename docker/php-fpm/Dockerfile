FROM php:8.0-fpm

RUN docker-php-ext-install mysqli

RUN apt-get update -y

RUN apt-get install -y p7zip \
     p7zip-full \
     unace \
     zip \
     unzip

# Xdebug
RUN pecl install xdebug apcu && docker-php-ext-enable xdebug;
COPY xdebug.ini /usr/local/etc/php/conf.d/

# Composer
RUN curl -sS https://getcomposer.org/installer \
    | tee composer-setup.php \
    && php composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv /var/www/html/composer.phar /usr/bin/composer

WORKDIR /app

CMD ["php-fpm"]

EXPOSE 9000


