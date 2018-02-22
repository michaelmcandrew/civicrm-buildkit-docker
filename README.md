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

## Usage

### CLI commands

Buildkit cli commands can be run from the host with:

`docker-compose exec -u buildkit civicrm <COMMAND>`

Alternatively, you can login to the container and run commands from there with:

`docker-compose exec -u buildkit civicrm bash`

Note: to avoid permissions issues, ensure that you connect to the containers as the buildkit user, not as root.

### The `/buildkit/build` mount

The `build` directory of this repository is mounted into the civicrm container at `/buildkit/build`. Builds created in the container are visible here, so files can be edited directly on the host.

### The `/extra` mount

We also mount an the `extra` directory of this repository at `/extra` for ad hoc use.

### Multiple builds and site URLs

In the Getting started example above, the URL has been explicitly set as <http://localhost:8080>. By default, new builds created with this Docker image are created at `http://[SITE_NAME].buildkit:8080`. This makes it simple to create multiple builds in the same container.

However you will need to manually create the `/etc/hosts` entries (or set up wildcard DNS) as the civicrm container is not able to access the host to do this for you.

### phpMyAdmin

phpMyAdmin is available for database admin at <http://localhost:8081>.

### Development email

Navigate to <http://localhost:8082> to view all email sent from the civicrm container.

Background: by default, buildkit disables outbound mail. We delete `civicrm-buildkit/app/civicrm.settings.d/100-mail.php` which re-enables outbound mail. We install `msmtp` on the `civicrm` container and configure it to deliver all mail to the `maildev` container.

### Command line aliases

docker-compose commands get quite verbose. You may want to create aliases as follows (assuming you have downloaded this repo to `$HOME/civicrm-buildkit-docker`):

- `alias bk='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml exec -u buildkit civicrm`
- `alias bkb='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml exec -u buildkit -e TERM=xterm-color civicrm bash'` # note the colour terminal
- `alias bkc='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml exec -u buildkit civicrm`
- `alias bku='docker-compose --file $HOME/civicrm-buildkit-docker/docker-compose.yml up -d`

You can then:

- bring up the containers with `dbu`
- run build and admin tools with `dbc`, for example `dbc civibuild create dmaster`.

## Architecture

We stick with the defaults and follow best pratice whenever possible. Sometimes CiviCM best practice and Docker best practice are at odds. In these situations we are often forced to do things the CiviCRM way. When this happens, we make a note in the 'cloud native' project of the steps we could take to make CiviCRM more Docker friendly (e.g. environment variables to configure SMTP).

The `docker-compose.yml` defines the following containers:

* **civicrm** (serves the sites; contains build and admin tools like `civibuild`, `cv`, etc.)
* **mysql**
* **phpmyadmin** for easier MySQL admin
* **maildev** to catch outbound email
