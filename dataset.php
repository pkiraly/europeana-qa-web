<?php

$configuration = parse_ini_file('config.cfg');

$graphs = array(
  'total' => array('label' => 'Every fields', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dcterms:alternative",
  	"Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:identifier",
  	"Proxy/dc:language", "Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dc:date",
  	"Proxy/dcterms:created", "Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:format", "Proxy/dc:source", "Proxy/dc:rights", "Proxy/dc:relation",
  	"Proxy/edm:isNextInSequence", "Proxy/edm:type", "Proxy/edm:rights", "Aggregation/edm:rights", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object", "Aggregation/edm:hasView")),
  'mandatory' => array('label' => 'Mandatory elements', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/edm:rights", "Aggregation/edm:rights",
  	"Aggregation/edm:provider", "Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy")),
  'descriptiveness' => array('label' => 'Descriptiveness', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:language", "Proxy/dc:subject", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dc:format", "Proxy/dc:source")),
  'searchability' => array('label' => 'Searchability', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:provider", "Aggregation/edm:dataProvider")),
  'contextualization' => array('label' => 'Contextualization', 'fields' => array("Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:type",
  	"Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf",
  	"Proxy/dc:relation", "Proxy/edm:isNextInSequence")),
  'identification' => array('label' => 'Identification', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:identifier", "Proxy/dc:date", "Proxy/dcterms:created", "Proxy/dcterms:issued", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider")),
  'browsing' => array('label' => 'Browsing', 'fields' => array("Proxy/dc:creator", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:date", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:hasView")),
  'viewing' => array('label' => 'Viewing', 'fields' => array("Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object",
  	"Aggregation/edm:hasView")),
  'reusability' => array('label' => 'Re-usability', 'fields' => array("Proxy/dc:publisher", "Proxy/dc:date", "Proxy/dcterms:created",
  	"Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dc:format", "Proxy/dc:rights", "Proxy/edm:rights",
  	"Aggregation/edm:rights", "Aggregation/edm:isShownBy", "Aggregation/edm:object")),
  'multilinguality' => array('label' => 'Multilinguality', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:language", "Proxy/dc:subject")),
  'entropy_dc_title_sum' => array('label' => 'dc:title entropy - cumulative'),
  'entropy_dc_title_avg' => array('label' => 'dc:title entropy - average'),
  'entropy_dcterms_alternative_sum' => array('label' => 'dcterms:alternative entropy - cumulative'),
  'entropy_dcterms_alternative_avg' => array('label' => 'dctersm:alternative entorpy - average'),
  'entropy_dc_description_sum' => array('label' => 'dc:description entropy - cumulative'),
  'entropy_dc_description_avg' => array('label' => 'dc:description entorpy - average'),
  'crd_identifier' => array('label' => 'Cardinality of identifier'),
  'crd_proxy_dc_title' => array('label' => 'Cardinality of Proxy/dc:title'),
  'crd_proxy_dcterms_alternative' => array('label' => 'Cardinality of Proxy/dcterms:alternative'),
  'crd_proxy_dc_description' => array('label' => 'Cardinality of Proxy/dc:description'),
  'crd_proxy_dc_creator' => array('label' => 'Cardinality of Proxy/dc:creator'),
  'crd_proxy_dc_publisher' => array('label' => 'Cardinality of Proxy/dc:publisher'),
  'crd_proxy_dc_contributor' => array('label' => 'Cardinality of Proxy/dc:contributor'),
  'crd_proxy_dc_type' => array('label' => 'Cardinality of Proxy/dc:type'),
  'crd_proxy_dc_identifier' => array('label' => 'Cardinality of Proxy/dc:identifier'),
  'crd_proxy_dc_language' => array('label' => 'Cardinality of Proxy/dc:language'),
  'crd_proxy_dc_coverage' => array('label' => 'Cardinality of Proxy/dc:coverage'),
  'crd_proxy_dcterms_temporal' => array('label' => 'Cardinality of Proxy/dcterms:temporal'),
  'crd_proxy_dcterms_spatial' => array('label' => 'Cardinality of Proxy/dcterms:spatial'),
  'crd_proxy_dc_subject' => array('label' => 'Cardinality of Proxy/dc:subject'),
  'crd_proxy_dc_date' => array('label' => 'Cardinality of Proxy/dc:date'),
  'crd_proxy_dcterms_created' => array('label' => 'Cardinality of Proxy/dcterms:created'),
  'crd_proxy_dcterms_issued' => array('label' => 'Cardinality of Proxy/dcterms:issued'),
  'crd_proxy_dcterms_extent' => array('label' => 'Cardinality of Proxy/dcterms:extent'),
  'crd_proxy_dcterms_medium' => array('label' => 'Cardinality of Proxy/dcterms:medium'),
  'crd_proxy_dcterms_provenance' => array('label' => 'Cardinality of Proxy/dcterms:provenance'),
  'crd_proxy_dcterms_hasPart' => array('label' => 'Cardinality of Proxy/dcterms:hasPart'),
  'crd_proxy_dcterms_isPartOf' => array('label' => 'Cardinality of Proxy/dcterms:isPartOf'),
  'crd_proxy_dc_format' => array('label' => 'Cardinality of Proxy/dc:format'),
  'crd_proxy_dc_source' => array('label' => 'Cardinality of Proxy/dc:source'),
  'crd_proxy_dc_rights' => array('label' => 'Cardinality of Proxy/dc:rights'),
  'crd_proxy_dc_relation' => array('label' => 'Cardinality of Proxy/dc:relation'),
  'crd_proxy_edm_isNextInSequence' => array('label' => 'Cardinality of Proxy/edm:isNextInSequence'),
  'crd_proxy_edm_type' => array('label' => 'Cardinality of Proxy/edm:type'),
  'crd_aggregation_edm_rights' => array('label' => 'Cardinality of Aggregation/edm:rights'),
  'crd_aggregation_edm_provider' => array('label' => 'Cardinality of Aggregation/edm:provider'),
  'crd_aggregation_edm_dataProvider' => array('label' => 'Cardinality of Aggregation/edm:dataProvider'),
  'crd_aggregation_edm_isShownAt' => array('label' => 'Cardinality of Aggregation/edm:isShownAt'),
  'crd_aggregation_edm_isShownBy' => array('label' => 'Cardinality of Aggregation/edm:isShownBy'),
  'crd_aggregation_edm_object' => array('label' => 'Cardinality of Aggregation/edm:object'),
  'crd_aggregation_edm_hasView' => array('label' => 'Cardinality of Aggregation/edm:hasView'),
  'long_subject' => array('label' => 'Metadata problem - Long subject'),
  'same_title_and_description' => array('label' => 'Metadata problem - title and description are the same'),
  'empty_string' => array('label' => 'Metadata problem - empty field')
);

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
  'aggregated' => 'All fields aggregated'
];

$title = 'Metadata Quality Assurance Framework';
if (isset($_GET['id'])) {
  $collectionId = $_GET['name'];
  $id = $_GET['id'];
  $type = $_GET['type'];
} else {
  $collectionId = $argv[1];
  $id = strstr($collectionId, '_', true);
  $type = 'c';
}

$n = 0;
$jsonCountFileName = $configuration['QA_R_PATH'] . '/json/' . $type . $id . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

$jsonFileName = $configuration['QA_R_PATH'] . '/json/' . $type . $id . '.json';
if (!file_exists($jsonFileName)) {
  die(sprintf("File doesn't exist: %s (collection: %s)\n", $jsonFileName, $collectionId));
} else {
  if (!isset($_GET['id'])) {
    printf("Processing: %s (collection: %s)\n", $jsonFileName, $collectionId);
  }
}

$stats = json_decode(file_get_contents($jsonFileName));

$assocStat = array();
foreach ($stats as $obj) {
  $key = $obj->_row;
  unset($obj->_row);
  $assocStat[$key] = $obj;
}

$freqFile = 'json/' . $type . $id . '.freq.json';
$freqFileExists = file_exists($freqFile);

$cardinalityFile = 'json/' . $type . $id . '.cardinality.json';
$cardinalityFileExists = file_exists($cardinalityFile);
$cardinalityProperties = ['sum', 'mean', 'median'];
if ($cardinalityFileExists) {
  $key = 'cardinality-property';
  $cardinalityProperty = isset($_GET[$key]) && in_array($_GET[$key], $cardinalityProperties) ? $_GET[$key] : 'sum';
}

$histFile = 'json/' . $type . $id . '.hist.json';
if (file_exists($histFile)) {
  $histograms = json_decode(file_get_contents($histFile));
} else {
  $histograms = FALSE;
}

$languageFile = 'json/' . $type . $id . '.languages.json';
if (file_exists($languageFile)) {
  $languages = json_decode(file_get_contents($languageFile));
} else {
  $languages = FALSE;
}

ob_start();
include('dataset.tpl.php');
$content = ob_get_contents();
ob_end_clean();

if (isset($_GET['id'])) {
  echo $content;
} else {
  file_put_contents($id . '.html', $content);
}
