<?php
$basicStatistics = [
  'min' => 'minimum',
  'max' => 'maximum',
  'SE.mean' => 'standard error on the mean',
  'CI.mean.0.95' => 'confidence interval of the mean',
  'var' => 'variance',
  'std.dev' => 'standard deviation',
  'coef.var' => 'variation coefficient',
  'Q1' => '1st quartile',
  'Q3' => '3rd quartile',
  'trimmedMean' => 'trimmed mean (90%)',
  'skew' => '<a href="https://en.wikipedia.org/wiki/Skewness" target="_blank">skewness</a>',
  'mad' => 'median absolute deviation',
  'kurtosis' => '<a href="https://en.wikipedia.org/wiki/Kurtosis" target="_blank">kurtosis</a>',
  'boxplot.lower' => 'lowest normal value (lower whisker)',
  'boxplot.upper' => 'highest normal value (upper whisker)',
  'boxplot.out.n' => 'number of outliers',
  'boxplot.out.perc' => 'percent of outliers',
  'boxplot.out.upper.n' => 'number of high outliers',
  'boxplot.out.lower.n' => 'number of low outliers',
];

function getLabel($key) {
  $label = $key;
  $format = 'simple';
  if ($format == 'simple') {
    $label = preg_replace('/_(taggedliterals|languages|literalsperlanguage)/', '', $label);
  } else {
    $label = preg_replace('/_taggedliterals/', ' / tagged literals', $label);
    $label = preg_replace('/_languages/', ' / languages', $label);
    $label = preg_replace('/_literalsperlanguage/', ' / literals per language', $label);
    $label = preg_replace('/^(.*?) /', '<strong>$1</strong> ', $label);
  }
  $label = preg_replace('/_/', ':', $label);
  return $label;
}

function conditional_format($num, $minimize = FALSE, $toDouble = FALSE) {
  if ($toDouble) {
    $num = (double)$num;
  } else {
    $num = strstr($num, '.') ? (double)$num : (int)$num;
  }
  if ($toDouble || is_double($num)) {
    $formatted = number_format($num, 2);
    if ($minimize) {
      $formatted = preg_replace('/\.$/', '', preg_replace('/0+$/', '', $formatted));
    }
    return $formatted;
  }
  return $msg . $num;
}

function format_histogram_range($label, $showBoth = FALSE) {
  list($a, $b) = explode(' - ', $label);

  if ($showBoth)
    return sprintf('<span title="%s">%s</span>-<span title="%s">%s</span>', 
      $a, conditional_format($a, TRUE), $b, conditional_format($b, TRUE));

  return sprintf('<span title="%s">%s</span>-', $a, conditional_format($a, TRUE));
}

function qa_format($num) {
  $num = preg_replace('/(\..*)/', '<span class="decimals">$1</span>', number_format($num, 2));

  return $num;
}

function labelsInBasicStatistics($key) {
  global $basicStatistics;
  if (isset($basicStatistics[$key])) {
    return $basicStatistics[$key];
  }
  return $key;
}
