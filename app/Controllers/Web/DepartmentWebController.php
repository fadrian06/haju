<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Repositories\Domain\DepartmentRepository;
use Throwable;

final class DepartmentWebController extends Controller {
  private readonly DepartmentRepository $departmentRepository;

  public function __construct() {
    parent::__construct();

    $this->departmentRepository = App::departmentRepository();
  }

  public function showDepartments(): void {
    $departments = $this->departmentRepository->getAll();
    $departmentsNumber = count($departments);

    renderPage(
      'departments',
      "Departamentos ({$departmentsNumber})",
      compact('departments'),
      'main'
    );
  }

  public function handleToggleStatus(int $id): void {
    $selectedDepartment = App::view()->get('department');
    $department = $this->departmentRepository->getById($id);
    $department->toggleStatus();

    $redirectUrl = $department->isInactive() && $department->isEqualTo($selectedDepartment)
      ? '/departamento/seleccionar'
      : '/departamentos';

    $this->departmentRepository->save($department);
    self::setMessage("Departamento de {$department->name} {$department->getActiveStatusText()} exitósamente");
    App::redirect($redirectUrl);
  }

  public function handleDepartmentEdition(int $id): void {
    try {
      $departament = $this->departmentRepository->getById($id);
      $departament->setName($this->data['name']);

      $this->departmentRepository->save($departament);
      self::setMessage('Departamento actualizado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/departamentos');
  }
}
