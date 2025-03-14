<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\Department;
use App\Repositories\Domain\DepartmentRepository;
use Throwable;

final class DepartmentWebController extends Controller {
  private readonly DepartmentRepository $repository;

  function __construct() {
    parent::__construct();

    $this->repository = App::departmentRepository();
  }

  function showDepartments(): void {
    $departments = $this->repository->getAll();
    $departmentsNumber = count($departments);

    App::renderPage(
      'departments',
      "Departamentos ($departmentsNumber)",
      compact('departments'),
      'main'
    );
  }

  function handleToggleStatus(int $id): void {
    $selectedDepartment = App::view()->get('department');
    $department = $this->repository->getById($id);
    $department->toggleStatus();

    $redirectUrl = $department->isInactive() && $department->isEqualTo($selectedDepartment)
      ? '/departamento/seleccionar'
      : '/departamentos';

    $this->repository->save($department);
    self::setMessage("Departamento de {$department->name} {$department->getActiveStatusText()} exitósamente");
    App::redirect($redirectUrl);
  }

  function handleDepartmentEdition(int $id): void {
    try {
      $departament = $this->repository->getById($id);
      $departament->setName($this->data['name']);

      $this->repository->save($departament);
      self::setMessage('Departamento actualizado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/departamentos');
  }
}
