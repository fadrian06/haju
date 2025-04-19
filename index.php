<?php

declare(strict_types=1);

const ROOT_PATH = __DIR__;
const APP_PATH = __DIR__ . '/app';
const DATABASE_PATH = __DIR__ . '/database';
const VIEWS_PATH = __DIR__ . '/resources/views';
const LOGS_PATH = __DIR__ . '/storage/logs';

require_once ROOT_PATH . '/vendor/autoload.php';

error_reporting(E_ALL);
