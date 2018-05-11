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
  list($from,$to) = explode('-', $string);
  if (isset($to)) {
    $string = sprintf("[%s TO %s]", $from, $to);
  }

  return $string;
}
