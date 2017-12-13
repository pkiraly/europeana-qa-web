<?php
include_once('common.functions.php');
include_once('../common/saturation-functions.php');
$templateDir = '../templates/newviz/multilinguality/';

$parameters = getParameters();
$data = (object)[
  'generic_prefixes' => getGenerixPrefixes(),
  'fields' => getFields(),
  'assocStat' => getAssocStat()
];

$html = callTemplate($data, $templateDir . 'top-level-scores.tpl.php');

header("Content-type: application/json");
echo json_encode([
  'html' => $html,
  'data' => json_encode($data->assocStat['specific'])
]);

function callTemplate($data, $file) {
  ob_start();
  include($file);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function getGenerixPrefixes() {
  return [
    'provider' => 'In provider proxy',
    'europeana' => 'In Europeana proxy',
    'object' => 'In object'
  ];
}

function getFields() {
  return [
    'languages_per_property' => 'Number of language tags',
    'taggedliterals' => 'Number of tagged literals',
    'distinctlanguages' => 'Number of distinct language tags',
    'taggedliterals_per_language' => 'Number of tagged literals per language'
  ];
}

function getAssocStat() {
  global $parameters;

  $assocStat = [];
  $saturationFile = '../json/' . $parameters->type . $parameters->id
    . '/' . $parameters->type . $parameters->id . '.saturation.json';
  $saturationFileExists = file_exists($saturationFile);
  if ($saturationFileExists) {
    $saturation = json_decode(file_get_contents($saturationFile));
    foreach ($saturation as $obj) {
      $key = preg_replace('/^saturation2_/', '', $obj->_row);
      // unset($obj->_row);

      $prefix = 'generic';
      if (preg_match('/^europeana_/', $key)) {
        $key = preg_replace('/^europeana_/', '', $key);
        $prefix = 'europeana';
      } else if (preg_match('/^provider_/', $key)) {
        $key = preg_replace('/^provider_/', '', $key);
        $prefix = 'provider';
      }

      if ($prefix == 'generic') {
        if (preg_match('/_in_providerproxy$/', $key)) {
          $prefix = 'provider';
        } else if (preg_match('/_in_europeanaproxy$/', $key)) {
          $prefix = 'europeana';
        } else if (preg_match('/_in_object$/', $key)) {
          $prefix = 'object';
        }
        $key = preg_replace('/_in_.*$/', '', $key);
        $assocStat['generic'][$key][$prefix] = $obj;
      } else {
        $fields[$key] = getLabel($key);
        $specific_type = "";
        $field = preg_replace('/_(taggedliterals|languages|literalsperlanguage)/', '', $key);
        if (preg_match('/_taggedliterals/', $key)) {
          $specific_type = 'taggedliterals';
        } else if (preg_match('/_languages/', $key)) {
          $specific_type = 'languages';
        } else if (preg_match('/_literalsperlanguage/', $key)) {
          $specific_type = 'literalsperlanguage';
        }

        // $assocStat['specific'][$specific_type][$key][$prefix] = $obj;
        $assocStat['specific'][$field][$specific_type][$prefix] = $obj;
      }
    }
  }
  return $assocStat;
}

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

function conditional_format($num, $minimize = FALSE) {
  $num = strstr($num, '.') ? (double)$num : (int)$num;
  if (is_double($num)) {
    $formatted = number_format($num, 2);
    if ($minimize) {
      $formatted = preg_replace('/\.$/', '', preg_replace('/0+$/', '', $formatted));
    }
    return $formatted;
  }
  return $num;
}

