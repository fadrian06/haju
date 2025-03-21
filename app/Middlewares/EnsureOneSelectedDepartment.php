<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;

final readonly class EnsureOneSelectedDepartment {
  public static function before(): void {
    $departmentId = App::session()->get('departmentId');
    $departments = [];

    foreach (App::view()->get('user')->getDepartment() as $department) {
      $departments[] = $department;
    }

    if ($departmentId) {
      $department = App::departmentRepository()->getById($departmentId);
    }

    App::view()->set('canChangeDepartment', count($departments) !== 1);
    App::view()->set('department', $department);
  }
}
