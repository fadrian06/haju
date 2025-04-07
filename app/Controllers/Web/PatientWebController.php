<?php

declare(strict_types=1);

namespace App\Controllers\Web;

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
use DateTime;
use DateTimeImmutable;
use Error;
use Flight;
use PDO;
use Throwable;

final readonly class PatientWebController extends Controller {
  public function __construct(
    private readonly PatientRepository $patientRepository,
    private readonly ConsultationCauseCategoryRepository $consultationCauseCategoryRepository,
    private readonly ConsultationCauseRepository $consultationCauseRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly DoctorRepository $doctorRepository,
  ) {
    parent::__construct();
  }

  public function showConsultations(): void {
    $pdo = container()->get(Connection::class)->instance();

    $stmt = $pdo->prepare(<<<sql
      SELECT id, type, registered_date, cause_id, department_id, doctor_id
      FROM consultations
      ORDER BY registered_date DESC
    sql);

    $stmt->execute();
    $consultations = [];

    while ($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->consultationCauseRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id'])
      );

      $consultation->setId($consultationRecord['id'])
        ->setRegisteredDate(DateTime::createFromFormat('Y-m-d H:i:s', $consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    renderPage('consultations/list', 'Consultas', [
      'consultations' => $consultations,
    ], 'main');
  }

  public function showPatients(): void {
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

  public function showPatient(int $patientId): void {
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

  public function handleEdition(int $patientId): void {
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

    renderPage('patients/add-consultation', 'Registrar consulta', compact(
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
      self::setMessage('Consulta registrada exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect(Flight::request()->referrer);
  }

  public function showHospitalizationRegister(): void {
    $patients = $this->patientRepository->getAll();
    $doctors = $this->doctorRepository->getAll();

    renderPage(
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
    self::setMessage('Hospitalización registrada exitósamente');

    Flight::redirect("/pacientes/{$patient->id}");
  }

  public function deletePatient(int $patientId): void {
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

    $pdo = container()->get(Connection::class)->instance();

    $pdo->beginTransaction();
    $stmt = $pdo->prepare('DELETE FROM patients WHERE id = ?');
    $stmt->execute([$patientId]);
    $pdo->commit();

    self::setMessage('Paciente eliminado exitósamente');
    Flight::redirect('/pacientes');
  }

  public function showEditHospitalization(int $hospitalizationId): void {
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
      self::setMessage('Alta procesada exitósamente');
      Flight::redirect("/pacientes/{$patient->id}");

      return;
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect(Flight::request()->referrer);
  }
}
