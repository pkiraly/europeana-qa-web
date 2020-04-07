<?php
$configuration = parse_ini_file('config.cfg');
include_once('newviz/common.functions.php');
include_once('common/common-functions.php');

// field=proxy_dc_title
// &excludeZeros=0
// &showNoOccurences=0
// &collectionId=c1529
// &intersection=null
// &version=v2019-03
error_log('plainjson2tree: ' . $_SERVER['QUERY_STRING']);

$field = $_GET['field'];
if (!isset($field))
  $field = 'aggregated';

$collectionId = $_GET['collectionId'];
if (!isset($collectionId))
  $collectionId = 'all';

error_log(sprintf('%s:%d collectionId: %s', __FILE__, __LINE__, $collectionId));

$excludeZeros     = (isset($_GET['excludeZeros'])     && $_GET['excludeZeros']     == 1) ? TRUE : FALSE;
$showNoOccurences = (isset($_GET['showNoOccurences']) && $_GET['showNoOccurences'] == 1) ? TRUE : FALSE;
$version = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);
$intersection = getOrDefault('intersection', NULL);
error_log(sprintf('%s:%d intersection: %s', __FILE__, __LINE__, $intersection));

$codes = [
  'no language',
  'no field instance',
  'resource'
];

$is_languages_all = (!is_null($version) && $version >= 'v2019-03');
$suffix = $is_languages_all ? '.languages-all.json' : '.languages.json';
if (!is_null($intersection) && $intersection != '') {
  $fileName = getDataDir() . '/json/' . $intersection . '/' . $intersection . $suffix;
} else {
  $fileName = getDataDir() . '/json/' . $collectionId . '/' . $collectionId . $suffix;
}
error_log(sprintf('%s:%d fileName: %s exist? %d', __FILE__, __LINE__, $fileName, (int) file_exists($fileName)));
$languages = json_decode(file_get_contents($fileName));

echo getTree($languages->$field, $field, $excludeZeros, $showNoOccurences, $is_languages_all);
// echo json_encode($languages->$field);

/*
foreach ($languages as $key => $source) {
  file_put_contents($key . '.json', getTree($source, $key));
}
*/

function getTree($source, $key, $excludeZeros, $showNoOccurences, $is_languages_all) {
  $result = [
    'name' => $key,
    'children' => []
  ];
  if (!$excludeZeros && isset($source->resource)) {
    $result['children'][] = [
      'name' => 'resource',
      'size' => ($is_languages_all ? $source->resource->occurences : $source->resource)
    ];
  }
  unset($source->resource);

  if (!$excludeZeros && isset($source->{'no language'})) {
    $result['children'][] = [
      'name' => 'no language',
      'size' => ($is_languages_all ? $source->{'no language'}->occurences : $source->{'no language'})
    ];
  }
  unset($source->{'no language'});

  if ($showNoOccurences && isset($source->{'no field instance'})) {
    $result['children'][] = [
      'name' => 'no field occurence',
      'size' => ($is_languages_all ? $source->{'no field instance'}->occurences : $source->{'no field instance'})
    ];
  }
  unset($source->{'no field instance'});

  $i = count($result['children']);
  $result['children'][$i] = ['name' => 'languages', 'children' => []];

  foreach ($source as $language => $size) {
    $result['children'][$i]['children'][] = [
      'name' => $language,
      'size' => ($is_languages_all ? $size->occurences : $size)
    ];
  }

  return json_encode($result);
}
