<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

const ROOT_PATH = __DIR__;
const APP_PATH = __DIR__ . '/app';
const DATABASE_PATH = __DIR__ . '/database';
const VIEWS_PATH = __DIR__ . '/resources/views';
const LOGS_PATH = __DIR__ . '/storage/logs';

require_once ROOT_PATH . '/vendor/autoload.php';

error_reporting(E_ALL);

$dotenv = new Dotenv;
$dotenv->bootEnv(ROOT_PATH . '/.env');

$_ENV['DB_USERNAME'] = isset($_ENV['DB_USERNAME']) && boolval($_ENV['DB_USERNAME'])
  ? strval($_ENV['DB_USERNAME'])
  : null;

$_ENV['DB_PASSWORD'] = isset($_ENV['DB_PASSWORD']) && boolval($_ENV['DB_PASSWORD'])
  ? strval($_ENV['DB_PASSWORD'])
  : null;

$_ENV['SECRET_KEY'] = isset($_ENV['SECRET_KEY']) ? strval($_ENV['SECRET_KEY']) : null;

date_default_timezone_set($_ENV['TIMEZONE_ID']);
