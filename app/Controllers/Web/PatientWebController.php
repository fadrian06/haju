<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\Consultation;
use App\Models\Hospitalization;
use App\Models\Patient;
use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use App\ValueObjects\ConsultationType;
use App\ValueObjects\Date;
use App\ValueObjects\DepartureStatus;
use App\ValueObjects\Gender;
use DateTimeImmutable;
use Error;
use Illuminate\Container\Container;
use Throwable;

final class PatientWebController extends Controller {
  public function __construct(
    private readonly PatientRepository $patientRepository,
    private readonly ConsultationCauseCategoryRepository $consultationCauseCategoryRepository,
    private readonly ConsultationCauseRepository $consultationCauseRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly DoctorRepository $doctorRepository,
  ) {
    parent::__construct();
  }

  public function showPatients(): void {
    $idCard = App::request()->query['cedula'];

    if (is_numeric($idCard)) {
      $patient = $this->patientRepository->getByIdCard((int) $idCard);

      if ($patient !== null) {
        App::redirect("/pacientes/{$patient->id}");

        return;
      }

      self::setError("Paciente v-{$idCard} no encontrado");
    }

    App::renderPage('patients/list', 'Pacientes', [
      'patients' => $this->patientRepository->getAll(),
    ], 'main');
  }

  public function showPatient(int $patientId): void {
    try {
      $patient = $this->patientRepository->getById($patientId);

      if ($patient === null) {
        throw new Error("Paciente #{$patientId} no encontrado");
      }

      $this->patientRepository->setConsultations($patient);
      $this->patientRepository->setHospitalizations($patient);

      App::renderPage(
        'patients/info',
        'Detalles del paciente',
        compact('patient'),
        'main'
      );
    } catch (Throwable $error) {
      self::setError($error);
      App::redirect('/pacientes');
    }
  }

  public function handleRegister(): void {
    try {
      if (!in_array($this->data['gender'], Gender::values(), true)) {
        throw new Error(sprintf(
          'El género es requerido y válido (%s)',
          implode(', ', Gender::values())
        ));
      }

      $patient = new Patient(
        $this->data['first_name'],
        $this->data['second_name'],
        $this->data['first_last_name'],
        $this->data['second_last_name'],
        Date::from($this->data['birth_date'], '-'),
        Gender::from($this->data['gender']),
        $this->data['id_card'],
        $this->loggedUser
      );

      $this->patientRepository->save($patient);
      parent::setMessage('Paciente registrado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    if (App::request()->query['referido'] !== null) {
      App::redirect(App::request()->query['referido']);

      return;
    }

    App::redirect('/pacientes');
  }

  public function handleEdition(int $patientId): void {
    try {
      $patient = $this->patientRepository->getById($patientId);

      $patient
        ?->setFullName((string) $this->data['full_name'])
        ->setIdCard((int) $this->data['id_card']);

      $patient->birthDate = Date::from($this->data['birth_date'], '-');

      $this->patientRepository->save($patient);
      parent::setMessage('Paciente actualizado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect('/pacientes');
  }

  public function showConsultationRegister(): void {
    $patients = $this->patientRepository->getAll();

    $consultationCauseCategories = $this
      ->consultationCauseCategoryRepository
      ->getAll();

    $doctors = $this->doctorRepository->getAll();
    $departments = [];

    foreach ($this->loggedUser?->getDepartment() as $department) {
      if ($department->belongsToExternalConsultation) {
        $departments[] = $department;
      }
    }

    App::renderPage('patients/add-consultation', 'Registrar consulta', compact(
      'patients',
      'consultationCauseCategories',
      'departments',
      'doctors'
    ), 'main');
  }

  public function handleConsultationRegister(): void {
    try {
      $patient = $this
        ->patientRepository
        ->getById((int) $this->data['id_card']);

      $consultation = new Consultation(
        $this->data['consultation_type']
          ? ConsultationType::from($this->data['consultation_type'])
          : ConsultationType::FirstTime,
        $this
          ->consultationCauseRepository
          ->getById($this->data['consultation_cause']),
        $this->departmentRepository->getById((int) $this->data['department']),
        $this->doctorRepository->getById((int) $this->data['doctor'])
      );

      $patient->setConsultations($consultation);
      $this->patientRepository->saveConsultationOf($patient);
      parent::setMessage('Consulta registrada exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect(App::request()->referrer);
  }

  public function showHospitalizationRegister(): void {
    $patients = $this->patientRepository->getAll();
    $doctors = $this->doctorRepository->getAll();

    App::renderPage(
      'patients/add-hospitalization',
      'Registrar hospitalización',
      compact('patients', 'doctors'),
      'main'
    );
  }

  public function handleHospitalizationRegister(): void {
    $patient = $this->patientRepository->getById((int) $this->data['id_card']);

    $hospitalization = new Hospitalization(
      $patient,
      $this->doctorRepository->getById((int) $this->data['doctor']),
      $this->data['admission_department'],
      new DateTimeImmutable($this->data['admission_date']),
      $this->data['departure_date']
        ? new DateTimeImmutable($this->data['departure_date'])
        : null,
      $this->data['departure_status']
        ? DepartureStatus::from($this->data['departure_status'])
        : null,
      $this->data['diagnoses'] ?: null
    );

    $patient->setHospitalization($hospitalization);
    $this->patientRepository->saveHospitalizationOf($patient);
    parent::setMessage('Hospitalización registrada exitósamente');

    App::redirect("/pacientes/{$patient->id}");
  }

  public function deletePatient(int $patientId): void {
    $patient = $this->patientRepository->getById($patientId);

    if ($patient === null) {
      self::setMessage('Paciente no encontrado');
      App::redirect('/pacientes', 404);

      return;
    }

    if (!$patient->canBeDeleted()) {
      self::setMessage('Este paciente no puede ser eliminado (tiene consultas u hospitalizaciones asignadas)');
      App::redirect('/pacientes', 409);

      return;
    }

    $pdo = Container::getInstance()->get(Connection::class)->instance();

    $pdo->beginTransaction();
    $stmt = $pdo->prepare('DELETE FROM patients WHERE id = ?');
    $stmt->execute([$patientId]);
    $pdo->commit();

    parent::setMessage('Paciente eliminado exitósamente');
    App::redirect('/pacientes');
  }

  public function showEditHospitalization(int $hospitalizationId): void {
    $patient = $this
      ->patientRepository
      ->getByHospitalizationId($hospitalizationId);

    if (!$patient) {
      self::setError('Hospitalización no encontrada');
      App::redirect('/pacientes', 404);

      return;
    }

    $this->patientRepository->setHospitalizations($patient);
    $hospitalization = $patient->getHospitalizationById($hospitalizationId);
    $doctors = $this->doctorRepository->getAll();

    App::renderPage(
      'patients/edit-hospitalization',
      "Dar de alta a {$patient->getFullName()}",
      compact('patient', 'hospitalization', 'doctors'),
      'main'
    );
  }

  public function handleUpdateHospitalization(int $hospitalizationId): void {
    try {
      $patient = $this
        ->patientRepository
        ->getByHospitalizationId($hospitalizationId);

      if (!$patient) {
        throw new Error('Hospitalización no encontrada');
      }

      $this->patientRepository->setHospitalizations($patient);
      $hospitalization = $patient->getHospitalizationById($hospitalizationId);

      $hospitalization
        ?->setAdmissionDate(new DateTimeImmutable($this->data['admission_date']))
        ->setDepartureDate(new DateTimeImmutable($this->data['departure_date']))
        ->setDepartureStatus(
          DepartureStatus::from($this->data['departure_status'])
        );

      $hospitalization->diagnoses = $this->data['diagnoses'] ?? '';

      $hospitalization->doctor = $this
        ->doctorRepository
        ->getById((int) $this->data['doctor']);

      $hospitalization->admissionDepartment = $this->data['admission_department'];

      $this->patientRepository->saveHospitalizationOf($patient);
      parent::setMessage('Alta procesada exitósamente');
      App::redirect("/pacientes/{$patient->id}");

      return;
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect(App::request()->referrer);
  }
}
