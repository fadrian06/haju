<?php

declare(strict_types=1);

use HAJU\Enums\DBDriver;
use Symfony\Component\Dotenv\Dotenv;

const ROOT_PATH = __DIR__;
const APP_PATH = ROOT_PATH . '/app';
const CONFIG_PATH = ROOT_PATH . '/config';
const DATABASE_PATH = ROOT_PATH . '/database';
const VIEWS_PATH = ROOT_PATH . '/resources/views';
const LOGS_PATH = ROOT_PATH . '/storage/logs';

require_once ROOT_PATH . '/vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = new Dotenv;
$dotenv->bootEnv(ROOT_PATH . '/.env');

$_ENV['DB_CONNECTION'] = isset($_ENV['DB_CONNECTION']) && boolval($_ENV['DB_CONNECTION'])
  ? DBDriver::from(strval($_ENV['DB_CONNECTION']))
  : DBDriver::SQLITE;

$_ENV['DB_HOST'] = isset($_ENV['DB_HOST']) && boolval($_ENV['DB_HOST'])
  ? strval($_ENV['DB_HOST'])
  : null;

$_ENV['DB_USERNAME'] = isset($_ENV['DB_USERNAME']) && boolval($_ENV['DB_USERNAME'])
  ? strval($_ENV['DB_USERNAME'])
  : null;

$_ENV['DB_PASSWORD'] = isset($_ENV['DB_PASSWORD']) && boolval($_ENV['DB_PASSWORD'])
  ? strval($_ENV['DB_PASSWORD'])
  : null;

$_ENV['SECRET_KEY'] = isset($_ENV['SECRET_KEY']) ? strval($_ENV['SECRET_KEY']) : null;

date_default_timezone_set($_ENV['TIMEZONE_ID']);

require_once CONFIG_PATH . '/database.php';
