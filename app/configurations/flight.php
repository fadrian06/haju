<?php

declare(strict_types=1);

use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;
use Illuminate\Container\Container;
use Leaf\Http\Session;

Flight::set('flight.views.path', VIEWS_PATH);
Flight::set('flight.handle_errors', false);
Flight::view()->path = VIEWS_PATH;
Flight::view()->preserveVars = false;

Flight::view()->set(
  'user',
  Container::getInstance()
    ->get(UserRepository::class)
    ->getById(intval(Session::get('userId'))),
);

Flight::view()->set(
  'department',
  Container::getInstance()
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
