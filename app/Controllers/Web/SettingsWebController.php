<?php

namespace App\Controllers\Web;

use App;
use App\Models\Appointment;
use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;

final class SettingsWebController extends Controller {
  private readonly UserRepository $userRepository;
  private readonly DepartmentRepository $departmentRepository;
  private readonly SettingsRepository $settingsRepository;

  function __construct() {
    parent::__construct();

    $this->departmentRepository = App::departmentRepository();
    $this->userRepository = App::userRepository();
    $this->settingsRepository = App::settingsRepository();
  }

  function showPermissions(): void {
    $departments = $this->departmentRepository->getAll();
    $users = $this->userRepository->getAll($this->loggedUser);

    $filteredUsers = array_filter($users, function (User $user): bool {
      return $this->loggedUser->appointment === Appointment::Director
        ? $user->appointment === Appointment::Coordinator
        : $user->appointment === Appointment::Secretary;
    });

    App::renderPage(
      'settings/permissions',
      'Configuración',
      ['users' => $filteredUsers, ...compact('departments')],
      'main'
    );
  }

  function handlePermissionAssignment(int $id): void {
    $userRequested = $this->userRepository->getById($id);
    $userRequested->assignDepartments();
    $departments = [];

    foreach (array_keys($this->data->getData()) as $departmentID) {
      $departments[] = $this->departmentRepository->getById($departmentID);
    }

    if ($departments) {
      $userRequested->assignDepartments(...$departments);
    }

    $this->userRepository->save($userRequested);
    self::setMessage('Asignaciones actualizadas exitósamente');
    App::redirect('/configuracion/permisos');
  }

  function showBackups(): void {
    App::renderPage(
      'settings/backups',
      'Respaldo y restauración',
      ['showRestore' => App::settingsRepository()->backupExists()],
      'main'
    );
  }

  function showGeneralConfigs(): void {
  }

  function handleCreateBackup(): void {
    $this->settingsRepository->backup();
    self::setMessage('Base de datos respaldada exitósamente');
    App::redirect('/configuracion/respaldo-restauracion');
  }

  function handleRestoreBackup(): void {
    $this->settingsRepository->restore();
    self::setMessage('Base de datos restaurada exitósamente');
    App::redirect('/salir');
  }
}
