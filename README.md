# CiviCRM buildkit on Docker

CiviCRM buildkit on Docker is primarily built for development. It may also be useful for hosting. Contributions welcome.

Please file specific issues on the [github issue queue](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues). More general discussion about CiviCRM and Docker should happen in CivCRM's ['cloud-native' project](https://lab.civicrm.org/dev/cloud-native).

The CiviCRM Dockerfile ([`civicrm/Dockerfile`]('civicrm/Dockerfile')) in this repo is published on Docker hub at <https://hub.docker.com/r/michaelmcandrew/civicrm/>

It is designed to work with a MySQL compatible container. You may wish to use it with other containers like `phpmyadmin` and `maildev`.

The docker-compose.yml file in this repository is a good starting point for Docker development. Advanced users may wish to create their own `docker-compose.yml`.

## Getting started

1. Install Docker and Docker compose
2. Clone this repository
3. From the repsitory root, run `docker-compose up -d`
4. Create dmaster with `docker-compose exec -u buildkit civicrm civibuild create dmaster --url http://localhost:8080`
5. Navigate to your new CiviCRM development site at <http://localhost:8080>

## CLI commands

Buildkit cli commands can be run from the host with:

`docker-compose exec -u buildkit civicrm <COMMAND>`

Alternatively, you can login to the container and run commands from there with:

`docker-compose exec -u buildkit civicrm bash`

## The `buildkit/build` directory

Buildkit's build directory is mounted from the host into the civicrm container. Any builds created with `civibuild` end up here and on the civicrm container, which means they can be edited directly on the host.

## Multiple builds and site URLs

The example in Getting started above has defines the URL explicitly as <http://localhost:8080>. But what about when you want to run mutiple sites? The buildkit approach (that we recommend) is to use apache virtual hosts.

By default, new builds created with this Docker image are available at `http://[SITE_NAME].buildkit:8080`. On bare metal, civibuild can create a `127.0.0.1 [SITE_NAME].buildkit` entry in your /etc/hosts file. This is not possible with Docker as a container cannot communicat with its host, so you need to take care of this step yourself.

The simplest way to do this is to create entries in the hosts `etc/hosts` file on behalf of civibuild as needed. Alternatively, you could set up a wildcard DNS for `*.buildkit` pointing to `127.0.0.1`.

## Archiecture

We stick with the defaults and follow best pratice whenever possible. Sometimes CiviCM best practice and Docker best practice are at odds. In these situations we are often forced to do things the CiviCRM way. When this happens, we make a note in the 'cloud native' project of the steps we could take to make CiviCRM more Docker friendly (e.g. environment variables to configure SMTP).

The `docker-compose.yml` defines the following containers:

* **civicrm** (serves the sites; contains build and admin tools like `civibuild`, `cv`, etc.)
* **mysql**
* **phpmyadmin** for easier MySQL admin
* **maildev** to catch outbound email

## Create aliases

docker-compose commands get quite verbose. You may want to create aliases as follows (assuming you have downloaded this repo to `$HOME/civicrm-buildkit-docker`):

- `alias bk='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml exec -u buildkit civicrm`
- `alias bkc='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml exec -u buildkit civicrm`
- `alias bku='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml up -d`

You can then:

- bring up the containers with `dbu`
- run build and admin tools with `dbc`, for example `dbc civibuild create dmaster`.

##

phpMyAdmin is available at <http://localhost:8081>.

## Viewing sent email

Navigate to <http://localhost:8082> to view all email sent from the civicrm container.

Background: by default, buildkit disables outbound mail. We delete `civicrm-buildkit/app/civicrm.settings.d/100-mail.php` which re-enables outbound mail. We install `msmtp` on the `civicrm` container and configure it to deliver all mail to the `maildev` container.
