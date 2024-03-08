<?php

namespace App\Models;

use App\Models\Exceptions\InvalidDateException;
use App\Models\Exceptions\InvalidPhoneException;
use DateTime;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

class User extends Model {
  private string $password;

  /**
   * @throws InvalidPhoneException
   * @throws InvalidEmailException
   * @throws InvalidUrlException
   * @throws InvalidDateException
   */
  function __construct(
    public string $firstName,
    public string $lastName,
    public Date $birthDate,
    public Gender $gender,
    public readonly Role $role,
    public ?ProfessionPrefix $prefix,
    public int $idCard,
    string $password,
    public ?Phone $phone = null,
    public ?Email $email = null,
    public ?string $address = null,
    public ?Url $avatar = null,
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
    return "{$this->firstName} {$this->lastName}";
  }

  function getParsedRole(): string {
    return $this->role->getParsed($this->gender);
  }
}
