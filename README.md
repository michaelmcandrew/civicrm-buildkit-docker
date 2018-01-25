# CiviCRM buildkit on Docker

A buildkit oriented docker set up for CiviCRM.

This repository creates a CiviCRM development environment based on buildkit.

It roughly follows the Docker principles of isolating services in containers (rather than creating an entire set up in a single container) which should make it easy to try out different versions and flavours of the various services.

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

## Browsing sites

Buildkit is designed on the assumption everything is happening on the same host. We have to jump through an extra hoop in order to browse the site that we created above.

civicrm-buildkit-docker creates sites on port 8080 and forwards this port to the host.

By adding dmaster.dev to our local hosts file, we should be able to browse the site at http://dmaster.dev:8080

# Next steps

* Configure maildev for better mail testing.
* Decide on whether we should use nginx or apache (giving the option of either seems a bit wrong if we are trying to standardise on a stack)
*
