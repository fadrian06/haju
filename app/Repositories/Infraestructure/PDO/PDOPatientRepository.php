<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Patient;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use PDO;
use PDOException;

final class PDOPatientRepository extends PDORepository implements PatientRepository {
  private const FIELDS = <<<SQL
    id, first_name as firstName, second_name as secondName, first_last_name as firstLastName,
    second_last_name as secondLastName, birth_date as birthDate, gender, id_card as idCard,
    registered_date as registeredDate, registered_by_id as registeredById
  SQL;

  function __construct(
    Connection $connection,
    string $baseUrl,
    private readonly PDOUserRepository $userRepository
  ) {
    parent::__construct($connection, $baseUrl);
  }

  protected static function getTable(): string {
    return 'patients';
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s',
        self::FIELDS,
        self::getTable()
      ))->fetchAll(PDO::FETCH_FUNC, [__CLASS__, 'mapper']);
  }

  function getById(int $id): ?Patient {
    return null;
  }

  function getByIdCard(int $idCard): ?Patient {
    return null;
  }

  function save(Patient $patient): void {
    try {
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
