<?php
$configuration = parse_ini_file('config.cfg');
include_once('newviz/common.functions.php');
include_once('common/common-functions.php');

$field = $_GET['field'];
if (!isset($field))
  $field = 'aggregated';

$collectionId = $_GET['collectionId'];
if (!isset($collectionId))
  $collectionId = 'all';

$excludeZeros    = (isset($_GET['excludeZeros'])    && $_GET['excludeZeros'] == 1) ? TRUE : FALSE;
$showNoInstances = (isset($_GET['showNoInstances']) && $_GET['showNoInstances'] == 1) ? TRUE : FALSE;

$codes = [
  'no language',
  'no field instance',
  'resource'
];

$fileName = getDataDir() . '/json/' . $collectionId . '/' . $collectionId . '.languages.json';
$languages = json_decode(file_get_contents($fileName));

echo getTree($languages->$field, $field, $excludeZeros, $showNoInstances);
// echo json_encode($languages->$field);

/*
foreach ($languages as $key => $source) {
  file_put_contents($key . '.json', getTree($source, $key));
}
*/

function getTree($source, $key, $excludeZeros, $showNoInstances) {
  $result = [
    'name' => $key,
    'children' => []
  ];
  if (!$excludeZeros && isset($source->resource)) {
    $result['children'][] = ['name' => 'resource', 'size' => $source->resource];
  }
  unset($source->resource);

  if (!$excludeZeros && isset($source->{'no language'})) {
    $result['children'][] = ['name' => 'no language', 'size' => $source->{'no language'}];
  }
  unset($source->{'no language'});

  if ($showNoInstances && isset($source->{'no field instance'})) {
    $result['children'][] = ['name' => 'no field instance', 'size' => $source->{'no field instance'}];
  }
  unset($source->{'no field instance'});

  $i = count($result['children']);
  $result['children'][$i] = ['name' => 'languages', 'children' => []];

  foreach ($source as $language => $size) {
    $result['children'][$i]['children'][] = ['name' => $language, 'size' => $size];
  }

  return json_encode($result);
}
