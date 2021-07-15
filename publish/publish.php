<?php
include_once 'include.php';
$baseFlavour = 'apache-buster';

// Update templates
foreach ($phpVersions as $phpVersion) {
  echo "Publishing {$phpVersion}\n";
  echo "- pull base image\n";
  passthru("docker pull php:$phpVersion-$baseFlavour");
  echo "- build image\n";
  passthru("docker build " . __DIR__ . "/civicrm/php{$phpVersion} --no-cache -t michaelmcandrew/civicrm-buildkit:php{$phpVersion}");
  echo "- push image\n";
  passthru("docker push michaelmcandrew/civicrm-buildkit:php$phpVersion");
}
echo "push 'latest'";
passthru("docker tag michaelmcandrew/civicrm-buildkit:php$defaultVersion michaelmcandrew/civicrm-buildkit:latest");
passthru("docker push michaelmcandrew/civicrm-buildkit:latest");
