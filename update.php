#! /usr/bin/env php
<?php

/**
 * Updates the repo and publishes the latest version of this repo.
 *
 * @package tsd/civicrm-buildkit-docker
 */

if (!is_dir(__DIR__ . '/publish/vendor')) {
    die("Error: application not installed - run composer install in the publish dir\n");
}
passthru('git -C ' . __DIR__ . ' pull');
passthru('php publish/generate.php');
exec('git -C ' . __DIR__ . ' status --porcelain', $status);
if (count($status)) {
    echo "Changes\n";
    passthru('git -C ' . __DIR__ . ' add .');
    passthru('git -C ' . __DIR__ . ' commit -m "update folders"');
    passthru('git -C ' . __DIR__ . ' push');
}
passthru('php publish/publish.php');
