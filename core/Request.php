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
    return $_POST[$key];
  }

  public static function unset($key)
  {
    unset($_POST[$key]);
  }


  public static function validate($rules)
  {
    return Validator::validate($_POST, $rules);
  }
}
