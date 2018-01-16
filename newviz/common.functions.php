<?php

function getParameters() {
  $parameters = (object)[
    'id' => '',
    'entity' => '',
    'type' => ''
  ];

  if (isset($_GET['id'])) {
    $parameters->id = $_GET['id'];
  }

  if (isset($_GET['type'])) {
    $parameters->type = $_GET['type'];
  } else {
    list($parameters->id, $parameters->type) = parseId($parameters->id);
  }

  return $parameters;
}

function parseId($id) {
  $type = substr($id, 0, 1);
  if (in_array($type, ['c', 'd'])) {
    $id = substr($id, 1);
  } else {
    $type = 'c';
  }
  return [$id, $type];
}

function callTemplate($data, $file) {
  ob_start();
  include($file);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
