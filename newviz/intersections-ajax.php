<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/common/common-functions.php');
include_once($root . '/newviz/common.functions.php');

$id = getOrDefault('id', NULL);
$type = getOrDefault('type', NULL);
$version = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);
$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;
$format = getOrDefault('format', 'json', ['json', 'html']);

if (is_null($id) || is_null($type) || !in_array($type, ['c', 'd', 'p']) || is_null($version)) {
  $content = [];
}

$dataDir = $configuration['DATA_PATH'] . '/' . $version;

$intersections = getIntersections($type, $id);

if ($format == 'json') {
  header("Content-type: application/json");
  echo json_encode($intersections);
} else if ($format == 'html') {
  $smarty = createSmarty('../templates/newviz');
  $smarty->assign('intersectionLabels', ['c' => 'datasets', 'd' => 'data providers', 'p' => 'providers']);
  $smarty->assign('intersections', $intersections);
  $smarty->display('intersections.smarty.tpl');
}

/*
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
*/

