<?php

namespace App\Controllers\Web;

use App;

class DepartmentWebController {
  static function showDepartments(): void {
    $departments = App::departmentRepository()->getAll();

    App::renderPage('departments', 'Departamentos', compact('departments'), 'main');
  }

  static function showRegister(): void {}

  static function handleRegister(): void {}

  static function activateDepartment(string $id): void {}

  static function disactivateDepartment(string $id): void {}

  static function showEditDepartment(string $id): void {}

  static function handleDepartmentEdition(string $id): void {}
}
