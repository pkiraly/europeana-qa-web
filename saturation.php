<?php
$configuration = parse_ini_file('config.cfg');

$types = [
  'sum' => 'cumulative',
  'average' => 'average',
  'normalized' => 'normalized average',
];

$fields = [
  'all' => 'Cumulated score',
  'proxy_dc_title' => 'Proxy / dc:title',
  'proxy_dcterms_alternative' => 'Proxy / dcterms:alternative',
  'proxy_dc_description' => 'Proxy / dc:description',
  'proxy_dc_creator' => 'Proxy / dc:creator',
  'proxy_dc_publisher' => 'Proxy / dc:publisher',
  'proxy_dc_contributor' => 'Proxy / dc:contributor',
  'proxy_dc_type' => 'Proxy / dc:type',
  'proxy_dc_identifier' => 'Proxy / dc:identifier',
  'proxy_dc_language' => 'Proxy / dc:language',
  'proxy_dc_coverage' => 'Proxy / dc:coverage',
  'proxy_dcterms_temporal' => 'Proxy / dcterms:temporal',
  'proxy_dcterms_spatial' => 'Proxy / dcterms:spatial',
  'proxy_dc_subject' => 'Proxy / dc:subject',
  'proxy_dc_date' => 'Proxy / dc:date',
  'proxy_dcterms_created' => 'Proxy / dcterms:created',
  'proxy_dcterms_issued' => 'Proxy / dcterms:issued',
  'proxy_dcterms_extent' => 'Proxy / dcterms:extent',
  'proxy_dcterms_medium' => 'Proxy / dcterms:medium',
  'proxy_dcterms_provenance' => 'Proxy / dcterms:provenance',
  'proxy_dcterms_hasPart' => 'Proxy / dcterms:hasPart',
  'proxy_dcterms_isPartOf' => 'Proxy / dcterms:isPartOf',
  'proxy_dc_format' => 'Proxy / dc:format',
  'proxy_dc_source' => 'Proxy / dc:source',
  'proxy_dc_rights' => 'Proxy / dc:rights',
  'proxy_dc_relation' => 'Proxy / dc:relation',
  'proxy_edm_europeanaProxy' => 'Proxy / edm:europeanaProxy',
  'proxy_edm_year' => 'Proxy / edm:year',
  'proxy_edm_userTag' => 'Proxy / edm:userTag',
  'proxy_ore_ProxyIn' => 'Proxy / ore:ProxyIn',
  'proxy_ore_ProxyFor' => 'Proxy / ore:ProxyFor',
  'proxy_dc_conformsTo' => 'Proxy / dc:conformsTo',
  'proxy_dcterms_hasFormat' => 'Proxy / dcterms:hasFormat',
  'proxy_dcterms_hasVersion' => 'Proxy / dcterms:hasVersion',
  'proxy_dcterms_isFormatOf' => 'Proxy / dcterms:isFormatOf',
  'proxy_dcterms_isReferencedBy' => 'Proxy / dcterms:isReferencedBy',
  'proxy_dcterms_isReplacedBy' => 'Proxy / dcterms:isReplacedBy',
  'proxy_dcterms_isRequiredBy' => 'Proxy / dcterms:isRequiredBy',
  'proxy_dcterms_isVersionOf' => 'Proxy / dcterms:isVersionOf',
  'proxy_dcterms_references' => 'Proxy / dcterms:references',
  'proxy_dcterms_replaces' => 'Proxy / dcterms:replaces',
  'proxy_dcterms_requires' => 'Proxy / dcterms:requires',
  'proxy_dcterms_tableOfContents' => 'Proxy / dcterms:tableOfContents',
  'proxy_edm_currentLocation' => 'Proxy / edm:currentLocation',
  'proxy_edm_hasMet' => 'Proxy / edm:hasMet',
  'proxy_edm_hasType' => 'Proxy / edm:hasType',
  'proxy_edm_incorporates' => 'Proxy / edm:incorporates',
  'proxy_edm_isDerivativeOf' => 'Proxy / edm:isDerivativeOf',
  'proxy_edm_isRelatedTo' => 'Proxy / edm:isRelatedTo',
  'proxy_edm_isRepresentationOf' => 'Proxy / edm:isRepresentationOf',
  'proxy_edm_isSimilarTo' => 'Proxy / edm:isSimilarTo',
  'proxy_edm_isSuccessorOf' => 'Proxy / edm:isSuccessorOf',
  'proxy_edm_realizes' => 'Proxy / edm:realizes',
  'proxy_edm_wasPresentAt' => 'Proxy / edm:wasPresentAt',
  'aggregation_edm_rights' => 'Aggregation / edm:rights',
  'aggregation_edm_provider' => 'Aggregation / edm:provider',
  'aggregation_edm_dataProvider' => 'Aggregation / edm:dataProvider',
  'aggregation_dc_rights' => 'Aggregation / dc:rights',
  'aggregation_edm_ugc' => 'Aggregation / edm:ugc',
  'aggregation_edm_aggregatedCHO' => 'Aggregation / edm:aggregatedCHO',
  'aggregation_edm_intermediateProvider' => 'Aggregation / edm:intermediateProvider',
  'place_dcterms_isPartOf' => 'Place / dcterms:isPartOf',
  'place_dcterms_hasPart' => 'Place / dcterms:hasPart',
  'place_skos_prefLabel' => 'Place / skos:prefLabel',
  'place_skos_altLabel' => 'Place / skos:altLabel',
  'place_skos_note' => 'Place / skos:note',
  'agent_edm_begin' => 'Agent / edm:begin',
  'agent_edm_end' => 'Agent / edm:end',
  'agent_edm_hasMet' => 'Agent / edm:hasMet',
  'agent_edm_isRelatedTo' => 'Agent / edm:isRelatedTo',
  'agent_owl_sameAs' => 'Agent / owl:sameAs',
  'agent_foaf_name' => 'Agent / foaf:name',
  'agent_dc_date' => 'Agent / dc:date',
  'agent_dc_identifier' => 'Agent / dc:identifier',
  'agent_rdaGr2_dateOfBirth' => 'Agent / rdaGr2:dateOfBirth',
  'agent_rdaGr2_placeOfBirth' => 'Agent / rdaGr2:placeOfBirth',
  'agent_rdaGr2_dateOfDeath' => 'Agent / rdaGr2:dateOfDeath',
  'agent_rdaGr2_placeOfDeath' => 'Agent / rdaGr2:placeOfDeath',
  'agent_rdaGr2_dateOfEstablishment' => 'Agent / rdaGr2:dateOfEstablishment',
  'agent_rdaGr2_dateOfTermination' => 'Agent / rdaGr2:dateOfTermination',
  'agent_rdaGr2_gender' => 'Agent / rdaGr2:gender',
  'agent_rdaGr2_professionOrOccupation' => 'Agent / rdaGr2:professionOrOccupation',
  'agent_rdaGr2_biographicalInformation' => 'Agent / rdaGr2:biographicalInformation',
  'agent_skos_prefLabel' => 'Agent / skos:prefLabel',
  'agent_skos_altLabel' => 'Agent / skos:altLabel',
  'agent_skos_note' => 'Agent / skos:note',
  'timespan_edm_begin' => 'Timespan / edm:begin',
  'timespan_edm_end' => 'Timespan / edm:end',
  'timespan_dcterms_isPartOf' => 'Timespan / dcterms:isPartOf',
  'timespan_dcterms_hasPart' => 'Timespan / dcterms:hasPart',
  'timespan_edm_isNextInSequence' => 'Timespan / edm:isNextInSequence',
  'timespan_owl_sameAs' => 'Timespan / owl:sameAs',
  'timespan_skos_prefLabel' => 'Timespan / skos:prefLabel',
  'timespan_skos_altLabel' => 'Timespan / skos:altLabel',
  'timespan_skos_note' => 'Timespan / skos:note',
  'concept_skos_broader' => 'Concept / skos:broader',
  'concept_skos_narrower' => 'Concept / skos:narrower',
  'concept_skos_related' => 'Concept / skos:related',
  'concept_skos_broadMatch' => 'Concept / skos:broadMatch',
  'concept_skos_narrowMatch' => 'Concept / skos:narrowMatch',
  'concept_skos_relatedMatch' => 'Concept / skos:relatedMatch',
  'concept_skos_exactMatch' => 'Concept / skos:exactMatch',
  'concept_skos_closeMatch' => 'Concept / skos:closeMatch',
  'concept_skos_notation' => 'Concept / skos:notation',
  'concept_skos_inScheme' => 'Concept / skos:inScheme',
  'concept_skos_prefLabel' => 'Concept / skos:prefLabel',
  'concept_skos_altLabel' => 'Concept / skos:altLabel',
  'concept_skos_note' => 'Concept / skos:note',
  // 'saturation_sum_aggregated' => 'All fields aggregated'
];

$collectionId = $_GET['collectionId'];
if (!isset($collectionId))
  $collectionId = 'all';

$field = $_GET['field'];
if (!isset($field))
  $field = 'all';

$type = $_GET['type'];
if (!isset($type))
  $type = 'sum';

$collectionType = 'data-providers';
$prefix = 'd';
$suffix = '.saturation';
function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($collectionType . '.txt'));

if ($field == 'all') {
  $statistic = 'saturation_' . $type;
} else {
  $statistic = 'saturation_' . $type . '_' . $field;
}

$summaryFile = 'json_cache/saturation-' . strtolower($statistic) . '-' . $prefix . '.json';

$stat = [];
if ($collectionId == 'all') {
  if (file_exists($summaryFile)) {
    $stat = json_decode(file_get_contents($summaryFile));
    // $problems[] = sprintf("reading %s...\n", $summaryFile);
  } else {
    $max = 0;
    foreach ($csv as $id => $row) {
      $id = $row[0];
      $collectionName = $row[1];
      $jsonFileName = $configuration['QA_R_PATH'] . '/json2/' . $prefix . $id . $suffix . '.json';
      if (file_exists($jsonFileName)) {
        if ($counter == 1) {
           // echo 'jsonFileName: ', $jsonFileName, "\n";
        }
        $stats = json_decode(file_get_contents($jsonFileName));
        $assocStat = array();
        foreach ($stats as $obj) {
          if (strtolower($obj->_row) == strtolower($statistic)) {
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
  $fileName = 'json/' . $collectionId . '.saturation.json';
  $languages = json_decode(file_get_contents($fileName));
}

include("saturation.tpl.php");
