<?php

declare(strict_types=1);

use HAJU\Enums\DBDriver;

require_once __DIR__ . '/vendor/autoload.php';

return [
  'DB_CONNECTION' => DBDriver::SQLite,
  'DB_HOST' => 'localhost',
  'DB_PORT' => 3306,
  'DB_DATABASE' => __DIR__ . '/database/haju.db',
  'DB_USERNAME' => '',
  'DB_PASSWORD' => '',
  'TIMEZONE' => 'America/Caracas',
  'LOCALE' => 'es',
  'APP_NAME' => 'HAJU',
];
