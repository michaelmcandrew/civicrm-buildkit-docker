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

# Create buildkit user
RUN useradd --create-home buildkit

# Give buildkit passwordless sudo
COPY sudo /etc/sudoers.d/buildkit

# The rest of this Dockerfile should be run as the buildkit user
USER buildkit

# Set the working directory to the buildkit user's home
WORKDIR /home/buildkit

RUN git clone https://github.com/civicrm/civicrm-buildkit.git

ENV PATH="/home/buildkit/civicrm-buildkit/bin:${PATH}"

RUN civi-download-tools

RUN civibuild cache-warmup

# Install a version of amp that supports custom nginx vhost templates
# (can stop doing this once  buildkit updates to a version of amp that supports
# custom templates)
RUN git clone https://github.com/amp-cli/amp \
  && composer install -d amp

# Configure amp non-interactivley
COPY --chown=buildkit:buildkit amp.services.yml /home/buildkit/.amp/services.yml

COPY --chown=buildkit:buildkit civibuild.conf /home/buildkit/civicrm-buildkit/app/civibuild.conf

# Custom nginx vhost that passes php to fpm container
COPY --chown=buildkit:buildkit nginx-vhost.php /home/buildkit/civicrm-buildkit/.amp/nginx-vhost.php
