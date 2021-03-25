# Publishing Dockerfiles

The following script creates various CiviCRM Dockerfiles and associated assets.

The script creates Dockerfiles for the versions of PHP as specified in [`include.php`](include.php), based on the appropriate `php:*.*-apache` base image. It could be extended to generate other versions of CiviCRM as well (e.g. `php7.3-fpm`)

Actually publishing images is not handled in this repo, but the alternate images are tagged and available on Docker Hub. If you don't want to use the images from Docker Hub, or you generate new ones, you can update your `docker-compose.yml` by replacing the `image:` declaration with a `build:` declaration.

```yml
civicrm:
  build: publish/civicrm/php7.3
```

1. Reference a different Dockerfile in the `docker-compose.yml` distributed with this repository:

```yml
civicrm:
  build: publish/civicrm/php7.3
```

2. Build and tag an image `docker build publish/civicrm/php7.3 -t civicrm:7.3`

## Updating Dockerfiles

1. From the `publish` directory, run `composer install`
2. Make any necessary changes to the `templates` and `generate.php` script.
3. Run `php generate.php`
4. Check the generated directories in `publish/civicrm`

If you don't have PHP or composer installed locally you can use Docker images to run the `generate.php` script as follows:

1. Move to the `publish` directory
1. Run `docker run -it --rm -u $(id -u):$(id -g) -v "$PWD":/app composer install`
1. Run `docker run -it --rm -u $(id -u):$(id -g) -v "$PWD/..":/usr/src/myapp -w /usr/src/myapp/publish php php generate.php`

## Publishing updates to https://hub.docker.com

This happens automatically each night. It can be done manually (with appropriate credentials)

From the repo root directory:

```
docker build civicrm --no-cache -t michaelmcandrew/civicrm-buildkit
docker push michaelmcandrew/civicrm-buildkit
```
