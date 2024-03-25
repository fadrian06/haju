<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Appointment;
use App\Models\Date;
use App\Models\Gender;
use App\Models\InstructionLevel;
use App\Models\Phone;
use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use PDO;
use PDOException;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;

class PDOUserRepository extends PDORepository implements UserRepository {
  private const FIELDS = <<<SQL_FIELDS
  users.id as id, first_name as firstName, second_name as secondName,
  first_last_name as firstLastName, second_last_name as secondLastName,
  birth_date as birthDateTimestamp, gender, appointments.name as appointment,
  instruction_levels.abbreviation as instructionAbbreviation, id_card as idCard,
  password, phone, email, address, profile_image_path as profileImagePath,
  users.registered_date as registeredDateTime, is_active as isActive
  SQL_FIELDS;

  private const TABLE = 'users';

  function __construct(
    Connection $connection,
    readonly private PDODepartmentRepository $departmentRepository
  ) {
    parent::__construct($connection);
  }

  function getAll(User ...$exclude): array {
    $ids = array_map(function (User $user): int {
      return $user->getId();
    }, $exclude);

    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s JOIN appointments JOIN instruction_levels
        ON users.appointment_id = appointments.id
        AND users.instruction_level_id = instruction_levels.id %s',
        self::FIELDS,
        self::TABLE,
        $ids !== []
          ? sprintf('WHERE id NOT IN (%s)', join(', ', $ids))
          : ''
      ))->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  function getByIdCard(int $idCard): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id_card = ?', self::FIELDS, self::TABLE));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper'])[0] ?? null;
  }

  function getById(int $id): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::TABLE));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper'])[0] ?? null;
  }

  function save(User $user): void {
    try {
      if ($user->getId()) {
        $this->assignDepartments($user);
        $this->update($user);

        return;
      }

      $query = sprintf(
        <<<SQL
          INSERT INTO %s (
            first_name, second_name, first_last_name, second_last_name,
            birth_date, gender, appointment_id, instruction_level_id, id_card,
            password, phone, email, address, profile_image_path, registered_date
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::TABLE
      );

      $datetime = date('Y-m-d H:i:s');

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $user->firstName,
          $user->secondName,
          $user->firstLastName,
          $user->secondLastName,
          $user->birthDate->timestamp,
          $user->gender->value,
          $user->appointment->getId(),
          $user->instructionLevel->getId(),
          $user->idCard,
          $user->getPassword(),
          $user->phone,
          $user->email->asString(),
          $user->address,
          $user->profileImagePath->asString(),
          $datetime
        ]);

      $user->setId($this->connection->instance()->lastInsertId())
        ->setRegisteredDate(self::parseDateTime($datetime));

      $this->assignDepartments($user);
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("ID card \"{$user->idCard}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.first_name, users.last_name')) {
        throw new DuplicatedNamesException("User \"{$user->getFullName()}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.phone')) {
        throw new DuplicatedPhonesException("Phone \"{$user->phone}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.email')) {
        throw new DuplicatedEmailsException("Email \"{$user->email->asString()}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.avatar')) {
        throw new DuplicatedAvatarsException("Avatar \"{$user->profileImagePath->asString()}\" already exists");
      }

      throw $exception;
    }
  }

  private function assignDepartments(User $user): void {
    $this->ensureIsConnected()
      ->prepare('DELETE FROM department_assignments WHERE user_id = ?')
      ->execute([$user->getId()]);

    $values = [];

    foreach ($user->getDepartment() as $department) {
      $values[] = "({$user->getId()}, {$department->getId()})";
    }

    if ($values) {
      $sql = sprintf(
        'INSERT INTO department_assignments (user_id, department_id) VALUES %s',
        join(', ', $values)
      );

      $this->ensureIsConnected()
        ->query($sql);
    }
  }

  private function update(User $user): void {
    $sql = sprintf(
      <<<SQL
        UPDATE %s SET first_name = ?, second_name = ?, first_last_name = ?,
        second_last_name = ?, birth_date = ?, gender = ?,
        phone = ?, email = ?, address = ?, password = ?,
        is_active = ? WHERE id = ?
      SQL,
      self::TABLE
    );

    $this->ensureIsConnected()
      ->prepare($sql)
      ->execute([
        $user->firstName,
        $user->secondName,
        $user->firstLastName,
        $user->secondLastName,
        $user->birthDate->timestamp,
        $user->gender->value,
        $user->phone,
        $user->email->asString(),
        $user->address,
        $user->getPassword(),
        $user->isActive,
        $user->getId()
      ]);
  }

  private function setDepartments(User $user): void {
    if ($user->appointment === Appointment::Director) {
      $user->assignDepartments(...$this->departmentRepository->getAll());

      return;
    }

    $join = <<<SQL
      SELECT id, name, d.registered as registered, is_active as isActive
      FROM department_assignments a
      JOIN departments d
      ON a.department_id = d.id
      WHERE user_id = ?
    SQL;

    $stmt = $this->ensureIsConnected()->prepare($join);
    $stmt->execute([$user->getId()]);

    $departments = $stmt->fetchAll(
      PDO::FETCH_FUNC,
      [$this->departmentRepository, 'mapper']
    );

    $user->assignDepartments(...$departments);
  }

  private function mapper(
    int $id,
    string $firstName,
    string $secondName,
    string $firstLastName,
    string $secondLastName,
    string $birthDateTimestamp,
    string $gender,
    string $appointment,
    string $instructionAbbreviation,
    int $idCard,
    string $password,
    string $phone,
    string $email,
    string $address,
    string $profileImagePath,
    string $registeredDateTime,
    bool $isActive
  ): User {
    $user = new User(
      $firstName,
      $secondName,
      $firstLastName,
      $secondLastName,
      Date::fromTimestamp($birthDateTimestamp),
      Gender::from($gender),
      Appointment::from($appointment),
      InstructionLevel::from($instructionAbbreviation),
      $idCard,
      $password,
      new Phone($phone),
      new Email($email),
      $address,
      new Url($profileImagePath),
      $isActive
    );

    $user->setId($id)->setRegisteredDate(self::parseDateTime($registeredDateTime));
    $this->setDepartments($user);

    return $user;
  }
}
