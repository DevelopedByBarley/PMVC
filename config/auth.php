  <?php

return [
  'csrf' => [
    'protect' => 1, // csrf protect 1 = on , 0 = off
    'safe_origins' => ['http://localhost:9090'],
    'secret' => $_ENV['CSRF_SECRET'] ?? 'secret-key-default',
    'lifetime' => 600,
  ],
  'auth' => []
];
