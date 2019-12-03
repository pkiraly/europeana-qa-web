<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/common/common-functions.php');
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

$version = getOrDefault('version', $configuration['DEFAULT_VERSION'],
  $configuration['version']);
$intersection = getOrDefault('intersection', NULL);
$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;

$filePrefix = ($id == 'all')
  ? $id
  : (
    (is_null($intersection) || empty($intersection) || $intersection == 'all')
    ? (
        in_array($type, ['cn', 'l', 'pd', 'p', 'cd'])
        ? $type . '-' . $id
        : $type . $id
      )
    : $intersection
  );

$handler = 'json-v1';
if ($version >= 'v2018-08') {
  $handler = 'csv-v2-proxy-based';
}

$allowedEntities = ['ProvidedCHO', 'Agent', 'Timespan', 'Concept', 'Place'];
$entity = getOrDefault('entity', 'ProvidedCHO', $allowedEntities);

$dataDir = getDataDir();
$smarty = createSmarty($templateDir);
$proxies = ['provider', 'europeana'];

$statistics = new stdClass();
readStatistics($type, $id, $entity, $filePrefix);

// $statistics: {
//  "entityCount",
//  "frequencyTable": ["values", "html"],
//  "cardinality": ["count","sum","median","mean","html"]
// }

$facetables = [
  'dc:contributor', 'dc:coverage', 'dc:creator', 'dc:date',
  'dc:identifier', 'dc:language', 'dc:rights', 'dc:source', 'dc:type'
];

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
  $properties->facetable = in_array($label, $facetables);

  if ($properties->isMandatory) {
    $properties->mandatory = $mandatory[$entity][$field];
    $properties->mandatoryIcon = getMandatoryIcon($mandatory[$entity][$field]);
  }

  if ($properties->hasFrequency) {
    if ($handler == 'csv-v2-proxy-based') {
      $properties->freqValues = new stdClass();
      $properties->zeros = new stdClass();
      $properties->nonZeros = new stdClass();
      $properties->percent = new stdClass();
      $properties->width = new stdClass();
      $properties->freqHtml = new stdClass();

      foreach ($proxies as $proxy) {
        $freq = $statistics->frequencyTable->{$key}->{$proxy};

        $histogram_entries = $statistics->histograms->{$key}->{$proxy}['values'];
        if ($histogram_entries[0]->min == '0.0' && $histogram_entries[0]->max == '0.0') {
          $zeros = $histogram_entries[0]->count;
        } else {
          $zeros = 0;
        }

        $values = is_array($freq['values'])
          ? json_decode(json_encode((object)$freq['values']))
          : $freq['values'];
        $properties->freqValues->{$proxy} = json_encode($values);
        $properties->zeros->{$proxy} = $zeros;
        $properties->nonZeros->{$proxy} = $statistics->proxyCount - $zeros;
        $properties->percent->{$proxy} = $properties->nonZeros->{$proxy}
          / $statistics->proxyCount;
        $properties->width->{$proxy} = (int)(200 * $properties->percent->{$proxy});

        $properties->freqHtml->{$proxy} = $freq['html'];
      }
    } else {
      $freq = $statistics->frequencyTable->{$key};
      $values = is_array($freq['values'])
        ? json_decode(json_encode((object)$freq['values']))
        : $freq['values'];
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
  }

  if ($properties->hasCardinality) {
    if ($handler == 'csv-v2-proxy-based') {
      $properties->cardinalityHtml = new stdClass();
      foreach ($proxies as $proxy) {
        $properties->cardinalityHtml->{$proxy} = $statistics->cardinality->{$key}->{$proxy}->html;
      }
    } else {
      $properties->cardinalityHtml = $statistics->cardinality->{$key}->html;
    }
  }

  if ($properties->hasHistograms) {
    if ($handler == 'csv-v2-proxy-based') {
      $properties->histogramHtml = new stdClass();
      foreach ($proxies as $proxy) {
        $properties->histogramHtml->{$proxy} = $statistics->histograms->{$key}->{$proxy}['html'];
      }
    } else {
      $properties->histogramHtml = $statistics->histograms->{$key}['html'];
    }
  }

  if ($properties->hasMinMaxRecords) {
    $properties->recMax = $statistics->minMaxRecords->{$key}->recMax;
    $properties->recMin = $statistics->minMaxRecords->{$key}->recMin;
  }

  $fieldProperties[$field] = $properties;
}

$smarty->assign('development', $development);
$smarty->assign('type', $type);
$smarty->assign('version', $version);
$smarty->assign('entity', $entity);
$smarty->assign('fields', $fieldProperties);
$smarty->assign('statistics', $statistics);
$smarty->assign('mandatory', $mandatory[$entity]);
if ($handler == 'csv-v2-proxy-based') {
  $smarty->display('proxy-based-frequency-per-entity.tpl');
} else {
  $smarty->display('frequency-per-entity.tpl');
}

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

function readStatistics($type, $id, $entity, $filePrefix) {
  global $fields, $statistics, $version, $development;

  $entityFields = array_map('strtolower', array_keys($fields[$entity]));

  if (:$version >= 'v2018-08') {
    $proxyIDField = strtolower('PROVIDER_Proxy_rdf_about');
    $entityIDField = ($entity == 'ProvidedCHO' ? 'Proxy' : $entity) . '_rdf_about';

    readFromProxyBasedCsv($filePrefix, $entityFields, $entityIDField, $proxyIDField);
  } else {
    $entityIDField = $entity . '_rdf_about';

    readFreqFileExistence($type, $id, $entityFields);
    readCardinality($type, $id, $entityFields);
    readFrequencyTable($type, $id, $entityIDField, $entityFields);
    readHistogram($type, $id, $entityFields);
    readMinMaxRecords($type, $id, $entityFields);

    // readImageFiles($type, $id, $entityFields);
  }

  return $statistics;
}

function readFromCsv($filePrefix, $entityFields, $entityIDField) {
  global $statistics, $smarty;

  $errors = [];
  $completeness = readCompleteness($filePrefix, $errors);
  $histogram = readHistogramFormCsv($filePrefix, $errors);

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
      $smarty->assign('displayTitle', TRUE);
      $html = $smarty->fetch('frequency-table.smarty.tpl');

      $statistics->frequencyTable->{$field} = [
        'values' => $values,
        'html' => $html,
      ];
    } else {
      error_log(sprintf('%d) Field %s is not in completeness', __LINE__, $field));
    }

    if ($filePrefix == 'all') {
      if (isset($completeness['crd_' . $field])) {
        $stat = $completeness['crd_' . $field];
        $cardinality = (object)[
          'count' => $completeness[$field]['mean'] * $statistics->entityCount,
          'sum' => $stat['mean'] * $statistics->entityCount,
          'median' => -1,
          'mean' => $stat['mean']
        ];
        $smarty->assign('cardinality', $cardinality);
        $smarty->assign('displayMedian', FALSE);
        $smarty->assign('displayTitle', TRUE);
        $cardinality->html = $smarty->fetch('cardinality.smarty.tpl');
        $statistics->cardinality->{$field} = $cardinality;

      } else {
        error_log(sprintf('%d) Field crd_%s is not in completeness', __LINE__, $field));
      }
    } else {
      if (isset($completeness[$field])) {
        $stat = $completeness[$field];
        $cardinality = (object)[
          'count' => $completeness[$field]['mean'] * $statistics->entityCount,
          'sum' => $stat['mean'] * $statistics->entityCount,
          'median' => -1,
          'mean' => $stat['mean']
        ];
        $smarty->assign('cardinality', $cardinality);
        $smarty->assign('displayMedian', FALSE);
        $smarty->assign('displayTitle', FALSE);
        $cardinality->html = $smarty->fetch('cardinality.smarty.tpl');
        $statistics->cardinality->{$field} = $cardinality;

      } else {
        error_log(sprintf('%d) Field crd_%s is not in completeness', __LINE__, $field));
      }
    }
  }
}

function readFromProxyBasedCsv($filePrefix, $entityFields, $entityIDField, $proxyIDField) {
  global $statistics, $smarty, $proxies;

  $errors = [];
  $completeness = readCompleteness($filePrefix, $errors);
  $histogram = readHistogramFormCsv($filePrefix, $errors);

  foreach ($proxies as $proxy) {
    $key = strtolower($proxy . '_' . $entityIDField);
    $statistics->entityCount[$proxy] = isset($completeness[$key]) ? $completeness[$key]['count'] : 0;
  }

  $statistics->proxyCount = $completeness[$proxyIDField]['count'];
  $statistics->frequencyTable = new stdClass();
  $statistics->cardinality = new stdClass();
  $statistics->histograms = new stdClass();

  $smarty->assign('proxyCount', $statistics->proxyCount);
  $smarty->assign('entityCount', $statistics->entityCount);
  $smarty->assign('fq', filePrefixToFq($filePrefix));
  $smarty->registerPlugin('function', 'toSolrField', 'toSolrField');
  $smarty->registerPlugin('function', 'toSolrRangeQuery', 'toSolrRangeQuery');

  foreach ($entityFields as $field) {
    foreach ($proxies as $proxy) {
      $qualifiedField = $proxy . '_' . $field;
      $zeros = $statistics->proxyCount;
      $instances = 0;
      if (isset($completeness[$qualifiedField])) {
        $zeros = $statistics->proxyCount - $completeness[$qualifiedField]['count'];
        $instances = $completeness[$qualifiedField]['sum'];
      }

      $frequencyTable = (object)[
        'entityCount' => $statistics->proxyCount,
        'values' => [
          0 => $zeros,
          1 => $statistics->proxyCount - $zeros
        ],
        'instances' => $instances
      ];

      $smarty->assign('frequencyTable', $frequencyTable);
      $smarty->assign('displayTitle', FALSE);
      $html = $smarty->fetch('frequency-table.smarty.tpl');

      if (!isset($statistics->frequencyTable->{$field})) {
        $statistics->frequencyTable->{$field} = (object)[
          'provider' => [],
          'europeana' => []
        ];
      }
      $statistics->frequencyTable->{$field}->{$proxy} = [
        'values' => $frequencyTable->values,
        'html' => $html,
      ];

      if (isset($completeness[$qualifiedField])) {
        $stat = $completeness[$qualifiedField];
        $cardinality = (object)[
          'count' => $stat['count'],
          'sum' => $stat['sum'],
          'median' => $stat['median'],
          'mean' => $stat['mean']
        ];
      } else {
        $cardinality = (object)['count' => 0, 'sum' => 0, 'median' => 0, 'mean' => 0];
      }

      $smarty->assign('cardinality', $cardinality);
      $smarty->assign('displayMedian', TRUE);
      $smarty->assign('displayTitle', FALSE);
      $cardinality->html = $smarty->fetch('cardinality.smarty.tpl');
      if (!isset($statistics->cardinality->{$field})) {
        $statistics->cardinality->{$field} = (object)[];
      }
      $statistics->cardinality->{$field}->{$proxy} = $cardinality;

      if (isset($histogram[$qualifiedField])) {
        $smarty->assign('histogram', $histogram[$qualifiedField]);
        $smarty->assign('field', $qualifiedField);
        $smarty->assign('displayTitle', FALSE);
        $html = $smarty->fetch('proxy-based-histogram.smarty.tpl');
        if (!isset($statistics->histograms->{$field})) {
          $statistics->histograms->{$field} = (object)[];
        }
        $statistics->histograms->{$field}->{$proxy} = [
          'values' => $histogram[$qualifiedField],
          'html' => $html,
        ];
      }
    }
  }
}

function filePrefixToFq($filePrefix) {
  $map = [
    'c' => 'dataset_i',
    'd' => 'dataProvider_i',
    'p-' => 'provider_i',
    'cn-' => 'country_i',
    'l-' => 'language_i'
  ];

  if ($filePrefix == 'all') {
    $fq = '*:*';
  } else {
    if (preg_match('/^(c|d|p-|cn-|l-)(\d+)$/', $filePrefix, $matches)) {
      $fq = sprintf('%s:%d', $map[$matches[1]], $matches[2]);
    } else if (preg_match('/^(cdp)-(\d+)-(\d+)-(\d+)$/', $filePrefix, $matches)) {
      $fq = sprintf(
        'dataset_i:%d&dataProvider_i:%d&provider_i:%d',
        $matches[2], $matches[3], $matches[4]
      );
    } else {
      error_log('Unhanded filePrefix: ' . $filePrefix);
    }
  }
  return $fq;
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
        $smarty->assign('displayMedian', TRUE);
        $smarty->assign('displayTitle', TRUE);
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

  error_log('readHistogram');

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
        $smarty->assign('displayTitle', TRUE);
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
  $solrField = 'crd_' .
    preg_replace('/^provider_/', 'PROVIDER_',
      preg_replace('/^europeana_/', 'EUROPEANA_',
        str_replace($subject, $target, $key)
      )
    )
    . '_i';

  return $solrField;
}

function toSolrRangeQuery($histogramItem) {
  return sprintf("[%s TO %s]", (int)$histogramItem->min, (int)$histogramItem->max);
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
  global $dataDir, $version;
  static $completeness;

  if (!isset($completeness)) {
    $completeness = [];
    $suffix = $version >= 'v2018-08'
      ? '.proxy-based-completeness.csv'
      : '.completeness.csv';
    $completenessFileName = $dataDir
      . '/json/' . $filePrefix
      . '/' . $filePrefix . $suffix;
    if (file_exists($completenessFileName)) {
      $keys = ($version == 'v2018-08')
        ? ["mean", "min", "max", "count", "sum", "median"]
        : ["mean", "min", "max", "count", "sum", "stddev", "median"];
      foreach (file($completenessFileName) as $line) {
        $values = str_getcsv($line);
        array_shift($values);
        $field = array_shift($values);
        $assoc = array_combine($keys, $values);
        $completeness[strtolower($field)] = $assoc;
      }
    } else {
      $msg = sprintf("%s:%d file %s is not existing", __FILE__, __LINE__, $completenessFileName);
      $errors[] = $msg;
      error_log($msg);
    }
  }

  return $completeness;
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