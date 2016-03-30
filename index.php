<?php
$configuration = parse_ini_file('config.cfg');

$features = array(
  'total' => 'Total',
  'mandatory' => 'Mandatory',
  'descriptiveness' => 'Descriptiveness',
  'searchability' => 'Searchability',
  'contextualization' => 'Contextualization',
  'identification' => 'Identification',
  'browsing' => 'Browsing',
  'viewing' => 'Viewing',
  'reusability' => 'Reusability',
  'multilinguality' => 'Multilinguality'
);

$feature = $_GET['feature'];
if (!isset($feature) || !isset($features[$feature])) {
  $feature = 'total';
}

$types = [
  'data-providers' => 'Data providers',
  'datasets' => 'Data sets'
];
$type = $_GET['type'];
if (!isset($type) || !isset($types[$type])) {
  $type = 'datasets';
}
$prefix = $type == 'datasets' ? 'c' : 'd';

// $csv = array_map('str_getcsv', file('collection-names.csv'));

function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($type . '.txt'));

$counter = 1;
$rows = array();
// include('dataset-directory-header.tpl.php');
foreach ($csv as $id => $row) {
  $id = $row[0];
  $collectionId = $row[1];

  $jsonFileName = $configuration['QA_R_PATH'] . '/json/' . $prefix . $id . '.json';
  if (file_exists($jsonFileName)) {
    if ($counter == 1) {
      // echo $jsonFileName, ' ';
    }
    $stats = json_decode(file_get_contents($jsonFileName));
    $assocStat = array();
    foreach ($stats as $obj) {
      if ($counter == 1) {
        // echo json_encode($obj);
      }
      if ($obj->_row == $feature) {
        unset($obj->recMin);
        unset($obj->recMax);
        unset($obj->_row);
        $obj->id = $id;
        $obj->type = $prefix;
        $obj->collectionId = $collectionId;
        $rows[$counter++] = $obj;
        break;
      }
    }
  }
}

include("index.tpl.php");
