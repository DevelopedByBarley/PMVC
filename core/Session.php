<?php

namespace Core;

class Session
{

  public static function create()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }


  public static function has($key)
  {
    return (bool)static::get($key);
  }

  public static function put($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public static function get($key, $default = null)
  {
    return $_SESSION[$key] ?? $_SESSION['_flash'][$key] ?? $default;
  }

  public static function unset($key)
  {
    if (isset($_SESSION[$key])) unset($_SESSION[$key]);
  }

  public static function flash($key, $value)
  {
    $_SESSION['_flash'][$key] = $value;
  }

  public static function unflash()
  {
    if (isset($_SESSION['_flash'])) unset($_SESSION['_flash']);
  }

  public static function flush()
  {
    $_SESSION = [];
  }
}
