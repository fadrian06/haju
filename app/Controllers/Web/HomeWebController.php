<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\UserRepository;

final readonly class HomeWebController extends Controller {
  public function __construct(
    private UserRepository $userRepository,
    private DepartmentRepository $departmentRepository,
    private PatientRepository $patientRepository,
    private DoctorRepository $doctorRepository,
  ) {
    parent::__construct();
  }

  public function showIndex(): void {
    $users = $this->userRepository->getAll($this->loggedUser);

    $filteredUsers = array_filter(
      $users,
      fn(User $user): bool => $user
        ->appointment
        ->isLowerOrEqualThan($this->loggedUser->appointment)
    );

    $usersNumber = count($filteredUsers);
    $departmentsNumber = $this->departmentRepository->getRowsCount();
    $patientsNumber = $this->patientRepository->getRowsCount();
    $consultationsNumber = $this->patientRepository->getConsultationsCount();
    $doctorsNumber = $this->doctorRepository->getRowsCount();

    renderPage('home', 'Inicio', compact(
      'usersNumber',
      'departmentsNumber',
      'patientsNumber',
      'consultationsNumber',
      'doctorsNumber'
    ), 'main');
  }
}
