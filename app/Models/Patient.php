<?php

namespace App\Models;

use App\Models\Contracts\Person;
use App\ValueObjects\Date;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\Gender;
use Generator;

final class Patient extends Person {
  /** @var array<int, Consultation> */
  private array $consultations = [];

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

  /** @return Generator<int, Consultation> */
  function getConsultation(): Generator {
    foreach ($this->consultations as $consultation) {
      yield $consultation;
    }
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

  function setConsultations(Consultation ...$consultations): self {
    $this->consultations = $consultations;

    return $this;
  }

  function getCauseById(int $causeId): ?ConsultationCause {
    foreach ($this->consultations as $consultation) {
      if ($consultation->cause->id === $causeId) {
        return $consultation->cause;
      }
    }

    return null;
  }
}