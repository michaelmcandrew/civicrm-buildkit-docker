# CiviCRM buildkit on Docker

A buildkit oriented docker set up for CiviCRM 4.7. This repository creates a CiviCRM development environment based on buildkit.

It tries to follow the Docker principles of isolating services in containers (rather than munging the whole set up into a single container) in order to make it easier to experiment with different versions and flavours of the various services.

* `nginx` from nginx: serves the development sites
* `fpm` from php-fpm: processes php requests from nginx
* `mysql` from mysql: the databases
* `cli` - from php-cli: for the build process *

\* `cli` also includes nodejs and a few other utilities.

The `civicrm-buildkit/build` directory is bind mounted at `./build` for local development.

## Installation

1. Install Docker and Docker compose
2. Clone this repository
3. Start the containers with `docker-compose up -d`
4. Change the ownership of the _buildkit_ directory `sudo chown your-username.your-group buildkit`
5. Run the init script `./init.sh`

Check the installation worked by browsing to `http://localhost:8080`.

## Create a site

1. `docker-compose exec cli civibuild create dmaster` To get more information and option on the command civibuild read the documentation at https://docs.civicrm.org/dev/en/latest/tools/civibuild/
2. `docker-compose restart nginx` (amp can't restart nginx from inside the cli container)
3. Add `dmaster.buildkit 127.0.0.1` to `/etc/hosts`

You site should be listed at `http://localhost:8080`. Visit it at http://dmaster.buildkit:8080.

## Create aliases

If you would rather not type `docker-compose...` all the time, consider creating aliases along the lines of the following:

Assuming you have downloaded this repo to `$HOME/civicrm-buildkit-docker`:

* `alias db='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml'`
* `alias dbc='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml run cli'`
* `alias dbu='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml up -d'`
* `alias dbr='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml restart nginx'`
* `alias dbd='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml down'`

You can then:

* bring up the containers with `dbu`
* run cli commands with `dbc`, for example `dbc civibuild create dmaster` and `dbc civibuild reinstall wpmaster`.
* restart nginx with `dbr`
* stop the containers with dbd

### Viewing sent email

Navigate to http://localhost:8082.

Background: by default, buildkit disables outbound mail. We delete `civicrm-buildkit/app/civicrm.settings.d/100-mail.php` which re-enables outbound mail. We install `msmtp` on the `fpm` and `cli` containers and configuring it to deliver all mail to `maildev` on the `mail` container.

# Roadmap

See the [issue queue](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues).
