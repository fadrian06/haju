<?php

use App\Repositories\Infraestructure\PDO\Connection;

$localVars = require_once __DIR__ . '/../../.env.php';
$_ENV += is_array($localVars) ? $localVars : [];

$connection = new Connection(
  $_ENV['DB_CONNECTION'],
  $_ENV['DB_DATABASE'],
  $_ENV['DB_HOST'],
  $_ENV['DB_PORT'],
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD']
);

$initDbFilePath = dirname(__DIR__, 2) . "/database/init.{$_ENV['DB_CONNECTION']->value}.sql";

foreach (explode(';', file_get_contents($initDbFilePath)) as $query) {
  $connection->instance()->query($query);
}

echo "DB installed correctly ✔";
