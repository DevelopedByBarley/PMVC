<?php

namespace Core;

class Authenticator
{
  public function checkPassword($email, $password, $table = 'users', $verified = false)
  {
    $db = Database::getInstance();

    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();

    // Először ellenőrizzük, hogy létezik-e a user
    if (!$user) {
      return false;
    }

    // Ha email verification szükséges és nincs verificálva
    if ($verified && is_null($user->email_verified_at)) {
      return false;
    }

    // Jelszó ellenőrzés
    if (password_verify($password, $user->password)) {
      return $user;
    }

    return false;
  }

  

  public function attempt($email, $password, $table = 'users', $verified = false)
  {
    $db = Database::getInstance();

    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();

    // Először ellenőrizzük, hogy létezik-e a user
    if (!$user) {
      return false;
    }

    // Ha email verification szükséges és nincs verificálva
    if ($verified && is_null($user->email_verified_at)) {
      return false;
    }

    
    // Jelszó ellenőrzés
    if (password_verify($password, $user->password)) {
      $this->login($user, $table); // Teljes user objektumot adjuk át
      return $user;
    }

    return false;
  }

  public static function login($user, $table = 'users')
  {
    // Session inicializálás ha még nincs
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // Entity név meghatározása (users -> user)
    $entity = rtrim($table, 's');

    // Teljes user objektum tárolása session-ben
    $_SESSION[$entity] = $user;

    session_regenerate_id(true);
  }

  public static function logout()
  {
    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
}
