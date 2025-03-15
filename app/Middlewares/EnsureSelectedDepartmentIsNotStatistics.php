<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use Leaf\Http\Session;

final readonly class EnsureSelectedDepartmentIsNotStatistics {
  public static function before(): true {
    $selectedDepartment = App::departmentRepository()->getById(Session::get('departmentId'));

    if (!$selectedDepartment->isStatistics()) {
      return true;
    }

    Session::set('error', 'No puedes registrar consultas desde el departamento de Estadística');
    App::redirect('/');
  }
}
