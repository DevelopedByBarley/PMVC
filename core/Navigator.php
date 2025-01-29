<?php

namespace Core;

class Navigator
{
  public static function redirect($url)
  {
    header("HTTP/1.1 302 Found");
    header("Location: " . $url);
    exit;
  }

  public static function redirectBack()
  {
    if (!empty($_SERVER['HTTP_REFERER']) && filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL)) {
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit; 
    } else {
      static::redirect('/default-page');
    }
  }
}
