<?php

use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Leaf\Http\Session;

$_ENV += require_once __DIR__ . '/../.env.php';

App::set('root', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

App::register('db', Connection::class, [
  $_ENV['DB_CONNECTION'],
  $_ENV['DB_DATABASE'],
  $_ENV['DB_HOST'],
  $_ENV['DB_PORT'],
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD']
]);

App::register(
  'userRepository',
  PDOUserRepository::class,
  [],
  function (PDOUserRepository $repository): void {
    $repository->setConnection(App::db());
  }
);

App::register(
  'departmentRepository',
  PDODepartmentRepository::class,
  [],
  function (PDODepartmentRepository $repository): void {
    $repository->setConnection(App::db());
  }
);

App::register('session', Session::class);
