<?php
include_once('common.functions.php');
$templateDir = '../templates/newviz/record-patterns/';

$parameters = getParameters();
$collectionId = $parameters->type . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$data = (object)[
  'fields' => getProfileFields($collectionId),
  'profiles' => getPatterns($collectionId, $count),
];

$html = callTemplate($data, $templateDir . 'record-patterns.tpl.php');

header("Content-type: application/json");
echo json_encode([
  'html' => $html
]);

function getProfileFields($collectionId) {
  $fileName = '../json/' . $collectionId . '/' .  $collectionId . '-fields.csv';
  return explode(';', file_get_contents($fileName));
}

function getPatterns($collectionId, $count) {
  $fileName = '../json/' . $collectionId . '/' .  $collectionId . '-profiles.csv';

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