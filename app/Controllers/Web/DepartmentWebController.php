<?php

namespace App\Controllers\Web;

use App;
use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;

class DepartmentWebController {
  static function showDepartments(): void {
    $departments = App::departmentRepository()->getAll();

    App::renderPage('departments', 'Departamentos', compact('departments'), 'main');
  }

  static function handleRegister(): void {
    $data = App::request()->data;
    $department = new Department($data['name'], $data['is_active'] ?? false);

    App::departmentRepository()->save($department);
    self::showDepartments();
  }

  static function handleToggleStatus(string $id): void {
    $department = App::departmentRepository()->getById((int) $id);
    $department->isActive = !$department->isActive;

    App::departmentRepository()->save($department);
    App::redirect('/departamentos');
  }

  static function handleDepartmentEdition(string $id): void {
    $departament = App::departmentRepository()->getById((int) $id);
    $departament->name = App::request()->data['name'];

    try {
      App::departmentRepository()->save($departament);
      App::session()->set('message', '✔ Departamento actualizado exitósamente');
    } catch (DuplicatedNamesException) {
      App::session()->set('error', "❌ Departamento \"{$departament->name}\" ya existe");
    }

    App::redirect('/departamentos');
  }
}
