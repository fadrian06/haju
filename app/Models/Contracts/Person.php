<?php

namespace App\Models\Contracts;

use App\Models\Exceptions\InvalidDateException;
use App\ValueObjects\Date;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\Gender;
use App\ValueObjects\Name;
use InvalidArgumentException;

/**
 * @property-read string $firstName
 * @property-read ?string $secondName
 * @property-read string $firstLastName
 * @property-read ?string $secondLastName
 * @property-read int $idCard
 */
abstract class Person extends Model {
  private Name $firstName;
  private ?Name $secondName = null;
  private Name $firstLastName;
  private ?Name $secondLastName = null;
  private int $idCard;

  /**
   * @throws InvalidDateException
   * @throws InvalidNameException
   */
  function __construct(
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

    $secondName && $this->setSecondName($secondName);
    $secondLastName && $this->setSecondLastName($secondLastName);
  }

  final function setIdCard(int $idCard): static {
    if ($idCard < 1) {
      throw new InvalidArgumentException('La cédula es requerida y válida');
    }

    $this->idCard = $idCard;

    return $this;
  }

  /** @throws InvalidNameException */
  final function setFirstName(string $firstName): static {
    $this->firstName = new Name($firstName, 'Primer nombre');

    return $this;
  }

  /** @throws InvalidNameException */
  final function setSecondName(?string $secondName): static {
    $this->secondName = $secondName !== null
      ? new Name($secondName, 'Segundo nombre')
      : null;

    return $this;
  }

  /** @throws InvalidNameException */
  final function setFirstLastName(string $firstLastName): static {
    $this->firstLastName = new Name($firstLastName, 'Primer apellido');

    return $this;
  }

  /** @throws InvalidNameException */
  final function setSecondLastName(?string $secondLastName): static {
    $this->secondLastName = $secondLastName !== null
      ? new Name($secondLastName, 'Segundo apellido')
      : null;

    return $this;
  }

  final function getFullName(): string {
    $fullName = $this->firstName;
    $fullName .= $this->secondName ? " {$this->secondName}" : '';
    $fullName .= " $this->firstLastName";
    $fullName .= $this->secondLastName ? " {$this->secondLastName}" : '';

    return $fullName;
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'firstName' => $this->firstName,
      'secondName' => $this->secondName,
      'firstLastName' => $this->firstLastName,
      'secondLastName' => $this->secondLastName,
      'idCard' => $this->idCard,
      default => parent::__get($property)
    };
  }
}