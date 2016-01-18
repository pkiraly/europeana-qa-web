<?php

$configuration = parse_ini_file('config.cfg');

$csv = array_map('str_getcsv', file('collection-names.csv'));

$counter = 0;
include('dataset-directory-header.tpl.php');
foreach ($csv as $id => $row) {
  $id = $row[0];
  $collectionId = $row[1];

  $jsonFileName = $configuration['QA_R_PATH'] . '/' . $id . '.json';
  if (file_exists($jsonFileName)) {

    $stats = json_decode(file_get_contents($jsonFileName));
    $assocStat = array();
    foreach ($stats as $obj) {
      if ($obj->_row == 'total') {
        $counter++;
        include('dataset-directory.tpl.php');
      }
    }
  }
}

print <<<EOT
</tbody>
</table>
EOT;
