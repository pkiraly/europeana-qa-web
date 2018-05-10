<?php

$baseUrl = 'http://localhost:8983/solr/qa-2018-03';

$q = $_GET['q'];
$fq = $_GET['fq'];

$url = $baseUrl . '/select'
  . '?q=' . $q
  . '&fq=' . $fq
  . '&f=id'
  . '&rows=10';

$response = json_decode(file_get_contents($url));

$ids = [];
foreach ($response->response->docs as $doc) {
  $ids[] = $doc->id;
}

header("Content-type: application/json");
echo json_encode([
  'total' => $response->response->numFound,
  'ids' => $ids
]);