<?php

declare(strict_types=1);

use flight\Container;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../configurations/environment.php';
require_once __DIR__ . '/../configurations/container.php';

$pdo = Container::getInstance()->get(PDO::class);

$initDbFilePath = (
  dirname(__DIR__, 2)
  . "/database/init.{$_ENV['DB_CONNECTION']->value}.sql"
);

foreach (explode(';', file_get_contents($initDbFilePath)) as $query) {
  $pdo->exec($query);
}

echo "DB installed correctly ✔";
