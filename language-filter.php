<?php

$field = $_GET['field'];
if (!isset($field))
  $field = 'all';
$excludeZeros = (isset($_GET['excludeZeros']) && $_GET['excludeZeros'] == 1) ? TRUE : FALSE;

$languages = json_decode(file_get_contents('json/languages.json'));
$filtered = [];
foreach ($languages->$field as $language => $count) {
  if ($excludeZeros && $language == 'not specified')
  	continue;
  $filtered[] = [
    'language' => $language,
    'count' => $count
  ];
}
echo json_encode($filtered);