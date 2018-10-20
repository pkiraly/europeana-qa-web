<?php
$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');
include_once('newviz/common.functions.php');

$title = 'Metadata Quality Assurance Framework for Europeana';
$id = $collectionId = $type = "";

$version = getOrDefault(
  'version', $configuration['DEFAULT_VERSION'], $configuration['version']
);
$dataDir = $configuration['DOWNLOAD_PATH'] . '/' . $version;
$file = getOrDefault('file');
$development = getOrDefault('development', 0, [0, 1]);

if (!is_null($file)) {
  if (preg_match('/^[\w-]*\.json.gz$/', $file)) {
    error_log('downloadFile: ' . $file);
    downloadFile($file);
    exit();
  } else {
    error_log('unmatched file: ' . $file);
  }
} else {
  error_log('null file: ' . gettype($file));
  error_log('GET: ' . json_encode($_GET));
}

$smarty = createSmarty('templates/download');
$smarty->assign('rand', rand());
$smarty->assign('version', $version);
$smarty->assign('versions', $configuration['version']);
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
  if (!file_exists($path)) {
    return;
  }

  if (ob_get_level()) {
    ob_end_clean();
  }

  header('Content-Type: application/gzip');
  header(sprintf('Content-Disposition: attachment; filename="%s"', $file));
  echo file_get_contents($path);
}
