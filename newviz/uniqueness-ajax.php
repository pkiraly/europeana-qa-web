<?php

$configuration = parse_ini_file('../config.cfg');
include_once('common.functions.php');
$templateDir = '../templates/newviz/uniqueness/';

$parameters = getParameters();
$collectionId = $parameters->type . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$dataDir = '../' . getDataDir();

$data = (object)[
  'version' => getOrDefault('version'),
  'file' => getUniquenessFile($collectionId),
  'histogram' => getHistogram(getUniquenessFile($collectionId)),
  'stars' => ['<i class="fa fa-certificate"></i>', '*****', '****', '***', '**', '*']
];

$smarty = createSmarty($templateDir);
$smarty->assign('data', $data);
$html = $smarty->fetch('uniqueness-histogram.smarty.tpl');

// $html = callTemplate($data, $templateDir . 'uniqueness-histogram.tpl.php');

header("Content-type: application/json");
echo json_encode([
  'uniqueness_file' => getUniquenessFile($collectionId),
  'uniqueness_file_exists' => file_exists(getUniquenessFile($collectionId)),
  'html' => $html,
  'data' => $data
]);

function getUniquenessFile($collectionId) {
  global $dataDir;

  return $dataDir . '/json/' . $collectionId . '/' .  $collectionId . '.uniqueness.histogram.json';
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