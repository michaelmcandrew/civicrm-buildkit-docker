FROM php:7.0-fpm-jessie

# Install required php extensions (curl, json, mbstring, openssl, posix, phar
# are installed already). The others need to be installed and many have
# dependencies on packages which need to be fulfilled.

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
  libpng-dev \
  && rm -r /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_mysql \
  && docker-php-ext-install gd \
  && docker-php-ext-install mysqli

COPY buildkit.ini /usr/local/etc/php/conf.d/buildkit.ini
