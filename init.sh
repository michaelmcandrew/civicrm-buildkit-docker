#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo git clone https://github.com/civicrm/civicrm-buildkit.git $DIR/buildkit
echo docker-compose --project-directory $DIR up -d
echo docker-compose --project-directory $DIR exec cli civi-download-tools
echo docker-compose --project-directory $DIR exec cli civibuild cache-warmup
echo mkdir $DIR/buildkit/.amp
echo cp $DIR/cli/amp.services.yml $DIR/buildkit/.amp/services.yml
echo cp $DIR/cli/civibuild.conf $DIR/buildkit/app/civibuild.conf
echo cp $DIR/cli/nginx-vhost.php $DIR/buildkit/.amp/nginx-vhost.php
echo rm $DIR/buildkit/app/civicrm.settings.d/100-mail.php

# Workaround, until a version of amp that allows nginx template overrides is
# packaged with buildkit
echo git clone https://github.com/amp-cli/amp $DIR/buildkit/amp
echo docker-compose --project-directory $DIR exec composer install -d /buildkit/amp
echo ln -sf $DIR/buildkit/amp/bin/amp $DIR/buildkit/bin/amp
