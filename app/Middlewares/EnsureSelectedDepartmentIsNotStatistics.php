<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Repositories\Domain\DepartmentRepository;
use Flight;
use Leaf\Http\Session;

final readonly class EnsureSelectedDepartmentIsNotStatistics {
  public function __construct(
    private DepartmentRepository $departmentRepository,
    private Session $session,
  ) {
  }

  public function before(): ?true {
    $departmentId = $this->session->get('departmentId');
    $selectedDepartment = $this->departmentRepository->getById($departmentId);

    if (!$selectedDepartment->isStatistics()) {
      return true;
    }

    $this->session->set('error', 'No puedes registrar consultas desde el departamento de Estadística');
    Flight::redirect('/');

    return null;
  }
}
