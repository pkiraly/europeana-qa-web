<?php
$configuration = parse_ini_file('../config.cfg');
include_once('common.functions.php');
include_once('../common/saturation-functions.php');
$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;

$templateDir = '../templates/newviz/multilinguality/';
$parameters = getParameters();
$collectionId = $parameters->type . $parameters->id;
$dataDir = '../' . getDataDir();

$data = (object)[
  'version' => $version,
  'generic_prefixes' => getGenerixPrefixes(),
  'fields' => getFields(),
  'assocStat' => getSaturationStatistics(),
  'languageDistribution' => getLanguageDistribution(),
  'collectionId' => $collectionId
];

if ($development) {
  define('SMARTY_DIR', getcwd() . '/../libs/smarty-3.1.32/libs/');
  define('_SMARTY', getcwd() . '/../libs/_smarty/');
  require_once(SMARTY_DIR . 'Smarty.class.php');
  $smarty = new Smarty();

  $smarty->setTemplateDir(getcwd() . '/' . $templateDir);
  $smarty->setCompileDir(_SMARTY . '/templates_c/');
  $smarty->setConfigDir(_SMARTY . '/configs/');
  $smarty->setCacheDir(_SMARTY . '/cache/');
  // standard PHP function
  $smarty->registerPlugin("modifier", "str_replace", "str_replace");
  // custom functions
  $smarty->registerPlugin("modifier", "conditional_format", "conditional_format");
  $smarty->registerPlugin("modifier", "fieldLabel", "fieldLabel");

  $smarty->assign('data', $data);
  $dir = $smarty->getTemplateDir();
  $html = $smarty->fetch('top-level-scores.smarty.tpl');
} else {
  $html = callTemplate($data, $templateDir . 'top-level-scores.tpl.php');
}

header("Content-type: application/json");
echo json_encode([
  'dir' => $dir,
  'html' => $html
]);

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
  global $parameters, $collectionId, $dataDir;

  $assocStat = [];
  $saturationFile = sprintf('%s/json/%s/%s.saturation.json', $dataDir, $collectionId, $collectionId);
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
  global $parameters, $collectionId, $dataDir;

  $languageDistribution = (object)[];
  $languageDistributionFile = sprintf('%s/json/%s/%s.languages.json', $dataDir, $collectionId, $collectionId);
  $languageDistributionFileExists = file_exists($languageDistributionFile);
  if ($languageDistributionFileExists) {
    $languageDistribution = json_decode(file_get_contents($languageDistributionFile));
  }
  return $languageDistribution;
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

function conditional_format($num, $minimize = FALSE, $toDouble = FALSE, $decimals = 2) {
  if ($toDouble) {
    $num = (double)$num;
  } else {
    $num = strstr($num, '.') ? (double)$num : (int)$num;
  }
  if (is_double($num)) {
    $formatted = number_format($num, $decimals);
    if ($minimize) {
      $formatted = preg_replace('/\.$/', '', preg_replace('/0+$/', '', $formatted));
    }
    return $formatted;
  }
  return $num;
}

function fieldLabel($key) {
  if ($key == 'aggregated')
    return 'All fields';

  $key = str_replace('_dc_', '/dc:', $key);
  $key = str_replace('_dcterms_', '/dcterms:', $key);
  $key = str_replace('_edm_', '/edm:', $key);
  $key = str_replace('_ore_', '/ore:', $key);
  $key = str_replace('_owl_', '/owl:', $key);
  $key = str_replace('_skos_', '/skos:', $key);
  $key = str_replace('_foaf_', '/foaf:', $key);
  $key = str_replace('_rdaGr2_', '/rdaGr2:', $key);

  return $key;
}