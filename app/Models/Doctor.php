<?php

namespace App\Models;

use App\Models\Contracts\Person;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use Generator;

final class Doctor extends Person {
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

  function canBeEditedBy(User $user): bool {
    return $user->appointment->isDirector() || $this->registeredBy->isEqualTo($user);
  }

  /** @return Generator<int, Consultation> */
  function getConsultation(): Generator {
    foreach ($this->consultations as $consultation) {
      yield $consultation;
    }
  }

  function setConsultations(Consultation ...$consultations): self {
    $this->consultations = $consultations;

    return $this;
  }
}
