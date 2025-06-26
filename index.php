<?php

use Jenssegers\Date\Date;

error_reporting(E_ALL);

try {
  require_once __DIR__ . '/vendor/autoload.php';

  ini_set('error_log', LOGS_PATH . '/php_errors.log');

  require_once CONFIGURATIONS_PATH . '/environment.php';
  require_once CONFIGURATIONS_PATH . '/container.php';
  require_once CONFIGURATIONS_PATH . '/flight.php';
  require_once ROUTES_PATH . '/web.php';
  require_once ROUTES_PATH . '/api.php';

  date_default_timezone_set($_ENV['TIMEZONE']);
  Date::setLocale($_ENV['LOCALE']);

  Flight::start();
} catch (Throwable $error) {
  error_log($error->__toString());

  $trace = $error->getTrace();

  $filteredTrace = array_filter(
    $trace,
    static fn(array $call): bool => array_key_exists('file', $call) && !str_contains($call['file'] ?? '', 'vendor')
  );

  $mappedTrace = array_map(
    static fn(array $call): string => @"{$call['file']}:{$call['line']}",
    $filteredTrace
  );

  echo '<pre>', $error->getMessage(), PHP_EOL;
  print_r($mappedTrace);
  exit('</pre>');
}
