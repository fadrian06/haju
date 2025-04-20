<?php

declare(strict_types=1);

namespace HAJU\Repositories\Infraestructure\PDO;

use HAJU\Models\Doctor;
use HAJU\Repositories\Domain\DoctorRepository;
use HAJU\Repositories\Domain\UserRepository;
use HAJU\Repositories\Exceptions\DuplicatedIdCardException;
use HAJU\Repositories\Exceptions\DuplicatedNamesException;
use HAJU\ValueObjects\Date;
use HAJU\ValueObjects\Gender;
use PDO;
use PDOException;

final class PDODoctorRepository
extends PDORepository
implements DoctorRepository {
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
  ) {
    parent::__construct($pdo, $baseUrl);
  }

  protected static function getTable(): string {
    return 'doctors';
  }

  public function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s ORDER BY idCard',
        self::FIELDS,
        self::getTable()
      ))->fetchAll(PDO::FETCH_FUNC, $this->mapper(...));
  }

  public function getById(int $id): ?Doctor {
    $stmt = $this->ensureIsConnected()->prepare(sprintf(
      'SELECT %s FROM %s WHERE id = ?',
      self::FIELDS,
      self::getTable()
    ));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function getByIdCard(int $idCard): ?Doctor {
    $stmt = $this->ensureIsConnected()->prepare(sprintf(
      'SELECT %s FROM %s WHERE id_card = ?',
      self::FIELDS,
      self::getTable()
    ));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function save(Doctor $doctor): void {
    try {
      if ($doctor->id) {
        $this->update($doctor);

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
          $doctor->firstName,
          $doctor->secondName,
          $doctor->firstLastName,
          $doctor->secondLastName,
          $doctor->birthDate->timestamp,
          $doctor->gender->value,
          $doctor->idCard,
          $datetime,
          $doctor->registeredBy->id
        ]);

      $doctor
        ->setId((int) $this->pdo->lastInsertId())
        ->setRegisteredDate(parent::parseDateTime($datetime));
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: patients.id_card')) {
        throw new DuplicatedIdCardException("Cédula \"{$doctor->idCard}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: patients.first_name')) {
        throw new DuplicatedNamesException("Usuario \"{$doctor->getFullName()}\" ya existe");
      }
    }
  }

  private function update(Doctor $doctor): self {
    $query = sprintf(
      <<<sql
        UPDATE %s SET first_name = ?, second_name = ?, first_last_name = ?,
        second_last_name = ?, birth_date = ?, id_card = ? WHERE id = ?
      sql,
      self::getTable()
    );

    $this->ensureIsConnected()
      ->prepare($query)
      ->execute([
        $doctor->firstName,
        $doctor->secondName,
        $doctor->firstLastName,
        $doctor->secondLastName,
        $doctor->birthDate->timestamp,
        $doctor->idCard,
        $doctor->id
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
  ): Doctor {
    $doctor = new Doctor(
      $firstName,
      $secondName,
      $firstLastName,
      $secondLastName,
      Date::fromTimestamp($birthDate),
      Gender::from($gender),
      $idCard,
      $this->userRepository->getById($registeredById)
    );

    $doctor->setId($id)->setRegisteredDate(parent::parseDateTime($registeredDate));

    return $doctor;
  }
}
