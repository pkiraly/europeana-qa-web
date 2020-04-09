<?php
error_log(sprintf('%s:%d timeline ajax', basename(__FILE__), __LINE__));

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');

$parameters = getParameters();
$collectionId = ($parameters->type == 'a') ? $parameters->id : $parameters->type . '-' . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$dataDir = getDataDir();

$files = getTimelineFiles($collectionId);
error_log(sprintf('%s:%d file: %s', basename(__FILE__), __LINE__, $files));

$data = (object)[
  'version' => getOrDefault('version'),
  'file' => $files,
  'histogram' => getHistogram($files),
  'stars' => ['<i class="fa fa-certificate"></i>', '*****', '****', '***', '**', '*'],
  'fq' => sprintf("%s:%d", ($parameters->type == 'c' ? 'collection_i' : 'provider_i'), $parameters->id)
];

$smarty = createSmarty('../templates/newviz/timeline/');
$smarty->assign('data', $data);
$smarty->display('timeline.smarty.tpl');

function getTimelineFiles($collectionId) {
  global $parameters, $configuration;

  $files = [];
  foreach ($configuration['version'] as $version) {
    if ($version >= 'v2019-08') {
      $dataDir = $configuration['DATA_PATH'] . '/' . $version;
      $files[] = $dataDir . '/json/' . $parameters->type . '/' . $collectionId . '/' .  $collectionId . '.completeness.csv';
    }
  }
  return $files;
}

function getHistogram($file) {
  $histogram = new stdClass();
  if (file_exists($file)) {
    $histogram = json_decode(file_get_contents($file));
  }
  return $histogram;
}


function getPatterns($collectionId, $count) {
  global $dataDir;

  $fileName = getProfileFile($collectionId);

  $profiles = [];
  if ($file = fopen($fileName, "r")) {
    $lineSet = FALSE;
    while(!feof($file)) {
      $line = trim(fgets($file));
      if ($line == '')
        continue;
      $row = [];
      list($profile, $row['nr'], $row['occurence'], $row['percent']) = explode(',', $line);
      if ($count != -1)
        $row['percent'] = $row['occurence'] / $count;
      $row['profileFields'] = explode(';', $profile);
      if ($row['percent'] * 100 < 1 && !$lineSet) {
        $lineSet = TRUE;
        $drawLine = TRUE;
      } else {
        $drawLine = FALSE;
      }
      $row['drawLine'] = $drawLine;
      $profiles[] = $row;
    }
    fclose($file);
  }

  return $profiles;
}