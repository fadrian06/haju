<?php

namespace App\Middlewares;

use App;
use Leaf\Http\Session;

final readonly class EnsureSelectedDepartmentIsNotStatistics {
  function before() {
    $selectedDepartment = App::departmentRepository()->getById(Session::get('departmentId'));

    if ($selectedDepartment->isStatistics()) {
      Session::set('error', 'No puedes registrar consultas desde el departamento de Estadística');
      App::redirect('/');
    } else {
      return true;
    }
  }
}
