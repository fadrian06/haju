<?php

namespace App\Models;

use App\Models\Contracts\Activable;
use App\Models\Contracts\Person;
use App\Models\Exceptions\InvalidDateException;
use App\Models\Exceptions\InvalidPhoneException;
use App\Models\Helpers\HasActiveStatus;
use Error;
use Generator;
use InvalidArgumentException;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

/**
 * @property-read string $password
 * @property-read string $address
 */
final class User extends Person implements Activable {
  use HasActiveStatus;

  /** @var array<int, Department> */
  private array $departments = [];
  private string $password;
  private string $address;

  /**
   * @throws InvalidPhoneException
   * @throws InvalidEmailException
   * @throws InvalidUrlException
   * @throws InvalidDateException
   */
  function __construct(
    string $firstName,
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    Date|\App\ValueObjects\Date $birthDate,
    Gender|\App\ValueObjects\Gender $gender,
    public readonly Appointment|\App\ValueObjects\Appointment $appointment,
    public InstructionLevel|\App\ValueObjects\InstructionLevel $instructionLevel,
    int $idCard,
    string $password,
    public Phone|\App\ValueObjects\Phone $phone,
    public Email $email,
    string $address,
    public string|Url $profileImagePath,
    bool $isActive = true
  ) {
    parent::__construct(
      $firstName,
      $secondName,
      $firstLastName,
      $secondLastName,
      $birthDate,
      $gender,
      $idCard
    );

    $this->isActive = $isActive;
    $this->setPassword($password)->setAddress($address);
  }

  function setPassword(string $password): static {
    if (!$password) {
      throw new InvalidArgumentException('La contraseña es requerida');
    }

    $this->password = str_contains($password, '$2y$10')
      ? $password
      : password_hash($password, PASSWORD_DEFAULT);

    return $this;
  }

  function setAddress(string $address): static {
    if (!$address) {
      throw new InvalidArgumentException('La dirección es requerida');
    }

    $this->address = $address;

    return $this;
  }

  /** @deprecated */
  function getPassword(): string {
    return $this->password;
  }

  /** @deprecated */
  function getAddress(): string {
    return $this->address;
  }

  function checkPassword(string $raw): bool {
    return password_verify($raw, $this->password);
  }

  function ensureThatIsActive(): static {
    if (!$this->isActive) {
      throw new Error('Este usuario se encuentra desactivado');
    }

    return $this;
  }

  /** @deprecated Use `$user->toggleStatus()` */
  function toggleActiveStatus(): static {
    $this->isActive = !$this->isActive;

    return $this;
  }

  function getProfileImageRelPath(): string {
    return $this->profileImagePath instanceof Url
      ? mb_substr($this->profileImagePath->asString(), strpos($this->profileImagePath->asString(), 'assets'))
      : $this->profileImagePath;
  }

  function ensureHasActiveDepartments(): static {
    foreach ($this->departments as $department) {
      if ($department->isActive()) {
        return $this;
      }
    }

    throw new Error('Este usuario no tiene departamentos asignados, o están inhabilitados');
  }

  function getParsedAppointment(): string {
    return $this->appointment->getParsed($this->gender);
  }

  function assignDepartments(Department ...$departments): self {
    $this->departments = $departments;

    return $this;
  }

  function hasDepartments(): bool {
    return $this->departments !== [];
  }

  function hasDepartment(string|Department $department): bool {
    if (is_string($department)) {
      foreach ($this->departments as $savedDepartment) {
        if ($savedDepartment->getName() === $department) {
          return true;
        }
      }

      return false;
    }

    foreach ($this->departments as $userDepartment) {
      if ($userDepartment->getName() === $department->getName()) {
        return true;
      }
    }

    return false;
  }

  /** @return Generator<int, Department> */
  function getDepartment(): Generator {
    foreach ($this->departments as $index => $department) {
      yield $index => $department;
    }
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'password' => $this->password,
      'address' => $this->address,
      default => parent::__get($property)
    };
  }
}
