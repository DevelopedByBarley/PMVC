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

    Log::info('Bejelentkezési kísérlet', [
      'email' => $email,
      'table' => $table,
      'verified_required' => $verified
    ]);

    $user = $db->query("SELECT * FROM $table WHERE email = :email", [
      'email' => $email
    ])->find();

    // Először ellenőrizzük, hogy létezik-e a user
    if (!$user) {
      Log::info('Sikertelen bejelentkezés - nem létező email', ['email' => $email, 'table' => $table]);
      return false;
    }

    // Ha email verification szükséges és nincs verificálva
    if ($verified && is_null($user->email_verified_at)) {
      Log::info('Sikertelen bejelentkezés - nem verificált email', ['email' => $email, 'table' => $table]);
      return false;
    }

    
    // Jelszó ellenőrzés
    if (password_verify($password, $user->password)) {
      Log::info('Sikeres bejelentkezés', ['email' => $email, 'table' => $table]);
      $this->login($user, $table); // Teljes user objektumot adjuk át
      return $user;
    }

    Log::warning('Sikertelen bejelentkezés - hibás jelszó', ['email' => $email, 'table' => $table]);
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

    Log::info('Felhasználó bejelentkezve', [
      'table' => $table,
      'entity' => $entity,
      'user_id' => $user->id ?? null,
      'email' => $user->email ?? null,
    ]);

    session_regenerate_id(true);
  }

  public static function logout()
  {
    Log::info('Kijelentkezés', [
      'entities' => array_keys($_SESSION ?? []),
    ]);

    $_SESSION = [];
    session_destroy();

    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
}
