<?php

namespace App\Controllers\Web;

use App;
use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;
use Error;
use PharIo\Manifest\Url;
use Throwable;

class DepartmentWebController extends Controller {
  static function showDepartments(): void {
    $departments = App::departmentRepository()->getAll();
    $departmentsNumber = count($departments);

    App::renderPage(
      'departments',
      "Departamentos ($departmentsNumber)",
      compact('departments'),
      'main'
    );
  }

  static function handleRegister(): void {
    $data = App::request()->data;

    try {
      if (!App::request()->files['department_icon']['size']) {
        throw new Error('El icono del departamento es requerido');
      }

      $iconUrlPath = self::uploadFile('department_icon', 'departments');

      $department = new Department(
        $data['name'],
        $iconUrlPath,
        $data['belongs_to_external_consultation'] ?? false,
        $data['is_active'] ?? false
      );

      App::departmentRepository()->save($department);
      self::setMessage('Departamento registrado exitósamente');
    } catch (Throwable $error) {
      self::setError($error->getMessage());
    }

    App::redirect('/departamentos');
  }

  static function handleToggleStatus(string $id): void {
    $department = App::departmentRepository()->getById((int) $id);
    $department->toggleStatus();

    App::departmentRepository()->save($department);
    App::redirect('/departamentos');
  }

  static function handleDepartmentEdition(string $id): void {
    $departament = App::departmentRepository()->getById((int) $id);
    $departament->setName(App::request()->data['name']);

    try {
      App::departmentRepository()->save($departament);
      self::setMessage('Departamento actualizado exitósamente');
    } catch (DuplicatedNamesException) {
      self::setError("Departamento \"{$departament->getName()}\" ya existe");
    }

    App::redirect('/departamentos');
  }
}
