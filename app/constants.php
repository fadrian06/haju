<?php

declare(strict_types=1);

/**
 * - `''`: with _composer serve_ -> _localhost:61001_
 * - `'/haju'`: with xampp -> _localhost/haju_
 * - `'/faslatam.42web.io/htdocs/haju'`: hosting uri
 */
define('BASE_URI', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

$_SERVER['HTTP_HOST'] ??= 'localhost:61001';

/**
 * `http://localhost:61001`
 */
define(
  'BASE_URL',
  Flight::request()->scheme . '://' . $_SERVER['HTTP_HOST'] . BASE_URI
);

const VIEWS_PATH = __DIR__ . '/../views';
