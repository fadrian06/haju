<?php

namespace HAJU\Middlewares;

use HAJU\Repositories\Domain\DepartmentRepository;
use Flight;
use Leaf\Http\Session;

final readonly class EnsureSelectedDepartmentIsNotStatistics
{
  public function __construct(private DepartmentRepository $departmentRepository)
  {
    // ...
  }

  public function before(): ?true
  {
    $departmentId = Session::get('departmentId');
    $selectedDepartment = $this->departmentRepository->getById($departmentId);

    if (!$selectedDepartment->isStatistics()) {
      return true;
    }

    Session::set('error', 'No puedes registrar consultas desde el departamento de Estadística');
    Flight::redirect('/');

    return null;
  }
}
