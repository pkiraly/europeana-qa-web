<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');
include_once($root . '/common/saturation-functions.php');
include_once($root . '/newviz/newviz-ajax-config.php');

$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;
$source = getOrDefault('source', 'json', ['json', 'csv']);

$parameters = getParameters();
$version = getOrDefault(
  'version',
  $configuration['DEFAULT_VERSION'],
  $configuration['version']
);
$intersection = getOrDefault('intersection', NULL);
if (empty($intersection))
  $intersection = NULL;

$collectionId = in_array($parameters->type, ['cn', 'l', 'pd', 'p', 'cd'])
  ? $parameters->type . '-' . $parameters->id
  : ($parameters->type == 'a'
    ? $parameters->id
    : $parameters->type . $parameters->id);

$dataDir = getDataDir();

$filePrefix = (is_null($intersection) || $intersection == 'all')
  ? $collectionId
  : $intersection;

// language
$languageDistribution = getLanguageDistribution();
$fieldsByLanguageList = getFieldsByLanguageList($languageDistribution);
$allFieldsList = getAllFields($languageDistribution);

$data = (object)[
  'version' => $version,
  'genericPrefixes' => getPrefixes('generic'),
  'specificPrefixes' => getPrefixes('specific'),
  'genericMetrics' => getGenericMetrics(),
  'specificMetrics' => getSpecificMetrics(),
  'assocStat' => getSaturationStatistics(),
  'languageDistribution' => $languageDistribution,
  'fieldsByLanguageList' => $fieldsByLanguageList,
  'allFieldsList' => $allFieldsList,
  'collectionId' => $collectionId,
  'fields' => prepareFields($fields['ProvidedCHO']),
];

$templateDir = '../templates/newviz/multilinguality/';
$smarty = createSmarty($templateDir);

$smarty->assign('data', $data);
$smarty->display('multilinguality.smarty.tpl');

function getPrefixes($type) {
  $values = [
    'provider' => 'In provider proxy',
    'europeana' => 'In Europeana proxy',
    'object' => 'In object'
  ];
  if ($type == 'specific') {
    unset($values['object']);
  }
  return $values;
}

function getGenericMetrics() {
  global $version;

  if ($version >= 'v2018-08') {
    $fields = [
      'taggedliterals' => 'Number of tagged literals',
      'distinctlanguagecount' => 'Number of distinct language tags',
      'taggedliterals_per_language' => 'Number of tagged literals per language tag',
      'numberoflanguages_per_property' => 'Average number of languages per property for which there is at least one language-tagged literal'
    ];
  } else {
    $fields = [
      'taggedliterals' => 'Number of tagged literals',
      'distinctlanguages' => 'Number of distinct language tags',
      'taggedliterals_per_language' => 'Number of tagged literals per language tag',
      'languages_per_property' => 'Average number of languages per property for which there is at least one language-tagged literal'
    ];
  }
  return $fields;
}

function getSpecificMetrics() {
  $fields = [
    'taggedliterals' => 'Number of tagged literals',
    'languages' => 'Number of distinct language tags',
    'literalsperlanguage' => 'Number of tagged literals per language tag',
  ];
  return $fields;
}

function getSaturationStatistics() {
  global $version;

  if ($version >= 'v2018-08') {
    $assocStat = getSaturationStatisticsFromCsv();
  } else {
    $assocStat = getSaturationStatisticsFromJson();
  }
  return $assocStat;
}

function getSaturationStatisticsFromJson() {
  global $parameters, $collectionId, $dataDir, $filePrefix;

  $assocStat = [];
  $saturationFile = sprintf('%s/json/%s/%s.saturation.json', $dataDir, $filePrefix, $filePrefix);
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
        $field = preg_replace('/_(taggedliterals|languages|literalsperlanguage)/', '', $key);
        if (preg_match('/_taggedliterals/', $key)) {
          $specific_type = 'taggedliterals';
        } else if (preg_match('/_languages/', $key)) {
          $specific_type = 'languages';
        } else if (preg_match('/_literalsperlanguage/', $key)) {
          $specific_type = 'literalsperlanguage';
        }

        // $assocStat['specific'][$specific_type][$key][$prefix] = $obj;
        $assocStat['specific'][$field][$specific_type][$prefix] = $obj;
      }
    }
  } else {
    error_log("Saturation file does not exist: " . $saturationFile);
  }
  return $assocStat;
}

function getSaturationStatisticsFromCsv() {
  global $parameters, $collectionId, $dataDir, $filePrefix;

  // id,    field,                            mean, min, max, count, std.dev, median
  // p-142, provider_dc_title_taggedLiterals, 0.0,  0.0, 0.0, 549,   0.0,     0.0
  $assocStat = [];
  $saturationFile = sprintf('%s/json/%s/%s.multilinguality.csv', $dataDir, $filePrefix, $filePrefix);
  if (file_exists($saturationFile)) {
    $keys = ["mean", "min", "max", "count", "std.dev", "median"]; // "sum",
    foreach (file($saturationFile) as $line) {
      $values = str_getcsv($line);
      array_shift($values); // id
      $field = array_shift($values);
      $assoc = (object)array_combine($keys, $values);
      $fieldInfo = getFieldInfo(strtolower($field));
      if ($fieldInfo->type != 'skippable') {
        if ($fieldInfo->type == 'generic') {
          $assocStat['generic'][$fieldInfo->key][$fieldInfo->prefix] = $assoc;
        } else if ($fieldInfo->type == 'specific') {
          $assocStat['specific'][$fieldInfo->edmField][$fieldInfo->specificType][$fieldInfo->prefix] = $assoc;
        }
      }
    }
  } else {
    $msg = sprintf("file %s is not existing", $saturationFile);
    $errors[] = $msg;
    error_log($msg);
  }
  return $assocStat;
}

function getFieldInfo($field) {
  static $skippable = ["dataset", "dataProvider", "provider", "country", "language"];

  if (in_array($field, $skippable)) {
    $type = 'skippable';
  } else if (preg_match('/^europeana_/', $field)) {
    $key = preg_replace('/^europeana_/', '', $field);
    $type = 'specific';
    $prefix = 'europeana';
  } else if (preg_match('/^provider_/', $field)) {
    $key = preg_replace('/^provider_/', '', $field);
    $type = 'specific';
    $prefix = 'provider';
  } else {
    $type = 'generic';
    if (preg_match('/inproviderproxy$/', $field)) {
      $prefix = 'provider';
    } else if (preg_match('/ineuropeanaproxy$/', $field)) {
      $prefix = 'europeana';
    } else if (preg_match('/inobject$/', $field)) {
      $prefix = 'object';
    }
    $key = preg_replace('/(per)(property|language)/', '_$1_$2',
      preg_replace('/in(providerproxy|europeanaproxy|object)$/', '', $field)
    );
  }

  if ($type == 'specific') {
    $specificType = "";
    $edmField = preg_replace('/_(taggedliterals|languages|literalsperlanguage)/', '', $key);
    if (preg_match('/_taggedliterals/', $key)) {
      $specificType = 'taggedliterals';
    } else if (preg_match('/_languages/', $key)) {
      $specificType = 'languages';
    } else if (preg_match('/_literalsperlanguage/', $key)) {
      $specificType = 'literalsperlanguage';
    }
  }

  $fieldInfo = (object)[
    'type' => $type,
    'prefix' => (isset($prefix) ? $prefix : ''),
    'key' => (isset($key) ? $key : ''),
    'edmField' => (isset($edmField) ? $edmField : ''),
    'specificType' => (isset($specificType) ? $specificType : ''),
  ];

  return $fieldInfo;
}

function getLanguageDistribution() {
  global $parameters, $collectionId, $dataDir, $filePrefix, $version;

  $non_language_fields = [
    'proxy_edm_year', 'proxy_edm_userTag', 'proxy_edm_hasMet', 'proxy_edm_incorporates',
    'proxy_edm_isDerivativeOf', 'proxy_edm_isRepresentationOf', 'proxy_edm_isSimilarTo',
    'proxy_edm_isSuccessorOf', 'proxy_edm_realizes', 'proxy_edm_wasPresentAt',

    'aggregation_edm_rights', 'aggregation_edm_ugc', 'aggregation_edm_aggregatedCHO',

    'agent_edm_hasMet', 'agent_edm_isRelatedTo', 'agent_owl_sameAs',

    'concept_skos_broader', 'concept_skos_narrower', 'concept_skos_related',
    'concept_skos_broadMatch', 'concept_skos_narrowMatch', 'concept_skos_relatedMatch',
    'concept_skos_exactMatch', 'concept_skos_closeMatch', 'concept_skos_notation',
    'concept_skos_inScheme',

    'timespan_owl_sameAs',
  ];

  $is_languages_all = (!is_null($version) && $version >= 'v2019-03');

  $languageDistribution = (object)[];
  if ($is_languages_all) {
    $languageDistributionFile = sprintf('%s/json/%s/%s.languages-all.json', $dataDir, $filePrefix, $filePrefix);
  } else {
    $languageDistributionFile = sprintf('%s/json/%s/%s.languages.json', $dataDir, $filePrefix, $filePrefix);
  }
  $languageDistributionFileExists = file_exists($languageDistributionFile);
  if ($languageDistributionFileExists) {
    $languageDistribution = json_decode(file_get_contents($languageDistributionFile));
    foreach ($non_language_fields as $field) {
      if (isset($languageDistribution->{$field})) {
        unset($languageDistribution->{$field});
      }
    }
  } else {
    error_log('Language file does not exist: ' . $languageDistributionFile);
  }
  return $languageDistribution;
}

function getFieldsByLanguageList($languageDistribution) {
  $byLanguage = [];
  foreach ($languageDistribution as $field => $fieldData) {
    if ($field != 'aggregated') {
      foreach ($fieldData as $language => $count) {
        if ($language != 'no field instance' && $language != 'no language') {
          if (!isset($byLanguage[$language])) {
            $byLanguage[$language] = [];
          }
          $byLanguage[$language][] = $field;
        }
      }
    }
  }
  return $byLanguage;
}

function getAllFields($languageDistribution) {
  $fields = [];
  foreach ($languageDistribution as $field => $fieldData) {
    if ($field != 'aggregated') {
      $fields[] = $field;
    }
  }
  return $fields;
}

function getLabel($key) {
  $label = $key;
  $format = 'simple';
  if ($format == 'simple') {
    $label = preg_replace('/_(taggedliterals|languages|literalsperlanguage)/', '', $label);
  } else {
    $label = preg_replace('/_taggedliterals/', ' / tagged literals', $label);
    $label = preg_replace('/_languages/', ' / languages', $label);
    $label = preg_replace('/_literalsperlanguage/', ' / literals per language', $label);
    $label = preg_replace('/^(.*?) /', '<strong>$1</strong> ', $label);
  }
  $label = preg_replace('/_/', ':', $label);
  return $label;
}

function prepareFields($fields) {
  $preparedFields = [];
  foreach ($fields as $field => $value) {
    if ($field == 'proxy_edm_isNextInSequence' || $field == 'proxy_edm_type') {
      continue;
    }
    $key = str_replace('proxy_', '', strtolower($field));
    $preparedFields[$key] = $value;
  }
  return $preparedFields;
}
