<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/common/common-functions.php');

$fragment = getOrDefault('fragment', NULL);
$type = getOrDefault('type', 'c', ['c', 'd']);
$fileName = ($type == 'c') ? 'datasets.txt' : 'data-providers.txt';
$version = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);

$dataDir = $root . '/data/' . $version;
$content = retrieveCsv($fileName, $fragment);

header("Content-type: application/json");
echo json_encode([
  'fragment' => $fragment,
  'type' => $type,
  'content' => $content,
]);

function retrieveCsv($fileName, $fragment) {
  global $dataDir;

  $list = [];
  $content = explode("\n", file_get_contents($dataDir . '/' . $fileName));
  foreach ($content as $line) {
    if ($line == '')
      continue;

    if (is_null($fragment) || $fragment == "" || stristr($line, $fragment)) {
      list($_id, $_name) = explode(';', $line, 2);
      $list[] = ['value' => $_id, 'name' => $_name];
    }
  }
  return $list;
}
