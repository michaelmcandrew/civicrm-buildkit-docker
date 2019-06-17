<?php
require_once __DIR__ . '/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/civicrm');
$twig = new Twig_Environment($loader, array());

$phpVersions = ['5.6', '7.0', '7.1', '7.2', '7.3'];

foreach ($phpVersions as $phpVersion) {
  $dir = __DIR__ . "/civicrm/php{$phpVersion}";
  if (!is_dir($dir)) {
    `mkdir -p $dir`;
  }
  foreach (glob(__DIR__ . '/templates/civicrm/*.twig') as $file) {
    $outputFile = $dir . '/' . basename($file, '.twig');
    $template = basename($file);
    file_put_contents($outputFile, $twig->render($template, ['php_version' => $phpVersion]));
  }
}
