# Publishing Dockerfiles

The following script creates various CiviCRM Dockerfiles and associated assets.

At the moment, it creates Dockerfiles for `civicrm:php5.6` and `civicrm:php7.0`, both based on the appropriate `php:*.*-apache-jessie` base image. It could be extended to generate other versions of CiviCRM as well (e.g. `php7.0-fpm`)

It does not currently build images for the Dockerfiles or publish these images to Dockerhub (though it could be extended to do so).

## Usage

Until the above images are automatically published, you can use these files as follows:

1. Reference a different Dockerfile in the `docker-compose.yml` distributed with this repository:

```yml
civicrm:
  build: publish/civicrm/php5.6
```

2. Build and tag an image `docker build publish/civicrm/php5.6 -t civicrm:5.6`

## Updating Dockerfiles

1. Make any necessary changes to the `templates` and `publish.php` script.
2. From the `publish` directory, `composer install` (if you haven't already) and run `php publish.php`
3. Check the generated directories in `publish/civicrm`

## Updating the `:latest` (default) Dockerfile

Copy the contents of `publish/civicrm/php7.0` to `civicrm` with `cp publish/civicrm/php7.0/* civicrm`.
