<?php
$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $collectionId = $type = "";
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
  $collectionId = $argv[1];
  $id = strstr($collectionId, '_', true);
  $type = 'c';
}
$fragment = getOrDefault('fragment', NULL);
$version  = getOrDefault('version', NULL);
if (is_null($version) || !in_array($version, $configuration['version'])) {
  $version = $configuration['version'][0];
}

$dataDir = 'data/' . $version;

$n = 0;
$jsonCountFileName = $dataDir . '/json/' . $type . $id . '/' . $type . $id . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

$jsonFreqFileName = $dataDir . '/json/' . $type . $id . '/' . $type . $id . '.freq.json';
if (file_exists($jsonFreqFileName)) {
  $frequencies = json_decode(file_get_contents($jsonFreqFileName));
  $entityCounts = (object)[];
  foreach ($frequencies as $freq) {
    if (preg_match('/_rdf_about$/', $freq->field)) {
      $entityCounts->{$freq->field} = number_format($freq->count, 0, '.', ' ');
    }
  }
}
$datasets = retrieveDatasets($type, $fragment);
$dataproviders = retrieveDataproviders($type, $fragment);

ob_start();
include('templates/newviz/newviz.tpl.php');
$content = ob_get_contents();
ob_end_clean();

echo $content;

/*
if (isset($_GET['id'])) {
  echo $content;
} else {
  file_put_contents($id . '.html', $content);
}
*/

function parseId($id) {
  $type = substr($id, 0, 1);
  if (in_array($type, ['c', 'd'])) {
    $id = substr($id, 1);
  } else {
    $type = 'c';
  }
  return [$id, $type];
}

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
  $file = ($type == 'c') ? 'datasets.txt' : "data-providers.txt";
  $content = explode("\n", file_get_contents($file));
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

function getPortalUrl($type, $collectionId) {
  $url = "https://www.europeana.eu/portal/en/search?";
  if ($type == 'c') {
    $url .= 'q=' . urlencode('europeana_collectionName:(' . $collectionId . ')');
  } else if ($type == 'd') {
    $url .= urlencode('f[DATA_PROVIDER][]') . '=' . urlencode($collectionId) . '&q=*';
  }
  return $url;
}