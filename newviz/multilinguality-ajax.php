<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');
include_once($root . '/common/saturation-functions.php');

$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;

$parameters = getParameters();
$version = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);
$intersection = getOrDefault('intersection', NULL);
if (empty($intersection))
  $intersection = NULL;

$collectionId = $parameters->type . $parameters->id;
$dataDir = getDataDir();

$filePrefix = (is_null($intersection) || $intersection == 'all') ? $collectionId : $intersection;

$languageDistribution = getLanguageDistribution();
$fieldsByLanguageList = getFieldsByLanguageList($languageDistribution);
$allFieldsList = getAllFields($languageDistribution);

$data = (object)[
  'version' => $version,
  'generic_prefixes' => getGenerixPrefixes(),
  'fields' => getFields(),
  'assocStat' => getSaturationStatistics(),
  'languageDistribution' => $languageDistribution,
  'fieldsByLanguageList' => $fieldsByLanguageList,
  'allFieldsList' => $allFieldsList,
  'collectionId' => $collectionId
];

$templateDir = '../templates/newviz/multilinguality/';
$smarty = createSmarty($templateDir);

$smarty->assign('data', $data);
$smarty->display('multilinguality.smarty.tpl');

function getGenerixPrefixes() {
  return [
    'provider' => 'In provider proxy',
    'europeana' => 'In Europeana proxy',
    'object' => 'In object'
  ];
}

function getFields() {
  return [
    'taggedliterals' => 'Number of tagged literals',
    'distinctlanguages' => 'Number of distinct language tags',
    'taggedliterals_per_language' => 'Number of tagged literals per language tag',
    'languages_per_property' => 'Average number of languages per property for which there is at least one language-tagged literal'
    //                          'Average number of language tags per property for which there is at least one language-tagged literal'
  ];
}

function getSaturationStatistics() {
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
  }
  return $assocStat;
}

function getLanguageDistribution() {
  global $parameters, $collectionId, $dataDir, $filePrefix;

  $languageDistribution = (object)[];
  $languageDistributionFile = sprintf('%s/json/%s/%s.languages.json', $dataDir, $filePrefix, $filePrefix);
  $languageDistributionFileExists = file_exists($languageDistributionFile);
  if ($languageDistributionFileExists) {
    $languageDistribution = json_decode(file_get_contents($languageDistributionFile));
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
