<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Models\User;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use App\ValueObjects\Appointment;
use Flight;
use Leaf\Http\Session;

final readonly class SettingsWebController extends Controller {
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly SettingsRepository $settingsRepository,
    private readonly ConsultationCauseRepository $consultationCauseRepository,
    private readonly Connection $connection,
  ) {
    parent::__construct();
  }

  public function showPermissions(): void {
    $departments = $this->departmentRepository->getAll();
    $users = $this->userRepository->getAll($this->loggedUser);

    $filteredUsers = array_filter(
      $users,
      fn(User $user): bool => $this->loggedUser->appointment->isDirector()
        ? $user->appointment === Appointment::Coordinator
        : $user->appointment === Appointment::Secretary
    );

    renderPage(
      'settings/permissions',
      'Roles y permisos',
      ['users' => $filteredUsers, ...compact('departments')],
      'main'
    );
  }

  public function handlePermissionAssignment(int $id): void {
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
    Flight::redirect('/configuracion/permisos');
  }

  public function showBackups(): void {
    $this->ensureUserIsAuthorized();

    renderPage(
      'settings/backups',
      'Respaldo y restauración',
      ['showRestore' => $this->settingsRepository->backupExists()],
      'main'
    );
  }

  public function showInstitutionConfigs(): void {
    renderPage('settings/institution', 'Institución', [
      'hospital' => $this->settingsRepository->getHospital()
    ], 'main');
  }

  public function handleCreateBackup(): void {
    $scriptPath = $this->ensureUserIsAuthorized()->settingsRepository->backup();
    $dataUrl = 'data:text/plain;base64,' . base64_encode(file_get_contents($scriptPath));

    self::setMessage('Base de datos respaldada exitósamente');
    Session::set('scriptPath', $dataUrl);
    Flight::redirect('/configuracion/respaldo-restauracion');
  }

  public function loadBackupFile(): void {
    $script = file_get_contents(Flight::request()->files->script['tmp_name']);
    $this->ensureUserIsAuthorized()->settingsRepository->restoreFromScript($script);
    self::setMessage('Base de datos restaurada exitósamente');
    Flight::redirect('/salir');
  }

  public function handleRestoreBackup(): void {
    $this->ensureUserIsAuthorized()->settingsRepository->restore();
    self::setMessage('Base de datos restaurada exitósamente');
    Flight::redirect('/salir');
  }

  public function handleInstitutionUpdate(): void {
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
    Flight::redirect('/configuracion/institucion');
  }

  public function showConsultationCausesConfigs(): void {
    $consultationCauses = $this->consultationCauseRepository->getAll();

    renderPage(
      'settings/consultation-causes',
      'Configurar causas de consulta',
      compact('consultationCauses'),
      'main'
    );
  }

  public function handleConsultationCausesUpdate(): void {
    $limitOf = array_map(
      static fn(string $limit): int => (int) $limit,
      array_filter(Flight::request()->data->limit_of)
    );

    $pdo = $this->connection->instance();
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
    Flight::redirect(Flight::request()->referrer);
  }

  public function showLogs(): void {
    $logsPath = __DIR__ . '/../../logs/authentications.log';
    $logs = [];

    if (file_exists($logsPath)) {
      $logs = array_filter(explode(';', file_get_contents($logsPath)));
    }

    renderPage('logs', 'Logs de usuarios', compact('logs'), 'main');
  }

  public function cleanLogs(): void {
    $logsPath = __DIR__ . '/../../logs/authentications.log';
    file_put_contents($logsPath, '');
    Flight::redirect('/logs');
  }

  private function ensureUserIsAuthorized(): static {
    if (
      !$this->loggedUser->appointment->isHigherThan(Appointment::Coordinator)
      || !$this->loggedUser->hasDepartment('Estadística')
    ) {
      Flight::redirect('/');
    }

    return $this;
  }
}
