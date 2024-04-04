<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Patient;
use App\Repositories\Domain\PatientRepository;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use PDO;

final class PDOPatientRepository extends PDORepository implements PatientRepository {
  private const FIELDS = <<<SQL
    id, first_name as firstName, second_name as secondName, first_last_name as firstLastName,
    second_last_name as secondLastName, birth_date as birthDate, gender, id_card as idCard,
    registered_date as registeredDate, registered_by_id as registeredById
  SQL;

  private const TABLE = 'patients';

  public function __construct(
    Connection $connection,
    string $baseUrl,
    private readonly PDOUserRepository $userRepository
  ) {
    parent::__construct($connection, $baseUrl);
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s',
        self::FIELDS,
        self::TABLE
      ))->fetchAll(PDO::FETCH_FUNC, [__CLASS__, 'mapper']);
  }

  function getById(int $id): ?Patient {
    return null;
  }

  function getByIdCard(int $idCard): ?Patient {
    return null;
  }

  function save(Patient $patient): void {

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
