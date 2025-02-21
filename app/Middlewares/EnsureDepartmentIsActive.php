<?php

namespace App\Middlewares;

use App;
use App\Models\Department;
use Leaf\Http\Session;

final readonly class EnsureDepartmentIsActive {
  function before() {
    $department = App::view()->get('department');

    if ($department instanceof Department && $department->isInactive()) {
      Session::set('error', "El departamento de {$department->name} ha sido desactivado");
      App::redirect('/salir');

      exit;
    }

    return true;
  }
}
