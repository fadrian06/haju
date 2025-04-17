<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\OldModels\Department;
use flight\template\View;

final readonly class EnsureOneSelectedDepartment {
  public function __construct(private View $view) {
  }

  public function before(): void {
    $department = $this->view->get('department');
    assert(is_null($department) || $department instanceof Department);

    $departments = [];

    foreach ($this->view->get('user')->getDepartment() as $userDepartment) {
      $departments[] = $userDepartment;
    }

    $this->view->set('canChangeDepartment', count($departments) !== 1);
    $this->view->set('department', $department);
  }
}
