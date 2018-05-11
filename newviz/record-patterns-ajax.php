<?php

$configuration = parse_ini_file('../config.cfg');
include_once('common.functions.php');
$templateDir = '../templates/newviz/record-patterns/';

$parameters = getParameters();
$collectionId = $parameters->type . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$dataDir = '../' . getDataDir();

$data = (object)[
  'fields' => getProfileFields($collectionId),
  'profiles' => getPatterns($collectionId, $count),
];

$smarty = createSmarty($templateDir);
$smarty->assign('data', $data);
$html = $smarty->fetch('record-patterns.smarty.tpl');

// $html = callTemplate($data, $templateDir . 'record-patterns.tpl.php');

header("Content-type: application/json");
echo json_encode([
  'fields_file' => getFieldsFile(),
  'fields_file_exists' => file_exists(getFieldsFile($collectionId)),
  'profile_file' => getProfileFile(),
  'profile_file_exist' => file_exists(getProfileFile($collectionId)),
  'html' => $html
]);

function getFieldsFile($collectionId) {
  global $dataDir;

  return $dataDir . '/json/' . $collectionId . '/' .  $collectionId . '-fields.csv';
}

function getProfileFile($collectionId) {
  global $dataDir;

  return $dataDir . '/json/' . $collectionId . '/' . $collectionId . '-profiles.csv';
}

function getProfileFields($collectionId) {
  return explode(';', file_get_contents(getFieldsFile($collectionId)));
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