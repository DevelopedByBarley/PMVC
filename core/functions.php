<?php


function base_path($path)
{
  return BASE_PATH . $path;
}


function view($path, $params = []): string
{
  ob_start();

  extract($params);

  $filePath = base_path('resources/views/' . $path . ".php");

  if (!file_exists($filePath)) {
    echo 'This file is doesnt exist!';
    throw new \Exception("View file not found: " . $filePath);
  }

  require $filePath;

  $output = ob_get_clean();

  if (!headers_sent()) {
    header("Content-Type: text/html; charset=UTF-8");
  }

  return $output;
}
