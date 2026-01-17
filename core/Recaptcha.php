<?php

namespace Core;

class Recaptcha
{
  /**
   * Verify reCAPTCHA v3 token with Google.
   *
   * @return array{ok:bool, score:float, action:string, hostname:string, errors:array, raw:array}
   */
  private function verifyRecaptchaV3(string $token, string $expectedAction, float $minScore = 0.5): bool
  {
    if ($token === '') {
      return false;
    }

    $payload = http_build_query([
      'secret'   => getEnv('RECAPTCHA_SECRET_KEY'),
      'response' => $token,
      'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
    ]);

    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
      return false;
    }

    $data = json_decode($response, true);

    return
      is_array($data) &&
      !empty($data['success']) &&
      ($data['action'] ?? '') === $expectedAction &&
      (float)($data['score'] ?? 0) >= $minScore;
  }
}
