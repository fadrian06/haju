<?php

declare(strict_types=1);

use App\Enums\DBDriver;
use Symfony\Component\Dotenv\Dotenv;

(new Dotenv)->load(
  __DIR__ . '/../../.env.dist',
  __DIR__ . '/../../.env'
);

$_ENV['DB_CONNECTION'] = DBDriver::from($_ENV['DB_CONNECTION']);
