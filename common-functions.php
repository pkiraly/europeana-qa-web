<?php

function getOrDefault($key, $default_value) {
  $value = isset($_GET[$key]) ? $_GET[$key] : $default_value;
  if (!isset($value)) {
    $value = $default_value;
  }
  return $value;
}