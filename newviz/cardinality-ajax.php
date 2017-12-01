<?php

$configuration = parse_ini_file('../config.cfg');
include_once('newviz-ajax-config.php');

$title = 'Metadata Quality Assurance Framework';
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

$statistics = readStatistics($type, $id, $entity);

header("Content-type: application/json");
echo json_encode([
  'entity' => $entity,
  'fields' => $fields[$entity],
  'statistics' => $statistics,
  'mandatory' => $mandatory[$entity],
]);

function parseId($id) {
  $type = substr($id, 0, 1);
  if (in_array($type, ['c', 'd'])) {
    $id = substr($id, 1);
  } else {
    $type = 'c';
  }
  return [$id, $type];
}

function readStatistics($type, $id, $entity) {
  global $fields;

  $entityFields = array_map('strtolower', array_keys($fields[$entity]));
  $entityIDField = $entity . '_rdf_about';

  $statistics = new stdClass();
  readFreqFileExistence($type, $id, $entityFields, $statistics);
  readCardinality($type, $id, $entityFields, $statistics);
  readFrequencyTable($type, $id, $entityIDField, $entityFields, $statistics);
  readHistogram($type, $id, $entityFields, $statistics);
  readMinMaxRecords($type, $id, $entityFields, $statistics);

  readImageFiles($type, $id, $entityFields, $statistics);
  return $statistics;
}

// concept_rdf_about
function readFreqFileExistence($type, $id, $entityFields, &$statistics) {
  $statistics->freqFile = '../json/' . $type . $id . '.freq.json';
  $statistics->freqFileExists = file_exists($statistics->freqFile);
}

function readCardinality($type, $id, $entityFields, &$statistics) {
  global $templateDir;

  $statistics->cardinalityFile = '../json/' . $type . $id . '.cardinality.json';
  $statistics->cardinalityFileExists = file_exists($statistics->cardinalityFile);
  $cardinalityProperties = ['sum', 'mean', 'median'];
  if ($statistics->cardinalityFileExists) {
    $key = 'cardinality-property';
    $statistics->cardinalityProperty = isset($_GET[$key]) && in_array($_GET[$key], $cardinalityProperties)
      ? $_GET[$key] : 'sum';

    $cardinality = json_decode(file_get_contents($statistics->cardinalityFile));
    $statistics->cardinalityMax = 0;
    foreach ($cardinality as $entry) {
      if (in_array($entry->field, $entityFields)) {
        $field = $entry->field;
        unset($entry->field);
        $statistics->cardinality->{$field} = $entry;
        $statistics->cardinality->{$field}->html = callTemplate($entry, $templateDir . 'newviz-cardinality.tpl.php');
        if ($statistics->cardinalityMax < $entry->sum) {
          $statistics->cardinalityMax = $entry->sum;
          $statistics->cardinalityMaxField = $field;
        }
      }
    }
  }
}

function readFrequencyTable($type, $id, $entityIDField, $entityFields, &$statistics) {
  global $templateDir;

  $statistics->frequencyTableFile = '../json/' . $type . $id . '.frequency.table.json';
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
          $statistics->frequencyTable->{$key} = [
            'values' => $value,
            'html' => callTemplate($data, $templateDir . 'newviz-frequency-table.tpl.php'),
          ];
        }
      }
    }
  } else {
    $statistics->frequencyTable = FALSE;
  }
}

function readHistogram($type, $id, $entityFields, &$statistics) {
  global $templateDir;

  $statistics->histFile = '../json/' . $type . $id . '.hist.json';
  if (file_exists($statistics->histFile)) {
    $histograms = json_decode(file_get_contents($statistics->histFile));
    foreach ($histograms as $key => $values) {
      $needle = str_replace('crd_', '', $key);
      if (in_array($needle, $entityFields)) {
        $data = (object)[
          'field' => $needle,
          'values' => $values
        ];
        $statistics->histograms->{$needle} = [
          'values' => $values,
          'html' => callTemplate($data, $templateDir . 'newviz-histogram.tpl.php'),
        ];
      }
      // unset($statistics->histograms->{$key});
    }
  } else {
    $statistics->histograms = FALSE;
  }
}

function readMinMaxRecords($type, $id, $entityFields, &$statistics) {
  global $templateDir;

  $statistics->minMaxRecordsFile = '../json/' . $type . $id . '.json';
  if (file_exists($statistics->minMaxRecordsFile)) {
    $histograms = json_decode(file_get_contents($statistics->minMaxRecordsFile));
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

function readImageFiles($type, $id, $entityFields, &$statistics) {
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

function callTemplate($data, $file) {
  ob_start();
  include($file);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
