<?php
$fragment = isset($_GET['fragment']) ? $_GET['fragment'] : NULL;
$type = isset($_GET['type']) && (in_array($_GET['type'], ['c', 'd'])) ? $_GET['type'] : 'c';
$fileName = ($type == 'c') ? 'datasets.txt' : 'data-providers.txt';

$content = retrieveCsv($fileName, $fragment);

header("Content-type: application/json");
echo json_encode([
  'fragment' => $fragment,
  'type' => $type,
  'content' => $content,
]);

function retrieveCsv($fileName, $fragment) {
  $list = [];
  $content = explode("\n", file_get_contents('../' . $fileName));
  foreach ($content as $line) {
    if ($line == '')
      continue;

    if (is_null($fragment) || stristr($line, $fragment)) {
      list($_id, $_name) = explode(';', $line, 2);
      $list[] = ['value' => $_id, 'name' => $_name];
    }
  }
  return $list;
}
