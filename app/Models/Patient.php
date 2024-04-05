<?php

namespace App\Models;

use App\Models\Contracts\Person;
use App\ValueObjects\Date;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\Gender;

final class Patient extends Person {
  function __construct(
    string $firstName,
    ?string $secondName,
    string $firstLastName,
    ?string $secondLastName,
    Date $birthDate,
    Gender $gender,
    int $idCard,
    public User $registeredBy
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
  }

  /** @throws InvalidNameException */
  function setFullName(string $fullName): self {
    @[$firstName, $secondName, $firstLastName, $secondLastName] = explode(' ', $fullName);

    $this->setFirstName($firstName);

    if (!$firstLastName) {
      return $this->setSecondName(null)
        ->setFirstLastName($secondName)
        ->setSecondLastName(null);
    }

    return $this->setSecondName($secondName)
      ->setFirstLastName($firstLastName)
      ->setSecondLastName($secondLastName);
  }
}
