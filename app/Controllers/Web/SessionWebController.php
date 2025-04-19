<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use Flight;
use Leaf\Http\Session;

final readonly class SessionWebController extends Controller {
  public function showDepartments(): void {
    $departments = [];

    foreach ($this->loggedUser->getDepartment() as $department) {
      $departments[] = $department;
    }

    if (count($departments) === 1) {
      Flight::redirect("/departamento/seleccionar/{$departments[0]->id}");

      return;
    }

    renderPage('select-department', 'Ingresar (2/2)');
  }

  public function saveChoice(int $id): void {
    Session::set('departmentId', $id);
    Flight::redirect('/');
  }
}
