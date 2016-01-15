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

$params = array(
  'metadataPrefix' => 'edm',
  'identifier' => ID_PREFIX . $id
);

$harvester = new OAIHarvester('GetRecord', 'http://oai.europeana.eu/oaicat/OAIHandler', $params);
$harvester->fetchContent();
$harvester->processContent();
if (($record = $harvester->getNextRecord()) != null) {
  $metadata = processRecord($record);
  if (!empty($metadata)) {
    $command = sprintf(
      "java -jar %s/europeana-qa-1.0-SNAPSHOT-jar-with-dependencies.jar '%s' 2>/dev/null",
      $configuration['JAR_PATH'],
      str_replace("'", "'\''", json_encode($metadata))
    );
    $analysis = json_decode(exec($command));
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
  	"Proxy/dc:language", "Proxy/dc:subject"))
);

$table = array();
$table[0] = array();
foreach ($graphs as $key => $object) {
  $table[0][] = $key == 'total' ? '&nbsp;' : $object['label'];
}

foreach ($graphs['total']['fields'] as $field) {
  $row = array($field);
  $color = in_array($field, $analysis->existingFields) ? 'green' : 'grey';
  // $color = $colors[rand(0, 2)];
  foreach ($graphs as $key => $object) {
    if ($key == 'total')
      continue;
    if (in_array($field, $object['fields'])) {
      if ($key == 'mandatory' && $color != 'green')
        $row[] = 'red';
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


function processRecord($record) {
  $metadata = array();
  $isDeleted = (isset($record['header']['@status']) && $record['header']['@status'] == 'deleted');
  $id = $record['header']['identifier'];
  if (!$isDeleted) {
    $metadata = dom_to_array($record['metadata']['childNode']);
    $metadata['qIdentifier'] = $record['header']['identifier'];
    $metadata['identifier'] = str_replace(ID_PREFIX, '', $record['header']['identifier']);
    $metadata['sets'] = $record['header']['setSpec'];
  }
  return $metadata;
}

/**
 * Transform DOM object to an array
 */
function dom_to_array($node, $parent_name = NULL) {
  $name = $node->nodeName;
  $metadata = array();

  // copy attributes
  foreach ($node->attributes as $attr) {
    $metadata['@' . $attr->name] = $attr->value;
  }

  // process children
  foreach ($node->childNodes as $child) {
    // process children elements
    if ($child->nodeType == XML_ELEMENT_NODE) {
      if ($node->childNodes->length == 1) {
        $metadata[$child->nodeName] = dom_to_array($child, $name);
      } else {
        $metadata[$child->nodeName][] = dom_to_array($child, $name);
      }
    }
    // copy text value
    elseif ($child->nodeType == XML_TEXT_NODE) {
      $value = trim($child->nodeValue);
      if (!empty($value)) {
        $metadata['#value'] = str_replace("\n", ' ', $value);
      }
    }
  }
  if ($parent_name !== NULL) {
    if (count($metadata) == 1 && isset($metadata['#value'])) {
      $metadata = $metadata['#value'];
    }
  }

  return $metadata;
}
