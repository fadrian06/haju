<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use App\Enums\DBDriver;
use Illuminate\Database\Capsule\Manager;

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$container = Container::getInstance();
$manager = new Manager($container);

$manager->addConnection([
  'driver' => $_ENV['DB_CONNECTION']->value,
  'host' => $_ENV['DB_HOST'],
  'database' => $_ENV['DB_CONNECTION']->getDatabaseName(),
  'username' => $_ENV['DB_USERNAME'],
  'password' => $_ENV['DB_PASSWORD'],
]);

$manager->setAsGlobal();
$manager->bootEloquent();

$container->singleton(
  PDO::class,
  static fn(): PDO => $manager->connection()->getPdo()
);

db()->connection($container->get(PDO::class));
$reflectionProperty = new ReflectionProperty(auth(), 'db');
$reflectionProperty->setValue(auth(), db());
