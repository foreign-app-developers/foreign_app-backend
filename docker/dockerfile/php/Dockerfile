FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      zlib1g-dev libicu-dev libzip-dev libonig-dev librabbitmq-dev g++ \
      git \
      mariadb-client \
      vim \
      curl \
      openssl \
      zip \
      && docker-php-ext-configure zip \
      && docker-php-ext-install zip \
      && docker-php-ext-install sysvsem \
      && docker-php-ext-install -j$(nproc) mbstring \
      && docker-php-ext-install -j$(nproc) pdo \
      && docker-php-ext-configure intl \
      && docker-php-ext-install -j$(nproc) intl \
      && docker-php-ext-install -j$(nproc) pdo_mysql \
      && docker-php-ext-install -j$(nproc) mysqli \
      && docker-php-ext-configure opcache --enable-opcache \
      && docker-php-ext-install opcache \
      && pecl install amqp \
      && docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer