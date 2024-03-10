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

    foreach (array_keys(App::request()->data->getData()) as $departmentID) {
      $department = App::departmentRepository()->getById((int) $departmentID);
      $userRequested->assignDepartments($department);
    }

    App::userRepository()->save($userRequested);
    App::redirect('/configuracion');
  }
}
