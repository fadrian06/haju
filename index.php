<?php

declare(strict_types=1);

use Jenssegers\Date\Date;

const ROOT_PATH = __DIR__;
const APP_PATH = ROOT_PATH . '/app';
const CONFIGURATIONS_PATH = APP_PATH . '/configurations';
const LOGS_PATH = APP_PATH . '/logs';
const ROUTES_PATH = APP_PATH . '/routes';
const DATABASE_PATH = ROOT_PATH . '/database';
const VIEWS_PATH = ROOT_PATH . '/views';

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

  require_once __DIR__ . '/app/configurations/environment.php';
  require_once __DIR__ . '/app/configurations/container.php';
  require_once __DIR__ . '/app/configurations/flight.php';
  require_once __DIR__ . '/app/routes/web.php';
  require_once __DIR__ . '/app/routes/api.php';

  date_default_timezone_set($_ENV['TIMEZONE']);
  Date::setLocale($_ENV['LOCALE']);

  Flight::start();
} catch (Throwable $error) {
  exit("<pre>{$error}</pre>");
}
