<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Models\Department;
use App\Repositories\Domain\DepartmentRepository;
use Flight;
use flight\template\View;
use Throwable;

final readonly class DepartmentWebController extends Controller {
  public function __construct(
    private readonly DepartmentRepository $departmentRepository,
    private readonly View $view,
  ) {
    parent::__construct();
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
    $selectedDepartment = $this->view->get('department');
    assert($selectedDepartment instanceof Department);

    $department = $this->departmentRepository->getById($id);
    $department->toggleStatus();

    $redirectUrl = (
      $department->isInactive()
      && $department->isEqualTo($selectedDepartment)
    )
      ? '/departamento/seleccionar'
      : '/departamentos';

    $this->departmentRepository->save($department);

    self::setMessage("
      Departamento de {$department->name} {$department->getActiveStatusText()}
       exitósamente
    ");

    Flight::redirect($redirectUrl);
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

    Flight::redirect('/departamentos');
  }
}
