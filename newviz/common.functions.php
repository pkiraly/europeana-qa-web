<?php

include_once getRootPath() . '/common/common-functions.php';

function getRootPath() {
  return realpath(__DIR__. '/../');
}

function getParameters() {
  $parameters = (object)[
    'id' => '',
    'entity' => '',
    'type' => ''
  ];

  if (isset($_GET['id'])) {
    $parameters->id = $_GET['id'];
  }

  if (isset($_GET['type'])) {
    $parameters->type = $_GET['type'];
  } else {
    list($parameters->id, $parameters->type) = parseId($parameters->id);
  }

  return $parameters;
}

function getDataDir() {
  global $configuration, $version;

  if (!isset($version)) {
    $version = getOrDefault(
      'version',
      $configuration['DEFAULT_VERSION'],
      $configuration['version']
    );
  }

  return $configuration['DATA_PATH'] . '/' . $version;
}

/**
 * prefixes
 * c - dataset
 * d - dataProvider
 * cd - [dataset] - [dataProvider]
 * pd - [provider] - [dataProvider]
 * p - provider
 * cn - country
 * l - language
 */
function parseId($id) {
  $type = substr($id, 0, 1);
  if (in_array($type, ['c', 'd', 'pd', 'p', 'cn', 'l', 'a'])) {
    $id = substr($id, 1);
  } else {
    $type = 'c';
  }
  return [$id, $type];
}

function callTemplate($data, $file) {
  ob_start();
  include($file);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function createSmarty($templateDir) {
  define('APPLICATION', 'europeana-qa');
  define('APPLICATION_DIR', $_SERVER['DOCUMENT_ROOT'] . '/' . APPLICATION);

  define('SMARTY_DIR', APPLICATION_DIR . '/libs/smarty-3.1.32/libs/');
  define('_SMARTY', APPLICATION_DIR . '/libs/_smarty/');

  require_once(SMARTY_DIR . 'Smarty.class.php');

  $smarty = new Smarty();

  $smarty->setTemplateDir(getcwd() . '/' . $templateDir);
  $smarty->setCompileDir(_SMARTY . '/templates_c/');
  $smarty->setConfigDir(_SMARTY . '/configs/');
  $smarty->setCacheDir(_SMARTY . '/cache/');
  $smarty->addPluginsDir(APPLICATION_DIR . '/common/smarty_plugins/');

  // standard PHP function
  $smarty->registerPlugin("modifier", "str_replace", "str_replace");
  $smarty->registerPlugin("modifier", "number_format", "number_format");

  return $smarty;
}

function readHistogramFormCsv($filePrefix, &$errors) {
  global $dataDir, $development;
  static $histogram;

  if (!isset($histogram)) {
    $histogram = [];
    $suffix = $development
      ? '.proxy-based-completeness-histogram.csv'
      : '.completeness-histogram.csv';
    $histogramFileName = $dataDir
      . '/json/' . $filePrefix
      . '/' . $filePrefix . $suffix;
    // error_log('histogramFileName: ' . $histogramFileName);
    if (file_exists($histogramFileName)) {
      $keys = ["id", "field", "entries"];
      foreach (file($histogramFileName) as $line) {
        $values = str_getcsv($line);
        $values = array_combine($keys, $values);
        $field = strtolower($values['field']);
        $raw_entries = explode(';', $values['entries']);
        $entries = [];
        foreach ($raw_entries as $raw) {
          list($min_max, $count) = explode(':', $raw);
          list($min, $max) = explode('-', $min_max);
          $entries[] = (object)[
            'min' => $min,
            'max' => $max,
            'count' => $count
          ];
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
