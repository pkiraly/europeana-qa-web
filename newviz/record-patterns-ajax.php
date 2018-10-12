<?php

$root = realpath(__DIR__. '/../');
$script = str_replace($root, '', __FILE__);

$configuration = parse_ini_file($root . '/config.cfg');
include_once($root . '/newviz/common.functions.php');

$parameters = getParameters();
$collectionId = $parameters->type . $parameters->id;
$count = isset($_GET['count']) ? (int)$_GET['count'] : -1;
$parameters->clustered = TRUE;

$dataDir = getDataDir();

$data = (object)[
  'fields' => getProfileFields($collectionId),
  'style' => 'jakob',
];

list($profiles, $fields) = getPatterns($collectionId, $count);
$data->profiles = $profiles;
$data->fields = $fields;

$tpl = $parameters->clustered
  ? 'record-patterns-clustered.smarty.tpl'
  : 'record-patterns.smarty.tpl';

$smarty = createSmarty('../templates/newviz/record-patterns/');
$smarty->assign('data', $data);
$smarty->display($tpl);

function getFieldsFile($collectionId) {
  global $dataDir;

  return $dataDir . '/json/' . $collectionId . '/' .  $collectionId . '-fields.csv';
}

function getProfileFile($collectionId) {
  global $dataDir, $parameters;

  if ($parameters->clustered)
    return $dataDir . '/json/' . $collectionId . '/' . $collectionId . '-profiles-clustered.csv';
  else
    return $dataDir . '/json/' . $collectionId . '/' . $collectionId . '-profiles.csv';
}

function getProfileFields($collectionId) {
  return reorderFields(explode(';', file_get_contents(getFieldsFile($collectionId))));
}

function getPatterns($collectionId, $count) {
  global $parameters;

  if ($parameters->clustered)
    return getClusteredPatterns($collectionId, $count);
  else
    return getIndividualPatterns($collectionId, $count);
}

function getClusteredPatterns($collectionId, $count) {
  global $parameters;

  $fileName = getProfileFile($collectionId);

  $clusters = [];
  $profiles = [];
  if ($file = fopen($fileName, "r")) {
    $lineSet = FALSE;
    while(!feof($file)) {
      $line = trim(fgets($file));
      if ($line == '' || preg_match('/^#/', $line))
        continue;
      $row = [];
      list($row['clusterID'], $profile, $row['length'], $row['count'],
          $row['total'], $row['percent']) = explode(',', $line);
      $row['profileFields'] = explode(';', $profile);
      if ($row['percent'] * 100 < 1 && !$lineSet) {
        $lineSet = TRUE;
        $drawLine = TRUE;
      } else {
        $drawLine = FALSE;
      }
      $row['drawLine'] = $drawLine;
      $profiles[] = $row;
      $clusters[$row['clusterID']]['rows'][] = $row;
    }
    fclose($file);
  }

  $lineSet = FALSE;
  $allFields = [];
  foreach ($clusters as $id => $cluster) {
    $profileFields = [];
    $cluster['id'] = $id;
    $cluster['patternCount'] = count($cluster['rows']);
    $cluster['total'] = -1;
    $cluster['count'] = 0;
    $cluster['fieldMin'] = 0;
    $cluster['fieldMax'] = 0;
    foreach ($cluster['rows'] as $row) {
      foreach ($row['profileFields'] as $field) {
        if (!isset($profileFields[$field]))
          $profileFields[$field] = ['pattern' => 0, 'record' => 0];
        $profileFields[$field]['pattern']++;
        $profileFields[$field]['record'] += $row['count'];

        if (!isset($allFields[$field]))
          $allFields[$field] = 0;
        $allFields[$field] += $row['count'];
      }
      $cluster['count'] += $row['count'];

      if ($cluster['total'] == -1)
        $cluster['total'] = $row['total'];

      if ($cluster['fieldMin'] == 0 || $cluster['fieldMin'] > $row['length'])
        $cluster['fieldMin'] = $row['length'];

      if ($cluster['fieldMax'] < $row['length'])
        $cluster['fieldMax'] = $row['length'];
    }
    $cluster['length'] = ($cluster['fieldMin'] != $cluster['fieldMax'])
      ? sprintf("%d-%d", $cluster['fieldMin'], $cluster['fieldMax'])
      : $cluster['fieldMin'];

    $cluster['percent'] = $cluster['count'] * 100 / $cluster['total'];
    foreach ($profileFields as $field => $counter) {
      $cluster['profileFields'][$field]['class'] = ($counter['pattern'] == $cluster['patternCount'])
        ? 'filled'
        : 'semi-filled';
      $cluster['profileFields'][$field]['opacity'] = $counter['record'] / $cluster['count'];
    }
    if ($cluster['percent'] < 1 && !$lineSet) {
      $lineSet = TRUE;
      $cluster['drawLine'] = TRUE;
    } else {
      $cluster['drawLine'] = FALSE;
    }

    $cluster['underOne'] = ($cluster['percent'] < 1);

    $clusters[$id] = $cluster;
  }
  arsort($allFields);

  return [$clusters, $allFields];
}

function getIndividualPatterns($collectionId, $count) {
  global $parameters;

  $fileName = getProfileFile($collectionId);

  $profiles = [];
  if ($file = fopen($fileName, "r")) {
    $lineSet = FALSE;
    while(!feof($file)) {
      $line = trim(fgets($file));
      if ($line == '')
        continue;
      $row = [];
      if ($parameters->clustered) {
        list($row['clusterID'], $profile, $row['length'], $row['count'],
             $row['total'], $row['percent']) = explode(',', $line);
      } else {
        list($profile, $row['nr'], $row['occurence'], $row['percent']) = explode(',', $line);
        if ($count != -1)
          $row['percent'] = $row['occurence'] / $count;
      }
      $row['profileFields'] = explode(';', $profile);
      if ($row['percent'] * 100 < 1 && !$lineSet) {
        $lineSet = TRUE;
        $drawLine = TRUE;
      } else {
        $drawLine = FALSE;
      }
      $row['drawLine'] = $drawLine;
      $profiles[] = $row;
    }
    fclose($file);
  }

  return $profiles;
}

function reorderFields($fields) {
  $canonicalOrder = [
    "dc:title", "dcterms:alternative", "dc:description", "dc:creator",
    "dc:publisher", "dc:contributor", "dc:type", "dc:identifier", "dc:language",
    "dc:coverage", "dcterms:temporal", "dcterms:spatial", "dc:subject", "dc:date",
    "dcterms:created", "dcterms:issued", "dcterms:extent", "dcterms:medium",
    "dcterms:provenance", "dcterms:hasPart", "dcterms:isPartOf", "dc:format",
    "dc:source", "dc:rights", "dc:relation", "edm:isNextInSequence", "edm:type",
    "edm:europeanaProxy", "edm:year", "edm:userTag", "ore:proxyIn", "ore:proxyFor",
    "dcterms:conformsTo", "dcterms:hasFormat", "dcterms:hasVersion",
    "dcterms:isFormatOf", "dcterms:isReferencedBy", "dcterms:isReplacedBy",
    "dcterms:isRequiredBy", "dcterms:isVersionOf", "dcterms:references",
    "dcterms:replaces", "dcterms:requires", "dcterms:tableOfContents",
    "edm:currentLocation", "edm:hasMet", "edm:hasType", "edm:incorporates",
    "edm:isDerivativeOf", "edm:isRelatedTo", "edm:isRepresentationOf",
    "edm:isSimilarTo", "edm:isSuccessorOf", "edm:realizes", "edm:wasPresentAt"
  ];
  $ordered = [];
  foreach ($canonicalOrder as $field) {
    if (in_array($field, $fields)) {
      $ordered[] = $field;
    }
  }
  if (count($ordered) != count($fields)) {
    $fields = array_diff($fields, $ordered);
    error_log("unrecognized fields: " . implode(', ', $fields));
  }
  return $ordered;
}
