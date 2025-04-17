<?php

declare(strict_types=1);

use App\OldModels\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;
use Illuminate\Container\Container;
use Leaf\Http\Session;

Flight::set('flight.views.path', VIEWS_PATH);
Flight::view()->path = VIEWS_PATH;
Flight::view()->preserveVars = false;

$container = Container::getInstance();

Flight::view()->set(
  'user',
  $container
    ->get(UserRepository::class)
    ->getById(intval(Session::get('userId'))),
);

Flight::view()->set(
  'department',
  $container
    ->get(DepartmentRepository::class)
    ->getById(intval(Session::get('departmentId'))),
);

Flight::view()->set(
  'canChangeDepartment',
  (static function (): bool {
    $user = Flight::view()->get('user');

    if ($user instanceof User) {
      return $user->hasDepartments();
    }

    return false;
  })(),
);
