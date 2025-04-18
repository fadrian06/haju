<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\OldModels\Department;
use Flight;
use flight\template\View;
use Leaf\Http\Session;

final readonly class EnsureDepartmentIsActive {
  public function __construct(private View $view, private Session $session) {
  }

  public function before(): ?true {
    $department = $this->view->get('department');

    if (!$department instanceof Department || !$department->isInactive()) {
      return true;
    }

    $this->session->set(
      'error',
      "El departamento de {$department->name} ha sido desactivado"
    );

    Flight::redirect('/salir');

    return null;
  }
}
