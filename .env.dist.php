<?php

declare(strict_types=1);

use HAJU\Enums\DBDriver;

return [
  'DB_CONNECTION' => DBDriver::SQLite,
  'DB_HOST' => 'localhost',
  'DB_PORT' => 3306,
  'DB_DATABASE' => DATABASE_PATH . '/haju.db',
  'DB_USERNAME' => '',
  'DB_PASSWORD' => '',
  'TIMEZONE' => 'America/Caracas',
  'LOCALE' => 'es',
  'APP_NAME' => 'HAJU',
  'SECRET_KEY' => '1234',
];
