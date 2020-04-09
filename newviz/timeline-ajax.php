<?php
$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');

$parameters = getParameters();
$collectionId = ($parameters->type == 'a') ? $parameters->id : $parameters->type . '-' . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$files = getTimelineFiles($collectionId);
$data = (object)[
  'version' => getOrDefault('version'),
  'files' => $files,
  'timelines' => getTimelines($files),
];

$smarty = createSmarty('../templates/newviz/timeline/');
$smarty->assign('data', $data);
$smarty->display('timeline.smarty.tpl');

function getTimelineFiles($collectionId) {
  global $parameters, $configuration;

  $files = [];
  foreach ($configuration['version'] as $version) {
    if ($version >= 'v2019-08') {
      $baseDatadir = $configuration['DATA_PATH'] . '/' . $version;
      $dataDir = $baseDatadir . '/json/' . $parameters->type . '/' . $collectionId;
      $files[$version] = $dataDir . '/' .  $collectionId . '.completeness.csv';
    }
  }
  ksort($files);

  return $files;
}

function getTimelines($files) {
  $timeline = [];
  foreach ($files as $version => $file) {
    if (file_exists($file)) {
      $keys = ($version == 'v2018-08')
        ? ["mean", "min", "max", "count", "sum", "median"]
        : ["mean", "min", "max", "count", "sum", "stddev", "median"];
      foreach (file($file) as $line) {
        $values = str_getcsv($line);
        $collection = array_shift($values);
        $field = array_shift($values);
        if (count($keys) != count($values)) {
          $msg = sprintf("%s:%d different counts: %d vs %d - values: %s",
            basename(__FILE__), __LINE__,
            count($keys), count($values), join(', ', $values));
          error_log($msg);
        }
        $row = array_combine($keys, $values);
        list($location, $entity, $encodedfield) = explode('_', $field, 3);
        $location = strtolower($location);
        $edmfield = preg_replace('/^([^_]+)_/', "$1:", $encodedfield);
        $timeline[$entity][$edmfield][$location][$version] = $row['mean'];
      }
    }
  }

  return $timeline;
}
