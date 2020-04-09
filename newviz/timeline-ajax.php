<?php
$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');

$parameters = getParameters();
$collectionId = ($parameters->type == 'a') ? $parameters->id : $parameters->type . '-' . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;

$feature = getOrDefault('feature', 'completeness', ['completeness', 'multilinguality']);
$statistic = getOrDefault('statistic', 'mean', ['mean', 'min', 'max', 'count', 'sum', 'stddev', 'median']);

$files = getTimelineFiles($collectionId, $feature);
$data = (object)[
  'version' => getOrDefault('version'),
  'files' => $files,
  'timelines' => getTimelines($files, $feature, $statistic),
  'multilinguality-general-locations' => [
    'InProviderProxy' => 'original',
    'InEuropeanaProxy' => 'enrichment',
    'InObject' => 'whole'
  ]
];

$smarty = createSmarty('../templates/newviz/timeline/');
$smarty->assign('data', $data);
$template = getTemplateName($feature);
if ($template != '') {
  $smarty->display($template);
}

function getTimelineFiles($collectionId, $feature) {
  global $parameters, $configuration;

  switch ($feature) {
    case 'multilinguality': $suffix = '.multilinguality.csv'; break;
    case 'completeness':
    default:
      $suffix = '.completeness.csv'; break;
  }

  $files = [];
  foreach ($configuration['version'] as $version) {
    if ($version >= 'v2019-08') {
      $baseDatadir = $configuration['DATA_PATH'] . '/' . $version;
      $dataDir = $baseDatadir . '/json/' . $parameters->type . '/' . $collectionId;
      $files[$version] = $dataDir . '/' .  $collectionId . $suffix;
    }
  }
  ksort($files);

  return $files;
}

function getTimelines($files, $feature, $statistic) {

  $timeline = [];
  $keys = ($feature == 'completeness')
    ? ["mean", "min", "max", "count", "sum", "stddev", "median"]
    : ["mean", "min", "max", "count", "stddev", "median"];
  foreach ($files as $version => $file) {
    if (file_exists($file)) {
      foreach (file($file) as $line) {
        $values = str_getcsv($line);
        $collection = array_shift($values);
        $field = array_shift($values);
        if (count($keys) != count($values)) {
          $msg = sprintf("%s:%d different counts: %d vs %d - values: %s",
            basename(__FILE__), __LINE__,
            count($keys), count($values), join(', ', $values));
          error_log($msg);
        }
        $row = array_combine($keys, $values);
        if ($feature == 'completeness') {
          list($location, $entity, $encodedfield) = explode('_', $field, 3);
          $location = strtolower($location);
          $edmfield = preg_replace('/^([^_]+)_/', "$1:", $encodedfield);
          $timeline[$entity][$edmfield][$location][$version] = $row[$statistic];
        } elseif ($feature == 'multilinguality') {
          if (preg_match('/^(europeana|provider)_(.*?)_(taggedLiterals|languages|literalsPerLanguage)$/', $field, $matches)) {
            $location = $matches[1];
            $encodedfield = $matches[2];
            $property = $matches[3];
            $edmfield = preg_replace('/^([^_]+)_/', "$1:", $encodedfield);
            $timeline['fields'][$edmfield][$location][$property][$version] = $row[$statistic];
          } elseif (preg_match('/^(.*?)(InProviderProxy|InEuropeanaProxy|InObject)$/', $field, $matches)) {
            $property = $matches[1];
            $location = $matches[2];
            $timeline['general'][$property][$location][$version] = $row[$statistic];
          }
        }
      }
    }
  }

  if ($feature == 'multilinguality')
    ksort($timeline['general']);

  return $timeline;
}


function getTemplateName($feature) {
  if ($feature == 'completeness') {
    return 'timeline.completeness.smarty.tpl';
  } elseif ($feature == 'multilinguality') {
    return 'timeline.multilinguality.smarty.tpl';
  }
  return '';
}