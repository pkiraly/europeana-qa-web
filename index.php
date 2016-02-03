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

$csv = array_map('str_getcsv', file('collection-names.csv'));

$counter = 0;
$rows = array();
// include('dataset-directory-header.tpl.php');
foreach ($csv as $id => $row) {
  $id = $row[0];
  $collectionId = $row[1];

  $jsonFileName = $configuration['QA_R_PATH'] . '/' . $id . '.json';
  if (file_exists($jsonFileName)) {
    $stats = json_decode(file_get_contents($jsonFileName));
    $assocStat = array();
    foreach ($stats as $obj) {
      if ($obj->_row == $feature) {
        unset($obj->recMin);
        unset($obj->recMax);
        unset($obj->_row);
        $obj->id = $id;
        $obj->collectionId = $collectionId;
        $rows[$counter++] = $obj;
        break;
      }
    }
  }
}

include("index.tpl.php");
