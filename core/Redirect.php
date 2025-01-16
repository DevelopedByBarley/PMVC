<?php

namespace Core;

class Redirect
{
  public static function redirect($uri = null)
  {
    if ($uri) {
      header('Location: ' . $uri);
      exit; // Biztosítja, hogy a szkript futása leálljon
    }

    throw new \InvalidArgumentException('No URI provided for redirection.');
  }

  public static function redirectBack()
  {
    if (!empty($_SERVER['HTTP_REFERER'])) {
      static::redirect($_SERVER['HTTP_REFERER']);
    } else {
      throw new \RuntimeException('HTTP_REFERER is not set, cannot redirect back.');
    }
  }
}
