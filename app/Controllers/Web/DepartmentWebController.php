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

  static function activateDepartment(): void {}

  static function disactivateDepartment(): void {}

  static function showEditDepartment(): void {}

  static function handleDepartmentEdition(): void {}
}
