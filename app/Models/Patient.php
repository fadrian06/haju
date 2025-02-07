<?php

namespace App\Models;

use App\Models\Contracts\Person;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use Generator;

final class Patient extends Person {
  /** @var array<int, Consultation> */
  private array $consultations = [];

  /** @var array<int, Hospitalization> */
  private array $hospitalizations = [];

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
    // TODO: que el coordinador del secretario que registró al paciente también pueda editarlo
    return $user->appointment->isDirector() || $this->registeredBy->isEqualTo($user);
  }

  /** @return Generator<int, Consultation> */
  function getConsultation(): Generator {
    foreach ($this->consultations as $consultation) {
      yield $consultation;
    }
  }

  /** @return Generator<int, Hospitalization> */
  function getHospitalization(): Generator {
    foreach ($this->hospitalizations as $hospitalization) {
      yield $hospitalization;
    }
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

  function setHospitalization(Hospitalization ...$hospitalizations): self {
    $this->hospitalizations = $hospitalizations;

    return $this;
  }

  /** @return Hospitalization[] */
  function getHospitalizations(): array {
    return $this->hospitalizations;
  }
}
