<?php

declare(strict_types=1);

namespace HAJU\Repositories\Infraestructure\PDO;

use HAJU\Models\Consultation;
use HAJU\Models\Hospitalization;
use HAJU\Models\Patient;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Domain\DoctorRepository;
use HAJU\Repositories\Domain\PatientRepository;
use HAJU\Repositories\Domain\UserRepository;
use HAJU\Repositories\Exceptions\DuplicatedIdCardException;
use HAJU\Repositories\Exceptions\DuplicatedNamesException;
use HAJU\Enums\ConsultationType;
use HAJU\ValueObjects\Date;
use HAJU\Enums\DepartureStatus;
use HAJU\Enums\Gender;
use DateTimeImmutable;
use PDO;
use PDOException;

final class PDOPatientRepository extends PDORepository implements PatientRepository
{
  private const FIELDS = <<<SQL
    id, first_name as firstName, second_name as secondName,
    first_last_name as firstLastName, second_last_name as secondLastName,
    birth_date as birthDate, gender, id_card as idCard,
    registered_date as registeredDate, registered_by_id as registeredById
  SQL;

  public function __construct(
    PDO $pdo,
    string $baseUrl,
    private readonly UserRepository $userRepository,
    private readonly ConsultationCauseRepository $causeRepository,
    private readonly DepartmentRepository $departmentRepository,
    private readonly DoctorRepository $doctorRepository,
    private bool $withHospitalizations = false,
    private bool $withConsultations = false,
  ) {
    parent::__construct($pdo, $baseUrl);
  }

  protected static function getTable(): string
  {
    return 'patients';
  }

  public function withHospitalizations(): PatientRepository
  {
    $patientRepository = clone $this;
    $patientRepository->withHospitalizations = true;

    return $patientRepository;
  }

  public function withConsultations(): PatientRepository
  {
    $patientRepository = clone $this;
    $patientRepository->withConsultations = true;

    return $patientRepository;
  }

  public function getAll(): array
  {
    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s ORDER BY idCard',
        self::FIELDS,
        self::getTable()
      ))->fetchAll(PDO::FETCH_FUNC, $this->mapper(...));
  }

  public function getById(int $id): ?Patient
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::getTable()));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function getByIdCard(int $idCard): ?Patient
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id_card = ?', self::FIELDS, self::getTable()));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function getConsultationsCount(): int
  {
    return $this->ensureIsConnected()
      ->query('SELECT count(id) FROM consultations')
      ->fetchColumn(0);
  }

  public function setConsultationsById(Patient $patient, int $causeId): void
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(<<<sql
        SELECT id, type, registered_date, cause_id, department_id, doctor_id
        FROM consultations
        WHERE patient_id = ? AND cause_id = ?
      sql);

    $stmt->execute([$patient->id, $causeId]);

    $consultations = [];

    while (is_array($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC))) {
      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->causeRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id']),
        $patient,
      );

      $consultation
        ->setId($consultationRecord['id'])
        ->setRegisteredDate(parent::parseDateTime($consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    $patient->setConsultations(...$consultations);
  }

  public function setHospitalizations(Patient $patient): void
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(<<<sql
        SELECT id, admission_department, admission_date, departure_date,
        departure_status, diagnoses, registered_date, doctor_id
        FROM hospitalizations
        WHERE patient_id = ?
        ORDER BY registered_date DESC
      sql);

    $stmt->execute([$patient->id]);

    /** @var Hospitalization[] */
    $hospitalizations = [];

    while (is_array($hospitalizationRecord = $stmt->fetch(PDO::FETCH_ASSOC))) {
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
        ->setRegisteredDate(parent::parseDateTime($hospitalizationRecord['registered_date']));

      $hospitalizations[] = $hospitalization;
    }

    $patient->setHospitalization(...$hospitalizations);
  }

  public function setConsultations(Patient $patient): void
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(<<<sql
        SELECT id, type, registered_date, cause_id, department_id, doctor_id
        FROM consultations
        WHERE patient_id = ?
        ORDER BY registered_date DESC
      sql);

    $stmt->execute([$patient->id]);

    $consultations = [];

    while (is_array($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC))) {
      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->causeRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id']),
        $patient,
      );

      $consultation->setId($consultationRecord['id'])
        ->setRegisteredDate(parent::parseDateTime($consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    $patient->setConsultations(...$consultations);
  }

  public function save(Patient $patient): void
  {
    try {
      if ($patient->id) {
        $this->update($patient);

        return;
      }

      $query = sprintf(
        <<<SQL
          INSERT INTO %s (
            first_name, second_name, first_last_name, second_last_name,
            birth_date, gender, id_card, registered_date, registered_by_id
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::getTable()
      );

      $datetime = parent::getCurrentDatetime();

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $patient->firstName,
          $patient->secondName,
          $patient->firstLastName,
          $patient->secondLastName,
          $patient->birthDate->timestamp,
          $patient->gender->value,
          $patient->idCard,
          $datetime,
          $patient->registeredBy->id,
        ]);

      $patient->setId((int) $this->pdo->lastInsertId())
        ->setRegisteredDate(parent::parseDateTime($datetime));
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: patients.id_card')) {
        throw new DuplicatedIdCardException("Cédula \"{$patient->idCard}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: patients.first_name')) {
        throw new DuplicatedNamesException("Usuario \"{$patient->getFullName()}\" ya existe");
      }
    }
  }

  public function saveHospitalizationOf(Patient $patient): void
  {
    $this->ensureIsConnected()->beginTransaction();

    try {
      /** @var Hospitalization[] */
      $hospitalizations = [];
      $registeredDate = parent::getCurrentDatetime();

      foreach ($patient->getHospitalization() as $hospitalization) {
        if ($hospitalization->id) {
          $this
            ->ensureIsConnected()
            ->prepare('
              UPDATE hospitalizations SET admission_department = ?, admission_date = ?,
              departure_date = ?, departure_status = ?, diagnoses = ?, patient_id = ?, doctor_id = ?
              WHERE id = ?
            ')
            ->execute([
              $hospitalization->admissionDepartment,
              $hospitalization->admissionDate->format(self::DATE_FORMAT),
              $hospitalization->departureDate?->format(self::DATE_FORMAT),
              $hospitalization->departureStatus?->value,
              $hospitalization->diagnoses,
              $hospitalization->patient->id,
              $hospitalization->doctor->id,
              $hospitalization->id
            ]);

          continue;
        }

        $this->ensureIsConnected()
          ->prepare("
            INSERT INTO hospitalizations (admission_department, admission_date,
            departure_date, departure_status, diagnoses, patient_id, doctor_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
          ")->execute([
            $hospitalization->admissionDepartment,
            $hospitalization->admissionDate->format(self::DATE_FORMAT),
            $hospitalization->departureDate?->format(self::DATE_FORMAT),
            $hospitalization->departureStatus?->value,
            $hospitalization->diagnoses,
            $hospitalization->patient->id,
            $hospitalization->doctor->id,
          ]);

        $hospitalization->setRegisteredDate(parent::parseDateTime($registeredDate));
        $patient->setHospitalization(...$hospitalizations);
      }

      $this->pdo->commit();
    } catch (PDOException) {
      $this->pdo->rollBack();
    }
  }

  public function saveConsultationOf(Patient $patient): void
  {
    $consultations = [];

    foreach ($patient->getConsultation() as $consultation) {
      if (!$consultation->id) {
        $consultations[] = $consultation;
      }
    }

    $registeredDate = parent::getCurrentDatetime();

    $this->ensureIsConnected()
      ->prepare("
        INSERT INTO consultations (type, registered_date, patient_id, cause_id,
        department_id, doctor_id)
        VALUES (?, ?, ?, ?, ?, ?)
      ")->execute([
        $consultations[0]->type->value,
        $registeredDate,
        $patient->id,
        $consultations[0]->cause->id,
        $consultations[0]->department->id,
        $consultations[0]->doctor->id
      ]);

    $consultations[0]->setRegisteredDate(parent::parseDateTime($registeredDate));
    $patient->setConsultations(...$consultations);
  }

  private function update(Patient $patient): self
  {
    $query = sprintf(
      <<<sql
        UPDATE %s SET first_name = ?, second_name = ?, first_last_name = ?, second_last_name = ?,
        birth_date = ?, id_card = ? WHERE id = ?
      sql,
      self::getTable()
    );

    $this->ensureIsConnected()
      ->prepare($query)
      ->execute([
        $patient->firstName,
        $patient->secondName,
        $patient->firstLastName,
        $patient->secondLastName,
        $patient->birthDate->timestamp,
        $patient->idCard,
        $patient->id
      ]);

    return $this;
  }

  public function getByHospitalizationId(int $id): ?Patient
  {
    $stmt = $this
      ->ensureIsConnected()
      ->prepare('SELECT patient_id FROM hospitalizations WHERE id = ?');

    $stmt->execute([$id]);

    return $this->getById($stmt->fetchColumn(0));
  }

  private function mapper(
    int $id,
    string $firstName,
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    int $birthDate,
    string $gender,
    int $idCard,
    string $registeredDate,
    int $registeredById
  ): Patient {
    $patient = new Patient(
      $firstName,
      $secondName,
      $firstLastName,
      $secondLastName,
      Date::fromTimestamp($birthDate),
      Gender::from($gender),
      $idCard,
      $this->userRepository->getById($registeredById)
    );

    $patient->setId($id)->setRegisteredDate(parent::parseDateTime($registeredDate));

    if ($this->withHospitalizations) {
      $this->setHospitalizations($patient);
    }

    if ($this->withConsultations) {
      $this->setConsultations($patient);
    }

    return $patient;
  }
}
