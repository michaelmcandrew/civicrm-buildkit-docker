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
passthru('php ' . __DIR__ . '/publish/generate.php');
exec('git -C ' . __DIR__ . ' status --porcelain', $status);
if (count($status)) {
    echo "Changes\n";
    passthru('git -C ' . __DIR__ . ' add .');
    passthru('git -C ' . __DIR__ . ' commit -m "Updating docker artifacts from templates"');
    passthru('git -C ' . __DIR__ . ' push civi master');
    passthru('git -C ' . __DIR__ . ' push hub master');
    passthru('git -C ' . __DIR__ . ' push 3sd master');
}
passthru('php ' . __DIR__ . '/publish/publish.php');
