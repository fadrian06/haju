<?php

declare(strict_types=1);

use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;
use Leaf\Http\Session;

Flight::set('flight.views.path', dirname(__DIR__, 2) . '/views');
Flight::set('flight.handle_errors', false);
Flight::view()->path = dirname(__DIR__, 2) . '/views';
Flight::view()->preserveVars = false;

Flight::view()->set(
  'user',
  $container
    ->get(UserRepository::class)
    ->getById(intval($container->get(Session::class)->get('userId')))
);

Flight::view()->set(
  'department',
  $container
    ->get(DepartmentRepository::class)
    ->getById(intval($container->get(Session::class)->get('departmentId'))),
);

Flight::view()->set(
  'canChangeDepartment',
  Flight::view()->get('user')?->hasDepartments() ?: false
);
