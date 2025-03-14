<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\ValueObjects\Appointment;
use Leaf\Http\Session;

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

    $filteredUsers = array_filter($users, fn(User $user): bool => $this->loggedUser->appointment === Appointment::Director
      ? $user->appointment === Appointment::Coordinator
      : $user->appointment === Appointment::Secretary);

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
    $this->ensureUserIsAuthorized();

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
    $scriptPath = $this->ensureUserIsAuthorized()->settingsRepository->backup();
    $dataUrl = 'data:text/plain;base64,' . base64_encode(file_get_contents($scriptPath));

    self::setMessage('Base de datos respaldada exitósamente');
    Session::set('scriptPath', $dataUrl);
    App::redirect('/configuracion/respaldo-restauracion');
  }

  function loadBackupFile(): void {
    $script = file_get_contents(App::request()->files->script['tmp_name']);
    $this->ensureUserIsAuthorized()->settingsRepository->restoreFromScript($script);
    self::setMessage('Base de datos restaurada exitósamente');
    App::redirect('/salir');
  }

  function handleRestoreBackup(): void {
    $this->ensureUserIsAuthorized()->settingsRepository->restore();
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

  function showConsultationCausesConfigs(): void {
    $consultationCauses = App::consultationCauseRepository()->getAll();

    App::renderPage(
      'settings/consultation-causes',
      'Configurar causas de consulta',
      compact('consultationCauses'),
      'main'
    );
  }

  function handleConsultationCausesUpdate(): void {
    $limitOf = array_map(
      static fn(string $limit): int => $limit,
      array_filter(App::request()->data->limit_of)
    );

    $pdo = App::db()->instance();
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('UPDATE consultation_causes SET weekly_cases_limit = :weeklyLimit WHERE id = :id');

    foreach ($limitOf as $consultationCauseId => $weeklyLimit) {
      $stmt->execute([
        ':weeklyLimit' => $weeklyLimit,
        ':id' => $consultationCauseId
      ]);
    }

    $pdo->commit();
    self::setMessage('Límites de casos semanales actualizados exitósamente');
    App::redirect(App::request()->referrer);
  }

  function showLogs(): void {
    $logsPath = __DIR__ . '/../../logs/authentications.log';
    $logs = [];

    if (file_exists($logsPath)) {
      $logs = array_filter(explode(';', file_get_contents($logsPath)));
    }

    App::renderPage('logs', 'Logs de usuarios', compact('logs'), 'main');
  }

  function cleanLogs(): void {
    $logsPath = __DIR__ . '/../../logs/authentications.log';
    file_put_contents($logsPath, '');
    App::redirect('/logs');
  }

  private function ensureUserIsAuthorized(): static {
    if (
      !$this->loggedUser->appointment->isHigherThan(Appointment::Coordinator)
      || !$this->loggedUser->hasDepartment('Estadística')
    ) {
      App::redirect('/');
    }

    return $this;
  }
}
