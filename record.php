<?php
$configuration = parse_ini_file('config.cfg');
include_once('common/common-functions.php');
include_once('newviz/common.functions.php');

define('LN', "\n");
define('OAI_PMH_URL_TEMPLATE', 'http://oai.europeana.eu/oaicat/OAIHandler?verb=GetRecord&metadataPrefix=edm&identifier=%s');
define('ID_PREFIX', 'http://data.europeana.eu/item/');
define('RECORD_API', 'http://144.76.218.178:8080/europeana-qa/record/%s.json?dataSource=mongo');
define('METRICS_API', 'http://144.76.218.178:8080/europeana-qa/%s.json?dataSource=cassandra');

$solrPort = 8984;

$baseUrl = 'http://localhost:8984/solr/';

$configuration = parse_ini_file('config.cfg');
require_once($configuration['OAI_PATH'] . '/OAIHarvester.php');

$id = isset($_GET['id']) ? $_GET['id'] : $argv[1];
if (strpos($id, ID_PREFIX) !== FALSE) {
  $id = str_replace(ID_PREFIX, '', $id);
}

$version  = getOrDefault('version', $configuration['DEFAULT_VERSION'], $configuration['version']);
$dataDir = $configuration['DATA_PATH'] . '/' . $version;

$metadata = json_decode(file_get_contents(sprintf(RECORD_API, $id)));

$subdimensions = [
  'total', 'mandatory', 'descriptiveness', 'searchability', 'contextualization',
  'identification', 'browsing', 'viewing', 'reusability', 'multilinguality'
];

$graphs = [
  'total' => [
    'label' => 'Completeness of all fields',
    'fields' => [
      "ProvidedCHO/rdf:about", "Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
      "Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type", "Proxy/dc:identifier",
    	"Proxy/dc:language", "Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial",
      "Proxy/dc:subject", "Proxy/dc:date", "Proxy/dcterms:created", "Proxy/dcterms:issued",
      "Proxy/dcterms:extent", "Proxy/dcterms:medium", "Proxy/dcterms:provenance",
  	  "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:format", "Proxy/dc:source",
      "Proxy/dc:rights", "Proxy/dc:relation", "Proxy/edm:isNextInSequence", "Proxy/edm:type",
      // "Proxy/edm:rights",
      "Aggregation/edm:rights", "Aggregation/edm:provider", "Aggregation/edm:dataProvider",
      "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object", "Aggregation/edm:hasView"
    ]
  ],
  'mandatory' => [
    'label' => 'Mandatory elements',
    'fields' => [
      "ProvidedCHO/rdf:about", "Proxy/dc:title", "Proxy/dc:description", "Proxy/dc:type", "Proxy/dc:coverage",
      "Proxy/dcterms:spatial", "Proxy/dc:subject",
      // "Proxy/edm:rights",
      "Aggregation/edm:rights",
    	"Aggregation/edm:provider", "Aggregation/edm:dataProvider", "Aggregation/edm:isShownAt",
      "Aggregation/edm:isShownBy"
    ]
  ],
  'descriptiveness' => [
    'label' => 'Descriptiveness',
    'description' => 'how much information has the metadata to describe what the object is about',
    'fields' => [
      "Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
    	"Proxy/dc:creator", "Proxy/dc:language", "Proxy/dc:subject", "Proxy/dcterms:extent",
      "Proxy/dcterms:medium", "Proxy/dcterms:provenance", "Proxy/dc:format", "Proxy/dc:source"
    ]
  ],
  'searchability' => [
    'label' => 'Searchability',
    'description' => 'the fields most heavily used in searches',
    'fields' => [
      "Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
    	"Proxy/dc:creator", "Proxy/dc:publisher", "Proxy/dc:contributor", "Proxy/dc:type",
      "Proxy/dc:coverage", "Proxy/dcterms:temporal", "Proxy/dcterms:spatial",
      "Proxy/dc:subject", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf",
      "Proxy/dc:relation", "Proxy/edm:isNextInSequence", "Proxy/edm:type",
      "Aggregation/edm:provider", "Aggregation/edm:dataProvider"
    ]
  ],
  'contextualization' => [
    'label' => 'Contextualization',
    'description' => 'the bases for finding connected entities (persons, places, times, etc.) in the record',
    'fields' => [
      "Proxy/dc:description", "Proxy/dc:creator", "Proxy/dc:type", "Proxy/dc:coverage",
      "Proxy/dcterms:temporal", "Proxy/dcterms:spatial", "Proxy/dc:subject",
      "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf", "Proxy/dc:relation",
      "Proxy/edm:isNextInSequence"
    ]
  ],
  'identification' => [
    'label' => 'Identification',
    'description' => 'for unambiguously identifying the object',
    'fields' => [
      "Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
    	"Proxy/dc:type", "Proxy/dc:identifier", "Proxy/dc:date", "Proxy/dcterms:created",
      "Proxy/dcterms:issued", "Aggregation/edm:provider", "Aggregation/edm:dataProvider"
    ]
  ],
  'browsing' => [
    'label' => 'Browsing',
    'description' => 'for the browsing features at the portal',
    'fields' => [
      "Proxy/dc:creator", "Proxy/dc:type", "Proxy/dc:coverage", "Proxy/dcterms:temporal",
    	"Proxy/dcterms:spatial", "Proxy/dc:date", "Proxy/dcterms:hasPart", "Proxy/dcterms:isPartOf",
      "Proxy/dc:relation", "Proxy/edm:isNextInSequence", "Proxy/edm:type", "Aggregation/edm:isShownAt",
      "Aggregation/edm:isShownBy", "Aggregation/edm:hasView"
    ]
  ],
  'viewing' => [
    'label' => 'Viewing',
    'description' => 'for the displaying at the portal',
    'fields' => [
      "Aggregation/edm:isShownAt", "Aggregation/edm:isShownBy", "Aggregation/edm:object",
    	"Aggregation/edm:hasView"
    ]
  ],
  'reusability' => [
    'label' => 'Re-usability',
    'description' => 'for reusing the metadata records in other systems',
    'fields' => [
      "Proxy/dc:publisher", "Proxy/dc:date", "Proxy/dcterms:created",
    	"Proxy/dcterms:issued", "Proxy/dcterms:extent", "Proxy/dcterms:medium",
      "Proxy/dc:format", "Proxy/dc:rights",
      // "Proxy/edm:rights",
    	"Aggregation/edm:rights", "Aggregation/edm:isShownBy", "Aggregation/edm:object"
    ]
  ],
  'multilinguality' => [
    'label' => 'Multilinguality',
    'description' => 'for multilingual aspects, to be understandable for all European citizen',
    'fields' => [
      "Proxy/dc:title", "Proxy/dcterms:alternative", "Proxy/dc:description",
    	"Proxy/dc:language", "Proxy/dc:subject"
    ]
  ],
  'dc:title:sum' => ['label' => 'dc:title entropy - cumulative'],
  // 'dc:title:avg' => ['label' => 'dc:title entropy - average'],
  // 'dcterms:alternative:sum' => ['label' => 'dcterms:alternative entropy - cumulative'],
  // 'dcterms:alternative:avg' => ['label' => 'dctersm:alternative entorpy - average'],
  // 'dc:description:sum' => ['label' => 'dc:description entropy - cumulative'],
  // 'dc:description:avg' => ['label' => 'dc:description entorpy - average']
];

$entityMap = [
  'Aggregation' => 'ore:Aggregation',
  'Proxy' => 'ore:Proxy',
  'EuropeanaAggregation' => 'edm:EuropeanaAggregation',
  'Agent' => 'edm:Agent',
  'Concept' => 'skos:Concept',
  'Place' => 'edm:Place',
  'Timespan' => 'edm:TimeSpan'
];

$fieldMap = [
  'rdf:about' => '@about'
];

$optional_groups = [
  'mandatory' => [
    [
      'fields' => ['Proxy/dc:title', 'Proxy/dc:description'],
      'has_value' => FALSE
    ],
    [
      'fields' => ['Proxy/dc:type', 'Proxy/dc:subject', 'Proxy/dc:coverage', 'Proxy/dcterms:temporal', 'Proxy/dcterms:spatial'],
      'has_value' => FALSE
    ],
    [
      'fields' => ['Aggregation/edm:isShownAt', 'Aggregation/edm:isShownBy'],
      'has_value' => FALSE
    ]
  ]
];

$multilinguality_labels = [
  'taggedliterals' => 'Number of tagged literals',
  'distinctlanguages' => 'Number of distinct language tags',
  'taggedliterals_per_language' => 'Number of tagged literals per language tag',
  'languages_per_property' => 'Average number of languages per property for which there is at least one language-tagged literal'
];

$rawMetrics = getMetricsFromSolr($id, $version);
$metrics = reorganizeMetrics($rawMetrics);

$has_alternatives = hasAlternative($optional_groups, $metrics);

$table = [];
$table[0] = [];
foreach ($graphs as $key => $object) {
  $table[0][] = $key == 'total' ? '&nbsp;' : $object['label'];
}

foreach ($graphs['total']['fields'] as $field) {
  $row = array($field);
  $color = in_array($field, $metrics->existence) && $metrics->existence[$field] == 1 ? 'green' : 'yellow';
  // $color = $colors[rand(0, 2)];
  foreach ($graphs as $key => $object) {
    if ($key == 'total' || !isset($object['fields']))
      continue;
    if (in_array($field, $object['fields'])) {
      if ($key == 'mandatory' && $color != 'green')
        $row[] = isset($has_alternatives[$field]) ? 'gray' : 'red';
      else
        $row[] = $color;
    } else {
      $row[] = '';
    }
  }
  $table[] = $row;
}

// print_r($metadata);
$structure = extractStructure($metadata, $graphs['total']['fields']);
$problems = [];
$structure = array_merge($structure, extractEntities($metadata, $problems));

$smarty = createSmarty('templates/record');
$smarty->assign('rand', rand());
$smarty->assign('id', $id);
$smarty->assign('title', 'Record view');
$smarty->assign('stylesheets', ['chart.css', 'style/newviz.css']);
$smarty->assign('version', $version);
$smarty->assign('metrics', $metrics);
$smarty->assign('table', $table);
$smarty->assign('graphs', $graphs);
$smarty->assign('subdimensions', $subdimensions);
$smarty->assign('metadata', $metadata);
$smarty->assign('structure', $structure);
$smarty->assign('problems', $problems);
$smarty->assign('collection', retrieveName($metrics->identifiers['collection'], 'c'));
$smarty->assign('provider', retrieveName($metrics->identifiers['provider'], 'd'));
$smarty->assign('multilinguality_labels', $multilinguality_labels);
$smarty->display('record.smarty.tpl');

function hasAlternative($optional_groups, $metrics) {

  $has_alternative = [];
  foreach ($optional_groups as $dimension => $groups) {
    for ($g = 0; $g < count($groups); $g++) {
      $group = $groups[$g];
      for ($i =0; $i < count($group['fields']); $i++) {
        $field = $group['fields'][$i];
        if ($metrics->existence[$field] == 1) {
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

function extractStructure($metadata, $fields) {
  $structure = [];
  $outOfStructure = [];
  $problems = [];
  $proxy = null;
  $europeanaProxy = null;
  foreach ($metadata->{'ore:Proxy'} as $pxy) {
    if (
        (is_bool($pxy->{'edm:europeanaProxy'}) && $pxy->{'edm:europeanaProxy'} === false)
        ||
        (is_array($pxy->{'edm:europeanaProxy'}) && $pxy->{'edm:europeanaProxy'}[0] == "false")
    ) {
      $proxy = $pxy;
    } else {
      $europeanaProxy = $pxy;
    }
  }
  $aggregation = $metadata->{'ore:Aggregation'}[0];
  $providedCHO = $metadata->{'edm:ProvidedCHO'}[0];

  $structure['edm:ProvidedCHO/rdf:about'] = [$providedCHO->{'@about'}];
  foreach ($proxy as $field => $values) {
    if ($field == '@about')
      $field == 'rdf:about';
    extractValues('Proxy/' . $field, $values, $fields, $structure, $outOfStructure, $problems);
  }
  foreach ($europeanaProxy as $field => $values) {
    if ($field == '@about')
      $field == 'rdf:about';
    extractValues('EuropeanaProxy/' . $field, $values, $fields, $structure, $outOfStructure, $problems);
  }
  foreach ($aggregation as $field => $values) {
    extractValues('Aggregation/' . $field, $values, $fields, $structure, $outOfStructure, $problems);
  }

  $goodOrder = [];
  foreach ($fields as $field) {
    if (isset($structure[$field])) {
      $goodOrder[$field] = $structure[$field];
    }
  }
  $structure = $goodOrder;
  foreach ($outOfStructure as $key => $value) {
    $structure['#' . $key] = $value;
  }

  # $structure['problems'] = $problems;
  return $structure;
}

function extractEntities($metadata, &$problems) {
  global $entityMap;

  $contextualEntities = ['Agent', 'Concept', 'Place', 'Timespan'];
  $entities = [];
  foreach ($entityMap as $key => $edmKey) {
    if (in_array($key, $contextualEntities)) {
      if (!isset($metadata->{$edmKey}))
        continue;

      foreach ($metadata->{$edmKey} as $entity) {
        foreach (array_keys(get_object_vars($entity)) as $field) {
          $entityKey = $key . '/' . ($field == '@about' ? 'rdf:about' : $field);
          if (!isset($entities[$entityKey])) {
            $entities[$entityKey] = [];
          }
          $values = $entity->$field;
          if (!is_array($values))
            $values = [$values];

          foreach ($values as $value) {
            if (is_string($value) || is_numeric($value)) {
              $entities[$entityKey][] = $value;
            } else if (is_object($value)) {
              if (isset($value->{'@resource'})) {
                $entities[$entityKey][] = sprintf('%s (@resource)', $value->{'@resource'});
              } else if (isset($value->{'@lang'})) {
                $entities[$entityKey][] = sprintf('"%s"@%s', $value->{'#value'}, $value->{'@lang'});
              } else {
                $problems[] = 'no resource: ' . json_encode($value);
              }
            } else {
              $problems[] = 'other type: ' . var_export($value, TRUE) . '->' . gettype($value);
            }
          }
        }
      }
    }
  }
  return $entities;
}

function extractValues($key, $values, $fields, &$structure, &$outOfStructure, &$problems) {
  static $ignorableFields = [
    'Proxy/@about', 'Proxy/edm:europeanaProxy', 'Proxy/ore:proxyFor', 'Proxy/ore:proxyIn',
    'Aggregation/@about', 'Aggregation/edm:aggregatedCHO'
  ];

  if (in_array($key, $fields)) {
    $container =& $structure;
  } else if (!in_array($key, $ignorableFields)) {
    $container =& $outOfStructure;
  }
  $container[$key] = [];
  if (!is_array($values)) {
    $values = [$values];
  }
  foreach ($values as $value) {
    if (is_string($value)) {
      $container[$key][] = $value;
    } else if (is_object($value)) {
      if (isset($value->{'@resource'})) {
        $container[$key][] = sprintf('%s (@resource)', $value->{'@resource'});
      } else if (isset($value->{'@lang'})) {
        $container[$key][] = sprintf('"%s"@%s', $value->{'#value'}, $value->{'@lang'});
      } else {
        $problems[] = 'no resource: ' . json_encode($value);
      }
    } else {
      $problems[] = 'other type: ' . var_export($value, TRUE);
    }
  }
}

function getFieldValue($field) {
  global $metadata, $entityMap, $fieldMap;
  $topValues = [];

  list($entityName, $fieldName) = explode('/', $field);
  if (isset($entityMap[$entityName])) {
    $entityName = $entityMap[$entityName];
  }

  if (isset($fieldMap[$fieldName])) {
    $fieldName = $fieldMap[$fieldName];
  }

  if (isset($metadata->{$entityName})) {
    $entities = $metadata->{$entityName};
    for ($i = 0, $len = count($entities); $i < $len; $i++) {
      $values = [];
      $entity = $entities[$i];
      if ($entityName == 'ore:Proxy' && $entity->{'edm:europeanaProxy'}[0] == 'true') {
        continue;
      }
      if (isset($entity->{$fieldName})) {
        // $values[] = $entity->{$fieldName};
        if (!is_array($entity->{$fieldName})) {
          $values[] = $entity->{$fieldName};
          $put++;
        } else {
          foreach ($entity->{$fieldName} as $value) {
            if (is_string($value)) {
              $values[] = $value;
              $put++;
            } else if (is_object($value)) {
              if (isset($value->{'@resource'})) {
                $values[] = $value->{'@resource'} . ' (@resource)';
                $put++;
              } else if (isset($value->{'@lang'})) {
                $values[] = '"' . $value->{'#value'} . '"@' . $value->{'@lang'};
                $put++;
              }
            }
          }
        }
      }
      if (!empty($values)) {
        $topValues[] = $values;
      }
    }
  }
  return $topValues;
}

function getMetricsFromSolr($id, $version) {
  global $solrPort;

  if (substr($version, 0, 1) == 'v')
    $version = substr($version, 1);

  $response = json_decode(file_get_contents(getSolrMetricsUrl($id, $version)));

  return $response->response->docs[0];
}

function getSolrMetricsUrl($id, $version) {
  global $solrPort;

  return sprintf('http://localhost:%s/solr/qa-%s/select?q=id:"%s"', $solrPort, $version, $id);
  $raw_metrics = $response->docs[0];
  return $raw_metrics;
}

function reorganizeMetrics($raw_metrics) {
  global $subdimensions;

  $reorganized = (object)[
    'identifiers' => [],
    'subdimensions' => [],
    'existence' => [],
    'cardinality' => [],
    'multilinguality' => (object)[
      'fields' => [],
      'global' => []
    ],
    'languages' => (object)[
      'fields' => [],
      'global' => []
    ],
    'problemCatalog' => [],
    'uncategorized' => [],
  ];

  $subdimensions_pattern = '/^(' . join('|', $subdimensions) . ')_f$/';

  foreach ($raw_metrics as $key => $value) {
    if (preg_match('/^(ProvidedCHO|Proxy|Aggregation|Place|Agent|Timespan|Concept)_/', $key)) {
      $reorganized->existence[existenceToEdm($key)] = $value;
    } else if (preg_match('/^crd_(ProvidedCHO|Proxy|Aggregation|Place|Agent|Timespan|Concept)_/', $key)) {
      $reorganized->cardinality[existenceToEdm(str_replace('crd_', '', $key))] = $value;
    } else if (preg_match($subdimensions_pattern, $key)) {
      $reorganized->subdimensions[str_replace('_f', '', $key)] = $value;
    } else if (preg_match('/^(provider|europeana)_(.*?)_(taggedliterals|languages|literalsperlanguage)_f/', $key, $matches)) {
      $reorganized->multilinguality->fields[$matches[1]][str_replace('_', ':', $matches[2])][$matches[3]] = $value;
    } else if (preg_match('/^(languages_per_property|taggedliterals_per_language|taggedliterals|distinctlanguages)_in_(providerproxy|europeanaproxy|object)_f/', $key, $matches)) {
      if (!isset($reorganized->multilinguality->global[$matches[1]]))
        $reorganized->multilinguality->global[$matches[1]] = (object)[];
      $reorganized->multilinguality->global[$matches[1]]->{$matches[2]} = $value;
    } else if (preg_match('/^lang_(.*?)_ss$/', $key, $matches)) {
      $reorganized->languages->fields[langToEdm($matches[1])] = $value;
    } else if (preg_match('/^(languages_ss)/', $key)) {
      $reorganized->languages->global = $value;
    } else if (preg_match('/^(id|collection_i|provider_i)$/', $key)) {
      $reorganized->identifiers[str_replace('_i', '', $key)] = $value;
    } else if (preg_match('/^(long_subject_f_f|same_title_and_description_f_f|empty_string_f)$/', $key)) {
      $reorganized->problemCatalog[problemCatalog($key)] = $value;
    } else if (preg_match('/^_version_$/', $key)) {
      // $reorganized->languages->global = $value;
    } else {
      $reorganized->uncategorized[$key] = $value;
    }
  }
  return $reorganized;
}

function existenceToEdm($key) {
  $key = preg_replace('/^(ProvidedCHO|Proxy|Aggregation|Place|Agent|Timespan|Concept)_(.*?)_f$/', "$1/$2", $key);
  $key = str_replace('_', ':', $key);
  return $key;
}

function langToEdm($key) {
  $key = preg_replace('/^(ProvidedCHO|proxy|aggregation|place|agent|timespan|concept)_(.*?)$/', "$1/$2", $key);
  $key = str_replace('_', ':', $key);
  return $key;
}

function problemCatalog($key) {
  $key = preg_replace('/(_f)?_f$/', "", $key);
  $key = str_replace('_', ' ', $key);
  return $key;
}

function retrieveName($id, $type) {
  global $dataDir;

  if (!isset($content)) {
    $file = ($type == 'c') ? 'datasets.txt' : "data-providers.txt";
    $content = explode("\n", file_get_contents($dataDir . '/' . $file));
  }

  $name = FALSE;
  foreach ($content as $line) {
    if (strlen($line) > 0) {
      list($_id, $_name) = explode(';', $line, 2);
      if ($_id == $id) {
        $name = $_name;
        break;
      }
    }
  }
  return $name;
}
