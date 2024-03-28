<?php

namespace App\Models;

use App\Models\Exceptions\InvalidDateException;
use App\Models\Exceptions\InvalidPhoneException;
use App\ValueObjects\Name;
use Error;
use Generator;
use InvalidArgumentException;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

class User extends Model {
  /** @var array<int, Department> */
  private array $departments = [];

  private Name $firstName;
  private ?Name $secondName = null;
  private Name $firstLastName;
  private ?Name $secondLastName = null;
  private string $password;
  private int $idCard;
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
    public Date $birthDate,
    public Gender $gender,
    public readonly Appointment $appointment,
    public InstructionLevel $instructionLevel,
    int $idCard,
    string $password,
    public Phone $phone,
    public Email $email,
    string $address,
    public string|Url $profileImagePath,
    private bool $isActive = true
  ) {
    $this->setFirstName($firstName)
      ->setFirstLastName($firstLastName)
      ->setIdCard($idCard)
      ->setPassword($password)
      ->setAddress($address);

    $secondName && $this->setSecondName($secondName);
    $secondLastName && $this->setSecondLastName($secondLastName);
  }

  function setFirstName(string $firstName): static {
    $this->firstName = new Name($firstName, 'Primer nombre');

    return $this;
  }

  function setSecondName(string $secondName): static {
    $this->secondName = new Name($secondName, 'Segundo nombre');

    return $this;
  }

  function setFirstLastName(string $firstLastName): static {
    $this->firstLastName = new Name($firstLastName, 'Primer apellido');

    return $this;
  }

  function setSecondLastName(string $secondLastName): static {
    $this->secondLastName = new Name($secondLastName, 'Segundo apellido');

    return $this;
  }

  function setIdCard(int $idCard): static {
    if ($idCard < 1) {
      throw new InvalidArgumentException("La cédula es requerida y válida");
    }

    $this->idCard = $idCard;

    return $this;
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

  function getFirstName(): string {
    return $this->firstName;
  }

  function getSecondName(): ?string {
    return $this->secondName;
  }

  function getFirstLastName(): string {
    return $this->firstLastName;
  }

  function getSecondLastName(): ?string {
    return $this->secondLastName;
  }

  function getPassword(): string {
    return $this->password;
  }

  function getIdCard(): int {
    return $this->idCard;
  }

  function getAddress(): string {
    return $this->address;
  }

  function checkPassword(string $raw): bool {
    return password_verify($raw, $this->password);
  }

  function toggleActiveStatus(): static {
    $this->isActive = !$this->isActive;

    return $this;
  }

  function getFullName(): string {
    $fullName = $this->firstName;
    $fullName .= $this->secondName ? " {$this->secondName}" : '';
    $fullName .= " $this->firstLastName";
    $fullName .= $this->secondLastName ? " {$this->secondLastName}" : '';

    return $fullName;
  }

  function getActiveStatus(): bool {
    return $this->isActive;
  }

  function getProfileImageRelPath(): string {
    return $this->profileImagePath instanceof Url
      ? mb_substr($this->profileImagePath->asString(), strpos($this->profileImagePath->asString(), 'assets'))
      : $this->profileImagePath;
  }

  function ensureThatIsActive(): static {
    if (!$this->isActive) {
      throw new Error('Este usuario se encuentra desactivado');
    }

    return $this;
  }

  function ensureHasActiveDepartments(): static {
    foreach ($this->departments as $department) {
      if ($department->getActiveStatus()) {
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

  function hasDepartment(Department $department): bool {
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
}
