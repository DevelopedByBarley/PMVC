<?php

namespace Core;

class Response
{
  public static function json($data, $status = 200)
  {
    header('Content-Type: application/json', true, $status);
    echo json_encode($data);
    exit;
  }

  public static function view($view, $layout = 'layout', $data = [])
  {

    $content = view($view, $layout, $data, TEMPLATE_ENGINE ?? false);

    // Majd a layout-ba wrappoljuk, ahol a 'root' paraméter lesz a renderelt tartalom
    echo view("components/{$layout}", '', array_merge($data, ['root' => $content]));
  }

  public static function data($data, $status = 200)
  {
    http_response_code($status);

    // Ha array vagy object, akkor JSON-ként küldjük
    if (is_array($data) || is_object($data)) {
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    // Ha string, akkor HTML-ként
    else if (is_string($data)) {
      header('Content-Type: text/html; charset=UTF-8');
      echo $data;
    }
    // Egyéb típusok esetén string konverzió
    else {
      header('Content-Type: text/plain; charset=UTF-8');
      echo (string) $data;
    }

    exit;
  }

  public static function redirect($url, $status = 302)
  {
    header("Location: {$url}", true, $status);
    exit;
  }

  public static function error($message, $status = 500)
  {
    http_response_code($status);
    echo "<h1>Error {$status}</h1>";
    echo "<p>{$message}</p>";
    exit;
  }

  public static function download($filePath, $fileName = null)
  {
    if (!file_exists($filePath)) {
      self::error("File not found", 404);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . ($fileName ?: basename($filePath)));
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
  }

  public static function api($data)
  {
    // CSRF token lekérése
    $csrfToken = (new CSRF())->generate()->getToken();
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token');
    header('Access-Control-Expose-Headers: X-CSRF-Token');
    header("X-CSRF-Token: {$csrfToken}");

    // Preflight request handling
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      http_response_code(204);
      exit;
    }

    return self::json($data);
  }

  public static function status($code)
  {
    http_response_code($code);
    require view_path("status/{$code}");
    exit;
  }
  public static function flash($key, $value)
  {
    Session::flash($key, $value);
  }
  public static function unsetFlash($key)
  {
    Session::unset($key);
  }
  public static function getFlash($key, $default = null)
  {
    return Session::get($key, $default);
  }
  public static function hasFlash($key)
  {
    return Session::has($key);
  }
  public static function unflash()
  {
    Session::unflash();
  }
  public static function flush()
  {
    Session::flush();
  }
  public static function has($key)
  {
    return Session::has($key);
  }
  public static function get($key, $default = null)
  {
    return Session::get($key, $default);
  }
  public static function put($key, $value)
  {
    Session::put($key, $value);
  }
}
