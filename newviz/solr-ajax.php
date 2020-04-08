<?php

$root = realpath(__DIR__. '/../');
include_once $root . '/common/common-functions.php';

$baseUrl = 'http://localhost:8984/solr/';
$baseUrl .= getOrDefault('version', 'v2019-10');

error_log(sprintf('%s:%d query: %s', basename(__FILE__), __LINE__, $_SERVER['QUERY_STRING']));

$q = $_GET['q'];
$fq = (isset($_GET['fq'])) ? $_GET['fq'] : '';
$rows = isset($_GET['rows']) ? (int)$_GET['rows'] : 0;

$url = $baseUrl . '/select'
  . '?q=' . urlencode($q);

if ($fq != '')
  $url .= '&fq=' . $fq;

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