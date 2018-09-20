<?php

include_once getRootPath() . '/common/common-functions.php';

function getRootPath() {
  return realpath(__DIR__. '/../');
}

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

function getDataDir() {
  global $configuration, $version;

  if (!isset($version)) {
    $version = getOrDefault('version', $configuration['version'][0], $configuration['version']);
  }

  return $configuration['DATA_PATH'] . '/' . $version;
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

function createSmarty($templateDir) {
  define('APPLICATION', 'europeana-qa');
  define('APPLICATION_DIR', $_SERVER['DOCUMENT_ROOT'] . '/' . APPLICATION);

  define('SMARTY_DIR', APPLICATION_DIR . '/libs/smarty-3.1.32/libs/');
  define('_SMARTY', APPLICATION_DIR . '/libs/_smarty/');

  require_once(SMARTY_DIR . 'Smarty.class.php');

  $smarty = new Smarty();

  $smarty->setTemplateDir(getcwd() . '/' . $templateDir);
  $smarty->setCompileDir(_SMARTY . '/templates_c/');
  $smarty->setConfigDir(_SMARTY . '/configs/');
  $smarty->setCacheDir(_SMARTY . '/cache/');
  $smarty->addPluginsDir(APPLICATION_DIR . '/common/smarty_plugins/');

  // standard PHP function
  $smarty->registerPlugin("modifier", "str_replace", "str_replace");
  $smarty->registerPlugin("modifier", "number_format", "number_format");

  return $smarty;
}