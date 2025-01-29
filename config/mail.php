<?php

return [
  'usage' => 'personal',
  'driver' => $_ENV['MAIL_MAILER'] ?? 'smtp',
  'scheme' => $_ENV['MAIL_SCHEME'] ?? null,
  'host' => $_ENV['MAIL_HOST'] ?? '127.0.0.1',
  'port' => $_ENV['MAIL_PORT'] ?? 2525,
  'username' => $_ENV['MAIL_USERNAME'] ?? null,
  'password' => $_ENV['MAIL_PASSWORD'] ?? null,
  'from' => [
    'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'hello@example.com',
    'name' => $_ENV['MAIL_FROM_NAME'] ?? ($_ENV['APP_NAME'] ?? 'PMVC')
  ],
];
