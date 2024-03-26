<?php

namespace App\Models;

use App\Models\Exceptions\InvalidDateException;
use App\Models\Exceptions\InvalidPhoneException;
use Generator;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

class User extends Model {
  /** @var array<int, Department> */
  private array $departments = [];

  private string $password;

  /**
   * @throws InvalidPhoneException
   * @throws InvalidEmailException
   * @throws InvalidUrlException
   * @throws InvalidDateException
   */
  function __construct(
    public string $firstName,
    public string $secondName,
    public string $firstLastName,
    public string $secondLastName,
    public Date $birthDate,
    public Gender $gender,
    public readonly Appointment $appointment,
    public InstructionLevel $instructionLevel,
    public int $idCard,
    string $password,
    public Phone $phone,
    public Email $email,
    public string $address,
    public string|Url $profileImagePath,
    public bool $isActive = true
  ) {
    $this->setPassword($password);
  }

  function getPassword(): string {
    return $this->password;
  }

  function setPassword(string $password): self {
    $this->password = str_contains($password, '$2y$10')
      ? $password
      : password_hash($password, PASSWORD_DEFAULT);

    return $this;
  }

  function checkPassword(string $raw): bool {
    return password_verify($raw, $this->password);
  }

  function getFullName(): string {
    return "{$this->firstName} {$this->firstLastName}";
  }

  function getParsedRole(): string {
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
    return array_search($department, $this->departments) !== false;
  }

  /** @return Generator<int, Department> */
  function getDepartment(): Generator {
    foreach ($this->departments as $index => $department) {
      yield $index => $department;
    }
  }
}
