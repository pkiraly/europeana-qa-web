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

if (empty($id)) {
  $datasets = retrieveDatasets($type, $fragment);
  foreach ($datasets as $id => $collectionId) {
    break;
  }
}

$filePrefix = is_null($intersection) || $intersection == 'all' ? $type . $id : $intersection;

$n = 0;
$errors = [];
$jsonCountFileName = $dataDir . '/json/' . $filePrefix . '/' . $filePrefix . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

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
  $errors[] = sprintf("file %s is not existing", $jsonFreqFileName);
}

$datasets = retrieveDatasets($type, $fragment);
$dataproviders = retrieveDataproviders($type, $fragment);

$smarty = createSmarty('templates/newviz');
$smarty->assign('rand', rand());
$smarty->assign('collectionId', $collectionId);
$smarty->assign('title', $title);
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
$smarty->assign('n', $n);
$smarty->assign('filePath', getRootPath());
$smarty->assign('errors', $errors);

$smarty->assign('intersections', getIntersections($type, $id));
$smarty->assign('intersection', $intersection);

$smarty->display('newviz.smarty.tpl');

function retrieveDatasets($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/datasets.txt', ($type == 'c' ? $fragment : NULL));
}

function retrieveDataproviders($type, $fragment) {
  global $dataDir;
  return retrieveCsv($dataDir . '/data-providers.txt', ($type == 'd' ? $fragment : NULL));
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
