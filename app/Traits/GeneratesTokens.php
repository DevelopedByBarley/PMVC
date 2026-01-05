<?php

namespace App\Traits;

trait GeneratesTokens
{
  protected function generateToken(): string
  {
    $random = random_bytes(32);
    $key = getenv('APP_KEY') ?: 'fallback';

    return hash_hmac('sha256', $random, $key, false);
  }

}
