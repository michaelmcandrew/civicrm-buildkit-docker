<?php
include_once('include.php');
require_once __DIR__ . '/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/civicrm');
$twig = new Twig_Environment($loader, array());

$dir = __DIR__;
// Update templates
foreach ($phpVersions as $phpVersion) {
  echo "Generating {$phpVersion}\n";
  $versionDir = $dir . "/civicrm/php{$phpVersion}";
  if (!is_dir($versionDir)) {
    `mkdir -p $versionDir`;
  }
  foreach (glob($dir . '/templates/civicrm/*.twig') as $file) {
    $outputFile = $versionDir . '/' . basename($file, '.twig');
    $template = basename($file);
    file_put_contents($outputFile, $twig->render($template, ['php_version' => $phpVersion]));
  }
}
`rm -r $dir/../civicrm`;
`cp -r $dir/civicrm/php$defaultVersion $dir/../civicrm`;
