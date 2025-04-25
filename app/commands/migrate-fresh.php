<?php

declare(strict_types=1);

use flight\Container;
use HAJU\Enums\DBDriver;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once CONFIGURATIONS_PATH . '/environment.php';
require_once CONFIGURATIONS_PATH . '/container.php';

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$pdo = Container::getInstance()->get(PDO::class);

foreach (explode(';', $_ENV['DB_CONNECTION']->getInitDbFile()) as $query) {
  $pdo->exec($query);
}

exit("DB installed correctly ✔");
