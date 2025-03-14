<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/configurations.php';
require_once __DIR__ . '/app/routes/web.php';
require_once __DIR__ . '/app/routes/api.php';

App::start();
