<?php



use HAJU\Models\User;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Domain\UserRepository;
use flight\Container;
use flight\util\Collection;
use Leaf\Http\Session;

Flight::set('flight.views.path', VIEWS_PATH);
Flight::set('flight.handle_errors', false);
Flight::view()->path = VIEWS_PATH;
Flight::view()->preserveVars = false;
Flight::registerContainerHandler(Container::getInstance());

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

Flight::view()->set('lastData', new Collection(Session::get('lastData', [])));
Flight::view()->set('error', Session::retrieve('error'));
Flight::view()->set('message', Session::retrieve('message'));
Flight::view()->set('scriptPath', Session::get('scriptPath'));

Flight::view()->set(
  'mustChangePassword',
  Session::get('mustChangePassword', false)
);
