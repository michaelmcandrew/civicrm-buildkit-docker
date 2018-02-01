# CiviCRM buildkit on Docker

A buildkit oriented docker set up for CiviCRM. This repository creates a CiviCRM development environment based on buildkit.

It tries to follow the Docker principles of isolating services in containers (rather than munging the whole set up into a single container) in order to make it easier to experiment with different versions and flavours of the various services.

* `nginx` from nginx: serves the development sites
* `fpm` from php-fpm: processes php requests from nginx
* `mysql` from mysql: the databases
* `cli` - from php-cli: for the build process *

\* `cli` also includes nodejs and a few other utilities.

The `civicrm-buildkit/build` directory is bind mounted at `./build` for local development.

## Getting started

1. Install Docker and Docker compose
2. Clone this repository
3. Start the containers with `docker-compose up -d`
4. Create a dmaster build with `docker-compose exec cli civibuild create dmaster`
5. The build will be available at `./build/dmaster`

## Tips

### Browsing sites

`civibuild` runs in a container and cannot update `/etc/hosts` on the hosts machine - you need to manually configure a `/etc/hosts` entry. `civibuild` is configured with the following URL_TEMPLATE: `http://%SITE_NAME%.buildkit:8080`. port 8080 on nginx is forwards to 8080 on the host. To access a site from the local machine add an entry to `/etc/hosts` along the lines of `127.0.0.1 %SITE_NAME%.buildkit`.

### Viewing sent email

By default, buildkit disables outbound mail. We stop buildkit from disabling outbound mail and redirect it to a maildev container. This is achieved by installing `msmtp` on the `fpm` container and configuring it appropriately and deleting the `civicrm-buildkit/app/civicrm.settings.d/100-mail.php` configuration file.

# Roadmap

See the [issue queue](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues).
