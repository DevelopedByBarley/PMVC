<?php

namespace Core;

class Language
{



  public static function set()
  {
    if (Cookie::get('lang')) return;
    $brows_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
    $pref_lang = explode(',', $brows_lang);

    $lang = strtolower(trim(explode(';', $pref_lang[0])[0]));
    $lang = explode('-', $lang)[0];

    $allowed_langs = ['hu', 'en', 'es'];

    if (!in_array($lang, $allowed_langs)) {
      $lang = "en-en";
    }

    Cookie::set('lang', $lang, 3600 * 24 * 30);
    if(!Cookie::get('lang')) {
      $_COOKIE['lang'] = $lang;
    }
  }

  public static function switch($lang)
  {
    $lang = strtolower($lang);
    $allowed_langs = ['hu', 'en', 'es'];


    if (!in_array($lang, $allowed_langs)) {
      die('Problem: ' . $lang . ' is not allowed langugage!');
    }

    Cookie::set('lang', $lang, 3600 * 24 * 30);
  }
}
?>
