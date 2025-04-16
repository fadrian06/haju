<?php

declare(strict_types=1);

use Illuminate\Container\Container;

require_once __DIR__ . '/../app/configurations.php';

$initDbFilePath = dirname(__DIR__)
  . "/database/init.{$_ENV['DB_CONNECTION']->value}.sql";

$pdo = Container::getInstance()->get(PDO::class);

foreach (explode(';', file_get_contents($initDbFilePath)) as $query) {
  try {
    $pdo->exec($query);
  } catch (PDOException) {
    echo "\n❌ Failed to execute SQL query: $query\n";

    exit(1);
  }
}

echo "\n✅ Database initialized\n";
