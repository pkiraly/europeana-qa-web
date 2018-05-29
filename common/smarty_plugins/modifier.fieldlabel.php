<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.fieldlabel.php
 * Type:     modifier
 * Name:     fieldlabel
 * Purpose:  Formatting field labels.
 * -------------------------------------------------------------
 */
function smarty_modifier_fieldlabel($string) {
  if ($string == 'aggregated') {
    $string = 'All fields';
  } else {
    $string = str_replace('dc_', 'dc:', $string);
    $string = str_replace('dcterms_', 'dcterms:', $string);
    $string = str_replace('edm_', 'edm:', $string);
    $string = str_replace('ore_', 'ore:', $string);
    $string = str_replace('owl_', 'owl:', $string);
    $string = str_replace('skos_', 'skos:', $string);
    $string = str_replace('foaf_', 'foaf:', $string);
    $string = str_replace('rdaGr2_', 'rdaGr2:', $string);
    $string = str_replace('proxy_', 'provider proxy/', $string);
    $string = str_replace('aggregation_', 'aggregation/', $string);
    $string = str_replace('agent_', 'agent/', $string);
    $string = str_replace('concept_', 'concept/', $string);
    $string = str_replace('place_', 'place/', $string);
    $string = str_replace('timespan_', 'timespan/', $string);
  }

  return $string;
}
