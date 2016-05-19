<?php

$field = $_GET['field'];
if (!isset($field))
  $field = 'all';
$excludeZeros = (isset($_GET['excludeZeros']) && $_GET['excludeZeros'] == 1) ? TRUE : FALSE;

$codes = [
  'no language',
  'no field instance',
  'resource'
];

$languages = json_decode(file_get_contents('json/languages.json'));
$filtered = [];
foreach ($languages->$field as $language => $count) {
  if ($excludeZeros && in_array($language, $codes))
  	continue;
  $filtered[] = [
    'language' => $language,
    'count' => $count
  ];
}
echo json_encode($filtered);
