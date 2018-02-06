#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
sudo chown $USER:$USER $DIR/buildkit
git clone https://github.com/civicrm/civicrm-buildkit.git $DIR/buildkit
docker-compose --project-directory $DIR up -d
docker-compose --project-directory $DIR run cli civi-download-tools
docker-compose --project-directory $DIR run cli civibuild cache-warmup
mkdir $DIR/buildkit/.amp
cp $DIR/cli/amp.services.yml $DIR/buildkit/.amp/services.yml
cp $DIR/cli/civibuild.conf $DIR/buildkit/app/civibuild.conf
cp $DIR/cli/nginx-vhost.php $DIR/buildkit/.amp/nginx-vhost.php
rm $DIR/buildkit/app/civicrm.settings.d/100-mail.php

# Workaround, until a version of amp that allows nginx template overrides is
# packaged with buildkit
git clone https://github.com/amp-cli/amp $DIR/buildkit/amp
docker-compose --project-directory $DIR run composer install -d /buildkit/amp
ln -sf $DIR/buildkit/amp/bin/amp $DIR/buildkit/bin/amp
