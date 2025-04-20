<?php

declare(strict_types=1);

namespace HAJU\Middlewares;

use HAJU\Models\Department;
use Flight;
use Leaf\Http\Session;

final readonly class EnsureDepartmentIsActive
{
  public function before(): ?true
  {
    $department = Flight::view()->get('department');

    if (!$department instanceof Department || !$department->isInactive()) {
      return true;
    }

    Session::set('error', "El departamento de {$department->name} ha sido desactivado");
    Flight::redirect('/salir');

    return null;
  }
}
