<?php
ini_set('memory_limit', -1);
$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');
include_once('newviz/common.functions.php');

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $collectionId = $type = "";

$version = getOrDefault(
  'version', $configuration['DEFAULT_VERSION'], $configuration['downloadable_version']
);
$dataDir = $configuration['DOWNLOAD_PATH'] . '/' . $version;
$file = getOrDefault('file');
$development = getOrDefault('development', 0, [0, 1]);

if (!is_null($file)) {
  if (preg_match('/^[\w-]*\.json.gz$/', $file)) {
    downloadFile($file);
    exit();
  } else {
    error_log(sprintf('%s:%d unmatched file: %s', basename(__FILE__), __LINE__, $file));
  }
} else {
  error_log(sprintf('%s:%d null file: ', basename(__FILE__), __LINE__, gettype($file)));
  error_log(sprintf('%s:%d GET: ', basename(__FILE__), __LINE__, json_encode($_GET)));
}

$smarty = createSmarty('templates/download');
$smarty->assign('rand', rand());
$smarty->assign('version', $version);
$smarty->assign('versions', $configuration['downloadable_version']);
$smarty->assign('title', 'Download');
$smarty->assign('files', getCsv());
$smarty->assign('development', $development);
$smarty->display('download.tpl');

function getCsv() {
  global $version, $dataDir;

  $files = [];
  $lines = file($dataDir . '/metadata.csv');
  foreach ($lines as $line) {
    list($row['records'], $row['bytes'], $row['file']) = explode(',', trim($line));
    if ($row['file'] != 'total')
      $row['file'] .= '.gz';
    $files[] = $row;
  }
  return $files;
}

function downloadFile($file) {
  global $dataDir;
  $path = $dataDir . '/full/' . $file;
  error_log(sprintf('%s:%d download path: %s', basename(__FILE__), __LINE__, $path));
  if (!file_exists($path)) {
    error_log(sprintf('%s:%d DOES NOT exist: %s', basename(__FILE__), __LINE__, $path));
    return;
  }

  if (ob_get_level()) {
    ob_end_clean();
  }

  header('Content-Type: application/gzip');
  header(sprintf('Content-Disposition: attachment; filename="%s"', $file));
  echo file_get_contents($path);
}
