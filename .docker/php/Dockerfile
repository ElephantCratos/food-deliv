FROM php:8.3-fpm

COPY php.ini /usr/local/etc/php/
COPY docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY .bashrc /root/

# mix
RUN apt-get update \
  && apt-get install -y build-essential zlib1g-dev default-mysql-client curl gnupg procps vim git unzip libzip-dev libpq-dev \
  && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql

# intl
RUN apt-get install -y libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# gd
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
docker-php-ext-install gd


# pcov
RUN pecl install pcov && docker-php-ext-enable pcov


# Node.js, NPM, Yarn
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
    RUN apt-get install -y nodejs
    RUN npm install npm@latest -g
    RUN npm install yarn -g
    
    # Composer
    RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    RUN php composer-setup.php
    RUN php -r "unlink('composer-setup.php');"
    RUN mv composer.phar /usr/local/bin/composer
    
    ENV COMPOSER_ALLOW_SUPERUSER 1
    ENV COMPOSER_HOME /composer
    ENV PATH $PATH:/composer/vendor/bin
    RUN composer config --global process-timeout 3600
    RUN composer global require "laravel/installer"
    
    RUN groupadd -g 1000 appgroup && \
        useradd -u 1000 -g appgroup -m appuser && \
        mkdir -p /composer && chown -R appuser:appgroup /composer
    
    RUN mkdir -p /var/www/vendor && chown -R appuser:appgroup /var/www
    
    USER appuser
    
    WORKDIR /var/www
    EXPOSE 5173
    