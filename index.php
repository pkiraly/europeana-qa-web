<?php
$configuration = parse_ini_file('config.cfg');

$features = array(
  'total' => 'Every field',
  'mandatory' => 'Mandatory',
  'descriptiveness' => 'Descriptiveness',
  'searchability' => 'Searchability',
  'contextualization' => 'Contextualization',
  'identification' => 'Identification',
  'browsing' => 'Browsing',
  'viewing' => 'Viewing',
  'reusability' => 'Reusability',
  'multilinguality' => 'Multilinguality',
  'entropy_dc_title_sum' => 'dc:title entropy - cumulative',
  'entropy_dc_title_avg' => 'dc:title entropy - average',
  'entropy_dcterms_alternative_sum' => 'dcterms:alternative entropy - cumulative',
  'entropy_dcterms_alternative_avg' => 'dctersm:alternative entorpy - average',
  'entropy_dc_description_sum' => 'dc:description entropy - cumulative',
  'entropy_dc_description_avg' => 'dc:description entorpy - average',
  'crd_identifier' => 'Cardinality of identifier',
  'crd_proxy_dc_title' => 'Cardinality of Proxy/dc:title',
  'crd_proxy_dcterms_alternative' => 'Cardinality of Proxy/dcterms:alternative',
  'crd_proxy_dc_description' => 'Cardinality of Proxy/dc:description',
  'crd_proxy_dc_creator' => 'Cardinality of Proxy/dc:creator',
  'crd_proxy_dc_publisher' => 'Cardinality of Proxy/dc:publisher',
  'crd_proxy_dc_contributor' => 'Cardinality of Proxy/dc:contributor',
  'crd_proxy_dc_type' => 'Cardinality of Proxy/dc:type',
  'crd_proxy_dc_identifier' => 'Cardinality of Proxy/dc:identifier',
  'crd_proxy_dc_language' => 'Cardinality of Proxy/dc:language',
  'crd_proxy_dc_coverage' => 'Cardinality of Proxy/dc:coverage',
  'crd_proxy_dcterms_temporal' => 'Cardinality of Proxy/dcterms:temporal',
  'crd_proxy_dcterms_spatial' => 'Cardinality of Proxy/dcterms:spatial',
  'crd_proxy_dc_subject' => 'Cardinality of Proxy/dc:subject',
  'crd_proxy_dc_date' => 'Cardinality of Proxy/dc:date',
  'crd_proxy_dcterms_created' => 'Cardinality of Proxy/dcterms:created',
  'crd_proxy_dcterms_issued' => 'Cardinality of Proxy/dcterms:issued',
  'crd_proxy_dcterms_extent' => 'Cardinality of Proxy/dcterms:extent',
  'crd_proxy_dcterms_medium' => 'Cardinality of Proxy/dcterms:medium',
  'crd_proxy_dcterms_provenance' => 'Cardinality of Proxy/dcterms:provenance',
  'crd_proxy_dcterms_hasPart' => 'Cardinality of Proxy/dcterms:hasPart',
  'crd_proxy_dcterms_isPartOf' => 'Cardinality of Proxy/dcterms:isPartOf',
  'crd_proxy_dc_format' => 'Cardinality of Proxy/dc:format',
  'crd_proxy_dc_source' => 'Cardinality of Proxy/dc:source',
  'crd_proxy_dc_rights' => 'Cardinality of Proxy/dc:rights',
  'crd_proxy_dc_relation' => 'Cardinality of Proxy/dc:relation',
  'crd_proxy_edm_isNextInSequence' => 'Cardinality of Proxy/edm:isNextInSequence',
  'crd_proxy_edm_type' => 'Cardinality of Proxy/edm:type',
  'crd_aggregation_edm_rights' => 'Cardinality of Aggregation/edm:rights',
  'crd_aggregation_edm_provider' => 'Cardinality of Aggregation/edm:provider',
  'crd_aggregation_edm_dataProvider' => 'Cardinality of Aggregation/edm:dataProvider',
  'crd_aggregation_edm_isShownAt' => 'Cardinality of Aggregation/edm:isShownAt',
  'crd_aggregation_edm_isShownBy' => 'Cardinality of Aggregation/edm:isShownBy',
  'crd_aggregation_edm_object' => 'Cardinality of Aggregation/edm:object',
  'crd_aggregation_edm_hasView' => 'Cardinality of Aggregation/edm:hasView',
  'long_subject' => 'Metadata problem - Long subject',
  'same_title_and_description' => 'Metadata problem - title and description are the same',
  'empty_string' => 'Metadata problem - empty field'
);

$feature = isset($_GET) && isset($_GET['feature']) ? $_GET['feature'] : 'total';
if (!isset($feature) || !isset($features[$feature])) {
  $feature = 'total';
}

$types = [
  'data-providers' => 'Data providers',
  'datasets' => 'Data sets'
];
$type = isset($_GET) && isset($_GET['type']) ? $_GET['type'] : 'data-providers';
if (!isset($type) || !isset($types[$type])) {
  $type = 'data-providers';
}
$prefix = $type == 'datasets' ? 'c' : 'd';

// $csv = array_map('str_getcsv', file('collection-names.csv'));

function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($type . '.txt'));

$summaryFile = 'json_cache/index-summary-' . $feature . '-' . $prefix . '.json';
$rows = [];
if (!file_exists($summaryFile)) {
  $counter = 1;
  foreach ($csv as $id => $row) {
    $id = $row[0];
    $collectionId = $row[1];

    $n = 0;
    $jsonCountFileName = $configuration['QA_R_PATH'] . '/json/' . $prefix . $id . '.count.json';
    if (file_exists($jsonCountFileName)) {
      $stats = json_decode(file_get_contents($jsonCountFileName));
      $n = $stats[0]->count;
    }

    $jsonFileName = $configuration['QA_R_PATH'] . '/json/' . $prefix . $id . '.json';
    if (file_exists($jsonFileName)) {
      if ($counter == 1) {
        // echo 'jsonFileName: ', $jsonFileName, "\n";
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
          $obj->n = $n;
          $obj->id = $id;
          $obj->type = $prefix;
          $obj->collectionId = $collectionId;
          $rows[$counter++] = $obj;
          break;
        }
      }
    }
  }
  // echo 'count: ', count($rows), "\n";
  file_put_contents($summaryFile, json_encode($rows));
} else {
  $rows = json_decode(file_get_contents($summaryFile));
}

include("index.tpl.php");
