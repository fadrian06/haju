<?php

namespace App\Controllers\Web;

use App;
use App\Models\Role;
use App\Models\User;

class SettingsWebController extends Controller {
  static function showConfigs(): void {
    $departments = App::departmentRepository()->getAll();
    $users = App::userRepository()->getAll(App::view()->get('user'));

    $filteredUsers = array_filter($users, function (User $user): bool {
      return $user->role === Role::Coordinator;
    });

    App::renderPage(
      'settings',
      'Configuración',
      ['departments' => $departments, 'users' => $filteredUsers],
      'main'
    );
  }

  static function handlePermissionAssignment(string $id): void {
    $userRequested = App::userRepository()->getById((int) $id);
    $userRequested->assignDepartments();
    $departments = [];

    foreach (array_keys(App::request()->data->getData()) as $departmentID) {
      $departments[] = App::departmentRepository()->getById((int) $departmentID);
    }

    if ($departments) {
      $userRequested->assignDepartments(...$departments);
    }

    App::userRepository()->save($userRequested);
    self::setMessage('Asignaciones actualizadas exitósamente');
    App::redirect('/configuracion');
  }
}
