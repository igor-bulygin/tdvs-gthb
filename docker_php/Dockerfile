FROM docker.io/php:5.6-cli

MAINTAINER <alexandernst> alexandernst@gmail.com

# Install other needed extensions
RUN apt-get update && apt-get install -y libfreetype6 libjpeg62-turbo libmcrypt4 libpng12-0 libxml2 openssl libicu52 git unzip npm node rsync --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN buildDeps=" \
        libfreetype6-dev \
        libcurl4-openssl-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libxml2-dev \
        libmcrypt-dev \
        libpng12-dev \
        zlib1g-dev \
        libsasl2-dev \
        libssl-dev \
    "; \
    set -x \
    && apt-get update && apt-get install -y $buildDeps --no-install-recommends && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) dom \
    && docker-php-ext-install -j$(nproc) curl \
    && docker-php-ext-configure gd --enable-gd-native-ttf --with-jpeg-dir=/usr/lib/x86_64-linux-gnu --with-png-dir=/usr/lib/x86_64-linux-gnu --with-freetype-dir=/usr/lib/x86_64-linux-gnu \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) json \
    && docker-php-ext-install -j$(nproc) mbstring \
    && docker-php-ext-install -j$(nproc) mcrypt \
    && docker-php-ext-install -j$(nproc) xml \
    && docker-php-ext-install -j$(nproc) zip \
    && cd /usr/src/php \
    && make clean

RUN pecl install redis
RUN docker-php-ext-enable redis
    
RUN pecl install mongo
RUN docker-php-ext-enable mongo

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt-get purge -y --auto-remove $buildDeps

# Setup timezone to Europe/Madrid
RUN cat /usr/src/php/php.ini-production | sed 's/^;\(date.timezone.*\)/\1 \"Europe\/Madrid\"/' > /usr/local/etc/php/php.ini

# Disable cgi.fix_pathinfo in php.ini
RUN sed -i 's/;\(cgi\.fix_pathinfo=\)1/\10/' /usr/local/etc/php/php.ini

RUN npm install -g bower

RUN composer global require "fxp/composer-asset-plugin:~1.1.1"

RUN composer config -g github-oauth.github.com cf2c7547fa26152fb1b1ed94c0ab82c21dd03119

EXPOSE 8080