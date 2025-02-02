<?php

namespace Core;

use Core\Database;

class EmailVerify
{
  private $db;
  private $based_token;
  private $expires_at;
  private $mailer;
  private $toast;

  public function __construct()
  {
    $this->db = Database::getInstance();
    $this->mailer = new Mailer();
    $this->toast = new Toast();
  }

  // Send metódus, amely statikus módon eléri az adatbázist
  public  function store($user_id, $token, $expires_at)
  {
    $this->expires_at = $expires_at;
    if ((int)$this->failIfFound($token)) {
      $this->toast->danger('Email küldése sikertelen, kérjük próbálja meg újra.')->redirect('/register');
    }
    $this->db->query("INSERT INTO `verification_tokens`
      (`id`, `user_ref_id`, `token`, `expires_at`, `created_at`)
        VALUES
      (NULL, :user_ref_id, :token, :expires_at, current_timestamp());", [':user_ref_id' => $user_id, ':token' => $token, ':expires_at' => date('Y-m-d H:i:s', $expires_at)]);

    return $this;
  }

  public function send($mail_address, $based_token)
  {
    $this->mailer->prepare($mail_address, 'Regisztráció hitelesítése')->template('email-verify', ['token' => $based_token])->send();
  }

  private function failIfFound($token)
  {
    return $this->db->query("SELECT COUNT(*) AS count FROM verification_tokens WHERE token = :token", [':token' => $token])->find()->count;
  }

  public function token($user_id)
  {
    return $this->db->query("SELECT token, expires_at FROM verification_tokens WHERE user_ref_id = :user_ref_id", [':user_ref_id' => $user_id])->findOrFail();
  }

  public function deleteVerificationToken($user_id)
  {
    return $this->db->query("DELETE  FROM verification_tokens WHERE user_ref_id = :user_ref_id", [':user_ref_id' => $user_id]);
  }
}
