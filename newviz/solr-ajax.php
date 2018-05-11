<?php

$baseUrl = 'http://localhost:8983/solr/qa-2018-03';

$q = $_GET['q'];
$fq = $_GET['fq'];
$rows = isset($_GET['rows']) ? (int)$_GET['rows'] : 0;

$url = $baseUrl . '/select'
  . '?q=' . urlencode($q)
  . '&fq=' . $fq;

if ($rows > 0) {
  $url .= '&f=id'
       .  '&rows=' . $rows;
} else {
  $url .= '&rows=0';
}

$response = json_decode(file_get_contents($url));

$ids = [];
if (isset($response->response->docs)) {
  foreach ($response->response->docs as $doc) {
    $ids[] = $doc->id;
  }
}

header("Content-type: application/json");
echo json_encode([
  'total' => $response->response->numFound,
  'ids' => $ids
]);