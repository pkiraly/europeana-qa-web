<?php
define('LN', "\n");
define('DEFAULT_FEATURE', 'all');

$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');
include_once('newviz/common.functions.php');

$features = [
  'all' => 'All multilingual dimensions (averages)',
  'saturation2_languages_per_property_in_providerproxy' => 'Languages per property in provider proxy',
  'saturation2_languages_per_property_in_europeanaproxy' => 'Languages per property in Europeana proxy',
  'saturation2_languages_per_property_in_object' => 'Languages per property in object',
  'saturation2_taggedliterals_in_providerproxy' => 'Tagged literals in provider proxy',
  'saturation2_taggedliterals_in_europeanaproxy' => 'Tagged literals in Europeana proxy',
  'saturation2_taggedliterals_in_object' => 'Tagged literals in object',
  'saturation2_distinctlanguages_in_providerproxy' => 'Distinct languages in provider proxy',
  'saturation2_distinctlanguages_in_europeanaproxy' => 'Distinct languages in Europeana proxy',
  'saturation2_distinctlanguages_in_object' => 'Distinct languages in object',
  'saturation2_taggedliterals_per_language_in_providerproxy' => 'Tagged literals per language in provider proxy',
  'saturation2_taggedliterals_per_language_in_europeanaproxy' => 'Tagged literals per language in Europeana proxy',
  'saturation2_taggedliterals_per_language_in_object' => 'Tagged literals per language in object',
];

$version  = getOrDefault('version', $configuration['version'][0], $configuration['version']);

$dataDir = getDataDir();
error_log('dataDir: ' . $dataDir);

$feature = getOrDefault('feature', DEFAULT_FEATURE, array_keys($features));

$types = [
  'data-providers' => 'Data providers',
  'datasets' => 'Data sets'
];

$statistics = [
  'mean' => 'mean',
  'median' => 'median'
];

$type = getOrDefault('type', 'data-providers');
$statistic = getOrDefault('statistic', 'mean');
$prefix = ($type == 'datasets') ? 'c' : 'd';

function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($dataDir . '/' . $type . '.txt'));

if ($feature == 'all') {
  $summaryFile = sprintf('json_cache/%s-multilinguality-%s-%s-%s.json', $version, $feature, $prefix, $statistic);
} else {
  $summaryFile = sprintf('json_cache/%s-multilinguality-%s-%s.json', $version, $feature, $prefix);
}
error_log('summaryFile: ' . $summaryFile);
$suffix = '.saturation';
$isSaturation2 = true;

$datasetLink = $isSaturation2 ? 'newviz.php' : 'dataset.php';

$errors = [];
$rows = [];

if (file_exists($summaryFile)) {
  $stat = stat($summaryFile);
  // $errors[] = sprintf('Summary file: %s, size: %d', $summaryFile, $stat['size']);
  $rows = json_decode(file_get_contents($summaryFile));
} else {
  set_time_limit(0);
  $counter = 1;
  $to_replace = [chr(0x82), chr(0x83), '', '', 'Ã'];
  $aggregation = (object)[
    'n' => 0,
    'id' => 'all',
    'collectionId' => 'all',
    'type' => $prefix
  ];

  foreach ($csv as $id => $row) {
    $id = $row[0];
    // error_log('id: ' . $id);
    $collectionId = $row[1];

    if (preg_match('/Landesbibliothek, Darmstadt/', $collectionId)) {
      $collectionId = 'Universitäts- und Landesbibliothek, Darmstadt';
    }

    $n = 0;
    $jsonCountFileName = $dataDir . '/json/' . $prefix . $id . '/' . $prefix . $id . '.count.json';
    if (file_exists($jsonCountFileName)) {
      $stats = json_decode(file_get_contents($jsonCountFileName));
      $n = $stats[0]->count;
    }

    $jsonFileName = $dataDir . '/json/' . $prefix . $id . '/' . $prefix . $id . $suffix . '.json';
    if (file_exists($jsonFileName)) {
      if ($counter == 1) {
        // echo 'jsonFileName: ', $jsonFileName, "\n";
      }
      $stats = json_decode(file_get_contents($jsonFileName));
      $assocStat = array();
      $obj2 = new stdClass();

      foreach ($stats as $obj) {
        if ($counter == 1) {
          // echo json_encode($obj);
        }

        if ($feature == 'all') {
          if (in_array($obj->_row, array_keys($features))) {
            $obj2->{$obj->_row} = $obj->$statistic;
          }
        } else {
          if ($obj->_row == $feature) {
            unset($obj->recMin);
            unset($obj->recMax);
            unset($obj->_row);
            $obj->n = $n;
            $obj->id = $id;
            $obj->type = $prefix;
            $obj->collectionId = $collectionId;
            $rows[$counter++] = $obj;

            $prev_n = $aggregation->n;
            $aggregation->n += $obj->n;
            if (!isset($aggregation->min) || $aggregation->min > $obj->min)
              $aggregation->min = $obj->min;
            if (!isset($aggregation->max) || $aggregation->max < $obj->max)
              $aggregation->max = $obj->max;
            if (!isset($aggregation->mean)) {
              $aggregation->mean = $obj->mean;
            } else {
              $aggregation->mean = (($aggregation->mean * ($aggregation->n - $obj->n)) + ($obj->mean * $obj->n)) / $aggregation->n;
            }

            if (isset($obj->{'std.dev'})) {
              if (!isset($aggregation->{'std.dev'})) {
                $aggregation->{'std.dev'} = $obj->{'std.dev'};
              } else {
                $aggr_avg = $aggregation->{'std.dev'} * $prev_n;
                $obj_avg = $obj->{'std.dev'} * $obj->n;
                $aggregation->{'std.dev'} = ($aggr_avg + $obj_avg) / $aggregation->n;
              }
            }

            break;
          }
        }
      }
      if ($feature == 'all') {
        $obj2->n = $n;
        $obj2->id = $id;
        $obj2->type = $prefix;
        $obj2->collectionId = $collectionId;
        $rows[$counter++] = $obj2;
      }
    } else {
      // $errors[] = sprintf("jsonFileName (%s) is not existing\n", $jsonFileName);
    }
  }
  $aggregation->mean = number_format($aggregation->mean, 4);
  $aggregation->{'std.dev'} = number_format($aggregation->{'std.dev'}, 4);
  $rows[$counter++] = $aggregation;
  // echo 'count: ', count($rows), "\n";
  // if ($suffix != '.weighted-completeness' && $suffix != '.saturation')
  file_put_contents($summaryFile, json_encode($rows));
}


$smarty = createSmarty('templates/multilinguality');
$smarty->assign('rand', rand());
$smarty->assign('rows', $rows);
$smarty->assign('features', $features);
$smarty->assign('feature', $feature);
// $smarty->assign('collectionId', $collectionId);
// $smarty->assign('title', $title);
$smarty->assign('types', $types);
$smarty->assign('type', $type);
$smarty->assign('statistics', $statistics);
$smarty->assign('statistic', $statistic);

// $smarty->assign('fragment', $fragment);
// $smarty->assign('id', $id);
// $smarty->assign('collectionId', $collectionId);
// $smarty->assign('portalUrl', getPortalUrl($type, $collectionId));
$smarty->assign('version', $version);
// $smarty->assign('development', $development);
$smarty->assign('configuration', $configuration);

// $smarty->assign('datasets', $datasets);
// $smarty->assign('dataproviders', $dataproviders);
// $smarty->assign('entityCounts', $entityCounts);
// $smarty->assign('n', $n);

$smarty->assign('datasetLink', $datasetLink);
$smarty->assign('filePath', getRootPath());
$smarty->assign('errors', $errors);

$smarty->display('multilinguality.smarty.tpl');
