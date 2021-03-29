# Publishing Images

The following script creates various CiviCRM Dockerfiles and associated assets.

The script creates Dockerfiles for the versions of PHP as specified in [`include.php`](include.php), based on the appropriate `php:*.*-apache` base image. It could be extended to generate other versions of CiviCRM as well (e.g. `php7.3-fpm`)

Actually publishing images is not handled in this repo, but the alternate images are tagged and available on Docker Hub. If you don't want to use the images from Docker Hub, or you generate new ones, you can update your `docker-compose.yml` by replacing the `image:` declaration with a `build:` declaration.

```yml
civicrm:
  build: publish/civicrm/php7.3
```

Or first, build and tag an image `docker build publish/civicrm/php7.3 -t myimage:php-7.3` and update the compose file:

```yml
civicrm:
  image: myimage:php-7.3
```

## Updating Image Builds (Dockerfiles)

For code-reuse and "DRY", the variants of the civicrm Dockerfile in this directory are generated using a [twig](https://github.com/twigphp/Twig)  template.

1. From the `publish` directory, run `composer install`
2. Make any necessary changes to the `templates` and `generate.php` script.
3. Run `php generate.php`
4. Check the generated directories in `publish/civicrm`

If you don't have PHP or composer installed locally you can use a container to run the `generate.php` script as follows:

1. From the `publish` directory
1. Run `docker run -it --rm -u $(id -u):$(id -g) -v "$PWD":/app composer install`
1. Run `docker run -it --rm -u $(id -u):$(id -g) -v "$PWD/..":/usr/src/myapp -w /usr/src/myapp/publish php php generate.php`

## Publishing updates to https://hub.docker.com

This happens automatically each night. It can be done manually (with appropriate credentials)

From the repo root directory:

```
docker build civicrm --no-cache -t michaelmcandrew/civicrm-buildkit
docker push michaelmcandrew/civicrm-buildkit
```
