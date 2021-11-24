FROM php:8.0-fpm
WORKDIR "/application"

RUN apt-get update

RUN apt-get install -y --allow-downgrades \
        libfreetype6-dev \
        zlib1g-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        libzip-dev \
        libxml2-dev \
        libpq-dev \
        supervisor

RUN docker-php-ext-install -j$(nproc) iconv pdo pdo_mysql zip exif \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable opcache \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN apt-get clean all
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -o /usr/local/bin/cachetool http://gordalina.github.io/cachetool/downloads/cachetool.phar

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN apt-get update && apt-get install -y \
    unzip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

