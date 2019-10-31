<?php
$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');
include_once('newviz/common.functions.php');

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $collectionId = $type = "";

$version  = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);
$dataDir = $configuration['DATA_PATH'] . '/' . $version;

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  if (isset($_GET['type'])) {
    $type = $_GET['type'];
  } else {
    list($id, $type) = parseId($id);
  }

  if (isset($_GET['name']) && $_GET['name'] != "") {
    $collectionId = $_GET['name'];
  } else {
    $collectionId = retrieveName($id, $type);
  }
} else {
  if (isset($argv)) {
    $collectionId = $argv[1];
    $id = strstr($collectionId, '_', true);
  }
  $type = 'c';
}

$fragment = getOrDefault('fragment', NULL);
$intersection = getOrDefault('intersection', NULL);

$development = getOrDefault('development', '0') == 1 ? TRUE : FALSE;
$intersection = getOrDefault('intersection', NULL);
$type2 = getOrDefault('type2', NULL);
$id2 = getOrDefault('id2', NULL);
$type3 = getOrDefault('type3', NULL);
$id3 = getOrDefault('id3', NULL);
$source = getOrDefault('source', 'json', ['json', 'csv']);

if ($id == '') {
  $datasets = retrieveDatasets($type, $fragment);
  foreach ($datasets as $id => $collectionId) {
    break;
  }
}

// getIntersections($type, $id, $type2, $id2, $type3, $intersection) {

$filePrefix = ($id == 'all')
  ? $id
  : (
  (is_null($intersection) || $intersection == 'all')
    ? (
  in_array($type, ['cn', 'l', 'pd', 'p', 'cd'])
    ? $type . '-' . $id
    : $type . $id
  )
    : $intersection
  );

$count = 0;
$errors = [];
$entityCounts = (object)[];

if ($version >= 'v2018-08') {
  $count = getCountFromCsv($filePrefix, $errors);
  $entityCounts = getEntityCountsFromCsv($filePrefix, $count, $errors);
// } else if ($development && $version >= 'v2018-08') {
//   $count = getCountFromCsv($filePrefix, $errors);
//   $entityCounts = getEntityCountsFromCsv($filePrefix, $count, $errors);
} else {
  $count = getCountFromRGeneratedJson($filePrefix, $errors);
  $entityCounts = getEntityCountsFromRGeneratedJson($filePrefix, $errors);
}

$datasets = retrieveDatasets($type, ($type == 'c' ? $fragment : ''));
$dataproviders = retrieveDataproviders($type, ($type == 'd' ? $fragment : ''));

$smarty = createSmarty('templates/newviz');
$smarty->assign('rand', rand());
$smarty->assign('collectionId', $collectionId);
$smarty->assign('title', $collectionId);
$smarty->assign('stylesheets', ['chart.css', 'style/newviz.css']);
$smarty->assign('type', $type);
$smarty->assign('fragment', $fragment);
$smarty->assign('id', $id);
$smarty->assign('collectionId', $collectionId);
$smarty->assign('portalUrl', getPortalUrl($type, $collectionId));
$smarty->assign('version', $version);
$smarty->assign('development', $development);
$smarty->assign('configuration', $configuration);

$smarty->assign('datasets', $datasets);
$smarty->assign('dataproviders', $dataproviders);
$smarty->assign('entityCounts', $entityCounts);
$smarty->assign('count', $count);
$smarty->assign('filePath', getRootPath());
$smarty->assign('errors', $errors);

$smarty->assign('intersectionLabels', ['c' => 'datasets', 'd' => 'data providers', 'p' => 'providers']);
$smarty->assign('intersections', getIntersections($type, $id));
$smarty->assign('intersection', $intersection);

$smarty->assign('type2', $type2);
$smarty->assign('id2', $id2);
$smarty->assign('type3', $type3);
$smarty->assign('id3', $id3);

$smarty->assign('source', $source);

$smarty->assign('languages', retrieveLanguages($type, ($type == 'l' ? $fragment : '')));
$smarty->assign('countries', retrieveCountries($type, ($type == 'cn' ? $fragment : '')));
$smarty->assign('providers', retrieveProviders($type, ($type == 'p' ? $fragment : '')));

$smarty->display('newviz.smarty.tpl');

function retrieveDatasets($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/datasets.csv', ($type == 'c' ? $fragment : NULL));
}

function retrieveDataproviders($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/data-providers.csv', ($type == 'd' ? $fragment : NULL));
}

function retrieveLanguages($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/languages.csv', ($type == 'd' ? $fragment : NULL));
}

function retrieveCountries($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/countries.csv', ($type == 'd' ? $fragment : NULL));
}

function retrieveProviders($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/providers.csv', ($type == 'd' ? $fragment : NULL));
}

function retrieveCsv($fileName, $fragment) {
  $list = [];
  $content = explode("\n", file_get_contents($fileName));
  foreach ($content as $line) {
    if ($line == '')
      continue;

    if (is_null($fragment) || $fragment == '' || stristr($line, $fragment)) {
      list($_id, $_name) = explode(';', $line, 2);
      $list[$_id] = $_name;
    }
  }
  return $list;
}

function getPortalUrl($type, $collectionId) {
  $url = "https://www.europeana.eu/portal/en/search?";
  if ($type == 'c') {
    $url .= 'q=' . urlencode('europeana_collectionName:(' . $collectionId . ')');
  } else if ($type == 'd') {
    $url .= urlencode('f[DATA_PROVIDER][]') . '=' . urlencode($collectionId) . '&q=*';
  }
  return $url;
}

/**
 * @param $dataDir
 * @param $filePrefix
 * @param $errors
 * @return array
 */
function getCountFromRGeneratedJson($filePrefix, &$errors) {
  global $dataDir;

  $jsonCountFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . '.count.json';
  if (file_exists($jsonCountFileName)) {
    $stats = json_decode(file_get_contents($jsonCountFileName));
    $count = $stats[0]->count;
  } else {
    $msg = sprintf("file %s is not existing", $jsonCountFileName);
    $errors[] = $msg;
    error_log($msg);
  }
  return $count;
}

/**
 * @param $dataDir
 * @param $filePrefix
 * @param $errors
 * @return array
 */
function getCountFromCsv($filePrefix, &$errors) {
  global $development, $version;

  $count = 0;
  $completeness = readCompleteness($filePrefix, $errors);
  if (!empty($completeness)) {
    $field = ($version >= 'v2018-08')
           ? 'PROVIDER_Proxy_rdf_about'
           : 'ProvidedCHO_rdf_about';
    $count = $completeness[$field]['count'];
  }

  return $count;
}

/**
 * @param $filePrefix
 * @param $errors
 * @param $dataDir
 * @return array
 */
function readCompleteness($filePrefix, &$errors) {
  global $dataDir, $development, $version;
  static $completeness;

  if (!isset($completeness)) {
    $completeness = [];
    $suffix = ($version == 'v2018-08')
      ? '.proxy-based-completeness.csv' : '.completeness.csv';
    $completenessFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . $suffix;
    if (file_exists($completenessFileName)) {
      $keys = ($version == 'v2018-08')
        ? ["mean", "min", "max", "count", "sum", "median"]
        : ["mean", "min", "max", "count", "sum", "stddev", "median"];
      foreach (file($completenessFileName) as $line) {
        $values = str_getcsv($line);
        array_shift($values);
        $field = array_shift($values);
        if (count($keys) != count($values)) {
          $msg = sprintf("%s:%d different counts: %d vs %d - values: %s",
            __FILE__, __LINE__,
            count($keys), count($values), join(', ', $values));
          error_log($msg);
        }
        $assoc = array_combine($keys, $values);
        $completeness[$field] = $assoc;
      }
    } else {
      $msg = sprintf("%s:%d file %s is not existing", __FILE__, __LINE__, $completenessFileName);
      $errors[] = $msg;
      error_log($msg);
    }
  }

  return $completeness;
}

/**
 * @param $dataDir
 * @param $filePrefix
 * @param $errors
 * @return array
 */
function getEntityCountsFromRGeneratedJson($filePrefix, &$errors) {
  global $dataDir;

  $jsonFreqFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . '.freq.json';
  if (file_exists($jsonFreqFileName)) {
    $frequencies = json_decode(file_get_contents($jsonFreqFileName));
    $entityCounts = (object)[];
    foreach ($frequencies as $freq) {
      if (preg_match('/_rdf_about$/', $freq->field)) {
        $entityCounts->{$freq->field} = number_format($freq->count, 0, '.', ' ');
      }
    }
  } else {
    $msg = sprintf("file %s is not existing", $jsonFreqFileName);
    $errors[] = $msg;
    error_log($msg);
  }
  return $entityCounts;
}

function getEntityCountsFromCsv($filePrefix, $count, &$errors) {
  $entityCounts = (object)[];
  $source = 'histogram';

  if ($source == 'completeness') {
    $completeness = readCompleteness($filePrefix, $errors);
    foreach ($completeness as $field => $values) {
      if (preg_match('/_rdf_about$/', $field)) {
        $entityCounts->{strtolower($field)} = number_format($values['count'], 0, '.', ' ');
        // $entityCounts->{strtolower($field)} = number_format($values['mean'] * $count, 0, '.', ' ');
      }
    }
  } else if ($source == 'histogram') {
    $histogram = readHistogramFormCsv($filePrefix, $errors);
    foreach ($histogram as $field => $values) {
      if (preg_match('/_rdf_about$/', $field)) {
        $count = 0;
        foreach ($values as $i => $value) {
          if ($value->min > 0 && $value->max > 0) {
            $count += $value->count;
          }
        }
        $entityCounts->{strtolower($field)} = number_format($count, 0, '.', ' ');
      }
    }
  }

  return $entityCounts;
}
