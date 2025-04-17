<?php

declare(strict_types=1);

use App\Enums\DBDriver;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv;
$dotenv->load(__DIR__ . '/../../.env.dist', __DIR__ . '/../../.env');

$_ENV['DB_CONNECTION'] = DBDriver::from($_ENV['DB_CONNECTION']);
$_ENV['DB_PORT'] = intval($_ENV['DB_PORT']);
$_ENV['DB_USERNAME'] = $_ENV['DB_USERNAME'] ?: null;
$_ENV['DB_PASSWORD'] = $_ENV['DB_PASSWORD'] ?: null;
