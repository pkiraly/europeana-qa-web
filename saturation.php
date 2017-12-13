<?php
$configuration = parse_ini_file('config.cfg');
include_once('common-functions.php');

$global_metrics = [
  'languages_per_property' => 'Languages per property',
  'taggedliterals' => 'Tagged literals',
  'distinctlanguages' => 'Distinct languages',
  'taggedliterals_per_language' => 'Tagged literals per language'
];

$global_scopes = [
  'in_providerproxy' => 'Provider proxy',
  'in_europeanaproxy' => 'Europeana proxy',
  'in_object' => 'Whole object',
];

$field_scopes = [
  'provider' => 'Provider proxy',
  'europeana' => 'Europeana proxy',
];

$field_metrics = [
  'taggedliterals' => 'tagged literals',
  'languages' => 'languages',
  'literalsperlanguage' => 'literals per language',
];

// saturation2_provider_dc_publisher_taggedliterals
// saturation2_provider_dc_publisher_languages
// saturation2_provider_dc_publisher_literalsperlanguage

$fields = [
  'all' => 'Cumulated score',
  'dc_title' => 'dc:title',
  'dcterms_alternative' => 'dcterms:alternative',
  'dc_description' => 'dc:description',
  'dc_creator' => 'dc:creator',
  'dc_publisher' => 'dc:publisher',
  'dc_contributor' => 'dc:contributor',
  'dc_type' => 'dc:type',
  'dc_identifier' => 'dc:identifier',
  'dc_language' => 'dc:language',
  'dc_coverage' => 'dc:coverage',
  'dcterms_temporal' => 'dcterms:temporal',
  'dcterms_spatial' => 'dcterms:spatial',
  'dc_subject' => 'dc:subject',
  'dc_date' => 'dc:date',
  'dcterms_created' => 'dcterms:created',
  'dcterms_issued' => 'dcterms:issued',
  'dcterms_extent' => 'dcterms:extent',
  'dcterms_medium' => 'dcterms:medium',
  'dcterms_provenance' => 'dcterms:provenance',
  'dcterms_hasPart' => 'dcterms:hasPart',
  'dcterms_isPartOf' => 'dcterms:isPartOf',
  'dc_format' => 'dc:format',
  'dc_source' => 'dc:source',
  'dc_rights' => 'dc:rights',
  'dc_relation' => 'dc:relation',
  'edm_year' => 'edm:year',
  'edm_userTag' => 'edm:userTag',
  'dc_conformsTo' => 'dc:conformsTo',
  'dcterms_hasFormat' => 'dcterms:hasFormat',
  'dcterms_hasVersion' => 'dcterms:hasVersion',
  'dcterms_isFormatOf' => 'dcterms:isFormatOf',
  'dcterms_isReferencedBy' => 'dcterms:isReferencedBy',
  'dcterms_isReplacedBy' => 'dcterms:isReplacedBy',
  'dcterms_isRequiredBy' => 'dcterms:isRequiredBy',
  'dcterms_isVersionOf' => 'dcterms:isVersionOf',
  'dcterms_references' => 'dcterms:references',
  'dcterms_replaces' => 'dcterms:replaces',
  'dcterms_requires' => 'dcterms:requires',
  'dcterms_tableOfContents' => 'dcterms:tableOfContents',
  'edm_currentLocation' => 'edm:currentLocation',
  'edm_hasMet' => 'edm:hasMet',
  'edm_hasType' => 'edm:hasType',
  'edm_incorporates' => 'edm:incorporates',
  'edm_isDerivativeOf' => 'edm:isDerivativeOf',
  'edm_isRelatedTo' => 'edm:isRelatedTo',
  'edm_isRepresentationOf' => 'edm:isRepresentationOf',
  'edm_isSimilarTo' => 'edm:isSimilarTo',
  'edm_isSuccessorOf' => 'edm:isSuccessorOf',
  'edm_realizes' => 'edm:realizes',
  'edm_wasPresentAt' => 'edm:wasPresentAt',
];

$collectionId = getOrDefault('collectionId', 'all');

$form = getOrDefault('form', 'global');
$global_metric = getOrDefault('global_metric', array_keys($global_metrics)[0]);
$global_scope = getOrDefault('global_scope', array_keys($global_scopes)[0]);

$field = getOrDefault('field', array_keys($fields)[0]);
$field_metric = getOrDefault('field_metric', array_keys($field_metrics)[0]);
$field_scope = getOrDefault('field_scope', array_keys($field_scopes)[0]);

$prefix = getOrDefault('prefix', 'd');
$collectionType = ($prefix == 'd') ? 'data-providers' : 'datasets';
$suffix = '.saturation';

function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($collectionType . '.txt'));

if ($form == 'global') {
  $statistic = join('_', ['saturation2', $global_metric, $global_scope]);
} elseif ($form == 'field') {
  $statistic = join('_', ['saturation2', $field_scope, $field, $field_metric]);
}

$summaryFile = 'json_cache/saturation-' . strtolower($statistic) . '-' . $prefix . '.json';

$stat = [];
if ($collectionId == 'all') {
  if (file_exists($summaryFile)) {
    $stat = json_decode(file_get_contents($summaryFile));
    // $problems[] = sprintf("reading %s...\n", $summaryFile);
  } else {
    $max = 0;
    $counter = 0;
    foreach ($csv as $id => $row) {
      $id = $row[0];
      $collectionName = $row[1];
      $jsonFileName = $configuration['QA_R_PATH'] . '/json2/' . $prefix . $id . '/' . $prefix . $id . $suffix . '.json';
      if (file_exists($jsonFileName)) {
        if ($counter == 1) {
           // echo 'jsonFileName: ', $jsonFileName, "\n";
        }
        $stats = json_decode(file_get_contents($jsonFileName));
        // $assocStat = array();
        foreach ($stats as $obj) {
          if (strtolower($obj->_row) == strtolower($statistic)) {
            $counter++;
            $stat['values'][$prefix . $id] = ['value' => $obj->mean, 'name' => $collectionName];
            if ($obj->mean > $max)
              $max = $obj->mean;
            break;
          }
        }
      } else {
        // $problems[] = sprintf("jsonFileName (%s) is not existing\n", $jsonFileName);
      }
    }
    $stat['max'] = $max;
    file_put_contents($summaryFile, json_encode($stat));
    // $problems[] = sprintf("writing to %s...\n", $summaryFile);
  }
} else {
  $fileName = 'json/' . $collectionId . '/' . $collectionId . '.saturation.json';
  $languages = json_decode(file_get_contents($fileName));
}

include("templates/saturation/saturation.tpl.php");
