<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use App\Models\Department;
use Leaf\Http\Session;

final readonly class EnsureDepartmentIsActive {
  public function before(): true {
    $department = App::view()->get('department');

    if ($department instanceof Department && $department->isInactive()) {
      Session::set('error', "El departamento de {$department->name} ha sido desactivado");
      App::redirect('/salir');

      exit;
    }

    return true;
  }
}
