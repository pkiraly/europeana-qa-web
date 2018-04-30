<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.conditional_format.php
 * Type:     modifier
 * Name:     conditional_format
 * Purpose:  Formatting numbers.
 * -------------------------------------------------------------
 */
function smarty_modifier_conditional_format($num, $minimize = FALSE, $toDouble = FALSE, $decimals = 2) {
  if ($toDouble) {
    $num = (double)$num;
  } else {
    $num = strstr($num, '.') ? (double)$num : (int)$num;
  }
  if (is_double($num)) {
    $formatted = number_format($num, $decimals);
    if ($minimize) {
      $formatted = preg_replace('/\.$/', '', preg_replace('/0+$/', '', $formatted));
    }
    return $formatted;
  }
  return $num;
}
