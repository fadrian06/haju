<?php



namespace HAJU\Middlewares;

use Flight;
use HAJU\Repositories\Domain\DepartmentRepository;
use Leaf\Http\Session;

final readonly class EnsureOneSelectedDepartment
{
  public function __construct(private DepartmentRepository $departmentRepository)
  {
    // ...
  }

  public function before(): void
  {
    $departmentId = Session::get('departmentId');
    $departments = [];

    foreach (Flight::view()->get('user')->getDepartment() as $department) {
      $departments[] = $department;
    }

    if ($departmentId !== null) {
      $department = $this->departmentRepository->getById((int) $departmentId);

      Flight::view()->set('department', $department);
    }

    Flight::view()->set('canChangeDepartment', count($departments) !== 1);
  }
}
