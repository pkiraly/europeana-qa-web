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
$type2 = getOrDefault('type2', NULL);
$id2 = getOrDefault('id2', NULL);
$targetType = getOrDefault('targetType', NULL);
$intersection = getOrDefault('intersection', NULL);

if (is_null($id) || is_null($type) || !in_array($type, ['c', 'd', 'p']) || is_null($version)) {
  $content = [];
}

$dataDir = $configuration['DATA_PATH'] . '/' . $version;

$intersections = getIntersections($type, $id, $type2, $id2, $targetType, $intersection);

if ($format == 'json') {
  header("Content-type: application/json");
  echo json_encode($intersections);
} else if ($format == 'html') {
  $smarty = createSmarty('../templates/newviz');
  $smarty->assign('intersectionLabels', ['c' => 'datasets', 'd' => 'data providers', 'p' => 'providers']);
  $smarty->assign('intersections', $intersections);
  $smarty->display('intersections.smarty.tpl');
}
