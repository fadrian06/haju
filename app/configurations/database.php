<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use App\Enums\DBDriver;

require_once __DIR__ . '/environment.php';

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

Container::getInstance()->singleton(PDO::class, static fn(): PDO => new PDO(
  $_ENV['DB_CONNECTION']->getPdoDsn(),
  $_ENV['DB_USERNAME'] ?? null,
  $_ENV['DB_PASSWORD'] ?? null,
));
