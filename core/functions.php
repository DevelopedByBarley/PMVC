<?php

use Core\Session;

function session($entity)
{
  return Session::get($entity);
}

function errors($key, $errors)
{
  if (isset($errors) && !empty($errors)) {
    if (isset($errors[$key]['errors'])) {
      foreach ($errors[$key]['errors'] as $error) {
        echo "<li class='list-unstyled text-danger'>{$error}</li>";
      }
    }
  }
}

function dd($value)
{
  echo "<pre>";
  var_dump($value);
  echo "</pre>";

  die();
}

function urlIs($value)
{
  return $_SERVER['REQUEST_URI'] === $value;
}

function abort($code = 404)
{
  http_response_code($code);

  require base_path("resources/views/status/{$code}.view.php");

  die();
}

function base_path($path)
{
  return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
  extract($attributes);

  require base_path('resources/views/' . $path . '.view.php');
}

function old($key, $default = '')
{
  return Core\Session::get('old')[$key] ?? $default;
}

function view_path($path)
{
  return BASE_PATH . 'resources/views/' . $path . '.view.php';
}

function mail_temp_path($path)
{
  return BASE_PATH . 'resources/views/mail/' . $path . '.mt.php';
}


function paginate($paginated) {
  require view_path('components/pagination');
}


function can($entity) {}

function cannot($entity) {}


/**
 * Általános szűrő és szanitizáló függvény.
 *
 * @param mixed $value A bemenet, amit tisztítani szeretnél.
 * @param string $type A bemenet típusa: 'string', 'int', 'email', 'url', stb.
 * @return mixed A szűrt és tisztított érték, vagy false, ha a validáció nem sikerült.
 */
/**
 * Általános szűrő és szanitizáló függvény.
 *
 * @param mixed $value A bemenet, amit tisztítani szeretnél.
 * @return mixed A szűrt és tisztított érték, vagy az eredeti érték, ha nem támogatott típus.
 */
function filter_sanitize($value)
{
  $type = gettype($value);

  switch ($type) {
    case 'string':
      return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    case 'integer':
      return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    case 'double':
      return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    case 'boolean':
      return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    case 'array':
      return array_map('filter_sanitize', $value);
    case 'NULL':
      return null;
    default:
      return $value;
  }
}

  function csrf()
  {
    (new \Core\CSRF)->generate()->input();
  }
