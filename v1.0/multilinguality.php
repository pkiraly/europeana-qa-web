<?php

include('multilinguality.functions.php');
$configuration = parse_ini_file('../config.cfg');

$graphs = [
  'saturation_normalized_aggregation_edm_rights' => [
    'label' => 'Multilingual saturation of Aggregation/edm:rights (normalized average)'
  ],
];

$fields = [
  'languages_per_property' => 'languages per property',
  'taggedliterals' => 'tagged literals',
  'distinctlanguages' => 'distinct languages',
  'taggedliterals_per_language' => 'tagged literals per language'
];

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $collectionId = $type = "";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  if (isset($_GET['name']))
    $collectionId = $_GET['name'];
  if (isset($_GET['type']))
    $type = $_GET['type'];
} else {
  if (isset($argv)) {
    $collectionId = $argv[1];
    $id = strstr($collectionId, '_', true);
  }
  $type = 'c';
}

if ($id == '')
  $id = 'all';

if ($id == 'all')
  $type = '';

$message = $type . $id;

$n = 0;
$jsonCountFileName = 'json/' . $type . $id . '/' . $type . $id . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

$assocStat = [];
$saturationFile = 'json/' . $type . $id . '/' . $type . $id . '.saturation.json';
$saturationFileExists = file_exists($saturationFile);
if ($saturationFileExists) {
  $saturation = json_decode(file_get_contents($saturationFile));
  foreach ($saturation as $obj) {
    $key = preg_replace('/^saturation2_/', '', $obj->_row);
    // unset($obj->_row);

    $prefix = 'generic';
    if (preg_match('/^europeana_/', $key)) {
      $key = preg_replace('/^europeana_/', '', $key);
      $prefix = 'europeana';
    } else if (preg_match('/^provider_/', $key)) {
      $key = preg_replace('/^provider_/', '', $key);
      $prefix = 'provider';
    }

    if ($prefix == 'generic') {
      if (preg_match('/_in_providerproxy$/', $key)) {
        $prefix = 'provider';
      } else if (preg_match('/_in_europeanaproxy$/', $key)) {
        $prefix = 'europeana';
      } else if (preg_match('/_in_object$/', $key)) {
        $prefix = 'object';
      }
      $key = preg_replace('/_in_.*$/', '', $key);
      $assocStat['generic'][$key][$prefix] = $obj;
    } else {
      $fields[$key] = getLabel($key);
      $specific_type = "";
      if (preg_match('/_taggedliterals/', $key)) {
        $specific_type = 'taggedliterals';
      } else if (preg_match('/_languages/', $key)) {
        $specific_type = 'languages';
      } else if (preg_match('/_literalsperlanguage/', $key)) {
        $specific_type = 'literalsperlanguage';
      }

      $assocStat['specific'][$specific_type][$key][$prefix] = $obj;
    }
  }
}

$specific_types = [
  'taggedliterals' => 'Tagged literals',
  'languages' => 'Languages',
  'literalsperlanguage' => 'Literals per languages'
];

$saturationHistFile = 'json/' . $type . $id . '/' . $type . $id . '.saturation.histogram.json';
if (file_exists($saturationHistFile)) {
  $histograms = (array)json_decode(file_get_contents($saturationHistFile));
}

$saturationNormalizedHistFile = 'json/' . $type . $id . '/' . $type . $id . '.saturation.normalized-histogram.json';
if (file_exists($saturationNormalizedHistFile)) {
  $normalizedHistograms = (array)json_decode(file_get_contents($saturationNormalizedHistFile));
}

$frequencyTable = (object)[];
$saturationFrequencyTableFile = 'json/' . $type . $id . '/' . $type . $id . '.saturation.frequency.table.json';
if (file_exists($saturationFrequencyTableFile)) {
  $saturationFrequencyTable = json_decode(file_get_contents($saturationFrequencyTableFile));
  foreach ($saturationFrequencyTable as $key => $value) {
    $frequencyTable->{strtolower($key)} = $value;
  }
}

$generic_prefixes = [
  'provider' => 'In provider proxy',
  'europeana' => 'In Europeana proxy',
  'object' => 'In object'
];
$specific_prefixes = ['provider', 'europeana'];

ob_start();
include('../templates/multilinguality/multilinguality.tpl.php');
$content = ob_get_contents();
ob_end_clean();

if (isset($id)) {
  echo $content;
} else {
  file_put_contents($id . '.html', $content);
}