<?php

declare(strict_types=1);

try {
  error_reporting(E_ALL);

  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/app/constants.php';
  require_once __DIR__ . '/app/configurations/environment.php';
  require_once __DIR__ . '/app/configurations/container.php';
  require_once __DIR__ . '/app/configurations/flight.php';
  require_once __DIR__ . '/app/routes/web.php';
  require_once __DIR__ . '/app/routes/api.php';

  date_default_timezone_set($_ENV['TIMEZONE']);

  Flight::start();
} catch (Throwable $error) {
  exit("<pre>{$error}</pre>");
}
