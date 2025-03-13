<?php

use Core\Cookie;
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

function urlContains($needle)
{
  return strpos($_SERVER['REQUEST_URI'], $needle) !== false;
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

function view($path, $params = [])
{
  ob_start();

  extract($params);

  $filePath = base_path('resources/views/' . $path . '.view.php');

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

function old($key, $default = '')
{
  return Core\Session::get('old')[$key] ?? $default;
}

function view_path($path)
{
  return BASE_PATH . 'resources/views/' . $path . '.view.php';
}

function tmpPath($file)
{
  return BASE_PATH . 'resources/views/messages/' . $file . '.tmp.php';
}

function mail_temp_path($path)
{
  return BASE_PATH . 'resources/views/mail/' . $path . '.mt.php';
}


function paginate($paginated, $with_search = true)
{
  require view_path('components/pagination');
}
function extractMapUrl($iframe)
{
  if (preg_match('/src="([^"]+)"/', $iframe, $matches)) {
    return $matches[1];
  }

  if (str_contains($iframe, 'https://www.google.com/maps/embed?pb=')) {
    return $iframe;
  }

  return null;
}

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
function sanitize($value)
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
      return array_map('sanitize', $value);
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

function lang($params)
{
  $lang = explode('-', Cookie::get('lang'))[0];
  $keys = explode('__', $params);

  [$file_name, $keys_of_arr] = $keys;

  $arr_key = explode('.', $keys_of_arr);
  $file = require base_path("langs/{$lang}/{$file_name}.lang.php");

  foreach ($arr_key as $key) {
    if (isset($file[$key])) {
      $file = $file[$key]; // Frissítjük a referencia helyét
    } else {
      return "Translation for '{$keys_of_arr}' not found."; // Ha nem találjuk, hibát dobunk
    }
  }
  return $file;
}

function cookie($key)
{
  return Cookie::get($key);
}

function public_file($path)
{
  return "/public/{$path}";
}

function arr_to_obj($arr)
{
  return json_decode(json_encode($arr));
}

function obj_to_arr($obj)
{
  return json_decode(json_encode($obj), true);
}
function isUrl(string $expectedUrl): bool
{
  $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Csak az útvonalat vesszük
  return trim($currentUrl, '/') === trim($expectedUrl, '/');
}


function ud()
{
  echo view('status/ud', []);
}

function deleteImage($path)
{
  $full_path = base_path('public' . $path);
  if (file_exists($full_path)) {
    unlink($full_path);
    echo "Fájl törölve: $full_path";
  } else {
    echo "A fájl nem található: $full_path";
  }
}

function getWeekRange($events)
{
  foreach ($events as $event) {
    $timestamp = strtotime($event->date_time);

    $weekStart = date('Y-m-d', strtotime('monday this week', $timestamp));
    $weekEnd = date('Y-m-d', strtotime('sunday this week', $timestamp));

    $event->week = "$weekStart - $weekEnd";
    $event->week_start = $weekStart;
    $event->week_end = $weekEnd;
  }

  return $events;
}
