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
  error_log("id: " . $id);

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

if ($id == '') {
  $datasets = retrieveDatasets($type, $fragment);
  foreach ($datasets as $id => $collectionId) {
    break;
  }
}

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

if ($id == 'all' && $version == 'v2018-08') {
  $count = getCountFromCsv($filePrefix, $errors);
  $entityCounts = getEntityCountsFromCsv($filePrefix, $count, $errors);
} else if ($development && $version == 'v2018-08') {
  $count = getCountFromCsv($filePrefix, $errors);
  $entityCounts = getEntityCountsFromCsv($filePrefix, $count, $errors);
} else {
  $count = getCountFromRGeneratedJson($filePrefix, $errors);
  $entityCounts = getEntityCountsFromRGeneratedJson($filePrefix, $errors);
}

$datasets = retrieveDatasets($type, $fragment);
$dataproviders = retrieveDataproviders($type, $fragment);

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

$smarty->assign('intersections', getIntersections($type, $id));
$smarty->assign('intersection', $intersection);

if ($development) {
  $smarty->assign('languages', retrieveLanguages($type, $fragment));
  $smarty->assign('countries', retrieveCountries($type, $fragment));
  $smarty->assign('providers', retrieveProviders($type, $fragment));
}

$smarty->display('newviz.smarty.tpl');

function retrieveDatasets($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/datasets.txt', ($type == 'c' ? $fragment : NULL));
}

function retrieveDataproviders($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/data-providers.txt', ($type == 'd' ? $fragment : NULL));
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
  error_log('retrieveCsv: ' . $fileName);
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

function retrieveName($id, $type) {
  global $dataDir;

  if (!isset($content)) {
    $file = ($type == 'c') ? 'datasets.txt' : "data-providers.txt";
    $content = explode("\n", file_get_contents($dataDir . '/' . $file));
  }

  $name = FALSE;
  foreach ($content as $line) {
    if (strlen($line) > 0) {
      list($_id, $_name) = explode(';', $line, 2);
      if ($_id == $id) {
        $name = $_name;
        break;
      }
    }
  }
  return $name;
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

function getIntersections($type, $id) {
  global $dataDir;

  if ($id == 'all' || !in_array($type, ['c', 'd'])) {
    return [];
  }

  $other_type = $type == 'c' ? 'd' : 'c';
  $file = $dataDir . '/intersections.json';
  $data = json_decode(file_get_contents($file));
  $list = $data->$type->$id;
  $rows = [(object)['id' => 'all', 'name'=> 'all', 'file'=> 'all']];
  $all_count = 0;
  foreach ($list as $_id => $item) {
    $item->id = $_id;
    $item->name = retrieveName($_id, $other_type);
    if ($type == 'c' && $item->name === FALSE)
      $item->name = 'unspecified';
    $rows[] = $item;
    $all_count += $item->count;
  }
  $rows[0]->count = $all_count;
  return $rows;
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
  global $development;

  $count = 0;
  $completeness = readCompleteness($filePrefix, $errors);
  if (!empty($completeness)) {
    $field = $development ? 'PROVIDER_Proxy_rdf_about' : 'ProvidedCHO_rdf_about';
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
  global $dataDir, $development;
  static $completeness;

  if (!isset($completeness)) {
    $completeness = [];
    $suffix = $development ? '.proxy-based-completeness.csv' : '.completeness.csv';
    $completenessFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . $suffix;
    if (file_exists($completenessFileName)) {
      $keys = ["mean", "min", "max", "count", "median"];
      foreach (file($completenessFileName) as $line) {
        $values = str_getcsv($line);
        array_shift($values);
        $field = array_shift($values);
        $assoc = array_combine($keys, $values);
        $completeness[$field] = $assoc;
      }
    } else {
      $msg = sprintf("file %s is not existing", $completenessFileName);
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
  $completeness = readCompleteness($filePrefix, $errors);
  foreach ($completeness as $field => $values) {
    if (preg_match('/_rdf_about$/', $field)) {
      $entityCounts->{strtolower($field)} = number_format($values['mean'] * $count, 0, '.', ' ');
    }
  }
  return $entityCounts;
}