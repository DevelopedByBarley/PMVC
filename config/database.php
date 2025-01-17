<?php
  return [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? 3306,
    'name' => $_ENV['DB_USER_NAME'] ?? 'root',
    'password' => $_ENV['DB_PW'] ?? '', 
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'db_name' => $_ENV['DB_NAME'] ?? 'database_name'
  ];
