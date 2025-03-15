<?php

declare(strict_types=1);

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Consultation;
use App\Models\Hospitalization;
use App\Models\Patient;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\ValueObjects\ConsultationType;
use App\ValueObjects\Date;
use App\ValueObjects\DepartureStatus;
use App\ValueObjects\Gender;
use DateTimeImmutable;
use PDO;
use PDOException;

final class PDOPatientRepository extends PDORepository implements PatientRepository {
  private const FIELDS = <<<SQL
    id, first_name as firstName, second_name as secondName, first_last_name as firstLastName,
    second_last_name as secondLastName, birth_date as birthDate, gender, id_card as idCard,
    registered_date as registeredDate, registered_by_id as registeredById
  SQL;

  public function __construct(
    Connection $connection,
    string $baseUrl,
    private readonly PDOUserRepository $userRepository,
    private readonly PDOConsultationCauseRepository $causeRepository,
    private readonly PDODepartmentRepository $departmentRepository,
    private readonly PDODoctorRepository $doctorRepository
  ) {
    parent::__construct($connection, $baseUrl);
  }

  protected static function getTable(): string {
    return 'patients';
  }

  public function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s ORDER BY idCard',
        self::FIELDS,
        self::getTable()
      ))->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper']);
  }

  public function getById(int $id): ?Patient {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::getTable()));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  public function getByIdCard(int $idCard): ?Patient {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id_card = ?', self::FIELDS, self::getTable()));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  public function getConsultationsCount(): int {
    return $this->ensureIsConnected()
      ->query('SELECT count(id) FROM consultations')
      ->fetchColumn(0);
  }

  public function setConsultationsById(Patient $patient, int $causeId): void {
    $stmt = $this->ensureIsConnected()
      ->prepare(<<<sql
        SELECT id, type, registered_date, cause_id, department_id, doctor_id
        FROM consultations
        WHERE patient_id = ? AND cause_id = ?
      sql);

    $stmt->execute([$patient->id, $causeId]);

    $consultations = [];

    while ($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->causeRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id'])
      );

      $consultation
        ->setId($consultationRecord['id'])
        ->setRegisteredDate(parent::parseDateTime($consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    $patient->setConsultations(...$consultations);
  }

  public function setHospitalizations(Patient $patient): void {
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

    while ($hospitalizationRecord = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $hospitalization = new Hospitalization(
        $patient,
        $this->doctorRepository->getById($hospitalizationRecord['doctor_id']),
        $hospitalizationRecord['admission_department'],
        new DateTimeImmutable($hospitalizationRecord['admission_date']),
        $hospitalizationRecord['departure_date']
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

  public function setConsultations(Patient $patient): void {
    $stmt = $this->ensureIsConnected()
      ->prepare(<<<sql
        SELECT id, type, registered_date, cause_id, department_id, doctor_id
        FROM consultations
        WHERE patient_id = ?
        ORDER BY registered_date DESC
      sql);

    $stmt->execute([$patient->id]);

    $consultations = [];

    while ($consultationRecord = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $consultation = new Consultation(
        ConsultationType::from($consultationRecord['type']),
        $this->causeRepository->getById($consultationRecord['cause_id']),
        $this->departmentRepository->getById($consultationRecord['department_id']),
        $this->doctorRepository->getById($consultationRecord['doctor_id'])
      );

      $consultation->setId($consultationRecord['id'])
        ->setRegisteredDate(parent::parseDateTime($consultationRecord['registered_date']));

      $consultations[] = $consultation;
    }

    $patient->setConsultations(...$consultations);
  }

  public function save(Patient $patient): void {
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
          $patient->registeredBy->id
        ]);

      $patient->setId($this->connection->instance()->lastInsertId())
        ->setRegisteredDate(parent::parseDateTime($datetime));
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: patients.id_card')) {
        throw new DuplicatedIdCardException("Cédula \"{$patient->idCard}\" ya existe");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: patients.first_name')) {
        throw new DuplicatedNamesException("Usuario \"{$patient->getFullName()}\" ya existe");
      }
    }
  }

  public function saveHospitalizationOf(Patient $patient): void {
    /** @var Hospitalization[] */
    $hospitalizations = [];

    foreach ($patient->getHospitalization() as $hospitalization) {
      if (!$hospitalization->id) {
        $hospitalizations[] = $hospitalization;
      }
    }

    $registeredDate = parent::getCurrentDatetime();

    $this->ensureIsConnected()
      ->prepare("
        INSERT INTO hospitalizations (admission_department, admission_date,
        departure_date, departure_status, diagnoses, patient_id, doctor_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
      ")->execute([
        $hospitalizations[0]->admissionDepartment,
        $hospitalizations[0]->admissionDate->format(self::DATE_FORMAT),
        $hospitalizations[0]->departureDate?->format(self::DATE_FORMAT),
        $hospitalizations[0]->departureStatus?->value,
        $hospitalizations[0]->diagnoses,
        $hospitalizations[0]->patient->id,
        $hospitalizations[0]->doctor->id
      ]);

    $hospitalizations[0]->setRegisteredDate(parent::parseDateTime($registeredDate));
    $patient->setHospitalization(...$hospitalizations);
  }

  public function saveConsultationOf(Patient $patient): void {
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

  private function update(Patient $patient): self {
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

    return $patient;
  }
}
