<?php

declare(strict_types=1);

use HAJU\Enums\DBDriver;
use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Dotenv\Dotenv;

const ROOT_DIR = __DIR__ . '/../../..';
const DATABASE_PATH = ROOT_DIR . '/database';

require 'vendor/autoload.php';

$dotenv = new Dotenv;
$dotenv->bootEnv(ROOT_DIR . '/.env');

$_ENV['DB_CONNECTION'] = isset($_ENV['DB_CONNECTION']) && boolval($_ENV['DB_CONNECTION'])
  ? DBDriver::from(strval($_ENV['DB_CONNECTION']))
  : DBDriver::SQLITE;

require ROOT_DIR . '/config/database.php';

Manager::schema()->dropAllTables();

foreach (glob(DATABASE_PATH . '/migrations/*.php') as $migrationFilePath) {
  $migration = require $migrationFilePath;
  $migration();
}
