<?php

namespace App\Controllers\Web;

use App;
use App\Models\User;

class HomeWebController {
  static function index(): void {
    $loggedUser = App::view()->get('user');
    $users = App::userRepository()->getAll($loggedUser);

    assert($loggedUser instanceof User);

    $filteredUsers = array_filter($users, function (User $user) use ($loggedUser): bool {
      return $user->appointment->getLevel() <= $loggedUser->appointment->getLevel();
    });

    $usersNumber = count($filteredUsers);
    $departmentsNumber = count(App::departmentRepository()->getAll());

    App::renderPage('home', 'Inicio', compact('usersNumber', 'departmentsNumber'), 'main');
  }
}
