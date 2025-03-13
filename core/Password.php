<?php

namespace Core;

class Password
{
  /**
   * Véletlenszerű jelszó generálása.
   *
   * @param int $length A jelszó hosszúsága (alapértelmezett 12 karakter).
   * @return string A generált jelszó.
   */
  public static function generate($length = 12)
  {
    // Kötelező karaktercsoportok
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';
    $specials = '@#$^&-_|.';

    // Összes karakter egyesítve
    $allCharacters = $lowercase . $uppercase . $digits . $specials;

    // Legalább egy karakter minden csoportból
    $password = $lowercase[random_int(0, strlen($lowercase) - 1)] .
      $uppercase[random_int(0, strlen($uppercase) - 1)] .
      $digits[random_int(0, strlen($digits) - 1)] .
      $specials[random_int(0, strlen($specials) - 1)];

    // Hátralévő karakterek véletlenszerűen az összes karakterből
    for ($i = 4; $i < $length; $i++) {
      $password .= $allCharacters[random_int(0, strlen($allCharacters) - 1)];
    }

    // Jelszó összekeverése, hogy ne legyen mindig ugyanaz a sorrend
    return str_shuffle($password);
  }
}
