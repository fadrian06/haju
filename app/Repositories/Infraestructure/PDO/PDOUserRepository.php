<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Appointment;
use App\Models\Date;
use App\Models\Gender;
use App\Models\InstructionLevel;
use App\Models\Phone;
use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use App\Repositories\Exceptions\DuplicatedProfileImagesException;
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

  private const JOINS = <<<SQL_JOINS
    JOIN appointments JOIN instruction_levels
    ON users.appointment_id = appointments.id
    AND users.instruction_level_id = instruction_levels.id
  SQL_JOINS;

  function __construct(
    Connection $connection,
    string $baseUrl,
    private readonly PDODepartmentRepository $departmentRepository
  ) {
    parent::__construct($connection, $baseUrl);
  }

  function getAll(User ...$exclude): array {
    $ids = array_map(function (User $user): int {
      return $user->getId();
    }, $exclude);

    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s %s %s',
        self::FIELDS,
        self::TABLE,
        self::JOINS,
        $ids !== []
          ? sprintf('WHERE users.id NOT IN (%s)', join(', ', $ids))
          : ''
      ))->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  function getByIdCard(int $idCard): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf(
        'SELECT %s FROM %s %s WHERE id_card = ?',
        self::FIELDS,
        self::TABLE,
        self::JOINS
      ));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper'])[0] ?? null;
  }

  function getById(int $id): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf(
        'SELECT %s FROM %s %s WHERE users.id = ?',
        self::FIELDS,
        self::TABLE,
        self::JOINS
      ));

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
          $user->getFirstName(),
          $user->getSecondName(),
          $user->getFirstLastName(),
          $user->getSecondLastName(),
          $user->birthDate->timestamp,
          $user->gender->value,
          $user->appointment->getId(),
          $user->instructionLevel->getId(),
          $user->getIdCard(),
          $user->getPassword(),
          $user->phone,
          $user->email->asString(),
          $user->getAddress(),
          $user->profileImagePath,
          $datetime
        ]);

      $user->setId($this->connection->instance()->lastInsertId())
        ->setRegisteredDate(self::parseDateTime($datetime));

      $this->assignDepartments($user);
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("Cédula \"{$user->getIdCard()}\" ya existe");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.first_name, users.last_name')) {
        throw new DuplicatedNamesException("Usuario \"{$user->getFullName()}\" ya existe");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.phone')) {
        throw new DuplicatedPhonesException("Teléfono \"{$user->phone}\" ya existe");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.email')) {
        throw new DuplicatedEmailsException("Correo \"{$user->email->asString()}\" ya existe");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.avatar')) {
        throw new DuplicatedProfileImagesException("Foto de perfil \"{$user->profileImagePath->asString()}\" ya existe");
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
        is_active = ?, id_card = ?, profile_image_path = ? WHERE id = ?
      SQL,
      self::TABLE
    );

    $this->ensureIsConnected()
      ->prepare($sql)
      ->execute([
        $user->getFirstName(),
        $user->getSecondName(),
        $user->getFirstLastName(),
        $user->getSecondLastName(),
        $user->birthDate->timestamp,
        $user->gender->value,
        $user->phone,
        $user->email->asString(),
        $user->getAddress(),
        $user->getPassword(),
        $user->getActiveStatus(),
        $user->getIdCard(),
        is_string($user->profileImagePath) ? $user->profileImagePath : $user->getProfileImageRelPath(),
        $user->getId()
      ]);
  }

  private function setDepartments(User $user): void {
    if ($user->appointment === Appointment::Director) {
      $user->assignDepartments(...$this->departmentRepository->getAll());

      return;
    }

    $join = <<<SQL
      SELECT id, name, departments.registered as registered, is_active as isActive
      FROM department_assignments
      JOIN departments
      ON department_assignments.department_id = departments.id
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
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    int $birthDateTimestamp,
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
      new Url("{$this->baseUrl}/" . urlencode($profileImagePath)),
      $isActive
    );

    $user->setId($id)->setRegisteredDate(self::parseDateTime($registeredDateTime));
    $this->setDepartments($user);

    return $user;
  }
}
