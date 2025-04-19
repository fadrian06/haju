<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;

$manager = new Manager;

$manager->addConnection([
  'driver' => $_ENV['DB_CONNECTION']->value,
  'host' => $_ENV['DB_HOST'],
  'database' => $_ENV['DB_CONNECTION']->getDatabaseName(),
  'username' => $_ENV['DB_USERNAME'],
  'password' => $_ENV['DB_PASSWORD'],
]);

$manager->setAsGlobal();
$manager->bootEloquent();
