<?php

namespace Core;

class Request
{

  public static function all()
  {
    return $_POST;
  }

  public static function key($key)
  {
    if(!$key || !isset($_POST[$key]) || empty($_POST[$key])) {
      return null;
    }
    return $_POST[$key];
  }

  public static function get($key)
  {
    if(!$key || !isset($_GET[$key]) || empty($_GET[$key])) {
      return null;
    }
    return $_GET[$key];
  }

  public static function unset($key)
  {
    unset($_POST[$key]);
  }


  public static function validate($rules, $post = null)
  {
    $post = $post ? $post : $_POST;
    return Validator::validate($post, $rules);
  }

  public static function setNull($key)
  {
    if (!$_POST[$key] || !isset($_POST[$key]) || empty($_POST[$key])) {
      $_POST[$key] = null;
    }
  }

  public function file($key)
  {
    return $_FILES[$key] ?? null;
  }
}
