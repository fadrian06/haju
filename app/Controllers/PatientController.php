<?php

namespace HAJU\Controllers;

use HAJU\Models\Consultation;
use HAJU\Models\Hospitalization;
use HAJU\Models\Patient;
use HAJU\Repositories\Domain\ConsultationCauseCategoryRepository;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Domain\DoctorRepository;
use HAJU\Repositories\Domain\PatientRepository;
use HAJU\Enums\ConsultationType;
use HAJU\ValueObjects\Date;
use HAJU\Enums\DepartureStatus;
use HAJU\Enums\Gender;
use DateTime;
use DateTimeImmutable;
use Error;
use Flight;
use PDO;
use Throwable;

final readonly class PatientController extends Controller
{
  public function __construct(
    private PatientRepository $patientRepository,
    private ConsultationCauseCategoryRepository $consultationCauseCategoryRepository,
    private ConsultationCauseRepository $consultationCauseRepository,
    private DepartmentRepository $departmentRepository,
    private DoctorRepository $doctorRepository,
    private PDO $pdo,
  ) {
    parent::__construct();
  }

  public function showHospitalizations(): void
  {
    $stmt = $this->pdo->prepare(<<<sql
      SELECT id, admission_department, admission_date, departure_date,
      departure_status, diagnoses, registered_date, doctor_id, patient_id
      FROM hospitalizations
      ORDER BY admission_date DESC
    sql);

    $stmt->execute();

    /** @var Hospitalization[] */
    $hospitalizations = [];

    $patients = [];

    while (is_array($hospitalizationRecord = $stmt->fetch(PDO::FETCH_ASSOC))) {
      $patient = $patients[$hospitalizationRecord['patient_id']] ?? $this
        ->patientRepository
        ->getById(intval($hospitalizationRecord['patient_id']));

      $patients[$hospitalizationRecord['patient_id']] ??= $patient;

      $hospitalization = new Hospitalization(
        $patient,
        $this->doctorRepository->getById($hospitalizationRecord['doctor_id']),
        $hospitalizationRecord['admission_department'],
        new DateTimeImmutable($hospitalizationRecord['admission_date']),
        boolval($hospitalizationRecord['departure_date'])
          ? new DateTimeImmutable($hospitalizationRecord['departure_date'])
          : null,
        DepartureStatus::tryFrom($hospitalizationRecord['departure_status'] ?? ''),
        $hospitalizationRecord['diagnoses'] ?: null
      );


      $hospitalization->setId($hospitalizationRecord['id'])
        ->setRegisteredDate(DateTime::createFromFormat('Y-m-d H:i:s', $hospitalizationRecord['registered_date']));

      $hospitalizations[] = $hospitalization;
    }

    renderPage('hospitalizations/list', 'Hospitalizaciones', [
      'hospitalizations' => $hospitalizations,
    ], 'main');
  }

  public function showConsultations(): void
  {
    $stmt = $this->pdo->prepare(<<<sql
      SELECT id, type, registered_date, cause_id, department_id, doctor_id, patient_id
      FROM consultations
      ORDER BY registered_date DESC
      LIMIT 100
    sql);

    $stmt->execute();

    $consultations = [];
    $patients = [];

    while (is_array($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC))) {
      $patient = $patients[$consultationRecord['patient_id']] ?? $this
        ->patientRepository
        ->getById(intval($consultationRecord['patient_id']));

      $patients[$consultationRecord['patient_id']] ??= $patient;

      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->consultationCauseRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id']),
        $patient,
      );

      $consultation->setId($consultationRecord['id'])
        ->setRegisteredDate(DateTime::createFromFormat('Y-m-d H:i:s', $consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    renderPage('consultations/list', 'Consultas', [
      'consultations' => $consultations,
      'consultationCauseCategories' => $this->consultationCauseCategoryRepository->getAll(),
    ], 'main');
  }

  public function showPatients(): void
  {
    $idCard = Flight::request()->query['cedula'];

    if (is_numeric($idCard)) {
      $patient = $this->patientRepository->getByIdCard((int) $idCard);

      if ($patient !== null) {
        Flight::redirect("/pacientes/{$patient->id}");

        return;
      }

      self::setError("Paciente v-{$idCard} no encontrado");
      Flight::redirect('/pacientes');

      return;
    }

    renderPage('patients/list', 'Pacientes', [
      'patients' => $this
        ->patientRepository
        ->withHospitalizations()
        ->getAll(),
    ], 'main');
  }

  public function showPatient(int $patientId): void
  {
    try {
      $patient = $this->patientRepository->getById($patientId);

      if ($patient === null) {
        throw new Error("Paciente #{$patientId} no encontrado");
      }

      $this->patientRepository->setConsultations($patient);
      $this->patientRepository->setHospitalizations($patient);

      renderPage(
        'patients/info',
        'Detalles del paciente',
        compact('patient'),
        'main'
      );
    } catch (Throwable $error) {
      self::setError($error);
      Flight::redirect('/pacientes');
    }
  }

  public function handleRegister(): void
  {
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
        intval($this->data['id_card']),
        $this->loggedUser
      );

      $this->patientRepository->save($patient);
      self::setMessage('Paciente registrado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    if (Flight::request()->query['referido'] !== null) {
      Flight::redirect(Flight::request()->query['referido']);

      return;
    }

    Flight::redirect('/pacientes');
  }

  public function handleEdition(int $patientId): void
  {
    try {
      $patient = $this->patientRepository->getById($patientId);

      $patient
        ?->setFullName((string) $this->data['full_name'])
        ->setIdCard((int) $this->data['id_card']);

      $patient->birthDate = Date::from($this->data['birth_date'], '-');

      $this->patientRepository->save($patient);
      self::setMessage('Paciente actualizado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect('/pacientes');
  }

  public function showConsultationRegister(): void
  {
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

    renderPage('patients/add-consultation', 'Registrar consulta', compact(
      'patients',
      'consultationCauseCategories',
      'departments',
      'doctors'
    ), 'main');
  }

  public function handleConsultationRegister(): void
  {
    try {
      $patient = $this
        ->patientRepository
        ->getById((int) $this->data['id_card']);

      $consultation = new Consultation(
        boolval($this->data['consultation_type'])
          ? ConsultationType::from($this->data['consultation_type'])
          : ConsultationType::FirstTime,
        $this
          ->consultationCauseRepository
          ->getById((int) $this->data['consultation_cause']),
        $this->departmentRepository->getById((int) $this->data['department']),
        $this->doctorRepository->getById((int) $this->data['doctor']),
        $patient,
      );

      $patient->setConsultations($consultation);
      $this->patientRepository->saveConsultationOf($patient);
      self::setMessage('Consulta registrada exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    Flight::redirect(Flight::request()->referrer);
  }

  public function showHospitalizationRegister(): void
  {
    $patients = $this->patientRepository->getAll();
    $doctors = $this->doctorRepository->getAll();

    renderPage(
      'patients/add-hospitalization',
      'Registrar hospitalización',
      compact('patients', 'doctors'),
      'main'
    );
  }

  public function handleHospitalizationRegister(): void
  {
    $patient = $this->patientRepository->getById((int) $this->data['id_card']);

    $hospitalization = new Hospitalization(
      $patient,
      $this->doctorRepository->getById((int) $this->data['doctor']),
      $this->data['admission_department'],
      new DateTimeImmutable($this->data['admission_date']),
      boolval($this->data['departure_date'])
        ? new DateTimeImmutable($this->data['departure_date'])
        : null,
      boolval($this->data['departure_status'])
        ? DepartureStatus::from($this->data['departure_status'])
        : null,
      $this->data['diagnoses'] ?: null
    );

    $patient->setHospitalization($hospitalization);
    $this->patientRepository->saveHospitalizationOf($patient);
    self::setMessage('Hospitalización registrada exitósamente');

    Flight::redirect("/pacientes/{$patient->id}");
  }

  public function deletePatient(int $patientId): void
  {
    $patient = $this->patientRepository->getById($patientId);

    if ($patient === null) {
      self::setMessage('Paciente no encontrado');
      Flight::redirect('/pacientes', 404);

      return;
    }

    if (!$patient->canBeDeleted()) {
      self::setMessage('Este paciente no puede ser eliminado (tiene consultas u hospitalizaciones asignadas)');
      Flight::redirect('/pacientes', 409);

      return;
    }

    $this->pdo->beginTransaction();
    $stmt = $this->pdo->prepare('DELETE FROM patients WHERE id = ?');
    $stmt->execute([$patientId]);

    $this->pdo->commit();

    self::setMessage('Paciente eliminado exitósamente');
    Flight::redirect('/pacientes');
  }

  public function showEditHospitalization(int $hospitalizationId): void
  {
    $patient = $this
      ->patientRepository
      ->getByHospitalizationId($hospitalizationId);

    if (!$patient) {
      self::setError('Hospitalización no encontrada');
      Flight::redirect('/pacientes', 404);

      return;
    }

    $this->patientRepository->setHospitalizations($patient);
    $hospitalization = $patient->getHospitalizationById($hospitalizationId);
    $doctors = $this->doctorRepository->getAll();

    renderPage(
      'patients/edit-hospitalization',
      "Dar de alta a {$patient->getFullName()}",
      compact('patient', 'hospitalization', 'doctors'),
      'main'
    );
  }

  public function handleUpdateHospitalization(int $hospitalizationId): void
  {
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
      self::setMessage('Alta procesada exitósamente');
      Flight::redirect("/pacientes/{$patient->id}");

      return;
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect(Flight::request()->referrer);
  }
}
