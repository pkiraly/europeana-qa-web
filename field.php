<?php
$configuration = parse_ini_file('config.cfg');

$fields = [
/*
  'identifier' => 'identifier',
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
  'proxy_edm_isNextInSequence' => 'Proxy / edm:isNextInSequence',
  'proxy_edm_type' => 'Proxy / edm:type',
  'aggregation_edm_rights' => 'Aggregation / edm:rights',
  'aggregation_edm_provider' => 'Aggregation / edm:provider',
  'aggregation_edm_dataProvider' => 'Aggregation / edm:dataProvider',
  'aggregation_edm_isShownAt' => 'Aggregation / edm:isShownAt',
  'aggregation_edm_isShownBy' => 'Aggregation / edm:isShownBy',
  'aggregation_edm_object' => 'Aggregation / edm:object',
  'aggregation_edm_hasView' => 'Aggregation / edm:hasView',
*/

  'providedcho_rdf_about' => 'ProvidedCHO/rdf:about',
  'proxy_dc_title' => 'Proxy/dc:title',
  'proxy_dcterms_alternative' => 'Proxy/dcterms:alternative',
  'proxy_dc_description' => 'Proxy/dc:description',
  'proxy_dc_creator' => 'Proxy/dc:creator',
  'proxy_dc_publisher' => 'Proxy/dc:publisher',
  'proxy_dc_contributor' => 'Proxy/dc:contributor',
  'proxy_dc_type' => 'Proxy/dc:type',
  'proxy_dc_identifier' => 'Proxy/dc:identifier',
  'proxy_dc_language' => 'Proxy/dc:language',
  'proxy_dc_coverage' => 'Proxy/dc:coverage',
  'proxy_dcterms_temporal' => 'Proxy/dcterms:temporal',
  'proxy_dcterms_spatial' => 'Proxy/dcterms:spatial',
  'proxy_dc_subject' => 'Proxy/dc:subject',
  'proxy_dc_date' => 'Proxy/dc:date',
  'proxy_dcterms_created' => 'Proxy/dcterms:created',
  'proxy_dcterms_issued' => 'Proxy/dcterms:issued',
  'proxy_dcterms_extent' => 'Proxy/dcterms:extent',
  'proxy_dcterms_medium' => 'Proxy/dcterms:medium',
  'proxy_dcterms_provenance' => 'Proxy/dcterms:provenance',
  'proxy_dcterms_haspart' => 'Proxy/dcterms:hasPart',
  'proxy_dcterms_ispartof' => 'Proxy/dcterms:isPartOf',
  'proxy_dc_format' => 'Proxy/dc:format',
  'proxy_dc_source' => 'Proxy/dc:source',
  'proxy_dc_rights' => 'Proxy/dc:rights',
  'proxy_dc_relation' => 'Proxy/dc:relation',
  'proxy_edm_isnextinsequence' => 'Proxy/edm:isNextInSequence',
  'proxy_edm_type' => 'Proxy/edm:type',
  'proxy_edm_europeanaproxy' => 'Proxy/edm:europeanaProxy',
  'proxy_edm_year' => 'Proxy/edm:year',
  'proxy_edm_usertag' => 'Proxy/edm:userTag',
  'proxy_ore_proxyin' => 'Proxy/ore:ProxyIn',
  'proxy_ore_proxyfor' => 'Proxy/ore:ProxyFor',
  'proxy_dc_conformsto' => 'Proxy/dc:conformsTo',
  'proxy_dcterms_hasformat' => 'Proxy/dcterms:hasFormat',
  'proxy_dcterms_hasversion' => 'Proxy/dcterms:hasVersion',
  'proxy_dcterms_isformatof' => 'Proxy/dcterms:isFormatOf',
  'proxy_dcterms_isreferencedby' => 'Proxy/dcterms:isReferencedBy',
  'proxy_dcterms_isreplacedby' => 'Proxy/dcterms:isReplacedBy',
  'proxy_dcterms_isrequiredby' => 'Proxy/dcterms:isRequiredBy',
  'proxy_dcterms_isversionof' => 'Proxy/dcterms:isVersionOf',
  'proxy_dcterms_references' => 'Proxy/dcterms:references',
  'proxy_dcterms_replaces' => 'Proxy/dcterms:replaces',
  'proxy_dcterms_requires' => 'Proxy/dcterms:requires',
  'proxy_dcterms_tableofcontents' => 'Proxy/dcterms:tableOfContents',
  'proxy_edm_currentlocation' => 'Proxy/edm:currentLocation',
  'proxy_edm_hasmet' => 'Proxy/edm:hasMet',
  'proxy_edm_hastype' => 'Proxy/edm:hasType',
  'proxy_edm_incorporates' => 'Proxy/edm:incorporates',
  'proxy_edm_isderivativeof' => 'Proxy/edm:isDerivativeOf',
  'proxy_edm_isrelatedto' => 'Proxy/edm:isRelatedTo',
  'proxy_edm_isrepresentationof' => 'Proxy/edm:isRepresentationOf',
  'proxy_edm_issimilarto' => 'Proxy/edm:isSimilarTo',
  'proxy_edm_issuccessorof' => 'Proxy/edm:isSuccessorOf',
  'proxy_edm_realizes' => 'Proxy/edm:realizes',
  'proxy_edm_waspresentat' => 'Proxy/edm:wasPresentAt',
  'aggregation_edm_rights' => 'Aggregation/edm:rights',
  'aggregation_edm_provider' => 'Aggregation/edm:provider',
  'aggregation_edm_dataprovider' => 'Aggregation/edm:dataProvider',
  'aggregation_edm_isshownat' => 'Aggregation/edm:isShownAt',
  'aggregation_edm_isshownby' => 'Aggregation/edm:isShownBy',
  'aggregation_edm_object' => 'Aggregation/edm:object',
  'aggregation_edm_hasview' => 'Aggregation/edm:hasView',
  'aggregation_dc_rights' => 'Aggregation/dc:rights',
  'aggregation_edm_ugc' => 'Aggregation/edm:ugc',
  'aggregation_edm_aggregatedcho' => 'Aggregation/edm:aggregatedCHO',
  'aggregation_edm_intermediateprovider' => 'Aggregation/edm:intermediateProvider',
  'aggregation_rdf_about' => 'Aggregation/rdf:about',
  'place_wgs84_lat' => 'Place/wgs84:lat',
  'place_wgs84_long' => 'Place/wgs84:long',
  'place_wgs84_alt' => 'Place/wgs84:alt',
  'place_dcterms_ispartof' => 'Place/dcterms:isPartOf',
  'place_wgs84_pos_lat_long' => 'Place/wgs84:pos:lat:long',
  'place_dcterms_haspart' => 'Place/dcterms:hasPart',
  'place_owl_sameas' => 'Place/owl:sameAs',
  'place_skos_preflabel' => 'Place/skos:prefLabel',
  'place_skos_altlabel' => 'Place/skos:altLabel',
  'place_skos_note' => 'Place/skos:note',
  'place_rdf_about' => 'Place/rdf:about',
  'agent_rdf_about' => 'Agent/rdf:about',
  'agent_edm_begin' => 'Agent/edm:begin',
  'agent_edm_end' => 'Agent/edm:end',
  'agent_edm_hasmet' => 'Agent/edm:hasMet',
  'agent_edm_isrelatedto' => 'Agent/edm:isRelatedTo',
  'agent_owl_sameas' => 'Agent/owl:sameAs',
  'agent_foaf_name' => 'Agent/foaf:name',
  'agent_dc_date' => 'Agent/dc:date',
  'agent_dc_identifier' => 'Agent/dc:identifier',
  'agent_rdagr2_dateofbirth' => 'Agent/rdaGr2:dateOfBirth',
  'agent_rdagr2_placeofbirth' => 'Agent/rdaGr2:placeOfBirth',
  'agent_rdagr2_dateofdeath' => 'Agent/rdaGr2:dateOfDeath',
  'agent_rdagr2_placeofdeath' => 'Agent/rdaGr2:placeOfDeath',
  'agent_rdagr2_dateofestablishment' => 'Agent/rdaGr2:dateOfEstablishment',
  'agent_rdagr2_dateoftermination' => 'Agent/rdaGr2:dateOfTermination',
  'agent_rdagr2_gender' => 'Agent/rdaGr2:gender',
  'agent_rdagr2_professionoroccupation' => 'Agent/rdaGr2:professionOrOccupation',
  'agent_rdagr2_biographicalinformation' => 'Agent/rdaGr2:biographicalInformation',
  'agent_skos_preflabel' => 'Agent/skos:prefLabel',
  'agent_skos_altlabel' => 'Agent/skos:altLabel',
  'agent_skos_note' => 'Agent/skos:note',
  'timespan_rdf_about' => 'Timespan/rdf:about',
  'timespan_edm_begin' => 'Timespan/edm:begin',
  'timespan_edm_end' => 'Timespan/edm:end',
  'timespan_dcterms_ispartof' => 'Timespan/dcterms:isPartOf',
  'timespan_dcterms_haspart' => 'Timespan/dcterms:hasPart',
  'timespan_edm_isnextinsequence' => 'Timespan/edm:isNextInSequence',
  'timespan_owl_sameas' => 'Timespan/owl:sameAs',
  'timespan_skos_preflabel' => 'Timespan/skos:prefLabel',
  'timespan_skos_altlabel' => 'Timespan/skos:altLabel',
  'timespan_skos_note' => 'Timespan/skos:note',
  'concept_rdf_about' => 'Concept/rdf:about',
  'concept_skos_broader' => 'Concept/skos:broader',
  'concept_skos_narrower' => 'Concept/skos:narrower',
  'concept_skos_related' => 'Concept/skos:related',
  'concept_skos_broadmatch' => 'Concept/skos:broadMatch',
  'concept_skos_narrowmatch' => 'Concept/skos:narrowMatch',
  'concept_skos_relatedmatch' => 'Concept/skos:relatedMatch',
  'concept_skos_exactmatch' => 'Concept/skos:exactMatch',
  'concept_skos_closematch' => 'Concept/skos:closeMatch',
  'concept_skos_notation' => 'Concept/skos:notation',
  'concept_skos_inscheme' => 'Concept/skos:inScheme',
  'concept_skos_preflabel' => 'Concept/skos:prefLabel',
  'concept_skos_altlabel' => 'Concept/skos:altLabel',
  'concept_skos_note' => 'Concept/skos:note',
];

$field = $_GET['field'];
if (!isset($field) || !isset($fields[$field])) {
  $field = 'identifier';
}


$types = [
  'data-providers' => 'Data providers',
  'datasets' => 'Data sets'
];
$type = $_GET['type'];
if (!isset($type) || !isset($types[$type])) {
  $type = 'datasets';
}
$prefix = $type == 'datasets' ? 'c' : 'd';

$excludeZeros = in_array(0, $_GET['exclusions']);
$excludeOnes = in_array(1, $_GET['exclusions']);
$excludeRest = in_array(2, $_GET['exclusions']);

// $csv = array_map('str_getcsv', file('collection-names.csv'));

function parse_csv($line) {
  return str_getcsv($line, ';');
}

$fieldSummaryFile = 'json_cache/field-summary-' . $field . '-' . $prefix . '.json';
if (!file_exists($fieldSummaryFile)) {
  $csv = array_map('parse_csv', file($type . '.txt'));
  $counter = 1;
  $rows = array();
  // include('dataset-directory-header.tpl.php');
  foreach ($csv as $id => $row) {
    $id = $row[0];
    $collectionId = $row[1];

    $jsonFileName = 'json/' . $prefix . $id . '.freq.json';
    if (file_exists($jsonFileName)) {
      if ($counter == 1) {
        // echo $jsonFileName, ' ', $jsonFileName;
      }
      $stats = json_decode(file_get_contents($jsonFileName));
      $assocStat = array();
      foreach ($stats as $obj) {
        if ($counter == 1) {
          // echo json_encode($obj);
        }
        if ($obj->field == $field) {
          // unset($obj->recMin);
          // unset($obj->recMax);
          // unset($obj->_row);
          $obj->id = $id;
          $obj->type = $prefix;
          $obj->collectionId = $collectionId;
          $rows[] = $obj;
          break;
        }
      }
    }
  }
  file_put_contents($fieldSummaryFile, json_encode($rows));
}

include("field.tpl.php");
