#! /usr/bin/env php
<?php

/**
 * Updates the repo and publishes the latest version of this repo.
 *
 * @package tsd/civicrm-buildkit-docker
 */

if (!is_dir('publish/vendor')) {
    die("Error: application not installed - run composer install in the publish dir\n");
}
passthru('php publish/generate.php');
