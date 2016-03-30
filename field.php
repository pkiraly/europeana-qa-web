<?php
$configuration = parse_ini_file('config.cfg');

$fields = [
  'identifier' => 'identifier',
  'proxy_dc_title' => 'Proxy / dc:title',
  'proxy_dcterms_alternative' => 'Proxy / dcterms:alternative',
  'proxy_dc_description' => 'Proxy / dc:description',
  'proxy_dc_creator' => 'Proxy / dc:creator',
  'proxy_dc_publisher' => 'Proxy / dc:publisher',
  'proxy_dc_contributor' => 'Proxy / dc:contributor',
  'proxy_dc_type' => 'Proxy / dc:type',
  'proxy_dc_identifier' => 'Proxy / dc:identifier',
  'proxy_dc_language' => 'Proxy / dc:language',
  'proxy_dc_coverage' => 'Proxy / dc:coverage',
  'proxy_dcterms_temporal' => 'Proxy / dcterms:temporal',
  'proxy_dcterms_spatial' => 'Proxy / dcterms:spatial',
  'proxy_dc_subject' => 'Proxy / dc:subject',
  'proxy_dc_date' => 'Proxy / dc:date',
  'proxy_dcterms_created' => 'Proxy / dcterms:created',
  'proxy_dcterms_issued' => 'Proxy / dcterms:issued',
  'proxy_dcterms_extent' => 'Proxy / dcterms:extent',
  'proxy_dcterms_medium' => 'Proxy / dcterms:medium',
  'proxy_dcterms_provenance' => 'Proxy / dcterms:provenance',
  'proxy_dcterms_hasPart' => 'Proxy / dcterms:hasPart',
  'proxy_dcterms_isPartOf' => 'Proxy / dcterms:isPartOf',
  'proxy_dc_format' => 'Proxy / dc:format',
  'proxy_dc_source' => 'Proxy / dc:source',
  'proxy_dc_rights' => 'Proxy / dc:rights',
  'proxy_dc_relation' => 'Proxy / dc:relation',
  'proxy_edm_isNextInSequence' => 'Proxy / edm:isNextInSequence',
  'proxy_edm_type' => 'Proxy / edm:type',
  'aggregation_edm_rights' => 'Aggregation / edm:rights',
  'aggregation_edm_provider' => 'Aggregation / edm:provider',
  'aggregation_edm_dataProvider' => 'Aggregation / edm:dataProvider',
  'aggregation_edm_isShownAt' => 'Aggregation / edm:isShownAt',
  'aggregation_edm_isShownBy' => 'Aggregation / edm:isShownBy',
  'aggregation_edm_object' => 'Aggregation / edm:object',
  'aggregation_edm_hasView' => 'Aggregation / edm:hasView',
];

$field = $_GET['field'];
if (!isset($field) || !isset($fields[$field])) {
  $field = 'identifier';
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

$excludeZeros = in_array(0, $_GET['exclusions']);
$excludeOnes = in_array(1, $_GET['exclusions']);
$excludeRest = in_array(2, $_GET['exclusions']);

// $csv = array_map('str_getcsv', file('collection-names.csv'));

function parse_csv($line) {
  return str_getcsv($line, ';');
}

$fieldSummaryFile = 'json/summary-' . $field . '-' . $prefix . '.json';
if (!file_exists($fieldSummaryFile)) {
  $csv = array_map('parse_csv', file($type . '.txt'));
  $counter = 1;
  $rows = array();
  // include('dataset-directory-header.tpl.php');
  foreach ($csv as $id => $row) {
    $id = $row[0];
    $collectionId = $row[1];

    $jsonFileName = $configuration['QA_R_PATH'] . '/json/' . $prefix . $id . '.freq.json';
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
        if ($obj->field == $field) {
          // unset($obj->recMin);
          // unset($obj->recMax);
          // unset($obj->_row);
          $obj->id = $id;
          $obj->type = $prefix;
          $obj->collectionId = $collectionId;
          $rows[] = $obj;
          break;
        }
      }
    }
  }
  file_put_contents($fieldSummaryFile, json_encode($rows));
}

include("field.tpl.php");
