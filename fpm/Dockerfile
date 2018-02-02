FROM php:7.0-fpm-jessie

# Install apt packages
#
# Required for php extensions
# * gd: libpng-dev
# * imagick: libmagickwand-dev
# * zip: zlib1g-dev
#
# Send mail to maildev
# * msmtp-mta

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
  libpng-dev \
  msmtp-mta \
  zlib1g-dev \
  && rm -r /var/lib/apt/lists/*


RUN docker-php-ext-install pdo_mysql \
  && docker-php-ext-install gd \
  && docker-php-ext-install mysqli \
  && pecl install xdebug \
  && docker-php-ext-install zip

COPY buildkit.ini /usr/local/etc/php/conf.d/buildkit.ini

COPY msmtprc /etc/msmtprc
