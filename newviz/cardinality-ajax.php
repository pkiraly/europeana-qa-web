<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/newviz-ajax-config.php');
include_once($root . '/newviz/common.functions.php');

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
error_log("id: " . $id);

$version = getOrDefault('version', $configuration['DEFAULT_VERSION'],
  $configuration['version']);
$intersection = getOrDefault('intersection', NULL);

$filePrefix = ($id == 'all')
  ? $id
  : (
    (is_null($intersection) || empty($intersection) || $intersection == 'all')
    ? $type . $id
    : $intersection
  );
error_log("filePrefix: " . $filePrefix);

$entity = 'ProvidedCHO';
$allowedEntities = ['ProvidedCHO', 'Agent', 'Timespan', 'Concept', 'Place'];
if (isset($_GET['entity']) && in_array($_GET['entity'], $allowedEntities)) {
  $entity = $_GET['entity'];
}

$dataDir = getDataDir();
$smarty = createSmarty($templateDir);

$statistics = new stdClass();
readStatistics($type, $id, $entity);

$fieldProperties = [];
foreach ($fields[$entity] as $field => $label) {
  $key = strtolower($field);
  $properties = (object)[
    'field' => $field,
    'key' => strtolower($field),
    'label' => $label,
    'isMandatory' => isset($mandatory[$entity][$field]),
    'hasFrequency' => isset($statistics->frequencyTable->{$key}),
    'hasCardinality' => isset($statistics->cardinality->{$key}),
    'hasHistograms' => isset($statistics->histograms->{$key}),
    'hasMinMaxRecords' => isset($statistics->minMaxRecords->{$key}),
  ];
  if ($properties->isMandatory) {
    $properties->mandatory = $mandatory[$entity][$field];
    $properties->mandatoryIcon = getMandatoryIcon($mandatory[$entity][$field]);
  }

  if ($properties->hasFrequency) {
    $freq = $statistics->frequencyTable->{$key};
    $values = is_array($freq['values']) ? json_decode(json_encode((object)$freq['values'])) : $freq['values'];
    $properties->freqValues = json_encode($values);
    $properties->zeros = isset($values->{'0'})
           ? (int)$values->{'0'}
           : (int)$statistics->entityCount;
    $properties->nonZeros = isset($values->{'1'})
           ? (int)$values->{'1'}
           : max($statistics->entityCount - $properties->zeros, 0);
    $properties->percent = $properties->nonZeros / $statistics->entityCount;
    $properties->width = (int)(300 * $properties->percent);

    $properties->freqHtml = $freq['html'];
  }
  if ($properties->hasCardinality) {
    $properties->cardinalityHtml = $statistics->cardinality->{$key}->html;
  }
  if ($properties->hasHistograms) {
    $properties->histogramHtml = $statistics->histograms->{$key}['html'];
  }
  if ($properties->hasMinMaxRecords) {
    $properties->recMax = $statistics->minMaxRecords->{$key}->recMax;
    $properties->recMin = $statistics->minMaxRecords->{$key}->recMin;
  }

  $fieldProperties[$field] = $properties;
}

$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;
$smarty->assign('development', $development);
$smarty->assign('type', $type);
$smarty->assign('version', $version);
$smarty->assign('entity', $entity);
$smarty->assign('fields', $fieldProperties);
$smarty->assign('statistics', $statistics);
$smarty->assign('mandatory', $mandatory[$entity]);
$smarty->display('frequency-per-entity.tpl');

function getMandatoryIcon($key) {
  $mandatoryIcon = '';
  switch ($key) {
    case 'black': $mandatoryIcon = 'check'; break;
    case 'blue': $mandatoryIcon = 'arrow-right'; break;
    case 'red': $mandatoryIcon = 'circle-o'; break;
    case 'green': $mandatoryIcon = 'gear'; break;
    case 'plus': $mandatoryIcon = 'plus'; break;
    default:
      $mandatoryIcon = '';
  }
  return $mandatoryIcon;
}


function readStatistics($type, $id, $entity) {
  global $fields, $statistics, $version;

  $entityFields = array_map('strtolower', array_keys($fields[$entity]));
  $entityIDField = $entity . '_rdf_about';

  error_log(join(', ', $entityFields));

  if ($id == 'all' && $version == 'v2018-08') {
    readFromCsv($id, $entityFields, strtolower($entityIDField));

  } else {
    readFreqFileExistence($type, $id, $entityFields);
    readCardinality($type, $id, $entityFields);
    readFrequencyTable($type, $id, $entityIDField, $entityFields);
    readHistogram($type, $id, $entityFields);
    readMinMaxRecords($type, $id, $entityFields);

    // readImageFiles($type, $id, $entityFields);
  }

  return $statistics;
}

function readFromCsv($id, $entityFields, $entityIDField) {
  global $statistics, $smarty;

  error_log('entityIDField: ' . $entityIDField);
  $errors = [];
  $completeness = readCompleteness($id, $errors);
  $histogram = readHistogramFormCsv($id, $errors);

  $statistics->entityCount = $completeness[$entityIDField]['mean'] * $completeness[$entityIDField]['count'];

  foreach ($entityFields as $field) {
    if (isset($completeness[$field])) {
      $frequencyTable = (object)['entityCount' => $statistics->entityCount];
      $values = [
        1 => $completeness[$field]['mean'] * $statistics->entityCount
      ];
      $values[0] = $statistics->entityCount - $values[1];
      $frequencyTable->values = $values;

      $smarty->assign('frequencyTable', $frequencyTable);
      $html = $smarty->fetch('frequency-table.smarty.tpl');

      $statistics->frequencyTable->{$field} = [
        'values' => $values,
        'html' => $html,
      ];
    } else {
      error_log(sprintf('Field %s is not in completeness', $field));
    }

    if (isset($completeness['crd_' . $field])) {
      $stat = $completeness['crd_' . $field];
      $cardinality = (object)[
        'count' => $completeness[$field]['mean'] * $statistics->entityCount,
        'sum' => $stat['mean'] * $statistics->entityCount,
        'median' => -1,
        'mean' => $stat['mean']
      ];
      $smarty->assign('cardinality', $cardinality);
      $cardinality->html = $smarty->fetch('cardinality.smarty.tpl');
      $statistics->cardinality->{$field} = $cardinality;

    } else {
      error_log(sprintf('Field crd_%s is not in completeness', $field));
    }

  }

  error_log('entityFields: ' . join(', ', $entityFields));
}

function readFreqFileExistence($type, $id, $entityFields) {
  global $dataDir, $statistics, $filePrefix;

  $statistics->freqFile = $dataDir . '/json/' . $filePrefix . '/'
    . $filePrefix . '.freq.json';
  $statistics->freqFileExists = file_exists($statistics->freqFile);
}

function readCardinality($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty, $filePrefix;

  $statistics->cardinalityFile = $dataDir . '/json/' . $filePrefix . '/'
    . $filePrefix . '.cardinality.json';
  $statistics->cardinalityFileExists = file_exists($statistics->cardinalityFile);

  $cardinalityProperties = ['sum', 'mean', 'median'];
  if ($statistics->cardinalityFileExists) {
    $key = 'cardinality-property';
    $statistics->cardinalityProperty = isset($_GET[$key])
                                       && in_array($_GET[$key], $cardinalityProperties)
      ? $_GET[$key]
      : 'sum';

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
        $statistics->cardinality->{$field}->html =
          $smarty->fetch('cardinality.smarty.tpl');

        if ($statistics->cardinalityMax < $entry->sum) {
          $statistics->cardinalityMax = $entry->sum;
          $statistics->cardinalityMaxField = $field;
        }
      }
    }
  }
}

function readFrequencyTable($type, $id, $entityIDField, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty, $filePrefix;

  $statistics->frequencyTableFile = $dataDir . '/json/' . $filePrefix . '/'
    . $filePrefix . '.frequency.table.json';
  $data = new stdClass();

  if (file_exists($statistics->frequencyTableFile)) {
    $statistics->frequencyTable = json_decode(
      file_get_contents($statistics->frequencyTableFile)
    );
    $frequencyTable = (object)[];
    foreach ($statistics->frequencyTable as $key => $value) {
      $value = clearJson($value);
      if ($key != strtolower($key)) {
        unset($statistics->frequencyTable->$key);
        $key = strtolower($key);
        if ($key == strtolower($entityIDField)) {
          $statistics->entityCount = $value->{'1'};
          $data->entityCount = $statistics->entityCount;
        } else if (in_array($key, $entityFields)) {
          $data->values = $value;
          $smarty->assign('frequencyTable', $data);
          $html = $smarty->fetch('frequency-table.smarty.tpl');

          $frequencyTable->{$key} = [
            'values' => $value,
            'html' => $html,
          ];
        }
      }
    }
    $statistics->frequencyTable = $frequencyTable;
  } else {
    $statistics->frequencyTable = FALSE;
  }
}

function readHistogram($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics, $smarty, $filePrefix;

  $statistics->histFile = $dataDir . '/json/' . $filePrefix . '/'
    .  $filePrefix . '.cardinality.histogram.json';
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
    'proxy', 'agent', 'concept', '_place', 'timespan', 'haspart', 'ispartof',
    'isnextinsequence', 'europeanaproxy', 'usertag', 'proxyin', 'proxyfor',
    'conformsto', 'hasformat', 'hasversion', 'preflabel', 'altlabel', 'hasmet',
    'isrelatedto', 'biographicalinformation', 'dateofbirth', 'dateofdeath',
    'dateOfEstablishment', 'rdagr2', 'dateoftermination', 'placeofbirth',
    'placeofdeath', 'professionoroccupation', 'sameas', 'broadmatch', 'narrowmatch',
    'relatedmatch', 'exactmatch', 'closematch', 'inscheme', 'isformatof',
    'isreferencedby', 'isreplacedby', 'isrequiredby', 'isversionof',
    'tableofcontents', 'currentlocation', 'hastype', 'isderivativeof',
    'isrepresentationof', 'issimilarto', 'issuccessorof'
  ];
  $target  = [
    'Proxy', 'Agent', 'Concept', '_Place', 'Timespan', 'hasPart', 'isPartOf',
    'isNextInSequence', 'europeanaProxy', 'userTag', 'ProxyIn', 'ProxyFor',
    'conformsTo', 'hasFormat', 'hasVersion', 'prefLabel', 'altLabel', 'hasMet',
    'isRelatedTo', 'biographicalInformation', 'dateOfBirth', 'dateOfDeath',
    'dateofestablishment', 'rdaGr2', 'dateOfTermination', 'placeOfBirth',
    'placeOfDeath', 'professionOrOccupation', 'sameAs', 'broadMatch', 'narrowMatch',
    'relatedMatch', 'exactMatch', 'closeMatch', 'inScheme', 'isFormatOf',
    'isReferencedBy', 'isReplacedBy', 'isRequiredBy', 'isVersionOf',
    'tableOfContents', 'currentLocation', 'hasType', 'isDerivativeOf',
    'isRepresentationOf', 'isSimilarTo', 'isSuccessorOf'
  ];
  $key = str_replace($subject, $target, $key);

  return $key . '_f';
}

function readMinMaxRecords($type, $id, $entityFields) {
  global $dataDir, $templateDir, $statistics, $filePrefix;

  $statistics->minMaxRecordsFile = $dataDir . '/json/' . $filePrefix . '/'
    .  $filePrefix . '.json';
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
  global $statistics, $filePrefix;

  foreach ($entityFields as $name) {
    $fileName = sprintf('img/%s/%s-%s.png', $filePrefix, $filePrefix, $name);
    $statistics->images[$name]['frequency'] = [
      'exists' => file_exists('../' . $fileName),
      'fileName' => $fileName,
      'html' => sprintf('<img src="%s" height="300" />', $fileName),
    ];

    $fileName = sprintf('img/%s/%s-crd_%s.png', $filePrefix, $filePrefix, $name);
    $statistics->images[$name]['cardinality'] = [
      'exists' => file_exists('../' . $fileName),
      'fileName' => $fileName,
      'html' => sprintf('<img src="%s" height="300" />', $fileName),
    ];
  }
}

/**
 * @param $filePrefix
 * @param $errors
 * @param $dataDir
 * @return array
 */
function readCompleteness($filePrefix, &$errors) {
  global $dataDir;
  static $completeness;

  if (!isset($completeness)) {
    $completeness = [];
    $completenessFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . '.completeness.csv';
    error_log($completenessFileName);
    if (file_exists($completenessFileName)) {
      $keys = ["mean", "min", "max", "count", "median"];
      foreach (file($completenessFileName) as $line) {
        $values = str_getcsv($line);
        array_shift($values);
        $field = array_shift($values);
        $assoc = array_combine($keys, $values);
        $completeness[strtolower($field)] = $assoc;
      }
    } else {
      $msg = sprintf("file %s is not existing", $completenessFileName);
      $errors[] = $msg;
      error_log($msg);
    }
  }

  return $completeness;
}

function readHistogramFormCsv($filePrefix, &$errors) {
  global $dataDir;
  static $histogram;

  if (!isset($histogram)) {
    $histogram = [];
    $histogramFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . '.completeness-histogram.csv';
    error_log($histogramFileName);
    if (file_exists($histogramFileName)) {
      $keys = ["id", "field", "entries"];
      foreach (file($histogramFileName) as $line) {
        $values = str_getcsv($line);
        $values = array_combine($keys, $values);
        $field = strtolower($values['field']);
        // error_log('entries: ' . $values['entries']);
        $raw_entries = explode(';', $values['entries']);
        $entries = [];
        foreach ($raw_entries as $raw) {
          list($min_max, $count) = explode(':', $raw);
          list($min, $max) = explode('-', $min_max);
          $entries[] = ['min' => $min, 'max' => $max, 'count' => $count];
        }
        $histogram[$field] = $entries;
      }
    } else {
      $msg = sprintf("file %s is not existing", $histogramFileName);
      $errors[] = $msg;
      error_log($msg);
    }
  }

  return $histogram;
}

function clearJson($values) {
  $data = (object)[];
  if (gettype($values) == 'object') {
    foreach (get_object_vars($values) as $key => $value) {
      $data->{(string)$key} = $value[0];
    }
  } else {
    foreach ($values as $key => $value) {
      $data->{(string)$key} = $value[0];
    }
  }
  return $data;
}