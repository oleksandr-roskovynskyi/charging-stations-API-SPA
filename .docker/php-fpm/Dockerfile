FROM php:7.4-fpm

WORKDIR /app

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ARG UID=1000
ARG GID=1000
ENV USERNAME=www-data

# Fix permissions
RUN usermod -u $UID $USERNAME \
    && groupmod -g $GID $USERNAME \
    && mkdir -p /home/$USERNAME \
    && chown -R $USERNAME:$USERNAME /home/$USERNAME \
    && mkdir -p /app \
    && chown $USERNAME:$USERNAME /app \
    && chown -R $USERNAME:$USERNAME /var/www

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    apt-utils \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql bcmath sockets zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN pecl channel-update pecl.php.net \
    && pecl install xdebug-2.9.5 \
    && docker-php-ext-enable xdebug

COPY --from=composer:1.10.6 /usr/bin/composer /usr/bin/composer

# Copy xdebug configuration for remote debugging
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

USER $USERNAME
