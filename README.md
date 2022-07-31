# CiviCRM buildkit on Docker

[CiviCRM buildkit on Docker](https://hub.docker.com/r/michaelmcandrew/civicrm-buildkit) is thin wrapper around CiviCRM's buildkit, allowing it to be run in a docker container.

Note: CiviCRM buildkit is primarily a development tool - it is not designed for production hosting. If you are looking for CiviCRM docker containers designed for hosting, see https://lab.3sd.io/tools/civicrm-buildkit.

The docker image at https://hub.docker.com/r/michaelmcandrew/civicrm-buildkit/ is built with the [`civicrm/Dockerfile`](civicrm/Dockerfile) in this repo and published nightly. It requires another container running MySQL. You may wish to use it with other containers like `phpmyadmin` and `maildev`.

The [`docker-compose.yml`](docker-compose.yml) file in this repository is a good starting point for a Docker-based CiviCRM development environment.

## Usage

### Get started

1. Ensure you meet the requirements (`docker` and `git`)
1. Clone this repository
2. From the repository root, run `docker-compose up -d` to start the containers defined in the `docker-compose.yml` file
3. Create a WordPres demo site with `docker-compose exec -u buildkit civicrm civibuild create wp-demo`
4. Navigate to your new CiviCRM development site at <http://dmaster.localhost:7979>

In order to 'interact' with your codebase with comand line tools such civix etc, you'll need to run a bash shell 'within' the container. Launch a bash shell in the container with `docker-compose exec -u buildkit civicrm bash`.

Most issues that people report with CiviCRM buildkit on Docker turn out to be CiviCRM buildkit issues - please consult the [Buildkit documentation](https://docs.civicrm.org/dev/en/latest/tools/buildkit/).

### Accessing the container

Login to a bash shell in the container: `docker-compose exec -u buildkit civicrm bash`

Execute a command in the conatiner `docker-compose exec -u buildkit civicrm <COMMAND>`

### The `/buildkit/build` directory

The `build` directory of this repository _is mounted into the civicrm container_ at `/buildkit/build`. Builds created in the container will be visible here which allows files to be edited directly on the host.

You may notice that all the files that buildkit creates in the build directory are gitignored. This makes sense from this repositories point of view, but can be annoying if you are working on files in this directory. For example, in Visual Studio Code these files appear greyed out and are exlcuded from search results.

As a workaround, you may want to open the build directory directly. It has a `.vscode` folder that requests that vscode ignores the git repository in its parent folder, hence files do not appear greyed out.

### Debugging

The container includes and configures the Xdebug php extension.  See `civicrm/docker-civicrm-entrypoint` for the relevant configuration.

The build directory also contains the relevant configuration for Visual Studio Code (in the `build/.vscode/launch.json` file). All you should need to do is install the [Visual Studio Code Xdebug extension](https://marketplace.visualstudio.com/items?itemName=kakumei.php-xdebug).

### The `/extra` mount

We mount the `extra` directory of this repository at `/extra` in the container for ad hoc use.

### Site URLs

By default, new builds created with this Docker image are created at `http://[SITE_NAME].localhost:7979`. This makes it simple to create multiple builds in the same container.

Other URLs can be passed to the civibuild command, however, you will need to ensure any domains are resolved appropriately, e.g. by adding entries to the `/etc/hosts` file.

### phpMyAdmin

The default `docker-compose.yml` configuration includes phpMyAdmin which should be available at <http://localhost:8081>.

### Development email

The default `docker-compose.yml` configuration maildev which should be available at <http://localhost:8082>.

Maildev will collect all email sent from the civicrm container.

Note: by default, buildkit disables outbound mail. We delete `civicrm-buildkit/app/civicrm.settings.d/100-mail.php` which re-enables outbound mail, install `msmtp` on the `civicrm` container, and configure it to deliver all mail to the `maildev` container.

### PHP versions

We aim to support all current versions of PHP. You can see currently supported versions here: `publish/include.php`. Please let us know if we are out of date.

See [publish/README.md](publish/README.md) for instructions on using CiviCRM containers that use different versions of PHP.

### UID and GID conflicts

The mounts we use for `/buildkit/build` and `/extra` share UIDs and GIDs between the host and container. The container expects them to both be 1000. If you want to use a different UID or GID (e.g. because your user does not ahve a UID and GID of 1000, you can create a custom image that passes BUILDKIT_UID and BUILDKIT_GID as arguments.

## Custom images

This repository comes with a `docker-compose-build.yml` that can be used for building custom images. Create a custom build with a custom UID for the buildkit user as follows:

1. Overwrite `docker-compose.yml` with the contents of `docker-compose-build.yml`.
2. Change the BUILDKIT_UID argument to match your user id if necessary (`echo $UID` should give you your user id).
3. run `docker-compose up -d --build`.

## Getting help

Ask any questions you have in the [docker](https://chat.civicrm.org/civicrm/channels/docker) chatroom (feel free to @michaelmcandrew if you like), or file an issue in the [github issue queue](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues).

## Upgrading

New docker images are released nightly on Docker Hub at <https://hub.docker.com/r/michaelmcandrew/civicrm/>. Upgrade to the latest version as follows:

1. `docker pull michaelmcandrew/civicrm` to download the latest images.
2. `docker-compose up -d` to restart your containers with the latest image.

## Architecture

The `docker-compose.yml` defines the following containers:

* **civicrm** (serves the sites; contains build and admin tools like `civibuild`, `cv`, `civix` etc.)
* **mysql**
* **phpmyadmin** for easier MySQL admin
* **maildev** to catch outbound email

We stick with the defaults and follow best practice whenever possible. When CiviCRM best practice and Docker best practice are in conflict we typically have to do things the CiviCRM way and make a note in the 'cloud native' project of the steps we could take to make CiviCRM more Docker friendly (e.g. environment variables to configure SMTP).

More general discussion about CiviCRM and Docker is welcome in CivCRM's ['cloud-native' project](https://lab.civicrm.org/dev/cloud-native).

## Windows

Windows is not officially supported. Please get in contact if you would like to help with support for windows. That said, here are a few notes that might be helpful.

There is an issue with file permissions if you are using Windows. Follow these [instructions for enabling WSL2](https://docs.docker.com/docker-for-windows/wsl/) integration and upgrading an existing WSL installation to WSL2. Background on the [issue #52](https://github.com/michaelmcandrew/civicrm-buildkit-docker/issues/52).

## Credits

CiviCRM Buildkit Docker is maintained by [Michael McAndrew](https://twitter.com/michaelmcandrew) of [Third Sector Design](https://thirdsectordesign.org/) who you can [contact](https://thirdsectordesign.org/contact) for help, support and further development.

## Contributing

Contributions to this repository are very welcome! Feel free to submit a pull request for minor improvements. For larger changes to reduce it probably makes sense to create an issue first.

## Administration

See [Publishing Dockerfiles](publish/README.md) for details on how to update the image published on https://hub.docker.com.

## License

This extension is licensed under [AGPL-3.0](LICENSE).
