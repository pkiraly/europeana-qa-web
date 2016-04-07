<?php
define('LN', "\n");
define('OAI_PMH_URL_TEMPLATE', 'http://oai.europeana.eu/oaicat/OAIHandler?verb=GetRecord&metadataPrefix=edm&identifier=%s');
define('ID_PREFIX', 'http://data.europeana.eu/item/');

$configuration = parse_ini_file('config.cfg');
require_once($configuration['OAI_PATH'] . '/OAIHarvester.php');

$id = isset($_GET['id']) ? $_GET['id'] : $argv[1];
if (strpos($id, ID_PREFIX) !== FALSE) {
  $id = str_replace(ID_PREFIX, '', $id);
}
if (substr($id, 0, 1) == '/') {
  $id = substr($id, 1);
}

$metadata = retrieveRecord($id);
if (!empty($metadata)) {
  $json = str_replace("'", "'\''", json_encode($metadata));
  $command = sprintf(
    "java -cp %s/europeana-qa-spark-1.0-SNAPSHOT-jar-with-dependencies.jar com.nsdr.spark.CLI '%s' 2>/dev/null",
    $configuration['SPARK_JAR_PATH'],
    $id
  );
  $result = exec($command);
  $analysis = json_decode($result);
  if (is_null($analysis)) {
    echo 'command: ', $command;
    echo 'metadata: ', $json;
    echo 'result: ', $result;
    die();
  }
}

$graphs = array(
  'total' => array('label' => 'Completeness', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dcterms:alternative",
  	"Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:identifier",
  	"Proxy/dc:language", "Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dc:date",
  	"Proxy/dcterms:created", "Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:format", "Proxy/dc:source", "Proxy/dc:rights", "Proxy/dc:relation",
  	"Proxy/edm:isNextInSequence", "Proxy/edm:type", "Proxy/edm:rights", "Aggregation/edm:rights", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object", "Aggregation/edm:hasView")),
  'mandatory' => array('label' => 'Mandatory elements', 'fields' => array("edm:ProvidedCHO/@about", "Proxy/dc:title", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/edm:rights", "Aggregation/edm:rights",
  	"Aggregation/edm:provider", "Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy")),
  'descriptiveness' => array('label' => 'Descriptiveness', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:language", "Proxy/dc:subject", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	"Proxy/dc:format", "Proxy/dc:source")),
  'searchability' => array('label' => 'Searchability', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:provider", "Aggregation/edm:dataProvider")),
  'contextualization' => array('label' => 'Contextualization', 'fields' => array("Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:type",
  	"Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf",
  	"Proxy/dc:relation", "Proxy/edm:isNextInSequence")),
  'identification' => array('label' => 'Identification', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:type", "Proxy/dc:identifier", "Proxy/dc:date", "Proxy/dcterms:created", "Proxy/dcterms:issued", "Aggregation/edm:provider",
  	"Aggregation/edm:dataProvider")),
  'browsing' => array('label' => 'Browsing', 'fields' => array("Proxy/dc:creator", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
  	"Proxy/dcterms:spatial", "Proxy/dc:date", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation", "Proxy/edm:isNextInSequence",
  	"Proxy/edm:type", "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:hasView")),
  'viewing' => array('label' => 'Viewing', 'fields' => array("Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object",
  	"Aggregation/edm:hasView")),
  'reusability' => array('label' => 'Re-usability', 'fields' => array("Proxy/dc:publisher", "Proxy/dc:date", "Proxy/dcterms:created",
  	"Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dc:format", "Proxy/dc:rights", "Proxy/edm:rights",
  	"Aggregation/edm:rights", "Aggregation/edm:isShownBy", "Aggregation/edm:object")),
  'multilinguality' => array('label' => 'Multilinguality', 'fields' => array("Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
  	"Proxy/dc:language", "Proxy/dc:subject")),
  'dc:title:sum' => array('label' => 'dc:title entropy - cumulative'),
  'dc:title:avg' => array('label' => 'dc:title entropy - average'),
  'dcterms:alternative:sum' => array('label' => 'dcterms:alternative entropy - cumulative'),
  'dcterms:alternative:avg' => array('label' => 'dctersm:alternative entorpy - average'),
  'dc:description:sum' => array('label' => 'dc:description entropy - cumulative'),
  'dc:description:avg' => array('label' => 'dc:description entorpy - average')
);

$optional_groups = [
  'mandatory' => [
    ['fields' => ['Proxy/dc:title', 'Proxy/dc:description'], 'has_value' => FALSE],
    ['fields' => ['Proxy/dc:type', 'Proxy/dc:subject', 'Proxy/dc:coverage', 'Proxy/dcterms:temporal', 'Proxy/dcterms:spatial'], 'has_value' => FALSE]
  ]
];
$has_alternatives = hasAlternative($optional_groups);

$table = array();
$table[0] = array();
foreach ($graphs as $key => $object) {
  $table[0][] = $key == 'total' ? '&nbsp;' : $object['label'];
}

foreach ($graphs['total']['fields'] as $field) {
  $row = array($field);
  $color = in_array($field, $analysis->existingFields) ? 'green' : 'yellow';
  // $color = $colors[rand(0, 2)];
  foreach ($graphs as $key => $object) {
    if ($key == 'total')
      continue;
    if (in_array($field, $object['fields'])) {
      if ($key == 'mandatory' && $color != 'green')
        $row[] = $has_alternatives[$field] ? 'gray' : 'red';
      else
        $row[] = $color;
    } else {
      $row[] = '';
    }
  }
  $table[] = $row;
}

include("record.tpl.php");

// echo json_encode(json_decode(file_get_contents("http://www.europeana.eu/portal/record/11620/MNHNBOTANY_MNHN_FRANCE_P04617748.json")), JSON_PRETTY_PRINT);


function retrieveRecord($id) {
  $cluster   = Cassandra::cluster()->build();
  $session   = $cluster->connect('europeana');
  $statement = new Cassandra\SimpleStatement(sprintf("SELECT content FROM edm WHERE id = '%s'", $id));
  $future    = $session->executeAsync($statement);
  $result    = $future->get();
  $json = null;
  foreach ($result as $row) {
    $json = $row['content'];
  }
  return json_decode($json);
}

function hasAlternative() {
  global $optional_groups, $analysis;

  $has_alternative = [];
  foreach ($optional_groups as $dimension => $groups) {
    for ($g = 0; $g < count($groups); $g++) {
      $group = $groups[$g];
      for ($i =0; $i < count($group['fields']); $i++) {
        $field = $group['fields'][$i];
        if (in_array($field, $analysis->existingFields)) {
          $optional_groups[$dimension][$g]['has_value'] = TRUE;
          break;
        }
      }
      foreach ($group['fields'] as $field) {
        $has_alternative[$field] = $optional_groups[$dimension][$g]['has_value'];
      }
    }
  }
  return $has_alternative;
}
