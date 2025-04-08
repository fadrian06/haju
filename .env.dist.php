<?php

declare(strict_types=1);

use App\Enums\DBDriver;

require_once __DIR__ . '/vendor/autoload.php';

return [
  'DB_CONNECTION' => DBDriver::MySQL,
  'DB_HOST' => 'localhost',
  'DB_PORT' => 3306,
  'DB_DATABASE' => '',
  'DB_USERNAME' => '',
  'DB_PASSWORD' => '',
  'TIMEZONE' => 'UTC',
];
