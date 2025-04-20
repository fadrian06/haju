<?php

declare(strict_types=1);

use Jenssegers\Date\Date;

error_reporting(E_ALL);

try {
  require_once __DIR__ . '/vendor/autoload.php';

  /**
   * - `''`: with _composer serve_ -> _localhost:61001_
   * - `'/haju'`: with xampp -> _localhost/haju_
   * - `'/faslatam.42web.io/htdocs/haju'`: hosting uri
   */
  define('BASE_URI', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

  $_SERVER['HTTP_HOST'] ??= 'localhost:61001';

  /** `http://localhost:61001` */
  define(
    'BASE_URL',
    Flight::request()->scheme . '://' . $_SERVER['HTTP_HOST'] . BASE_URI
  );

  require_once CONFIGURATIONS_PATH . '/environment.php';
  require_once CONFIGURATIONS_PATH . '/container.php';
  require_once CONFIGURATIONS_PATH . '/flight.php';
  require_once ROUTES_PATH . '/web.php';
  require_once ROUTES_PATH . '/api.php';

  date_default_timezone_set($_ENV['TIMEZONE']);
  Date::setLocale($_ENV['LOCALE']);

  Flight::start();
} catch (Throwable $error) {
  exit("<pre>{$error}</pre>");
}
