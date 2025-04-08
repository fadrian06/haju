<?php

declare(strict_types=1);

namespace App\Repositories\Infraestructure\PDO;

use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use App\Repositories\Exceptions\DuplicatedProfileImagesException;
use App\ValueObjects\AdultBirthDate;
use App\ValueObjects\Appointment;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;
use App\ValueObjects\Phone;
use PDO;
use PDOException;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;

final class PDOUserRepository extends PDORepository implements UserRepository {
  private const FIELDS = <<<sql
  users.id as id, first_name as firstName, second_name as secondName,
  first_last_name as firstLastName, second_last_name as secondLastName,
  birth_date as birthDateTimestamp, gender, appointments.name as appointment,
  instruction_levels.abbreviation as instructionAbbreviation, id_card as idCard,
  password, phone, email, address, profile_image_path as profileImagePath,
  users.registered_date as registeredDateTime, is_active as isActive,
  registered_by_id as registeredById
  sql;

  private const JOINS = <<<SQL_JOINS
    JOIN appointments JOIN instruction_levels
    ON users.appointment_id = appointments.id
    AND users.instruction_level_id = instruction_levels.id
  SQL_JOINS;

  public function __construct(
    PDO $pdo,
    string $baseUrl,
    private readonly DepartmentRepository $departmentRepository,
  ) {
    parent::__construct($pdo, $baseUrl);
  }

  protected static function getTable(): string {
    return 'users';
  }

  public function getAll(User ...$exclude): array {
    $ids = array_map(fn(User $user): int => $user->id, $exclude);

    return $this->ensureIsConnected()
      ->query(sprintf(
        'SELECT %s FROM %s %s %s',
        self::FIELDS,
        self::getTable(),
        self::JOINS,
        $ids !== []
          ? sprintf('WHERE users.id NOT IN (%s)', implode(', ', $ids))
          : ''
      ))->fetchAll(PDO::FETCH_FUNC, $this->mapper(...));
  }

  public function getByIdCard(int $idCard): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf(
        'SELECT %s FROM %s %s WHERE id_card = ?',
        self::FIELDS,
        self::getTable(),
        self::JOINS
      ));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function getById(int $id): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf(
        'SELECT %s FROM %s %s WHERE users.id = ?',
        self::FIELDS,
        self::getTable(),
        self::JOINS
      ));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...))[0] ?? null;
  }

  public function save(User $user): void {
    try {
      if ($user->id) {
        $this->assignDepartments($user)->update($user);

        return;
      }

      $query = sprintf(
        <<<SQL
          INSERT INTO %s (
            first_name, second_name, first_last_name, second_last_name,
            birth_date, gender, appointment_id, instruction_level_id, id_card,
            password, phone, email, address, profile_image_path, registered_date,
            registered_by_id
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::getTable()
      );

      $datetime = parent::getCurrentDatetime();

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
          $user->password,
          $user->phone,
          $user->email->asString(),
          $user->address,
          $user->profileImagePath,
          $datetime,
          $user->registeredBy?->id
        ]);

      $user->setId((int) $this->pdo->lastInsertId())
        ->setRegisteredDate(self::parseDateTime($datetime));

      $this->assignDepartments($user);
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("Cédula \"{$user->idCard}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: users.first_name')) {
        throw new DuplicatedNamesException("Usuario \"{$user->getFullName()}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: users.phone')) {
        throw new DuplicatedPhonesException("Teléfono \"{$user->phone}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: users.email')) {
        throw new DuplicatedEmailsException("Correo \"{$user->email->asString()}\" ya existe");
      }

      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: users.avatar')) {
        throw new DuplicatedProfileImagesException("Foto de perfil \"{$user->profileImagePath->asString()}\" ya existe");
      }

      throw $exception;
    }
  }

  private function assignDepartments(User $user): self {
    $this->ensureIsConnected()
      ->prepare('DELETE FROM department_assignments WHERE user_id = ?')
      ->execute([$user->id]);

    $values = [];

    foreach ($user->getDepartment() as $department) {
      $values[] = "({$user->id}, {$department->id})";
    }

    if ($values) {
      $sql = sprintf(
        'INSERT INTO department_assignments (user_id, department_id) VALUES %s',
        implode(', ', $values)
      );

      $this->ensureIsConnected()
        ->query($sql);
    }

    return $this;
  }

  private function update(User $user): self {
    $sql = sprintf(
      <<<SQL
        UPDATE %s SET first_name = ?, second_name = ?, first_last_name = ?,
        second_last_name = ?, birth_date = ?, gender = ?,
        phone = ?, email = ?, address = ?, password = ?,
        is_active = ?, id_card = ?, profile_image_path = ? WHERE id = ?
      SQL,
      self::getTable()
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
        $user->password,
        $user->isActive(),
        $user->idCard,
        is_string($user->profileImagePath) ? $user->profileImagePath : $user->getProfileImageRelPath(),
        $user->id
      ]);

    return $this;
  }

  private function setDepartments(User $user): void {
    if ($user->appointment === Appointment::Director) {
      $user->assignDepartments(...$this->departmentRepository->getAll());

      return;
    }

    $join = <<<SQL
      SELECT departments.id, name, departments.registered_date as registeredDateTime,
      belongs_to_external_consultation as belongsToExternalConsultation,
      icon_file_path as iconFilePath, is_active as isActive
      FROM department_assignments
      JOIN departments
      ON department_assignments.department_id = departments.id
      WHERE user_id = ?
    SQL;

    $stmt = $this->ensureIsConnected()->prepare($join);
    $stmt->execute([$user->id]);

    $departments = $stmt->fetchAll(
      PDO::FETCH_FUNC,
      $this->departmentRepository->mapper(...)
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
    bool $isActive,
    ?int $registeredById
  ): User {
    $user = new User(
      $firstName,
      $secondName,
      $firstLastName,
      $secondLastName,
      AdultBirthDate::fromTimestamp($birthDateTimestamp),
      Gender::from($gender),
      Appointment::from($appointment),
      InstructionLevel::from($instructionAbbreviation),
      $idCard,
      $password,
      new Phone($phone),
      new Email($email),
      $address,
      new Url("{$this->baseUrl}/" . $profileImagePath),
      $isActive,
      $registeredById ? $this->getById($registeredById) : null
    );

    $user->setId($id)->setRegisteredDate(self::parseDateTime($registeredDateTime));
    $this->setDepartments($user);

    return $user;
  }
}
