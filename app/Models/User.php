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
    public readonly string $firstName,
    public readonly string $lastName,
    public readonly Date $birthDate,
    public readonly Gender $gender,
    public readonly Role $role,
    public ?ProfessionPrefix $prefix,
    public readonly int $idCard,
    string $password,
    public readonly ?Phone $phone = null,
    public readonly ?Email $email = null,
    public readonly ?string $address = null,
    public readonly ?Url $avatar = null,
    public readonly ?DateTime $registered = null
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
