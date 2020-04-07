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

function readHistogramFormCsv($type, $filePrefix, &$errors) {
  global $dataDir, $development, $version;
  static $histogram;

  if (!isset($histogram)) {
    $histogram = [];
    $suffix = $development && $version == 'v2018-08'
      ? '.proxy-based-completeness-histogram.csv'
      : '.completeness-histogram.csv';
    $histogramFileName = $dataDir . '/json/' . $type . '/' . $filePrefix
      . '/' . $filePrefix . $suffix;
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
      $msg = sprintf("%s:%d file %s is not existing", basename(__FILE__), __LINE__, $histogramFileName);
      $errors[] = $msg;
      error_log($msg);
    }
  }

  return $histogram;
}

function getIntersections($type,
                          $id,
                          $type2 = NULL,
                          $id2 = NULL,
                          $targetType = NULL,
                          $intersection = NULL) {
  global $dataDir, $version, $development;

  if ($id == 'all' || !in_array($type, ['c', 'd', 'p'])) {
    return (object)[];
  }

  if ($version >= 'v2018-08') {
    $other_types = ($type == 'c' || $type == 'p') ? ['d'] : ['c', 'p'];
    $file = $dataDir . '/proxy-based-intersections.json';
    if (is_null($id2) && !is_null($intersection) && !is_null($type2)) {
      $id2 = extractSubId($intersection, $type2);
    }
  } else {
    $other_type = ($type == 'c') ? 'd' : 'c';
    $file = $dataDir . '/intersections.json';
  }

  $data = json_decode(file_get_contents($file));

  $list = $data->$type->$id;
  if (!is_null($id2)) {
    if (isset($list->$type2->$id2->$targetType)) {
      $list = (object)[$targetType => $list->$type2->$id2->$targetType];
    } else {
      return [];
    }
  }

  $all_count = 0;
  if ($version >= 'v2018-08') {
    $rows = (object)['list' => (object)[]];
    foreach ($list as $other_type => $original_items) {
      $items = [];
      foreach ($original_items as $_id => $item) {
        $entry = $item->entry;
        $entry->id = $_id;
        $entry->type = $other_type;
        $entry->name = retrieveName($_id, $other_type);
        if ($type == 'c' && $entry->name === FALSE)
          $entry->name = 'unspecified';
        $items[] = $entry;
        $all_count += $entry->count;
      }
      $rows->list->{$other_type} = (object)[
        'items' => $items,
        'count' => count($items)
      ];
    }
    $rows->all_count = $all_count;
  } else {
    $rows = [(object)['id' => 'all', 'name'=> 'all', 'file'=> 'all']];
    foreach ($list as $_id => $item) {
      $item->id = $_id;
      $item->name = retrieveName($_id, $other_type);
      if ($type == 'c' && $item->name === FALSE)
        $item->name = 'unspecified';
      $rows[] = $item;
      $all_count += $item->count;
    }
    $rows[0]->count = $all_count;
  }
  return $rows;
}

function extractSubId($intersection, $subType) {
  list($types, $id1, $id2) = explode('-', $intersection);
  if ($subType == $types[0]) {
    return $id1;
  } else if ($subType == $types[1]) {
    return $id2;
  }
  return NULL;
}

function retrieveName($id, $type) {
  global $dataDir;

  if ($type == 'a')
    return 'all Europeana';

  $files = [
    'c' => 'datasets.csv',
    'd' => 'data-providers.csv',
    'p' => 'providers.csv',
    'cn' => 'countries.csv',
    'l' => 'languages.csv',
  ];

  if (!isset($content)) {
    $file = $files[$type];
    $path = $dataDir . '/' . $file;
    error_log(sprintf('%s:%d path: %s', basename(__FILE__), __LINE__, $path));
    $content = explode("\n", file_get_contents($dataDir . '/' . $file));
  }

  $name = FALSE;
  foreach ($content as $line) {
    list($_id, $_name) = explode(';', $line, 2);
    if ($_id == $id) {
      $name = $_name;
      break;
    }
  }
  return $name;
}
