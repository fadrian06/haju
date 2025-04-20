<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use App\Enums\DBDriver;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/constants.php';
require_once CONFIGURATIONS_PATH . '/environment.php';
require_once CONFIGURATIONS_PATH . '/container.php';
require_once CONFIGURATIONS_PATH . '/database.php';

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$initDbFilePath = DATABASE_PATH . "/init.{$_ENV['DB_CONNECTION']->value}.sql";
$pdo = Container::getInstance()->get(PDO::class);
$queries = explode(';', $_ENV['DB_CONNECTION']->getInitDatabaseScript());

foreach ($queries as $query) {
  try {
    $pdo->exec($query);
  } catch (PDOException) {
    echo "\n❌ Failed to execute SQL query: {$query}\n";

    exit(1);
  }
}

echo "\n✅ Database initialized\n";
