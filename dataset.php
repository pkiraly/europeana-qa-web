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

  'saturation_proxy_dc_title' => array('label' => 'Multilingual saturation of Proxy/dc:title'),
  'saturation_proxy_dcterms_alternative' => array('label' => 'Multilingual saturation of Proxy/dcterms:alternative'),
  'saturation_proxy_dc_description' => array('label' => 'Multilingual saturation of Proxy/dc:description'),
  'saturation_proxy_dc_creator' => array('label' => 'Multilingual saturation of Proxy/dc:creator'),
  'saturation_proxy_dc_publisher' => array('label' => 'Multilingual saturation of Proxy/dc:publisher'),
  'saturation_proxy_dc_contributor' => array('label' => 'Multilingual saturation of Proxy/dc:contributor'),
  'saturation_proxy_dc_type' => array('label' => 'Multilingual saturation of Proxy/dc:type'),
  'saturation_proxy_dc_identifier' => array('label' => 'Multilingual saturation of Proxy/dc:identifier'),
  'saturation_proxy_dc_language' => array('label' => 'Multilingual saturation of Proxy/dc:language'),
  'saturation_proxy_dc_coverage' => array('label' => 'Multilingual saturation of Proxy/dc:coverage'),
  'saturation_proxy_dcterms_temporal' => array('label' => 'Multilingual saturation of Proxy/dcterms:temporal'),
  'saturation_proxy_dcterms_spatial' => array('label' => 'Multilingual saturation of Proxy/dcterms:spatial'),
  'saturation_proxy_dc_subject' => array('label' => 'Multilingual saturation of Proxy/dc:subject'),
  'saturation_proxy_dc_date' => array('label' => 'Multilingual saturation of Proxy/dc:date'),
  'saturation_proxy_dcterms_created' => array('label' => 'Multilingual saturation of Proxy/dcterms:created'),
  'saturation_proxy_dcterms_issued' => array('label' => 'Multilingual saturation of Proxy/dcterms:issued'),
  'saturation_proxy_dcterms_extent' => array('label' => 'Multilingual saturation of Proxy/dcterms:extent'),
  'saturation_proxy_dcterms_medium' => array('label' => 'Multilingual saturation of Proxy/dcterms:medium'),
  'saturation_proxy_dcterms_provenance' => array('label' => 'Multilingual saturation of Proxy/dcterms:provenance'),
  'saturation_proxy_dcterms_haspart' => array('label' => 'Multilingual saturation of Proxy/dcterms:hasPart'),
  'saturation_proxy_dcterms_ispartof' => array('label' => 'Multilingual saturation of Proxy/dcterms:isPartOf'),
  'saturation_proxy_dc_format' => array('label' => 'Multilingual saturation of Proxy/dc:format'),
  'saturation_proxy_dc_source' => array('label' => 'Multilingual saturation of Proxy/dc:source'),
  'saturation_proxy_dc_rights' => array('label' => 'Multilingual saturation of Proxy/dc:rights'),
  'saturation_proxy_dc_relation' => array('label' => 'Multilingual saturation of Proxy/dc:relation'),
  'saturation_proxy_edm_europeanaproxy' => array('label' => 'Multilingual saturation of Proxy/edm:europeanaProxy'),
  'saturation_proxy_edm_year' => array('label' => 'Multilingual saturation of Proxy/edm:year'),
  'saturation_proxy_edm_usertag' => array('label' => 'Multilingual saturation of Proxy/edm:userTag'),
  'saturation_proxy_ore_proxyin' => array('label' => 'Multilingual saturation of Proxy/ore:ProxyIn'),
  'saturation_proxy_ore_proxyfor' => array('label' => 'Multilingual saturation of Proxy/ore:ProxyFor'),
  'saturation_proxy_dc_conformsto' => array('label' => 'Multilingual saturation of Proxy/dc:conformsTo'),
  'saturation_proxy_dcterms_hasformat' => array('label' => 'Multilingual saturation of Proxy/dcterms:hasFormat'),
  'saturation_proxy_dcterms_hasversion' => array('label' => 'Multilingual saturation of Proxy/dcterms:hasVersion'),
  'saturation_proxy_dcterms_isformatof' => array('label' => 'Multilingual saturation of Proxy/dcterms:isFormatOf'),
  'saturation_proxy_dcterms_isreferencedby' => array('label' => 'Multilingual saturation of Proxy/dcterms:isReferencedBy'),
  'saturation_proxy_dcterms_isreplacedby' => array('label' => 'Multilingual saturation of Proxy/dcterms:isReplacedBy'),
  'saturation_proxy_dcterms_isrequiredby' => array('label' => 'Multilingual saturation of Proxy/dcterms:isRequiredBy'),
  'saturation_proxy_dcterms_isversionof' => array('label' => 'Multilingual saturation of Proxy/dcterms:isVersionOf'),
  'saturation_proxy_dcterms_references' => array('label' => 'Multilingual saturation of Proxy/dcterms:references'),
  'saturation_proxy_dcterms_replaces' => array('label' => 'Multilingual saturation of Proxy/dcterms:replaces'),
  'saturation_proxy_dcterms_requires' => array('label' => 'Multilingual saturation of Proxy/dcterms:requires'),
  'saturation_proxy_dcterms_tableofcontents' => array('label' => 'Multilingual saturation of Proxy/dcterms:tableOfContents'),
  'saturation_proxy_edm_currentlocation' => array('label' => 'Multilingual saturation of Proxy/edm:currentLocation'),
  'saturation_proxy_edm_hasmet' => array('label' => 'Multilingual saturation of Proxy/edm:hasMet'),
  'saturation_proxy_edm_hastype' => array('label' => 'Multilingual saturation of Proxy/edm:hasType'),
  'saturation_proxy_edm_incorporates' => array('label' => 'Multilingual saturation of Proxy/edm:incorporates'),
  'saturation_proxy_edm_isderivativeof' => array('label' => 'Multilingual saturation of Proxy/edm:isDerivativeOf'),
  'saturation_proxy_edm_isrelatedto' => array('label' => 'Multilingual saturation of Proxy/edm:isRelatedTo'),
  'saturation_proxy_edm_isrepresentationof' => array('label' => 'Multilingual saturation of Proxy/edm:isRepresentationOf'),
  'saturation_proxy_edm_issimilarto' => array('label' => 'Multilingual saturation of Proxy/edm:isSimilarTo'),
  'saturation_proxy_edm_issuccessorof' => array('label' => 'Multilingual saturation of Proxy/edm:isSuccessorOf'),
  'saturation_proxy_edm_realizes' => array('label' => 'Multilingual saturation of Proxy/edm:realizes'),
  'saturation_proxy_edm_waspresentat' => array('label' => 'Multilingual saturation of Proxy/edm:wasPresentAt'),

  'saturation_aggregation_edm_rights' => array('label' => 'Multilingual saturation of Aggregation/edm:rights'),
  'saturation_aggregation_edm_provider' => array('label' => 'Multilingual saturation of Aggregation/edm:provider'),
  'saturation_aggregation_edm_dataprovider' => array('label' => 'Multilingual saturation of Aggregation/edm:dataProvider'),
  'saturation_aggregation_dc_rights' => array('label' => 'Multilingual saturation of Aggregation/dc:rights'),
  'saturation_aggregation_edm_ugc' => array('label' => 'Multilingual saturation of Aggregation/edm:ugc'),
  'saturation_aggregation_edm_aggregatedcho' => array('label' => 'Multilingual saturation of Aggregation/edm:aggregatedCHO'),
  'saturation_aggregation_edm_intermediateprovider' => array('label' => 'Multilingual saturation of Aggregation/edm:intermediateProvider'),

  'saturation_place_dcterms_ispartof' => array('label' => 'Multilingual saturation of Place/dcterms:isPartOf'),
  'saturation_place_dcterms_haspart' => array('label' => 'Multilingual saturation of Place/dcterms:hasPart'),
  'saturation_place_skos_preflabel' => array('label' => 'Multilingual saturation of Place/skos:prefLabel'),
  'saturation_place_skos_altlabel' => array('label' => 'Multilingual saturation of Place/skos:altLabel'),
  'saturation_place_skos_note' => array('label' => 'Multilingual saturation of Place/skos:note'),

  'saturation_agent_edm_begin' => array('label' => 'Multilingual saturation of Agent/edm:begin'),
  'saturation_agent_edm_end' => array('label' => 'Multilingual saturation of Agent/edm:end'),
  'saturation_agent_edm_hasmet' => array('label' => 'Multilingual saturation of Agent/edm:hasMet'),
  'saturation_agent_edm_isrelatedto' => array('label' => 'Multilingual saturation of Agent/edm:isRelatedTo'),
  'saturation_agent_owl_sameas' => array('label' => 'Multilingual saturation of Agent/owl:sameAs'),
  'saturation_agent_foaf_name' => array('label' => 'Multilingual saturation of Agent/foaf:name'),
  'saturation_agent_dc_date' => array('label' => 'Multilingual saturation of Agent/dc:date'),
  'saturation_agent_dc_identifier' => array('label' => 'Multilingual saturation of Agent/dc:identifier'),
  'saturation_agent_rdagr2_dateofbirth' => array('label' => 'Multilingual saturation of Agent/rdaGr2:dateOfBirth'),
  'saturation_agent_rdagr2_placeofbirth' => array('label' => 'Multilingual saturation of Agent/rdaGr2:placeOfBirth'),
  'saturation_agent_rdagr2_dateofdeath' => array('label' => 'Multilingual saturation of Agent/rdaGr2:dateOfDeath'),
  'saturation_agent_rdagr2_placeofdeath' => array('label' => 'Multilingual saturation of Agent/rdaGr2:placeOfDeath'),
  'saturation_agent_rdagr2_dateofestablishment' => array('label' => 'Multilingual saturation of Agent/rdaGr2:dateOfEstablishment'),
  'saturation_agent_rdagr2_dateoftermination' => array('label' => 'Multilingual saturation of Agent/rdaGr2:dateOfTermination'),
  'saturation_agent_rdagr2_gender' => array('label' => 'Multilingual saturation of Agent/rdaGr2:gender'),
  'saturation_agent_rdagr2_professionoroccupation' => array('label' => 'Multilingual saturation of Agent/rdaGr2:professionOrOccupation'),
  'saturation_agent_rdagr2_biographicalinformation' => array('label' => 'Multilingual saturation of Agent/rdaGr2:biographicalInformation'),
  'saturation_agent_skos_preflabel' => array('label' => 'Multilingual saturation of Agent/skos:prefLabel'),
  'saturation_agent_skos_altlabel' => array('label' => 'Multilingual saturation of Agent/skos:altLabel'),
  'saturation_agent_skos_note' => array('label' => 'Multilingual saturation of Agent/skos:note'),

  'saturation_timespan_edm_begin' => array('label' => 'Multilingual saturation of Timespan/edm:begin'),
  'saturation_timespan_edm_end' => array('label' => 'Multilingual saturation of Timespan/edm:end'),
  'saturation_timespan_dcterms_ispartof' => array('label' => 'Multilingual saturation of Timespan/dcterms:isPartOf'),
  'saturation_timespan_dcterms_haspart' => array('label' => 'Multilingual saturation of Timespan/dcterms:hasPart'),
  'saturation_timespan_edm_isnextinsequence' => array('label' => 'Multilingual saturation of Timespan/edm:isNextInSequence'),
  'saturation_timespan_owl_sameas' => array('label' => 'Multilingual saturation of Timespan/owl:sameAs'),
  'saturation_timespan_skos_preflabel' => array('label' => 'Multilingual saturation of Timespan/skos:prefLabel'),
  'saturation_timespan_skos_altlabel' => array('label' => 'Multilingual saturation of Timespan/skos:altLabel'),
  'saturation_timespan_skos_note' => array('label' => 'Multilingual saturation of Timespan/skos:note'),

  'saturation_concept_skos_broader' => array('label' => 'Multilingual saturation of Concept/skos:broader'),
  'saturation_concept_skos_narrower' => array('label' => 'Multilingual saturation of Concept/skos:narrower'),
  'saturation_concept_skos_related' => array('label' => 'Multilingual saturation of Concept/skos:related'),
  'saturation_concept_skos_broadmatch' => array('label' => 'Multilingual saturation of Concept/skos:broadMatch'),
  'saturation_concept_skos_narrowmatch' => array('label' => 'Multilingual saturation of Concept/skos:narrowMatch'),
  'saturation_concept_skos_relatedmatch' => array('label' => 'Multilingual saturation of Concept/skos:relatedMatch'),
  'saturation_concept_skos_exactmatch' => array('label' => 'Multilingual saturation of Concept/skos:exactMatch'),
  'saturation_concept_skos_closematch' => array('label' => 'Multilingual saturation of Concept/skos:closeMatch'),
  'saturation_concept_skos_notation' => array('label' => 'Multilingual saturation of Concept/skos:notation'),
  'saturation_concept_skos_inscheme' => array('label' => 'Multilingual saturation of Concept/skos:inScheme'),
  'saturation_concept_skos_preflabel' => array('label' => 'Multilingual saturation of Concept/skos:prefLabel'),
  'saturation_concept_skos_altlabel' => array('label' => 'Multilingual saturation of Concept/skos:altLabel'),
  'saturation_concept_skos_note' => array('label' => 'Multilingual saturation of Concept/skos:note'),

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
  'aggregated' => 'All fields aggregated',
  'agent_dc_date' => 'Agent / dc:date',
  'agent_dc_identifier' => 'Agent / dc:identifier',
  'agent_edm_begin' => 'Agent / edm:begin',
  'agent_edm_end' => 'Agent / edm:end',
  'agent_edm_hasMet' => 'Agent / edm:hasMet',
  'agent_edm_isRelatedTo' => 'Agent / edm:isRelatedTo',
  'agent_foaf_name' => 'Agent / foaf:name',
  'agent_owl_sameAs' => 'Agent / owl:sameAs',
  'agent_rdaGr2_biographicalInformation' => 'Agent / rdaGr2:biographicalInformation',
  'agent_rdaGr2_dateOfBirth' => 'Agent / rdaGr2:dateOfBirth',
  'agent_rdaGr2_dateOfDeath' => 'Agent / rdaGr2:dateOfDeath',
  'agent_rdaGr2_dateOfEstablishment' => 'Agent / rdaGr2:dateOfEstablishment',
  'agent_rdaGr2_dateOfTermination' => 'Agent / rdaGr2:dateOfTermination',
  'agent_rdaGr2_gender' => 'Agent / rdaGr2:gender',
  'agent_rdaGr2_placeOfBirth' => 'Agent / rdaGr2:placeOfBirth',
  'agent_rdaGr2_placeOfDeath' => 'Agent / rdaGr2:placeOfDeath',
  'agent_rdaGr2_professionOrOccupation' => 'Agent / rdaGr2:professionOrOccupation',
  'agent_skos_altLabel' => 'Agent / skos:altLabel',
  'agent_skos_note' => 'Agent / skos:note',
  'agent_skos_prefLabel' => 'Agent / skos:prefLabel',
  'aggregation_dc_rights' => 'Aggregation / dc:rights',
  'aggregation_edm_aggregatedCHO' => 'Aggregation / edm:aggregatedCHO',
  'aggregation_edm_dataProvider' => 'Aggregation / edm:dataProvider',
  'aggregation_edm_hasView' => 'Aggregation / edm:hasView',
  'aggregation_edm_intermediateProvider' => 'Aggregation / edm:intermediateProvider',
  'aggregation_edm_isShownAt' => 'Aggregation / edm:isShownAt',
  'aggregation_edm_isShownBy' => 'Aggregation / edm:isShownBy',
  'aggregation_edm_object' => 'Aggregation / edm:object',
  'aggregation_edm_provider' => 'Aggregation / edm:provider',
  'aggregation_edm_rights' => 'Aggregation / edm:rights',
  'aggregation_edm_ugc' => 'Aggregation / edm:ugc',
  'concept_skos_altLabel' => 'Concept / skos:altLabel',
  'concept_skos_broader' => 'Concept / skos:broader',
  'concept_skos_broadMatch' => 'Concept / skos:broadMatch',
  'concept_skos_closeMatch' => 'Concept / skos:closeMatch',
  'concept_skos_exactMatch' => 'Concept / skos:exactMatch',
  'concept_skos_inScheme' => 'Concept / skos:inScheme',
  'concept_skos_narrower' => 'Concept / skos:narrower',
  'concept_skos_narrowMatch' => 'Concept / skos:narrowMatch',
  'concept_skos_notation' => 'Concept / skos:notation',
  'concept_skos_note' => 'Concept / skos:note',
  'concept_skos_prefLabel' => 'Concept / skos:prefLabel',
  'concept_skos_related' => 'Concept / skos:related',
  'concept_skos_relatedMatch' => 'Concept / skos:relatedMatch',
  'identifier' => 'identifier',
  'place_dcterms_hasPart' => 'Place / dcterms:hasPart',
  'place_dcterms_isPartOf' => 'Place / dcterms:isPartOf',
  'place_skos_altLabel' => 'Place / skos:altLabel',
  'place_skos_note' => 'Place / skos:note',
  'place_skos_prefLabel' => 'Place / skos:prefLabel',
  'proxy_dc_conformsTo' => 'Proxy / dc:conformsTo',
  'proxy_dc_contributor' => 'Proxy / dc:contributor',
  'proxy_dc_coverage' => 'Proxy / dc:coverage',
  'proxy_dc_creator' => 'Proxy / dc:creator',
  'proxy_dc_date' => 'Proxy / dc:date',
  'proxy_dc_description' => 'Proxy / dc:description',
  'proxy_dc_format' => 'Proxy / dc:format',
  'proxy_dc_identifier' => 'Proxy / dc:identifier',
  'proxy_dc_language' => 'Proxy / dc:language',
  'proxy_dc_publisher' => 'Proxy / dc:publisher',
  'proxy_dc_relation' => 'Proxy / dc:relation',
  'proxy_dc_rights' => 'Proxy / dc:rights',
  'proxy_dc_source' => 'Proxy / dc:source',
  'proxy_dc_subject' => 'Proxy / dc:subject',
  'proxy_dc_title' => 'Proxy / dc:title',
  'proxy_dc_type' => 'Proxy / dc:type',
  'proxy_dcterms_alternative' => 'Proxy / dcterms:alternative',
  'proxy_dcterms_created' => 'Proxy / dcterms:created',
  'proxy_dcterms_extent' => 'Proxy / dcterms:extent',
  'proxy_dcterms_hasFormat' => 'Proxy / dcterms:hasFormat',
  'proxy_dcterms_hasPart' => 'Proxy / dcterms:hasPart',
  'proxy_dcterms_hasVersion' => 'Proxy / dcterms:hasVersion',
  'proxy_dcterms_isFormatOf' => 'Proxy / dcterms:isFormatOf',
  'proxy_dcterms_isPartOf' => 'Proxy / dcterms:isPartOf',
  'proxy_dcterms_isReferencedBy' => 'Proxy / dcterms:isReferencedBy',
  'proxy_dcterms_isReplacedBy' => 'Proxy / dcterms:isReplacedBy',
  'proxy_dcterms_isRequiredBy' => 'Proxy / dcterms:isRequiredBy',
  'proxy_dcterms_issued' => 'Proxy / dcterms:issued',
  'proxy_dcterms_isVersionOf' => 'Proxy / dcterms:isVersionOf',
  'proxy_dcterms_medium' => 'Proxy / dcterms:medium',
  'proxy_dcterms_provenance' => 'Proxy / dcterms:provenance',
  'proxy_dcterms_references' => 'Proxy / dcterms:references',
  'proxy_dcterms_replaces' => 'Proxy / dcterms:replaces',
  'proxy_dcterms_requires' => 'Proxy / dcterms:requires',
  'proxy_dcterms_spatial' => 'Proxy / dcterms:spatial',
  'proxy_dcterms_tableOfContents' => 'Proxy / dcterms:tableOfContents',
  'proxy_dcterms_temporal' => 'Proxy / dcterms:temporal',
  'proxy_edm_currentLocation' => 'Proxy / edm:currentLocation',
  'proxy_edm_europeanaProxy' => 'Proxy / edm:europeanaProxy',
  'proxy_edm_hasMet' => 'Proxy / edm:hasMet',
  'proxy_edm_hasType' => 'Proxy / edm:hasType',
  'proxy_edm_incorporates' => 'Proxy / edm:incorporates',
  'proxy_edm_isDerivativeOf' => 'Proxy / edm:isDerivativeOf',
  'proxy_edm_isNextInSequence' => 'Proxy / edm:isNextInSequence',
  'proxy_edm_isRelatedTo' => 'Proxy / edm:isRelatedTo',
  'proxy_edm_isRepresentationOf' => 'Proxy / edm:isRepresentationOf',
  'proxy_edm_isSimilarTo' => 'Proxy / edm:isSimilarTo',
  'proxy_edm_isSuccessorOf' => 'Proxy / edm:isSuccessorOf',
  'proxy_edm_realizes' => 'Proxy / edm:realizes',
  'proxy_edm_type' => 'Proxy / edm:type',
  'proxy_edm_userTag' => 'Proxy / edm:userTag',
  'proxy_edm_wasPresentAt' => 'Proxy / edm:wasPresentAt',
  'proxy_edm_year' => 'Proxy / edm:year',
  'proxy_ore_ProxyFor' => 'Proxy / ore:ProxyFor',
  'proxy_ore_ProxyIn' => 'Proxy / ore:ProxyIn',
  'timespan_dcterms_hasPart' => 'Timespan / dcterms:hasPart',
  'timespan_dcterms_isPartOf' => 'Timespan / dcterms:isPartOf',
  'timespan_edm_begin' => 'Timespan / edm:begin',
  'timespan_edm_end' => 'Timespan / edm:end',
  'timespan_edm_isNextInSequence' => 'Timespan / edm:isNextInSequence',
  'timespan_owl_sameAs' => 'Timespan / owl:sameAs',
  'timespan_skos_altLabel' => 'Timespan / skos:altLabel',
  'timespan_skos_note' => 'Timespan / skos:note',
  'timespan_skos_prefLabel' => 'Timespan / skos:prefLabel'
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
$jsonCountFileName = 'json/' . $type . $id . '.count.json';
if (file_exists($jsonCountFileName)) {
  $stats = json_decode(file_get_contents($jsonCountFileName));
  $n = $stats[0]->count;
}

$jsonFileName = 'json/' . $type . $id . '.json';
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

$freqFile = 'json/' . $type . $id . '.freq.json';
$freqFileExists = file_exists($freqFile);

$cardinalityFile = 'json/' . $type . $id . '.cardinality.json';
$cardinalityFileExists = file_exists($cardinalityFile);
$cardinalityProperties = ['sum', 'mean', 'median'];
if ($cardinalityFileExists) {
  $key = 'cardinality-property';
  $cardinalityProperty = isset($_GET[$key]) && in_array($_GET[$key], $cardinalityProperties) 
    ? $_GET[$key] : 'sum';
}

$frequencyTableFile = 'json/' . $type . $id . '.frequency.table.json';
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

$histFile = 'json/' . $type . $id . '.hist.json';
if (file_exists($histFile)) {
  $histograms = json_decode(file_get_contents($histFile));
} else {
  $histograms = FALSE;
}

$languageFile = 'json/' . $type . $id . '.languages.json';
if (file_exists($languageFile)) {
  $languages = json_decode(file_get_contents($languageFile));
} else {
  $languages = FALSE;
}

$saturationFile = 'json/' . $type . $id . '.saturation.json';
$saturationFileExists = file_exists($saturationFile);
if ($saturationFileExists) {
  $saturation = json_decode(file_get_contents($saturationFile));
  foreach ($saturation as $obj) {
    $key = $obj->_row;
    unset($obj->_row);
    $assocStat[$key] = $obj;
  }
}

$saturationHistFile = 'json/' . $type . $id . '.saturation.histogram.json';
if (file_exists($saturationHistFile)) {
  $histograms = (object) array_merge(
    (array)$histograms, 
    (array)json_decode(file_get_contents($saturationHistFile))
  );
}

$saturationFrequencyTableFile = 'json/' . $type . $id . '.saturation.frequency.table.json';
if (file_exists($saturationFrequencyTableFile)) {
  $saturationFrequencyTable = json_decode(file_get_contents($saturationFrequencyTableFile));
  foreach ($saturationFrequencyTable as $key => $value) {
    $frequencyTable->{strtolower($key)} = $value;
  }
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
