<?php

declare(strict_types=1);

namespace HAJU\Models;

use HAJU\Models\Contracts\Activable;
use HAJU\Models\Contracts\Person;
use HAJU\Models\Helpers\HasActiveStatus;
use HAJU\ValueObjects\AdultBirthDate;
use HAJU\Enums\Appointment;
use HAJU\ValueObjects\Exceptions\InvalidDateException;
use HAJU\ValueObjects\Exceptions\InvalidPhoneException;
use HAJU\Enums\Gender;
use HAJU\Enums\InstructionLevel;
use HAJU\ValueObjects\Phone;
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
final class User extends Person implements Activable
{
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
  public function __construct(
    string $firstName,
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    AdultBirthDate $birthDate,
    Gender $gender,
    public readonly Appointment $appointment,
    public InstructionLevel $instructionLevel,
    int $idCard,
    string $password,
    public Phone $phone,
    public Email $email,
    string $address,
    public string|Url $profileImagePath,
    bool $isActive = true,
    public ?self $registeredBy = null
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

  public function setPassword(string $password): static
  {
    if (!$password) {
      throw new InvalidArgumentException('La contraseña es requerida');
    }

    $this->password = str_contains($password, '$2y$10')
      ? $password
      : password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 10
      ]);

    return $this;
  }

  public function setAddress(string $address): static
  {
    if (!$address) {
      throw new InvalidArgumentException('La dirección es requerida');
    }

    $this->address = $address;

    return $this;
  }

  public function checkPassword(string $raw): bool
  {
    return password_verify($raw, $this->password);
  }

  public function ensureThatIsActive(): static
  {
    if (!$this->isActive) {
      throw new Error('Este usuario se encuentra desactivado');
    }

    return $this;
  }

  public function getProfileImageRelPath(): string
  {
    return $this->profileImagePath instanceof Url
      ? mb_substr($this->profileImagePath->asString(), strpos($this->profileImagePath->asString(), 'assets'))
      : $this->profileImagePath;
  }

  public function ensureHasActiveDepartments(): static
  {
    foreach ($this->departments as $department) {
      if ($department->isActive()) {
        return $this;
      }
    }

    throw new Error('Este usuario no tiene departamentos asignados, o están inhabilitados');
  }

  public function getParsedAppointment(): string
  {
    return $this->appointment->getParsed($this->gender);
  }

  public function assignDepartments(Department ...$departments): self
  {
    $this->departments = $departments;

    return $this;
  }

  public function hasDepartments(): bool
  {
    return $this->departments !== [];
  }

  public function hasDepartment(string|Department $department): bool
  {
    if (is_string($department)) {
      foreach ($this->departments as $savedDepartment) {
        if ($savedDepartment->name === $department) {
          return true;
        }
      }

      return false;
    }

    foreach ($this->departments as $userDepartment) {
      if ($userDepartment->name === $department->name) {
        return true;
      }
    }

    return false;
  }

  /** @return Generator<int, Department> */
  public function getDepartment(): Generator
  {
    foreach ($this->departments as $index => $department) {
      yield $index => $department;
    }
  }

  public function __get(string $property): null|int|string
  {
    return match ($property) {
      'password' => $this->password,
      'address' => $this->address,
      default => parent::__get($property)
    };
  }

  public function jsonSerialize(): array
  {
    return parent::jsonSerialize() + [
      'isDirector' => $this->appointment->isDirector(),
    ];
  }
}
