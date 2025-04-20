<?php

declare(strict_types=1);

use flight\Container;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once CONFIGURATIONS_PATH . '/environment.php';
require_once CONFIGURATIONS_PATH . '/container.php';

$pdo = Container::getInstance()->get(PDO::class);
$initDbFilePath = DATABASE_PATH . "/init.{$_ENV['DB_CONNECTION']->value}.sql";

foreach (explode(';', file_get_contents($initDbFilePath)) as $query) {
  $pdo->exec($query);
}

echo "DB installed correctly ✔";
