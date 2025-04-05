<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\ValueObjects\Date;
use App\ValueObjects\Exceptions\InvalidDateException;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\Gender;
use App\ValueObjects\IdCard;
use App\ValueObjects\Name;

/** @property-read int $idCard */
abstract class Person extends Model {
  private Name $firstName;
  private ?Name $secondName = null;
  private Name $firstLastName;
  private ?Name $secondLastName = null;
  private IdCard $idCard;

  /**
   * @throws InvalidDateException
   * @throws InvalidNameException
   */
  public function __construct(
    string $firstName,
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    public Date $birthDate,
    public Gender $gender,
    int $idCard,
  ) {
    $this->setFirstName($firstName)
      ->setFirstLastName($firstLastName)
      ->setIdCard($idCard);

    if ($secondName !== null) {
      $this->setSecondName($secondName);
    }

    if ($secondLastName !== null) {
      $this->setSecondLastName($secondLastName);
    }
  }

  final public function setIdCard(int $idCard): static {
    $this->idCard = new IdCard($idCard);

    return $this;
  }

  /** @throws InvalidNameException */
  final public function setFirstName(string $firstName): static {
    $this->firstName = new Name($firstName, 'Primer nombre');

    return $this;
  }

  /** @throws InvalidNameException */
  final public function setSecondName(?string $secondName): static {
    $this->secondName = $secondName !== null
      ? new Name($secondName, 'Segundo nombre')
      : null;

    return $this;
  }

  /** @throws InvalidNameException */
  final public function setFirstLastName(string $firstLastName): static {
    $this->firstLastName = new Name($firstLastName, 'Primer apellido');

    return $this;
  }

  /** @throws InvalidNameException */
  final public function setSecondLastName(?string $secondLastName): static {
    $this->secondLastName = $secondLastName !== null
      ? new Name($secondLastName, 'Segundo apellido')
      : null;

    return $this;
  }

  /** @throws InvalidNameException */
  public function setFullName(string $fullName): self {
    $fullName = explode(' ', $fullName);

    $firstName = $fullName[0] ?? null;
    $secondName = $fullName[1] ?? null;
    $firstLastName = $fullName[2] ?? null;
    $secondLastName = $fullName[3] ?? null;

    $this->setFirstName($firstName);

    if ($firstLastName === null) {
      return $this->setSecondName(null)
        ->setFirstLastName($secondName ?? '')
        ->setSecondLastName(null);
    }

    return $this->setSecondName($secondName)
      ->setFirstLastName($firstLastName)
      ->setSecondLastName($secondLastName);
  }

  final public function getFullName(): string {
    $fullName = $this->firstName;
    $fullName .= ($this->secondName !== null) ? " {$this->secondName}" : '';

    return $fullName . " $this->firstLastName";
  }

  public function __get(string $property): null|int|string {
    return match ($property) {
      'firstName' => $this->firstName->__toString(),
      'secondName' => $this->secondName?->__toString(),
      'firstLastName' => $this->firstLastName->__toString(),
      'secondLastName' => $this->secondLastName?->__toString(),
      'idCard' => $this->idCard->value,
      default => parent::__get($property)
    };
  }

  public function jsonSerialize(): array {
    return parent::jsonSerialize() + [
      'firstName' => $this->firstName->__toString(),
      'secondName' => $this->secondName?->__toString(),
      'firstLastName' => $this->firstLastName->__toString(),
      'secondLastName' => $this->secondLastName?->__toString(),
      'idCard' => $this->idCard->value,
      'fullName' => $this->getFullName(),
      'gender' => $this->gender->value,
    ];
  }
}
