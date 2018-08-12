<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/common/common-functions.php');
include_once($root . '/newviz/common.functions.php');

$id = getOrDefault('id', NULL);
$type = getOrDefault('type', NULL);
$version = getOrDefault('version', $configuration['version'][0], $configuration['version']);

if (is_null($id) || is_null($type) || !in_array($type, ['c', 'd']) || is_null($version)) {
  $content = [];
}

$dataDir = $root . '/data/' . $version;

$content = getIntersections($type, $id);

header("Content-type: application/json");
echo json_encode($content);

function getIntersections($type, $id) {
  global $dataDir;

  $other_type = $type == 'c' ? 'd' : 'c';
  $file = $dataDir . '/intersections.json';
  $data = json_decode(file_get_contents($file));

  $rows = [(object)['id' => 'all', 'name'=> 'all', 'file'=> 'all']];
  $all_count = 0;
  if (isset($data->$type->{$id})) {
    $list = $data->$type->$id;
    foreach ($list as $_id => $item) {
      $item->id = $_id;
      $item->name = retrieveName($_id, $other_type);
      if ($type == 'c' && $item->name === FALSE)
        $item->name = 'unspecified';
      $rows[] = $item;
      $all_count += $item->count;
    }
  } else {
    error_log(sprintf('no intersection for type: %s, id: %s', $type, $id));
  }
  $rows[0]->count = $all_count;
  return $rows;
}

function retrieveName($id, $type) {
  global $dataDir;

  if (!isset($content)) {
    $file = ($type == 'c') ? 'datasets.txt' : "data-providers.txt";
    $content = explode("\n", file_get_contents($dataDir . '/' . $file));
  }

  $name = FALSE;
  foreach ($content as $line) {
    list($_id, $_name) = explode(';', $line, 2);
    if ($_id == $id) {
      $name = $_name;
      break;
    }
  }
  return $name;
}
