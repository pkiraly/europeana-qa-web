<?php

$configuration = parse_ini_file('config.cfg');

$graphs = array(
  'total' => array('label' => 'Every fields', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dcterms:alternative",
  	"Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:identifier",
  	"Proxy/dc:language", "Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dc:date",
  	"Proxy/dcterms:created", "Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:format", "Proxy/dc:source", "Proxy/dc:rights", "Proxy/dc:relation",
  	"Proxy/edm:isNextInSequence", "Proxy/edm:type", "Proxy/edm:rights", "Aggregation/edm:rights", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object", "Aggregation/edm:hasView")),
  'mandatory' => array('label' => 'Mandatory elements', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/edm:rights", "Aggregation/edm:rights",
  	"Aggregation/edm:provider", "Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy")),
  'descriptiveness' => array('label' => 'Descriptiveness', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:language", "Proxy/dc:subject", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dc:format", "Proxy/dc:source")),
  'searchability' => array('label' => 'Searchability', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:provider", "Aggregation/edm:dataProvider")),
  'contextualization' => array('label' => 'Contextualization', 'fields' => array("Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:type",
  	"Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf",
  	"Proxy/dc:relation", "Proxy/edm:isNextInSequence")),
  'identification' => array('label' => 'Identification', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:identifier", "Proxy/dc:date", "Proxy/dcterms:created", "Proxy/dcterms:issued", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider")),
  'browsing' => array('label' => 'Browsing', 'fields' => array("Proxy/dc:creator", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:date", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:hasView")),
  'viewing' => array('label' => 'Viewing', 'fields' => array("Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object",
  	"Aggregation/edm:hasView")),
  'reusability' => array('label' => 'Re-usability', 'fields' => array("Proxy/dc:publisher", "Proxy/dc:date", "Proxy/dcterms:created",
  	"Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dc:format", "Proxy/dc:rights", "Proxy/edm:rights",
  	"Aggregation/edm:rights", "Aggregation/edm:isShownBy", "Aggregation/edm:object")),
  'multilinguality' => array('label' => 'Multilinguality', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:language", "Proxy/dc:subject")),
  'entropy_dc_title_sum' => array('label' => 'dc:title entropy - cumulative'),
  'entropy_dc_title_avg' => array('label' => 'dc:title entropy - average'),
  'entropy_dcterms_alternative_sum' => array('label' => 'dcterms:alternative entropy - cumulative'),
  'entropy_dcterms_alternative_avg' => array('label' => 'dctersm:alternative entorpy - average'),
  'entropy_dc_description_sum' => array('label' => 'dc:description entropy - cumulative'),
  'entropy_dc_description_avg' => array('label' => 'dc:description entorpy - average'),


  'crd_providedcho_rdf_about' => array('label' => 'Cardinality of ProvidedCHO/rdf:about'),
  'crd_proxy_dc_title' => array('label' => 'Cardinality of Proxy/dc:title'),
  'crd_proxy_dcterms_alternative' => array('label' => 'Cardinality of Proxy/dcterms:alternative'),
  'crd_proxy_dc_description' => array('label' => 'Cardinality of Proxy/dc:description'),
  'crd_proxy_dc_creator' => array('label' => 'Cardinality of Proxy/dc:creator'),
  'crd_proxy_dc_publisher' => array('label' => 'Cardinality of Proxy/dc:publisher'),
  'crd_proxy_dc_contributor' => array('label' => 'Cardinality of Proxy/dc:contributor'),
  'crd_proxy_dc_type' => array('label' => 'Cardinality of Proxy/dc:type'),
  'crd_proxy_dc_identifier' => array('label' => 'Cardinality of Proxy/dc:identifier'),
  'crd_proxy_dc_language' => array('label' => 'Cardinality of Proxy/dc:language'),
  'crd_proxy_dc_coverage' => array('label' => 'Cardinality of Proxy/dc:coverage'),
  'crd_proxy_dcterms_temporal' => array('label' => 'Cardinality of Proxy/dcterms:temporal'),
  'crd_proxy_dcterms_spatial' => array('label' => 'Cardinality of Proxy/dcterms:spatial'),
  'crd_proxy_dc_subject' => array('label' => 'Cardinality of Proxy/dc:subject'),
  'crd_proxy_dc_date' => array('label' => 'Cardinality of Proxy/dc:date'),
  'crd_proxy_dcterms_created' => array('label' => 'Cardinality of Proxy/dcterms:created'),
  'crd_proxy_dcterms_issued' => array('label' => 'Cardinality of Proxy/dcterms:issued'),
  'crd_proxy_dcterms_extent' => array('label' => 'Cardinality of Proxy/dcterms:extent'),
  'crd_proxy_dcterms_medium' => array('label' => 'Cardinality of Proxy/dcterms:medium'),
  'crd_proxy_dcterms_provenance' => array('label' => 'Cardinality of Proxy/dcterms:provenance'),
  'crd_proxy_dcterms_haspart' => array('label' => 'Cardinality of Proxy/dcterms:hasPart'),
  'crd_proxy_dcterms_ispartof' => array('label' => 'Cardinality of Proxy/dcterms:isPartOf'),
  'crd_proxy_dc_format' => array('label' => 'Cardinality of Proxy/dc:format'),
  'crd_proxy_dc_source' => array('label' => 'Cardinality of Proxy/dc:source'),
  'crd_proxy_dc_rights' => array('label' => 'Cardinality of Proxy/dc:rights'),
  'crd_proxy_dc_relation' => array('label' => 'Cardinality of Proxy/dc:relation'),
  'crd_proxy_edm_isnextinsequence' => array('label' => 'Cardinality of Proxy/edm:isNextInSequence'),
  'crd_proxy_edm_type' => array('label' => 'Cardinality of Proxy/edm:type'),
  'crd_proxy_edm_europeanaproxy' => array('label' => 'Cardinality of Proxy/edm:europeanaProxy'),
  'crd_proxy_edm_year' => array('label' => 'Cardinality of Proxy/edm:year'),
  'crd_proxy_edm_usertag' => array('label' => 'Cardinality of Proxy/edm:userTag'),
  'crd_proxy_ore_proxyin' => array('label' => 'Cardinality of Proxy/ore:ProxyIn'),
  'crd_proxy_ore_proxyfor' => array('label' => 'Cardinality of Proxy/ore:ProxyFor'),
  'crd_proxy_dc_conformsto' => array('label' => 'Cardinality of Proxy/dc:conformsTo'),
  'crd_proxy_dcterms_hasformat' => array('label' => 'Cardinality of Proxy/dcterms:hasFormat'),
  'crd_proxy_dcterms_hasversion' => array('label' => 'Cardinality of Proxy/dcterms:hasVersion'),
  'crd_proxy_dcterms_isformatof' => array('label' => 'Cardinality of Proxy/dcterms:isFormatOf'),
  'crd_proxy_dcterms_isreferencedby' => array('label' => 'Cardinality of Proxy/dcterms:isReferencedBy'),
  'crd_proxy_dcterms_isreplacedby' => array('label' => 'Cardinality of Proxy/dcterms:isReplacedBy'),
  'crd_proxy_dcterms_isrequiredby' => array('label' => 'Cardinality of Proxy/dcterms:isRequiredBy'),
  'crd_proxy_dcterms_isversionof' => array('label' => 'Cardinality of Proxy/dcterms:isVersionOf'),
  'crd_proxy_dcterms_references' => array('label' => 'Cardinality of Proxy/dcterms:references'),
  'crd_proxy_dcterms_replaces' => array('label' => 'Cardinality of Proxy/dcterms:replaces'),
  'crd_proxy_dcterms_requires' => array('label' => 'Cardinality of Proxy/dcterms:requires'),
  'crd_proxy_dcterms_tableofcontents' => array('label' => 'Cardinality of Proxy/dcterms:tableOfContents'),
  'crd_proxy_edm_currentlocation' => array('label' => 'Cardinality of Proxy/edm:currentLocation'),
  'crd_proxy_edm_hasmet' => array('label' => 'Cardinality of Proxy/edm:hasMet'),
  'crd_proxy_edm_hastype' => array('label' => 'Cardinality of Proxy/edm:hasType'),
  'crd_proxy_edm_incorporates' => array('label' => 'Cardinality of Proxy/edm:incorporates'),
  'crd_proxy_edm_isderivativeof' => array('label' => 'Cardinality of Proxy/edm:isDerivativeOf'),
  'crd_proxy_edm_isrelatedto' => array('label' => 'Cardinality of Proxy/edm:isRelatedTo'),
  'crd_proxy_edm_isrepresentationof' => array('label' => 'Cardinality of Proxy/edm:isRepresentationOf'),
  'crd_proxy_edm_issimilarto' => array('label' => 'Cardinality of Proxy/edm:isSimilarTo'),
  'crd_proxy_edm_issuccessorof' => array('label' => 'Cardinality of Proxy/edm:isSuccessorOf'),
  'crd_proxy_edm_realizes' => array('label' => 'Cardinality of Proxy/edm:realizes'),
  'crd_proxy_edm_waspresentat' => array('label' => 'Cardinality of Proxy/edm:wasPresentAt'),
  'crd_aggregation_edm_rights' => array('label' => 'Cardinality of Aggregation/edm:rights'),
  'crd_aggregation_edm_provider' => array('label' => 'Cardinality of Aggregation/edm:provider'),
  'crd_aggregation_edm_dataprovider' => array('label' => 'Cardinality of Aggregation/edm:dataProvider'),
  'crd_aggregation_edm_isshownat' => array('label' => 'Cardinality of Aggregation/edm:isShownAt'),
  'crd_aggregation_edm_isshownby' => array('label' => 'Cardinality of Aggregation/edm:isShownBy'),
  'crd_aggregation_edm_object' => array('label' => 'Cardinality of Aggregation/edm:object'),
  'crd_aggregation_edm_hasview' => array('label' => 'Cardinality of Aggregation/edm:hasView'),
  'crd_aggregation_dc_rights' => array('label' => 'Cardinality of Aggregation/dc:rights'),
  'crd_aggregation_edm_ugc' => array('label' => 'Cardinality of Aggregation/edm:ugc'),
  'crd_aggregation_edm_aggregatedcho' => array('label' => 'Cardinality of Aggregation/edm:aggregatedCHO'),
  'crd_aggregation_edm_intermediateprovider' => array('label' => 'Cardinality of Aggregation/edm:intermediateProvider'),
  'crd_aggregation_rdf_about' => array('label' => 'Cardinality of Aggregation/rdf:about'),
  'crd_place_wgs84_lat' => array('label' => 'Cardinality of Place/wgs84:lat'),
  'crd_place_wgs84_long' => array('label' => 'Cardinality of Place/wgs84:long'),
  'crd_place_wgs84_alt' => array('label' => 'Cardinality of Place/wgs84:alt'),
  'crd_place_dcterms_ispartof' => array('label' => 'Cardinality of Place/dcterms:isPartOf'),
  'crd_place_wgs84_pos_lat_long' => array('label' => 'Cardinality of Place/wgs84:pos:lat:long'),
  'crd_place_dcterms_haspart' => array('label' => 'Cardinality of Place/dcterms:hasPart'),
  'crd_place_owl_sameas' => array('label' => 'Cardinality of Place/owl:sameAs'),
  'crd_place_skos_preflabel' => array('label' => 'Cardinality of Place/skos:prefLabel'),
  'crd_place_skos_altlabel' => array('label' => 'Cardinality of Place/skos:altLabel'),
  'crd_place_skos_note' => array('label' => 'Cardinality of Place/skos:note'),
  'crd_place_rdf_about' => array('label' => 'Cardinality of Place/rdf:about'),
  'crd_agent_rdf_about' => array('label' => 'Cardinality of Agent/rdf:about'),
  'crd_agent_edm_begin' => array('label' => 'Cardinality of Agent/edm:begin'),
  'crd_agent_edm_end' => array('label' => 'Cardinality of Agent/edm:end'),
  'crd_agent_edm_hasmet' => array('label' => 'Cardinality of Agent/edm:hasMet'),
  'crd_agent_edm_isrelatedto' => array('label' => 'Cardinality of Agent/edm:isRelatedTo'),
  'crd_agent_owl_sameas' => array('label' => 'Cardinality of Agent/owl:sameAs'),
  'crd_agent_foaf_name' => array('label' => 'Cardinality of Agent/foaf:name'),
  'crd_agent_dc_date' => array('label' => 'Cardinality of Agent/dc:date'),
  'crd_agent_dc_identifier' => array('label' => 'Cardinality of Agent/dc:identifier'),
  'crd_agent_rdagr2_dateofbirth' => array('label' => 'Cardinality of Agent/rdaGr2:dateOfBirth'),
  'crd_agent_rdagr2_placeofbirth' => array('label' => 'Cardinality of Agent/rdaGr2:placeOfBirth'),
  'crd_agent_rdagr2_dateofdeath' => array('label' => 'Cardinality of Agent/rdaGr2:dateOfDeath'),
  'crd_agent_rdagr2_placeofdeath' => array('label' => 'Cardinality of Agent/rdaGr2:placeOfDeath'),
  'crd_agent_rdagr2_dateofestablishment' => array('label' => 'Cardinality of Agent/rdaGr2:dateOfEstablishment'),
  'crd_agent_rdagr2_dateoftermination' => array('label' => 'Cardinality of Agent/rdaGr2:dateOfTermination'),
  'crd_agent_rdagr2_gender' => array('label' => 'Cardinality of Agent/rdaGr2:gender'),
  'crd_agent_rdagr2_professionoroccupation' => array('label' => 'Cardinality of Agent/rdaGr2:professionOrOccupation'),
  'crd_agent_rdagr2_biographicalinformation' => array('label' => 'Cardinality of Agent/rdaGr2:biographicalInformation'),
  'crd_agent_skos_preflabel' => array('label' => 'Cardinality of Agent/skos:prefLabel'),
  'crd_agent_skos_altlabel' => array('label' => 'Cardinality of Agent/skos:altLabel'),
  'crd_agent_skos_note' => array('label' => 'Cardinality of Agent/skos:note'),
  'crd_timespan_rdf_about' => array('label' => 'Cardinality of Timespan/rdf:about'),
  'crd_timespan_edm_begin' => array('label' => 'Cardinality of Timespan/edm:begin'),
  'crd_timespan_edm_end' => array('label' => 'Cardinality of Timespan/edm:end'),
  'crd_timespan_dcterms_ispartof' => array('label' => 'Cardinality of Timespan/dcterms:isPartOf'),
  'crd_timespan_dcterms_haspart' => array('label' => 'Cardinality of Timespan/dcterms:hasPart'),
  'crd_timespan_edm_isnextinsequence' => array('label' => 'Cardinality of Timespan/edm:isNextInSequence'),
  'crd_timespan_owl_sameas' => array('label' => 'Cardinality of Timespan/owl:sameAs'),
  'crd_timespan_skos_preflabel' => array('label' => 'Cardinality of Timespan/skos:prefLabel'),
  'crd_timespan_skos_altlabel' => array('label' => 'Cardinality of Timespan/skos:altLabel'),
  'crd_timespan_skos_note' => array('label' => 'Cardinality of Timespan/skos:note'),
  'crd_concept_rdf_about' => array('label' => 'Cardinality of Concept/rdf:about'),
  'crd_concept_skos_broader' => array('label' => 'Cardinality of Concept/skos:broader'),
  'crd_concept_skos_narrower' => array('label' => 'Cardinality of Concept/skos:narrower'),
  'crd_concept_skos_related' => array('label' => 'Cardinality of Concept/skos:related'),
  'crd_concept_skos_broadmatch' => array('label' => 'Cardinality of Concept/skos:broadMatch'),
  'crd_concept_skos_narrowmatch' => array('label' => 'Cardinality of Concept/skos:narrowMatch'),
  'crd_concept_skos_relatedmatch' => array('label' => 'Cardinality of Concept/skos:relatedMatch'),
  'crd_concept_skos_exactmatch' => array('label' => 'Cardinality of Concept/skos:exactMatch'),
  'crd_concept_skos_closematch' => array('label' => 'Cardinality of Concept/skos:closeMatch'),
  'crd_concept_skos_notation' => array('label' => 'Cardinality of Concept/skos:notation'),
  'crd_concept_skos_inscheme' => array('label' => 'Cardinality of Concept/skos:inScheme'),
  'crd_concept_skos_preflabel' => array('label' => 'Cardinality of Concept/skos:prefLabel'),
  'crd_concept_skos_altlabel' => array('label' => 'Cardinality of Concept/skos:altLabel'),
  'crd_concept_skos_note' => array('label' => 'Cardinality of Concept/skos:note'),

/*  
  'crd_identifier' => array('label' => 'Cardinality of identifier'),
  'crd_proxy_dc_title' => array('label' => 'Cardinality of Proxy/dc:title'),
  'crd_proxy_dcterms_alternative' => array('label' => 'Cardinality of Proxy/dcterms:alternative'),
  'crd_proxy_dc_description' => array('label' => 'Cardinality of Proxy/dc:description'),
  'crd_proxy_dc_creator' => array('label' => 'Cardinality of Proxy/dc:creator'),
  'crd_proxy_dc_publisher' => array('label' => 'Cardinality of Proxy/dc:publisher'),
  'crd_proxy_dc_contributor' => array('label' => 'Cardinality of Proxy/dc:contributor'),
  'crd_proxy_dc_type' => array('label' => 'Cardinality of Proxy/dc:type'),
  'crd_proxy_dc_identifier' => array('label' => 'Cardinality of Proxy/dc:identifier'),
  'crd_proxy_dc_language' => array('label' => 'Cardinality of Proxy/dc:language'),
  'crd_proxy_dc_coverage' => array('label' => 'Cardinality of Proxy/dc:coverage'),
  'crd_proxy_dcterms_temporal' => array('label' => 'Cardinality of Proxy/dcterms:temporal'),
  'crd_proxy_dcterms_spatial' => array('label' => 'Cardinality of Proxy/dcterms:spatial'),
  'crd_proxy_dc_subject' => array('label' => 'Cardinality of Proxy/dc:subject'),
  'crd_proxy_dc_date' => array('label' => 'Cardinality of Proxy/dc:date'),
  'crd_proxy_dcterms_created' => array('label' => 'Cardinality of Proxy/dcterms:created'),
  'crd_proxy_dcterms_issued' => array('label' => 'Cardinality of Proxy/dcterms:issued'),
  'crd_proxy_dcterms_extent' => array('label' => 'Cardinality of Proxy/dcterms:extent'),
  'crd_proxy_dcterms_medium' => array('label' => 'Cardinality of Proxy/dcterms:medium'),
  'crd_proxy_dcterms_provenance' => array('label' => 'Cardinality of Proxy/dcterms:provenance'),
  'crd_proxy_dcterms_hasPart' => array('label' => 'Cardinality of Proxy/dcterms:hasPart'),
  'crd_proxy_dcterms_isPartOf' => array('label' => 'Cardinality of Proxy/dcterms:isPartOf'),
  'crd_proxy_dc_format' => array('label' => 'Cardinality of Proxy/dc:format'),
  'crd_proxy_dc_source' => array('label' => 'Cardinality of Proxy/dc:source'),
  'crd_proxy_dc_rights' => array('label' => 'Cardinality of Proxy/dc:rights'),
  'crd_proxy_dc_relation' => array('label' => 'Cardinality of Proxy/dc:relation'),
  'crd_proxy_edm_isNextInSequence' => array('label' => 'Cardinality of Proxy/edm:isNextInSequence'),
  'crd_proxy_edm_type' => array('label' => 'Cardinality of Proxy/edm:type'),
  'crd_aggregation_edm_rights' => array('label' => 'Cardinality of Aggregation/edm:rights'),
  'crd_aggregation_edm_provider' => array('label' => 'Cardinality of Aggregation/edm:provider'),
  'crd_aggregation_edm_dataProvider' => array('label' => 'Cardinality of Aggregation/edm:dataProvider'),
  'crd_aggregation_edm_isShownAt' => array('label' => 'Cardinality of Aggregation/edm:isShownAt'),
  'crd_aggregation_edm_isShownBy' => array('label' => 'Cardinality of Aggregation/edm:isShownBy'),
  'crd_aggregation_edm_object' => array('label' => 'Cardinality of Aggregation/edm:object'),
  'crd_aggregation_edm_hasView' => array('label' => 'Cardinality of Aggregation/edm:hasView'),
*/
  'long_subject' => array('label' => 'Metadata problem - Long subject'),
  'same_title_and_description' => array('label' => 'Metadata problem - title and description are the same'),
  'empty_string' => array('label' => 'Metadata problem - empty field')
);

$fields = [
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
  'aggregated' => 'All fields aggregated'
];

$title = 'Metadata Quality Assurance Framework';
if (isset($_GET['id'])) {
  $collectionId = $_GET['name'];
  $id = $_GET['id'];
  $type = $_GET['type'];
} else {
  $collectionId = $argv[1];
  $id = strstr($collectionId, '_', true);
  $type = 'c';
}

$n = 0;
$jsonCountFileName = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

$jsonFileName = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.json';
if (!file_exists($jsonFileName)) {
  die(sprintf("File doesn't exist: %s (collection: %s)\n", $jsonFileName, $collectionId));
} else {
  if (!isset($_GET['id'])) {
    printf("Processing: %s (collection: %s)\n", $jsonFileName, $collectionId);
  }
}

$stats = json_decode(file_get_contents($jsonFileName));

$assocStat = array();
foreach ($stats as $obj) {
  $key = $obj->_row;
  unset($obj->_row);
  $assocStat[$key] = $obj;
}

$freqFile = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.freq.json';
$freqFileExists = file_exists($freqFile);

$cardinalityFile = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.cardinality.json';
$cardinalityFileExists = file_exists($cardinalityFile);
$cardinalityProperties = ['sum', 'mean', 'median'];
if ($cardinalityFileExists) {
  $key = 'cardinality-property';
  $cardinalityProperty = isset($_GET[$key]) && in_array($_GET[$key], $cardinalityProperties) ? $_GET[$key] : 'sum';
}

$frequencyTableFile = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.frequency.table.json';
if (file_exists($frequencyTableFile)) {
  $frequencyTable = json_decode(file_get_contents($frequencyTableFile));
  foreach ($frequencyTable as $key => $value) {
    if ($key != strtolower($key)) {
      unset($frequencyTable->$key);
      $key = strtolower($key);
      $frequencyTable->{$key} = $value;
    }
  }
} else {
  $frequencyTable = FALSE;
}

$histFile = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.hist.json';
if (file_exists($histFile)) {
  $histograms = json_decode(file_get_contents($histFile));
} else {
  $histograms = FALSE;
}

$languageFile = $configuration['QA_R_PATH'] . '/json2/' . $type . $id . '.languages.json';
if (file_exists($languageFile)) {
  $languages = json_decode(file_get_contents($languageFile));
} else {
  $languages = FALSE;
}

ob_start();
include('dataset.tpl.php');
$content = ob_get_contents();
ob_end_clean();

if (isset($_GET['id'])) {
  echo $content;
} else {
  file_put_contents($id . '.html', $content);
}
