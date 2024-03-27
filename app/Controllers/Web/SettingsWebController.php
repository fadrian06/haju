<?php

namespace App\Controllers\Web;

use App;
use App\Models\Appointment;
use App\Models\User;

class SettingsWebController extends Controller {
  static function showPermissions(): void {
    $loggedUser = App::view()->get('user');

    assert($loggedUser instanceof User);

    $departments = App::departmentRepository()->getAll();
    $users = App::userRepository()->getAll($loggedUser);

    $filteredUsers = array_filter($users, function (User $user) use ($loggedUser): bool {
      return $loggedUser->appointment === Appointment::Director
        ? $user->appointment === Appointment::Coordinator
        : $user->appointment === Appointment::Secretary;
    });

    App::renderPage(
      'settings/permissions',
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
    App::redirect('/configuracion/permisos');
  }

  static function showBackups(): void {
    App::renderPage(
      'settings/backups',
      'Respaldo y restauración',
      ['showRestore' => App::settingsRepository()->backupExists()],
      'main'
    );
  }

  static function showGeneralConfigs(): void {
  }

  static function handleCreateBackup(): void {
    App::settingsRepository()->backup();
    self::setMessage('Base de datos respaldada exitósamente');
    App::redirect('/configuracion/respaldo-restauracion');
  }

  static function handleRestoreBackup(): void {
    App::settingsRepository()->restore();
    self::setMessage('Base de datos restaurada exitósamente');
    App::redirect('/salir');
  }
}
