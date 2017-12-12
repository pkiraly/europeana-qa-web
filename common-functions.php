<?php

function getOrDefault($key, $default_value) {
  $value = $_GET[$key];
  if (!isset($value)) {
    $value = $default_value;
  }
  return $value;
}