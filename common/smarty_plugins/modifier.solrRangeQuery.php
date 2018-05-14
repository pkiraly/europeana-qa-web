<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.solrRangeQuery.php
 * Type:     modifier
 * Name:     solrRangeQuery
 * Purpose:  Formatting field labels.
 * -------------------------------------------------------------
 */
function smarty_modifier_solrRangeQuery($string) {
  $parts = explode('-', $string);
  if (count($parts) == 2) {
    $string = sprintf("[%s TO %s]", $parts[0], $parts[1]);
  }

  return $string;
}
