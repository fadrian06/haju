<?php

namespace App\Controllers\Web;

use App;
use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;

final class HomeWebController extends Controller {
  private readonly UserRepository $userRepository;
  private readonly DepartmentRepository $departmentRepository;

  function __construct() {
    parent::__construct();

    $this->userRepository = App::userRepository();
    $this->departmentRepository = App::departmentRepository();
  }

  function showIndex(): void {
    $users = $this->userRepository->getAll($this->loggedUser);

    $filteredUsers = array_filter($users, function (User $user): bool {
      return $user->appointment->isLowerOrEqualThan($this->loggedUser->appointment);
    });

    $usersNumber = count($filteredUsers);
    $departmentsNumber = count($this->departmentRepository->getAll());

    App::renderPage(
      'home',
      'Inicio',
      compact('usersNumber', 'departmentsNumber'),
      'main'
    );
  }
}
