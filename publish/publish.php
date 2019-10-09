<?php
include_once('include.php');
$baseFlavour = 'apache-stretch';

// Update templates
foreach ($phpVersions as $phpVersion) {
  echo "Publishing {$phpVersion}\n";
  echo "- pull base image\n";
  `docker pull php:7.2-$baseFlavour`;
  echo "- build image\n";
  $command = "docker build " . __DIR__ . "/civicrm/php{$phpVersion} --no-cache -t michaelmcandrew/civicrm-buildkit:php{$phpVersion}";
  shell_exec($command);
  echo "- push image\n";
  `docker push michaelmcandrew/civicrm-buildkit:php$phpVersion`;
}
echo "push 'latest'";
`docker tag michaelmcandrew/civicrm-buildkit:php$defaultVersion michaelmcandrew/civicrm-buildkit:latest`;
`docker push michaelmcandrew/civicrm-buildkit:latest`;
