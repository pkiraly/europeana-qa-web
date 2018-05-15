<?php

$configuration = parse_ini_file('../config.cfg');
include_once('newviz-ajax-config.php');
include_once('common.functions.php');

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $entity = $type = "";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

if (isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  list($id, $type) = parseId($id);
}

$entity = 'ProvidedCHO';
$allowedEntities = ['ProvidedCHO', 'Agent', 'Timespan', 'Concept', 'Place'];
if (isset($_GET['entity']) && in_array($_GET['entity'], $allowedEntities)) {
  $entity = $_GET['entity'];
}

$dataDir = '../' . getDataDir();

$smarty = createSmarty($templateDir);
/*
define('SMARTY_DIR', getcwd() . '/../libs/smarty-3.1.32/libs/');
define('_SMARTY', getcwd() . '/../libs/_smarty/');
require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir(getcwd() . '/' . $templateDir);
$smarty->setCompileDir(_SMARTY . '/templates_c/');
$smarty->setConfigDir(_SMARTY . '/configs/');
$smarty->setCacheDir(_SMARTY . '/cache/');
$smarty->addPluginsDir(getcwd() . '/../common/smarty_plugins/');
// standard PHP function
$smarty->registerPlugin("modifier", "str_replace", "str_replace");
$smarty->registerPlugin("modifier", "number_format", "number_format");
*/

$statistics = new stdClass();
readStatistics($type, $id, $entity);

header("Content-type: application/json");
echo json_encode([
  'version' => $version,
  'entity' => $entity,
  'fields' => $fields[$entity],
  'statistics' => $statistics,
  'mandatory' => $mandatory[$entity],
]);

function readStatistics($type, $id, $entity) {
  global $fields, $statistics;

  $entityFields = array_map('strtolower', array_keys($fields[$entity]));
  $entityIDField = $entity . '_rdf_about';

  readFreqFileExistence($type, $id, $entityFields);
  readCardinality($type, $id, $entityFields);
  readFrequencyTable($type, $id, $entityIDField, $entityFields);
  readHistogram($type, $id, $entityFields);
  readMinMaxRecords($type, $id, $entityFields);

  readImageFiles($type, $id, $entityFields);
  return $statistics;
}

// concept_rdf_about
function readFreqFileExistence($type, $id, $entityFields) {
  global $dataDir, $statistics;

  $statistics->freqFile = $dataDir . '/json/' . $type . $id . '/' . $type . $id . '.freq.json';
  $statistics->freqFileExists = file_exists($statistics->freqFile);
}

function readCardinality($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty;

  $statistics->cardinalityFile = $dataDir . '/json/' . $type . $id . '/' . $type . $id . '.cardinality.json';
  $statistics->cardinalityFileExists = file_exists($statistics->cardinalityFile);

  $cardinalityProperties = ['sum', 'mean', 'median'];
  if ($statistics->cardinalityFileExists) {
    $key = 'cardinality-property';
    $statistics->cardinalityProperty = isset($_GET[$key]) && in_array($_GET[$key], $cardinalityProperties)
      ? $_GET[$key] : 'sum';

    $cardinality = json_decode(file_get_contents($statistics->cardinalityFile));
    $statistics->cardinalityMax = 0;
    if (!isset($statistics->cardinality))
      $statistics->cardinality = (object)[];
    foreach ($cardinality as $entry) {
      if (in_array($entry->field, $entityFields)) {
        $field = $entry->field;
        unset($entry->field);
        $statistics->cardinality->{$field} = $entry;
        $smarty->assign('cardinality', $entry);
        $statistics->cardinality->{$field}->html = $smarty->fetch('cardinality.smarty.tpl');

        if ($statistics->cardinalityMax < $entry->sum) {
          $statistics->cardinalityMax = $entry->sum;
          $statistics->cardinalityMaxField = $field;
        }
      }
    }
  }
}

function readFrequencyTable($type, $id, $entityIDField, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty;

  $statistics->frequencyTableFile = $dataDir . '/json/' . $type . $id . '/' . $type . $id . '.frequency.table.json';
  $data = new stdClass();

  if (file_exists($statistics->frequencyTableFile)) {
    $statistics->frequencyTable = json_decode(file_get_contents($statistics->frequencyTableFile));
    foreach ($statistics->frequencyTable as $key => $value) {
      if ($key != strtolower($key)) {
        unset($statistics->frequencyTable->$key);
        $key = strtolower($key);
        // echo $key, ", ";
        if ($key == strtolower($entityIDField)) {
          $statistics->entityCount = $value->{'1'}[0];
          $data->entityCount = $statistics->entityCount;
        } else if (in_array($key, $entityFields)) {
          $data->values = $value;
          $smarty->assign('frequencyTable', $data);
          $html = $smarty->fetch('frequency-table.smarty.tpl');

          $statistics->frequencyTable->{$key} = [
            'values' => $value,
            'html' => $html,
          ];
        }
      }
    }
  } else {
    $statistics->frequencyTable = FALSE;
  }
}

function readHistogram($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty;

  // $statistics->histFile = '../json/' . $type . $id . '/' .  $type . $id . '.hist.json';
  $statistics->histFile = $dataDir . '/json/' . $type . $id . '/' .  $type . $id . '.cardinality.histogram.json';
  $statistics->histFile_exists = file_exists($statistics->histFile);
  if (file_exists($statistics->histFile)) {
    $histograms = json_decode(file_get_contents($statistics->histFile));
    if (!isset($statistics->histograms))
      $statistics->histograms = (object)[];
    $fq = sprintf("%s:%d", ($type == 'c' ? 'collection_i' : 'provider_i'), $id);
    foreach ($histograms as $key => $values) {
      $needle = str_replace('crd_', '', $key);
      if (in_array($needle, $entityFields)) {
        $data = (object)[
          'solrField' => toSolrField($key),
          'fq' => $fq,
          'field' => $needle,
          'values' => $values
        ];
        $smarty->assign('histogram', $data);
        $html = $smarty->fetch('histogram.smarty.tpl');

        $statistics->histograms->{$needle} = [
          'values' => $values,
          'html' => $html,
        ];
      }
      // unset($statistics->histograms->{$key});
    }
  } else {
    $statistics->histograms = FALSE;
  }
}

function toSolrField($key) {
  $subject = [
    'proxy', 'agent', 'concept', '_place', 'timespan', 'haspart', 'ispartof', 'isnextinsequence',
    'europeanaproxy', 'usertag', 'proxyin', 'proxyfor', 'conformsto', 'hasformat',
    'hasversion', 'preflabel', 'altlabel', 'hasmet', 'isrelatedto', 'biographicalinformation',
    'dateofbirth', 'dateofdeath', 'dateOfEstablishment', 'rdagr2', 'dateoftermination', 'placeofbirth',
    'placeofdeath', 'professionoroccupation', 'sameas', 'broadmatch', 'narrowmatch',
    'relatedmatch', 'exactmatch', 'closematch', 'inscheme', 'isformatof', 'isreferencedby',
    'isreplacedby', 'isrequiredby', 'isversionof', 'tableofcontents', 'currentlocation',
    'hastype', 'isderivativeof', 'isrepresentationof', 'issimilarto', 'issuccessorof'
  ];
  $target  = [
    'Proxy', 'Agent', 'Concept', '_Place', 'Timespan', 'hasPart', 'isPartOf', 'isNextInSequence',
    'europeanaProxy', 'userTag', 'ProxyIn', 'ProxyFor', 'conformsTo', 'hasFormat',
    'hasVersion', 'prefLabel', 'altLabel', 'hasMet', 'isRelatedTo', 'biographicalInformation',
    'dateOfBirth', 'dateOfDeath', 'dateofestablishment', 'rdaGr2', 'dateOfTermination', 'placeOfBirth',
    'placeOfDeath', 'professionOrOccupation', 'sameAs', 'broadMatch', 'narrowMatch',
    'relatedMatch', 'exactMatch', 'closeMatch', 'inScheme', 'isFormatOf', 'isReferencedBy',
    'isReplacedBy', 'isRequiredBy', 'isVersionOf', 'tableOfContents', 'currentLocation',
    'hasType', 'isDerivativeOf', 'isRepresentationOf', 'isSimilarTo', 'isSuccessorOf'
  ];
  $key = str_replace($subject, $target, $key);

  return $key . '_f';
}

function readMinMaxRecords($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics;

  $statistics->minMaxRecordsFile = $dataDir . '/json/' . $type . $id . '/' .  $type . $id . '.json';
  if (file_exists($statistics->minMaxRecordsFile)) {
    $histograms = json_decode(file_get_contents($statistics->minMaxRecordsFile));
    if (!isset($statistics->minMaxRecords))
      $statistics->minMaxRecords = (object)[];
    foreach ($histograms as $key => $value) {
      // $statistics->minMaxKeys[] = $value->{'_row'};
      $needle = str_replace('crd_', '', $value->{'_row'});
      if (in_array($needle, $entityFields)) {
        // $statistics->minMaxRecords->{$needle} = $value;
        $statistics->minMaxRecords->{$needle} = (object)[
          'recMin' => $value->recMin,
          'recMax' => $value->recMax,
        ];
        /*
        */
      }
      // unset($statistics->histograms->{$key});
    }
  } else {
    $statistics->minMaxRecords = FALSE;
  }
}

function readImageFiles($type, $id, $entityFields) {
  global $statistics;

  foreach ($entityFields as $name) {
    $fileName = sprintf('img/%s%s/%s%s-%s.png', $type, $id, $type, $id, $name);
    $statistics->images[$name]['frequency'] = [
      'exists' => file_exists('../' . $fileName),
      'fileName' => $fileName,
      'html' => sprintf('<img src="%s" height="300" />', $fileName),
    ];

    $fileName = sprintf('img/%s%s/%s%s-crd_%s.png', $type, $id, $type, $id, $name);
    $statistics->images[$name]['cardinality'] = [
      'exists' => file_exists('../' . $fileName),
      'fileName' => $fileName,
      'html' => sprintf('<img src="%s" height="300" />', $fileName),
    ];
  }
}

