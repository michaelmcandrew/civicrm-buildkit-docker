FROM php:7.0-apache-jessie

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
  && pecl install xdebug \
  && docker-php-ext-install zip

RUN a2enmod rewrite

COPY buildkit.ini /usr/local/etc/php/conf.d/buildkit.ini

COPY msmtprc /etc/msmtprc

RUN useradd --create-home buildkit

COPY sudo /etc/sudoers.d/buildkit

RUN mkdir /buildkit

RUN chown buildkit:buildkit /buildkit

USER buildkit

WORKDIR /home/buildkit

ENV PATH="/buildkit/bin:${PATH}"

RUN git clone https://github.com/civicrm/civicrm-buildkit.git /buildkit

RUN civi-download-tools

RUN civibuild download dmaster

COPY --chown=buildkit:buildkit amp.services.yml /home/buildkit/.amp/services.yml

# Need to create this before we configure apache, otherwise it will complain
RUN mkdir .amp/apache.d

COPY apache.conf /etc/apache2/conf-enabled/buildkit.conf

# TODO: Allow SMTP params to be configured with an environment variable
RUN rm /buildkit/app/civicrm.settings.d/100-mail.php

COPY civibuild.conf /buildkit/app/civibuild.conf


USER root
