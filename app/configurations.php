<?php

use App\Repositories\Infraestructure\Files\FilesSettingsRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseCategoryRepository;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseRepository;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDODoctorRepository;
use App\Repositories\Infraestructure\PDO\PDOPatientRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Leaf\Http\Session;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

///////////////////////////
// ENVIRONMENT VARIABLES //
///////////////////////////
$_ENV += require __DIR__ . '/../.env.php';

////////////////////
// ERR0R HANDLING //
////////////////////
if (!$_ENV['DEBUG']) {
  error_reporting(0);
  ini_set('display_errors', 'Off');
  ini_set('html_errors', 'Off');
}

ini_set('error_log', __DIR__ . '/logs/errors.log');
$whoops = new Run;
$whoops->pushHandler($_ENV['DEBUG'] ? new PrettyPageHandler : new PlainTextHandler);
$whoops->register();
App::set('flight.handle_errors', false);

//////////////
// TIMEZONE //
//////////////
date_default_timezone_set($_ENV['TIMEZONE']);

//////////////////////
// GLOBAL CONSTANTS //
//////////////////////
App::set('root', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
App::set('fullRoot', App::request()->scheme . '://' . App::request()->host . App::get('root'));
App::view()->set('root', App::get('root'));
App::view()->set('user', null);

//////////////////
// DEPENDENCIES //
//////////////////
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
  FilesSettingsRepository::class,
  [App::db()]
);

App::register(
  'consultationCauseCategoryRepository',
  PDOConsultationCauseCategoryRepository::class,
  [App::db(), App::get('fullRoot')]
);

App::register(
  'consultationCauseRepository',
  PDOConsultationCauseRepository::class,
  [App::db(), App::get('fullRoot'), App::consultationCauseCategoryRepository()]
);

App::register('doctorRepository', PDODoctorRepository::class, [
  App::db(),
  App::get('fullRoot'),
  App::userRepository()
]);

App::register('patientRepository', PDOPatientRepository::class, [
  App::db(),
  App::get('fullRoot'),
  App::userRepository(),
  App::consultationCauseRepository(),
  App::departmentRepository(),
  App::doctorRepository()
]);

App::register('session', Session::class);
