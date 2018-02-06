# TODO Change to php:7.0 (no need for fpm)
FROM php:7.0-fpm-jessie

# Install apt packages
#
# Required for php extensions
# * gd: libpng-dev
# * imagick: libmagickwand-dev
# * imap: libc-client-dev, libkrb5-dev
# * intl: libicu-dev
# * mcrypt: libmcrypt-dev
# * soap: libxml2-dev
# * zip: zlib1g-dev
#
# Used in the build process
# * git
# * mysql-client
# * sudo
# * unzip
# * zip
# * node 9.x (from nodesource repository)
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash \
  && apt-get update \
  && apt-get install -y --no-install-recommends \
  git \
  libc-client-dev \
  libicu-dev \
  libkrb5-dev \
  libmagickwand-dev \
  libmcrypt-dev \
  libpng-dev \
  libxml2-dev \
  msmtp-mta \
  mysql-client \
  nodejs \
  sudo \
  unzip \
  vim \
  zip \
  zlib1g-dev \
  && rm -r /var/lib/apt/lists/*

# Install php extensions (curl, json, mbstring, openssl, posix, phar
# are installed already and don't need to be specified here)
RUN docker-php-ext-install bcmath \
  && docker-php-ext-install gd \
  && docker-php-ext-install gettext \
  && pecl install imagick \
  && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
  && docker-php-ext-install imap \
  && docker-php-ext-install intl \
  && docker-php-ext-install mcrypt \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install soap \
  && docker-php-ext-install zip

COPY buildkit.ini /usr/local/etc/php/conf.d/buildkit.ini

COPY msmtprc /etc/msmtprc

# Create buildkit user
RUN useradd --create-home buildkit

# Give buildkit passwordless sudo
COPY sudo /etc/sudoers.d/buildkit

RUN mkdir /buildkit

RUN chown buildkit:buildkit /buildkit

USER buildkit

WORKDIR /home/buildkit

ENV PATH="/buildkit/bin:${PATH}"

# Workaround, until this version of amp is packaged with buildkit
RUN git clone https://github.com/amp-cli/amp\
  && composer install -d amp
