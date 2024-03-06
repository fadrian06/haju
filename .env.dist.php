<?php

use App\Repositories\Infraestructure\PDO\DBConnection;

require_once __DIR__ . '/vendor/autoload.php';

return [
  'DB_CONNECTION' => DBConnection::MySQL,
  'DB_HOST' => 'localhost',
  'DB_PORT' => 3306,
  'DB_DATABASE' => '',
  'DB_USERNAME' => '',
  'DB_PASSWORD' => '',
  'TIMEZONE' => 'UTC'
];
