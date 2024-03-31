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
      'Roles y permisos',
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
      ['showRestore' => $this->settingsRepository->backupExists()],
      'main'
    );
  }

  function showInstitutionConfigs(): void {
    App::renderPage('settings/institution', 'Institución', [
      'hospital' => $this->settingsRepository->getHospital()
    ], 'main');
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

  function handleInstitutionUpdate(): void {
    $hospital = $this->settingsRepository->getHospital();

    $hospital->setAsic($this->data['asic'])
      ->setHealthDepartment($this->data['health_department'])
      ->setMunicipality($this->data['municipality'])
      ->setName($this->data['name'])
      ->setParish($this->data['parish'])
      ->setPlace($this->data['place'])
      ->setRegion($this->data['region'])
      ->setType($this->data['type']);

    $this->settingsRepository->save($hospital);

    self::setMessage('Institución actualizada exitósamente');
    App::redirect('/configuracion/institucion');
  }
}
