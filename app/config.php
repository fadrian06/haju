<?php

use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDOPatientRepository;
use App\Repositories\Infraestructure\PDO\PDOSettingsRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Leaf\Http\Session;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$_ENV += require __DIR__ . '/../.env.php';

date_default_timezone_set($_ENV['TIMEZONE']);

$whoops = new Run();
$whoops->pushHandler($_ENV['DEBUG'] ? new PrettyPageHandler : new PlainTextHandler);
$whoops->register();

App::set('flight.handle_errors', false);
App::set('root', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
App::set('fullRoot', App::request()->scheme . '://' . App::request()->host . App::get('root'));
App::view()->set('root', App::get('root'));
App::view()->set('user', null);

App::register('db', Connection::class, [
  $_ENV['DB_CONNECTION'],
  $_ENV['DB_DATABASE'],
  $_ENV['DB_HOST'],
  $_ENV['DB_PORT'],
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD']
]);

App::register(
  'departmentRepository',
  PDODepartmentRepository::class,
  [App::db(), App::get('fullRoot')]
);

App::register(
  'userRepository',
  PDOUserRepository::class,
  [App::db(), App::get('fullRoot'), App::departmentRepository()]
);

App::register(
  'settingsRepository',
  PDOSettingsRepository::class,
  [App::db(), App::get('fullRoot')]
);

App::register(
  'patientRepository',
  PDOPatientRepository::class,
  [App::db(), App::get('fullRoot'), App::userRepository()]
);

App::register('session', Session::class);
