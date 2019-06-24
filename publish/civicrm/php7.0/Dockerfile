FROM php:7.0-apache

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
#
# iproute2 is required to get host ip from container

RUN curl -sL https://deb.nodesource.com/setup_9.x | bash \
  && apt-get update \
  && apt-get install -y --no-install-recommends \
  git \
  iproute2 \
  libc-client-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
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
  && docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ \
  && docker-php-ext-install gd \
  && docker-php-ext-install gettext \
  && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
  && docker-php-ext-install imap \
  && docker-php-ext-install intl \
  && docker-php-ext-install mcrypt \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install opcache \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install soap \
  && docker-php-ext-install zip

# Install and enable imagick PECL extensions
RUN pecl install imagick \
  && docker-php-ext-enable imagick

# Install xdebug PECL extension
RUN pecl install xdebug

RUN a2enmod rewrite

RUN a2enmod headers

ARG BUILDKIT_UID=1000

ARG BUILDKIT_GID=$BUILDKIT_UID

RUN addgroup --gid=$BUILDKIT_GID buildkit

RUN useradd --home-dir /buildkit --create-home --uid $BUILDKIT_UID --gid $BUILDKIT_GID buildkit

COPY sudo /etc/sudoers.d/buildkit

USER buildkit

WORKDIR /buildkit

ENV PATH="/buildkit/bin:${PATH}"

RUN git clone https://github.com/civicrm/civicrm-buildkit.git buildkit-tmp

RUN mv buildkit-tmp/* buildkit-tmp/.git* .

RUN rmdir buildkit-tmp

# Need to create this before we configure apache, otherwise it will complain
RUN mkdir -p .amp/apache.d

RUN mkdir -p .cache/bower

RUN mkdir .composer

RUN mkdir .drush

RUN mkdir .npm

RUN civi-download-tools

RUN civibuild cache-warmup

COPY --chown=buildkit:buildkit amp.services.yml /buildkit/.amp/services.yml

COPY buildkit.ini /usr/local/etc/php/conf.d/buildkit.ini

COPY msmtprc /etc/msmtprc

COPY apache.conf /etc/apache2/conf-enabled/buildkit.conf

RUN rm /buildkit/app/civicrm.settings.d/100-mail.php

COPY civibuild.conf /buildkit/app/civibuild.conf

COPY apache24-vhost.php /buildkit/build/.amp/apache24-vhost.php

USER root

COPY ./docker-civicrm-entrypoint /usr/local/bin

ENTRYPOINT [ "docker-civicrm-entrypoint" ]

CMD ["apache2-foreground"]
