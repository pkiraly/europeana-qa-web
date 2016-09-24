<?php
define('LN', "\n");
$configuration = parse_ini_file('config.cfg');

$features = array(
  'total' => 'Every field',
  'mandatory' => 'Mandatory',
  'descriptiveness' => 'Descriptiveness',
  'searchability' => 'Searchability',
  'contextualization' => 'Contextualization',
  'identification' => 'Identification',
  'browsing' => 'Browsing',
  'viewing' => 'Viewing',
  'reusability' => 'Reusability',
  'multilinguality' => 'Multilinguality',

/*
  'entropy_dc_title_sum' => 'dc:title entropy - cumulative',
  'entropy_dc_title_avg' => 'dc:title entropy - average',
  'entropy_dcterms_alternative_sum' => 'dcterms:alternative entropy - cumulative',
  'entropy_dcterms_alternative_avg' => 'dctersm:alternative entorpy - average',
  'entropy_dc_description_sum' => 'dc:description entropy - cumulative',
  'entropy_dc_description_avg' => 'dc:description entorpy - average',
  'crd_identifier' => 'Cardinality of identifier',
  'crd_proxy_dc_title' => 'Cardinality of Proxy/dc:title',
  'crd_proxy_dcterms_alternative' => 'Cardinality of Proxy/dcterms:alternative',
  'crd_proxy_dc_description' => 'Cardinality of Proxy/dc:description',
  'crd_proxy_dc_creator' => 'Cardinality of Proxy/dc:creator',
  'crd_proxy_dc_publisher' => 'Cardinality of Proxy/dc:publisher',
  'crd_proxy_dc_contributor' => 'Cardinality of Proxy/dc:contributor',
  'crd_proxy_dc_type' => 'Cardinality of Proxy/dc:type',
  'crd_proxy_dc_identifier' => 'Cardinality of Proxy/dc:identifier',
  'crd_proxy_dc_language' => 'Cardinality of Proxy/dc:language',
  'crd_proxy_dc_coverage' => 'Cardinality of Proxy/dc:coverage',
  'crd_proxy_dcterms_temporal' => 'Cardinality of Proxy/dcterms:temporal',
  'crd_proxy_dcterms_spatial' => 'Cardinality of Proxy/dcterms:spatial',
  'crd_proxy_dc_subject' => 'Cardinality of Proxy/dc:subject',
  'crd_proxy_dc_date' => 'Cardinality of Proxy/dc:date',
  'crd_proxy_dcterms_created' => 'Cardinality of Proxy/dcterms:created',
  'crd_proxy_dcterms_issued' => 'Cardinality of Proxy/dcterms:issued',
  'crd_proxy_dcterms_extent' => 'Cardinality of Proxy/dcterms:extent',
  'crd_proxy_dcterms_medium' => 'Cardinality of Proxy/dcterms:medium',
  'crd_proxy_dcterms_provenance' => 'Cardinality of Proxy/dcterms:provenance',
  'crd_proxy_dcterms_hasPart' => 'Cardinality of Proxy/dcterms:hasPart',
  'crd_proxy_dcterms_isPartOf' => 'Cardinality of Proxy/dcterms:isPartOf',
  'crd_proxy_dc_format' => 'Cardinality of Proxy/dc:format',
  'crd_proxy_dc_source' => 'Cardinality of Proxy/dc:source',
  'crd_proxy_dc_rights' => 'Cardinality of Proxy/dc:rights',
  'crd_proxy_dc_relation' => 'Cardinality of Proxy/dc:relation',
  'crd_proxy_edm_isNextInSequence' => 'Cardinality of Proxy/edm:isNextInSequence',
  'crd_proxy_edm_type' => 'Cardinality of Proxy/edm:type',
  'crd_aggregation_edm_rights' => 'Cardinality of Aggregation/edm:rights',
  'crd_aggregation_edm_provider' => 'Cardinality of Aggregation/edm:provider',
  'crd_aggregation_edm_dataProvider' => 'Cardinality of Aggregation/edm:dataProvider',
  'crd_aggregation_edm_isShownAt' => 'Cardinality of Aggregation/edm:isShownAt',
  'crd_aggregation_edm_isShownBy' => 'Cardinality of Aggregation/edm:isShownBy',
  'crd_aggregation_edm_object' => 'Cardinality of Aggregation/edm:object',
  'crd_aggregation_edm_hasView' => 'Cardinality of Aggregation/edm:hasView',
*/

/*
  'providedcho_rdf_about' => 'Existence of ProvidedCHO/rdf:about',
  'proxy_dc_title' => 'Existence of Proxy/dc:title',
  'proxy_dcterms_alternative' => 'Existence of Proxy/dcterms:alternative',
  'proxy_dc_description' => 'Existence of Proxy/dc:description',
  'proxy_dc_creator' => 'Existence of Proxy/dc:creator',
  'proxy_dc_publisher' => 'Existence of Proxy/dc:publisher',
  'proxy_dc_contributor' => 'Existence of Proxy/dc:contributor',
  'proxy_dc_type' => 'Existence of Proxy/dc:type',
  'proxy_dc_identifier' => 'Existence of Proxy/dc:identifier',
  'proxy_dc_language' => 'Existence of Proxy/dc:language',
  'proxy_dc_coverage' => 'Existence of Proxy/dc:coverage',
  'proxy_dcterms_temporal' => 'Existence of Proxy/dcterms:temporal',
  'proxy_dcterms_spatial' => 'Existence of Proxy/dcterms:spatial',
  'proxy_dc_subject' => 'Existence of Proxy/dc:subject',
  'proxy_dc_date' => 'Existence of Proxy/dc:date',
  'proxy_dcterms_created' => 'Existence of Proxy/dcterms:created',
  'proxy_dcterms_issued' => 'Existence of Proxy/dcterms:issued',
  'proxy_dcterms_extent' => 'Existence of Proxy/dcterms:extent',
  'proxy_dcterms_medium' => 'Existence of Proxy/dcterms:medium',
  'proxy_dcterms_provenance' => 'Existence of Proxy/dcterms:provenance',
  'proxy_dcterms_haspart' => 'Existence of Proxy/dcterms:hasPart',
  'proxy_dcterms_ispartof' => 'Existence of Proxy/dcterms:isPartOf',
  'proxy_dc_format' => 'Existence of Proxy/dc:format',
  'proxy_dc_source' => 'Existence of Proxy/dc:source',
  'proxy_dc_rights' => 'Existence of Proxy/dc:rights',
  'proxy_dc_relation' => 'Existence of Proxy/dc:relation',
  'proxy_edm_isnextinsequence' => 'Existence of Proxy/edm:isNextInSequence',
  'proxy_edm_type' => 'Existence of Proxy/edm:type',
  'proxy_edm_europeanaproxy' => 'Existence of Proxy/edm:europeanaProxy',
  'proxy_edm_year' => 'Existence of Proxy/edm:year',
  'proxy_edm_usertag' => 'Existence of Proxy/edm:userTag',
  'proxy_ore_proxyin' => 'Existence of Proxy/ore:ProxyIn',
  'proxy_ore_proxyfor' => 'Existence of Proxy/ore:ProxyFor',
  'proxy_dc_conformsto' => 'Existence of Proxy/dc:conformsTo',
  'proxy_dcterms_hasformat' => 'Existence of Proxy/dcterms:hasFormat',
  'proxy_dcterms_hasversion' => 'Existence of Proxy/dcterms:hasVersion',
  'proxy_dcterms_isformatof' => 'Existence of Proxy/dcterms:isFormatOf',
  'proxy_dcterms_isreferencedby' => 'Existence of Proxy/dcterms:isReferencedBy',
  'proxy_dcterms_isreplacedby' => 'Existence of Proxy/dcterms:isReplacedBy',
  'proxy_dcterms_isrequiredby' => 'Existence of Proxy/dcterms:isRequiredBy',
  'proxy_dcterms_isversionof' => 'Existence of Proxy/dcterms:isVersionOf',
  'proxy_dcterms_references' => 'Existence of Proxy/dcterms:references',
  'proxy_dcterms_replaces' => 'Existence of Proxy/dcterms:replaces',
  'proxy_dcterms_requires' => 'Existence of Proxy/dcterms:requires',
  'proxy_dcterms_tableofcontents' => 'Existence of Proxy/dcterms:tableOfContents',
  'proxy_edm_currentlocation' => 'Existence of Proxy/edm:currentLocation',
  'proxy_edm_hasmet' => 'Existence of Proxy/edm:hasMet',
  'proxy_edm_hastype' => 'Existence of Proxy/edm:hasType',
  'proxy_edm_incorporates' => 'Existence of Proxy/edm:incorporates',
  'proxy_edm_isderivativeof' => 'Existence of Proxy/edm:isDerivativeOf',
  'proxy_edm_isrelatedto' => 'Existence of Proxy/edm:isRelatedTo',
  'proxy_edm_isrepresentationof' => 'Existence of Proxy/edm:isRepresentationOf',
  'proxy_edm_issimilarto' => 'Existence of Proxy/edm:isSimilarTo',
  'proxy_edm_issuccessorof' => 'Existence of Proxy/edm:isSuccessorOf',
  'proxy_edm_realizes' => 'Existence of Proxy/edm:realizes',
  'proxy_edm_waspresentat' => 'Existence of Proxy/edm:wasPresentAt',
  'aggregation_edm_rights' => 'Existence of Aggregation/edm:rights',
  'aggregation_edm_provider' => 'Existence of Aggregation/edm:provider',
  'aggregation_edm_dataprovider' => 'Existence of Aggregation/edm:dataProvider',
  'aggregation_edm_isshownat' => 'Existence of Aggregation/edm:isShownAt',
  'aggregation_edm_isshownby' => 'Existence of Aggregation/edm:isShownBy',
  'aggregation_edm_object' => 'Existence of Aggregation/edm:object',
  'aggregation_edm_hasview' => 'Existence of Aggregation/edm:hasView',
  'aggregation_dc_rights' => 'Existence of Aggregation/dc:rights',
  'aggregation_edm_ugc' => 'Existence of Aggregation/edm:ugc',
  'aggregation_edm_aggregatedcho' => 'Existence of Aggregation/edm:aggregatedCHO',
  'aggregation_edm_intermediateprovider' => 'Existence of Aggregation/edm:intermediateProvider',
  'aggregation_rdf_about' => 'Existence of Aggregation/rdf:about',
  'place_wgs84_lat' => 'Existence of Place/wgs84:lat',
  'place_wgs84_long' => 'Existence of Place/wgs84:long',
  'place_wgs84_alt' => 'Existence of Place/wgs84:alt',
  'place_dcterms_ispartof' => 'Existence of Place/dcterms:isPartOf',
  'place_wgs84_pos_lat_long' => 'Existence of Place/wgs84:pos:lat:long',
  'place_dcterms_haspart' => 'Existence of Place/dcterms:hasPart',
  'place_owl_sameas' => 'Existence of Place/owl:sameAs',
  'place_skos_preflabel' => 'Existence of Place/skos:prefLabel',
  'place_skos_altlabel' => 'Existence of Place/skos:altLabel',
  'place_skos_note' => 'Existence of Place/skos:note',
  'place_rdf_about' => 'Existence of Place/rdf:about',
  'agent_rdf_about' => 'Existence of Agent/rdf:about',
  'agent_edm_begin' => 'Existence of Agent/edm:begin',
  'agent_edm_end' => 'Existence of Agent/edm:end',
  'agent_edm_hasmet' => 'Existence of Agent/edm:hasMet',
  'agent_edm_isrelatedto' => 'Existence of Agent/edm:isRelatedTo',
  'agent_owl_sameas' => 'Existence of Agent/owl:sameAs',
  'agent_foaf_name' => 'Existence of Agent/foaf:name',
  'agent_dc_date' => 'Existence of Agent/dc:date',
  'agent_dc_identifier' => 'Existence of Agent/dc:identifier',
  'agent_rdagr2_dateofbirth' => 'Existence of Agent/rdaGr2:dateOfBirth',
  'agent_rdagr2_placeofbirth' => 'Existence of Agent/rdaGr2:placeOfBirth',
  'agent_rdagr2_dateofdeath' => 'Existence of Agent/rdaGr2:dateOfDeath',
  'agent_rdagr2_placeofdeath' => 'Existence of Agent/rdaGr2:placeOfDeath',
  'agent_rdagr2_dateofestablishment' => 'Existence of Agent/rdaGr2:dateOfEstablishment',
  'agent_rdagr2_dateoftermination' => 'Existence of Agent/rdaGr2:dateOfTermination',
  'agent_rdagr2_gender' => 'Existence of Agent/rdaGr2:gender',
  'agent_rdagr2_professionoroccupation' => 'Existence of Agent/rdaGr2:professionOrOccupation',
  'agent_rdagr2_biographicalinformation' => 'Existence of Agent/rdaGr2:biographicalInformation',
  'agent_skos_preflabel' => 'Existence of Agent/skos:prefLabel',
  'agent_skos_altlabel' => 'Existence of Agent/skos:altLabel',
  'agent_skos_note' => 'Existence of Agent/skos:note',
  'timespan_rdf_about' => 'Existence of Timespan/rdf:about',
  'timespan_edm_begin' => 'Existence of Timespan/edm:begin',
  'timespan_edm_end' => 'Existence of Timespan/edm:end',
  'timespan_dcterms_ispartof' => 'Existence of Timespan/dcterms:isPartOf',
  'timespan_dcterms_haspart' => 'Existence of Timespan/dcterms:hasPart',
  'timespan_edm_isnextinsequence' => 'Existence of Timespan/edm:isNextInSequence',
  'timespan_owl_sameas' => 'Existence of Timespan/owl:sameAs',
  'timespan_skos_preflabel' => 'Existence of Timespan/skos:prefLabel',
  'timespan_skos_altlabel' => 'Existence of Timespan/skos:altLabel',
  'timespan_skos_note' => 'Existence of Timespan/skos:note',
  'concept_rdf_about' => 'Existence of Concept/rdf:about',
  'concept_skos_broader' => 'Existence of Concept/skos:broader',
  'concept_skos_narrower' => 'Existence of Concept/skos:narrower',
  'concept_skos_related' => 'Existence of Concept/skos:related',
  'concept_skos_broadmatch' => 'Existence of Concept/skos:broadMatch',
  'concept_skos_narrowmatch' => 'Existence of Concept/skos:narrowMatch',
  'concept_skos_relatedmatch' => 'Existence of Concept/skos:relatedMatch',
  'concept_skos_exactmatch' => 'Existence of Concept/skos:exactMatch',
  'concept_skos_closematch' => 'Existence of Concept/skos:closeMatch',
  'concept_skos_notation' => 'Existence of Concept/skos:notation',
  'concept_skos_inscheme' => 'Existence of Concept/skos:inScheme',
  'concept_skos_preflabel' => 'Existence of Concept/skos:prefLabel',
  'concept_skos_altlabel' => 'Existence of Concept/skos:altLabel',
  'concept_skos_note' => 'Existence of Concept/skos:note',
*/
  'crd_providedcho_rdf_about' => 'Cardinality of ProvidedCHO/rdf:about',
  'crd_proxy_dc_title' => 'Cardinality of Proxy/dc:title',
  'crd_proxy_dcterms_alternative' => 'Cardinality of Proxy/dcterms:alternative',
  'crd_proxy_dc_description' => 'Cardinality of Proxy/dc:description',
  'crd_proxy_dc_creator' => 'Cardinality of Proxy/dc:creator',
  'crd_proxy_dc_publisher' => 'Cardinality of Proxy/dc:publisher',
  'crd_proxy_dc_contributor' => 'Cardinality of Proxy/dc:contributor',
  'crd_proxy_dc_type' => 'Cardinality of Proxy/dc:type',
  'crd_proxy_dc_identifier' => 'Cardinality of Proxy/dc:identifier',
  'crd_proxy_dc_language' => 'Cardinality of Proxy/dc:language',
  'crd_proxy_dc_coverage' => 'Cardinality of Proxy/dc:coverage',
  'crd_proxy_dcterms_temporal' => 'Cardinality of Proxy/dcterms:temporal',
  'crd_proxy_dcterms_spatial' => 'Cardinality of Proxy/dcterms:spatial',
  'crd_proxy_dc_subject' => 'Cardinality of Proxy/dc:subject',
  'crd_proxy_dc_date' => 'Cardinality of Proxy/dc:date',
  'crd_proxy_dcterms_created' => 'Cardinality of Proxy/dcterms:created',
  'crd_proxy_dcterms_issued' => 'Cardinality of Proxy/dcterms:issued',
  'crd_proxy_dcterms_extent' => 'Cardinality of Proxy/dcterms:extent',
  'crd_proxy_dcterms_medium' => 'Cardinality of Proxy/dcterms:medium',
  'crd_proxy_dcterms_provenance' => 'Cardinality of Proxy/dcterms:provenance',
  'crd_proxy_dcterms_haspart' => 'Cardinality of Proxy/dcterms:hasPart',
  'crd_proxy_dcterms_ispartof' => 'Cardinality of Proxy/dcterms:isPartOf',
  'crd_proxy_dc_format' => 'Cardinality of Proxy/dc:format',
  'crd_proxy_dc_source' => 'Cardinality of Proxy/dc:source',
  'crd_proxy_dc_rights' => 'Cardinality of Proxy/dc:rights',
  'crd_proxy_dc_relation' => 'Cardinality of Proxy/dc:relation',
  'crd_proxy_edm_isnextinsequence' => 'Cardinality of Proxy/edm:isNextInSequence',
  'crd_proxy_edm_type' => 'Cardinality of Proxy/edm:type',
  'crd_proxy_edm_europeanaproxy' => 'Cardinality of Proxy/edm:europeanaProxy',
  'crd_proxy_edm_year' => 'Cardinality of Proxy/edm:year',
  'crd_proxy_edm_usertag' => 'Cardinality of Proxy/edm:userTag',
  'crd_proxy_ore_proxyin' => 'Cardinality of Proxy/ore:ProxyIn',
  'crd_proxy_ore_proxyfor' => 'Cardinality of Proxy/ore:ProxyFor',
  'crd_proxy_dc_conformsto' => 'Cardinality of Proxy/dc:conformsTo',
  'crd_proxy_dcterms_hasformat' => 'Cardinality of Proxy/dcterms:hasFormat',
  'crd_proxy_dcterms_hasversion' => 'Cardinality of Proxy/dcterms:hasVersion',
  'crd_proxy_dcterms_isformatof' => 'Cardinality of Proxy/dcterms:isFormatOf',
  'crd_proxy_dcterms_isreferencedby' => 'Cardinality of Proxy/dcterms:isReferencedBy',
  'crd_proxy_dcterms_isreplacedby' => 'Cardinality of Proxy/dcterms:isReplacedBy',
  'crd_proxy_dcterms_isrequiredby' => 'Cardinality of Proxy/dcterms:isRequiredBy',
  'crd_proxy_dcterms_isversionof' => 'Cardinality of Proxy/dcterms:isVersionOf',
  'crd_proxy_dcterms_references' => 'Cardinality of Proxy/dcterms:references',
  'crd_proxy_dcterms_replaces' => 'Cardinality of Proxy/dcterms:replaces',
  'crd_proxy_dcterms_requires' => 'Cardinality of Proxy/dcterms:requires',
  'crd_proxy_dcterms_tableofcontents' => 'Cardinality of Proxy/dcterms:tableOfContents',
  'crd_proxy_edm_currentlocation' => 'Cardinality of Proxy/edm:currentLocation',
  'crd_proxy_edm_hasmet' => 'Cardinality of Proxy/edm:hasMet',
  'crd_proxy_edm_hastype' => 'Cardinality of Proxy/edm:hasType',
  'crd_proxy_edm_incorporates' => 'Cardinality of Proxy/edm:incorporates',
  'crd_proxy_edm_isderivativeof' => 'Cardinality of Proxy/edm:isDerivativeOf',
  'crd_proxy_edm_isrelatedto' => 'Cardinality of Proxy/edm:isRelatedTo',
  'crd_proxy_edm_isrepresentationof' => 'Cardinality of Proxy/edm:isRepresentationOf',
  'crd_proxy_edm_issimilarto' => 'Cardinality of Proxy/edm:isSimilarTo',
  'crd_proxy_edm_issuccessorof' => 'Cardinality of Proxy/edm:isSuccessorOf',
  'crd_proxy_edm_realizes' => 'Cardinality of Proxy/edm:realizes',
  'crd_proxy_edm_waspresentat' => 'Cardinality of Proxy/edm:wasPresentAt',
  'crd_aggregation_edm_rights' => 'Cardinality of Aggregation/edm:rights',
  'crd_aggregation_edm_provider' => 'Cardinality of Aggregation/edm:provider',
  'crd_aggregation_edm_dataprovider' => 'Cardinality of Aggregation/edm:dataProvider',
  'crd_aggregation_edm_isshownat' => 'Cardinality of Aggregation/edm:isShownAt',
  'crd_aggregation_edm_isshownby' => 'Cardinality of Aggregation/edm:isShownBy',
  'crd_aggregation_edm_object' => 'Cardinality of Aggregation/edm:object',
  'crd_aggregation_edm_hasview' => 'Cardinality of Aggregation/edm:hasView',
  'crd_aggregation_dc_rights' => 'Cardinality of Aggregation/dc:rights',
  'crd_aggregation_edm_ugc' => 'Cardinality of Aggregation/edm:ugc',
  'crd_aggregation_edm_aggregatedcho' => 'Cardinality of Aggregation/edm:aggregatedCHO',
  'crd_aggregation_edm_intermediateprovider' => 'Cardinality of Aggregation/edm:intermediateProvider',
  'crd_aggregation_rdf_about' => 'Cardinality of Aggregation/rdf:about',
  'crd_place_wgs84_lat' => 'Cardinality of Place/wgs84:lat',
  'crd_place_wgs84_long' => 'Cardinality of Place/wgs84:long',
  'crd_place_wgs84_alt' => 'Cardinality of Place/wgs84:alt',
  'crd_place_dcterms_ispartof' => 'Cardinality of Place/dcterms:isPartOf',
  'crd_place_wgs84_pos_lat_long' => 'Cardinality of Place/wgs84:pos:lat:long',
  'crd_place_dcterms_haspart' => 'Cardinality of Place/dcterms:hasPart',
  'crd_place_owl_sameas' => 'Cardinality of Place/owl:sameAs',
  'crd_place_skos_preflabel' => 'Cardinality of Place/skos:prefLabel',
  'crd_place_skos_altlabel' => 'Cardinality of Place/skos:altLabel',
  'crd_place_skos_note' => 'Cardinality of Place/skos:note',
  'crd_place_rdf_about' => 'Cardinality of Place/rdf:about',
  'crd_agent_rdf_about' => 'Cardinality of Agent/rdf:about',
  'crd_agent_edm_begin' => 'Cardinality of Agent/edm:begin',
  'crd_agent_edm_end' => 'Cardinality of Agent/edm:end',
  'crd_agent_edm_hasmet' => 'Cardinality of Agent/edm:hasMet',
  'crd_agent_edm_isrelatedto' => 'Cardinality of Agent/edm:isRelatedTo',
  'crd_agent_owl_sameas' => 'Cardinality of Agent/owl:sameAs',
  'crd_agent_foaf_name' => 'Cardinality of Agent/foaf:name',
  'crd_agent_dc_date' => 'Cardinality of Agent/dc:date',
  'crd_agent_dc_identifier' => 'Cardinality of Agent/dc:identifier',
  'crd_agent_rdagr2_dateofbirth' => 'Cardinality of Agent/rdaGr2:dateOfBirth',
  'crd_agent_rdagr2_placeofbirth' => 'Cardinality of Agent/rdaGr2:placeOfBirth',
  'crd_agent_rdagr2_dateofdeath' => 'Cardinality of Agent/rdaGr2:dateOfDeath',
  'crd_agent_rdagr2_placeofdeath' => 'Cardinality of Agent/rdaGr2:placeOfDeath',
  'crd_agent_rdagr2_dateofestablishment' => 'Cardinality of Agent/rdaGr2:dateOfEstablishment',
  'crd_agent_rdagr2_dateoftermination' => 'Cardinality of Agent/rdaGr2:dateOfTermination',
  'crd_agent_rdagr2_gender' => 'Cardinality of Agent/rdaGr2:gender',
  'crd_agent_rdagr2_professionoroccupation' => 'Cardinality of Agent/rdaGr2:professionOrOccupation',
  'crd_agent_rdagr2_biographicalinformation' => 'Cardinality of Agent/rdaGr2:biographicalInformation',
  'crd_agent_skos_preflabel' => 'Cardinality of Agent/skos:prefLabel',
  'crd_agent_skos_altlabel' => 'Cardinality of Agent/skos:altLabel',
  'crd_agent_skos_note' => 'Cardinality of Agent/skos:note',
  'crd_timespan_rdf_about' => 'Cardinality of Timespan/rdf:about',
  'crd_timespan_edm_begin' => 'Cardinality of Timespan/edm:begin',
  'crd_timespan_edm_end' => 'Cardinality of Timespan/edm:end',
  'crd_timespan_dcterms_ispartof' => 'Cardinality of Timespan/dcterms:isPartOf',
  'crd_timespan_dcterms_haspart' => 'Cardinality of Timespan/dcterms:hasPart',
  'crd_timespan_edm_isnextinsequence' => 'Cardinality of Timespan/edm:isNextInSequence',
  'crd_timespan_owl_sameas' => 'Cardinality of Timespan/owl:sameAs',
  'crd_timespan_skos_preflabel' => 'Cardinality of Timespan/skos:prefLabel',
  'crd_timespan_skos_altlabel' => 'Cardinality of Timespan/skos:altLabel',
  'crd_timespan_skos_note' => 'Cardinality of Timespan/skos:note',
  'crd_concept_rdf_about' => 'Cardinality of Concept/rdf:about',
  'crd_concept_skos_broader' => 'Cardinality of Concept/skos:broader',
  'crd_concept_skos_narrower' => 'Cardinality of Concept/skos:narrower',
  'crd_concept_skos_related' => 'Cardinality of Concept/skos:related',
  'crd_concept_skos_broadmatch' => 'Cardinality of Concept/skos:broadMatch',
  'crd_concept_skos_narrowmatch' => 'Cardinality of Concept/skos:narrowMatch',
  'crd_concept_skos_relatedmatch' => 'Cardinality of Concept/skos:relatedMatch',
  'crd_concept_skos_exactmatch' => 'Cardinality of Concept/skos:exactMatch',
  'crd_concept_skos_closematch' => 'Cardinality of Concept/skos:closeMatch',
  'crd_concept_skos_notation' => 'Cardinality of Concept/skos:notation',
  'crd_concept_skos_inscheme' => 'Cardinality of Concept/skos:inScheme',
  'crd_concept_skos_preflabel' => 'Cardinality of Concept/skos:prefLabel',
  'crd_concept_skos_altlabel' => 'Cardinality of Concept/skos:altLabel',
  'crd_concept_skos_note' => 'Cardinality of Concept/skos:note',


  'long_subject' => 'Metadata problem - Long subject',
  'same_title_and_description' => 'Metadata problem - title and description are the same',
  'empty_string' => 'Metadata problem - empty field'
);

$feature = isset($_GET) && isset($_GET['feature']) ? $_GET['feature'] : 'total';
if (!isset($feature) || !isset($features[$feature])) {
  $feature = 'total';
}

$types = [
  'data-providers' => 'Data providers',
  'datasets' => 'Data sets'
];
$type = isset($_GET) && isset($_GET['type']) ? $_GET['type'] : 'data-providers';
if (!isset($type) || !isset($types[$type])) {
  $type = 'data-providers';
}
$prefix = $type == 'datasets' ? 'c' : 'd';

// $csv = array_map('str_getcsv', file('collection-names.csv'));

function parse_csv($t) {
  return str_getcsv($t, ';');
}
$csv = array_map('parse_csv', file($type . '.txt'));

$summaryFile = 'json_cache/index-summary-' . $feature . '-' . $prefix . '.json';
// echo 'summaryFile: ', $summaryFile, LN;
$rows = [];
if (!file_exists($summaryFile)) {
  $counter = 1;
  foreach ($csv as $id => $row) {
    $id = $row[0];
    $collectionId = $row[1];

    $n = 0;
    $jsonCountFileName = $configuration['QA_R_PATH'] . '/json2/' . $prefix . $id . '.count.json';
    if (file_exists($jsonCountFileName)) {
      $stats = json_decode(file_get_contents($jsonCountFileName));
      $n = $stats[0]->count;
    }

    $jsonFileName = $configuration['QA_R_PATH'] . '/json2/' . $prefix . $id . '.json';
    echo 'jsonFileName: ', $jsonFileName, LN;
    if (file_exists($jsonFileName)) {
      if ($counter == 1) {
        // echo 'jsonFileName: ', $jsonFileName, "\n";
      }
      $stats = json_decode(file_get_contents($jsonFileName));
      $assocStat = array();
      foreach ($stats as $obj) {
        if ($counter == 1) {
          // echo json_encode($obj);
        }
        echo $obj->_row, LN;
        if ($obj->_row == $feature) {
          echo 'bumm', LN;
          unset($obj->recMin);
          unset($obj->recMax);
          unset($obj->_row);
          $obj->n = $n;
          $obj->id = $id;
          $obj->type = $prefix;
          $obj->collectionId = $collectionId;
          $rows[$counter++] = $obj;
          break;
        }
      }
    } else {
      echo 'jsonFileName is not existing', LN;
    }
  }
  // echo 'count: ', count($rows), "\n";
  // file_put_contents($summaryFile, json_encode($rows));
} else {
  $rows = json_decode(file_get_contents($summaryFile));
}

include("index.tpl.php");
