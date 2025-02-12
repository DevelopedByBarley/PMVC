<?php

return [
  'csrf' => [
    'protect' => 1, // csrf protect 1 = on , 0 = off
    'safe_origins' => ['http://localhost:9090','http://localhost:8080'],
    'secret' => $_ENV['CSRF_SECRET'] ?? 'secret-key-default',
    'lifetime' => 600,
  ],
  'token' => [
    'protect' => 1,
    'secret' => $_ENV['TOKEN_SECRET'] ?? 'secret-key-default',
    'lifetime' => 60 * 24,
  ],
  'auth' => []
];
