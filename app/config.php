<?php

use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDOSettingsRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Leaf\Form;
use Leaf\Http\Session;

$_ENV += require_once __DIR__ . '/../.env.php';

date_default_timezone_set($_ENV['TIMEZONE']);
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

App::register('session', Session::class);
App::register('form', Form::class);

App::form()->addRule('textonly', '/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{2,}$/', 'El {Field} debe tener letras con inicial en mayúscula');
App::form()->addMessage('required', '{Field} es requerido');
App::form()->addMessage('date', '{Field} debe ser una fecha válida');
