<?php

namespace App\Middlewares;

use App;

class EnsureOneSelectedDepartment {
  static function before(): void {
    $departmentId = App::session()->get('departmentId');

    if ($departmentId) {
      $department = App::departmentRepository()->getById((int) $departmentId);
    }

    App::view()->set('canChangeDepartment', (bool) App::session()->get('canChangeDepartment'));
    App::view()->set('department', $department);
  }
}
