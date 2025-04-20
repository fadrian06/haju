<?php

declare(strict_types=1);

namespace HAJU\Middlewares;

use HAJU\Repositories\Domain\DepartmentRepository;
use flight\template\View;
use Leaf\Http\Session;

final readonly class EnsureOneSelectedDepartment {
  public function __construct(
    private Session $session,
    private View $view,
    private DepartmentRepository $departmentRepository,
  ) {
  }

  public function before(): void {
    $departmentId = $this->session->get('departmentId');
    $departments = [];

    foreach ($this->view->get('user')->getDepartment() as $department) {
      $departments[] = $department;
    }

    if ($departmentId !== null) {
      $department = $this->departmentRepository->getById((int) $departmentId);
    }

    $this->view->set('canChangeDepartment', count($departments) !== 1);
    $this->view->set('department', $department);
  }
}
