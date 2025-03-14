<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\UserRepository;

final class HomeWebController extends Controller {
  private readonly UserRepository $userRepository;
  private readonly DepartmentRepository $departmentRepository;
  private readonly PatientRepository $patientRepository;
  private readonly DoctorRepository $doctorRepository;

  function __construct() {
    parent::__construct();

    $this->userRepository = App::userRepository();
    $this->departmentRepository = App::departmentRepository();
    $this->patientRepository = App::patientRepository();
    $this->doctorRepository = App::doctorRepository();
  }

  function showIndex(): void {
    $users = $this->userRepository->getAll($this->loggedUser);

    $filteredUsers = array_filter($users, fn(User $user): bool => $user->appointment->isLowerOrEqualThan($this->loggedUser->appointment));

    $usersNumber = count($filteredUsers);
    $departmentsNumber = $this->departmentRepository->getRowsCount();
    $patientsNumber = $this->patientRepository->getRowsCount();
    $consultationsNumber = $this->patientRepository->getConsultationsCount();
    $doctorsNumber = $this->doctorRepository->getRowsCount();

    App::renderPage('home', 'Inicio', compact(
      'usersNumber',
      'departmentsNumber',
      'patientsNumber',
      'consultationsNumber',
      'doctorsNumber'
    ), 'main');
  }
}
