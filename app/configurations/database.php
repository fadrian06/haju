<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use App\Enums\DBDriver;
use Illuminate\Database\Capsule\Manager;

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$manager = new Manager(Container::getInstance());

$manager->addConnection([
  'driver' => $_ENV['DB_CONNECTION']->value,
  'host' => $_ENV['DB_HOST'],
  'database' => $_ENV['DB_CONNECTION']->getDatabaseName(),
  'username' => $_ENV['DB_USERNAME'],
  'password' => $_ENV['DB_PASSWORD'],
]);

$manager->setAsGlobal();
$manager->bootEloquent();

Container::getInstance()->singleton(
  PDO::class,
  static fn(): PDO => $manager->connection()->getPdo()
);

db()->connection(Container::getInstance()->get(PDO::class));

$reflectionProperty = new ReflectionProperty(auth(), 'db');
$reflectionProperty->setValue(auth(), db());
