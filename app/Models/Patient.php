<?php

declare(strict_types=1);

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

  public function __construct(
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

  public function isHospitalized(): bool {
    foreach ($this->hospitalizations as $hospitalization) {
      if (!$hospitalization->isFinished()) {
        return true;
      }
    }

    return false;
  }

  public function canBeDeleted(): bool {
    return !$this->hasConsultations() && !$this->hasHospitalizations();
  }

  public function hasHospitalizations(): bool {
    return $this->hospitalizations !== [];
  }

  public function hasConsultations(): bool {
    return $this->consultations !== [];
  }

  public function canBeEditedBy(User $user): bool {
    // TODO: que el coordinador del secretario que registró al paciente también pueda editarlo
    if ($user->appointment->isDirector()) {
      return true;
    }

    return $this->registeredBy->isEqualTo($user);
  }

  /** @return Generator<int, Consultation> */
  public function getConsultation(): Generator {
    foreach ($this->consultations as $consultation) {
      yield $consultation;
    }
  }

  /** @return Generator<int, Hospitalization> */
  public function getHospitalization(): Generator {
    foreach ($this->hospitalizations as $hospitalization) {
      yield $hospitalization;
    }
  }

  public function setConsultations(Consultation ...$consultations): self {
    $this->consultations = $consultations;

    return $this;
  }

  public function getCauseById(int $causeId): ?ConsultationCause {
    foreach ($this->consultations as $consultation) {
      if ($consultation->cause->id === $causeId) {
        return $consultation->cause;
      }
    }

    return null;
  }

  public function setHospitalization(Hospitalization ...$hospitalizations): self {
    $this->hospitalizations = $hospitalizations;

    return $this;
  }

  /** @return Hospitalization[] */
  public function getHospitalizations(): array {
    return $this->hospitalizations;
  }

  public function getHospitalizationById(int $hospitalizationId): ?Hospitalization {
    foreach ($this->hospitalizations as $hospitalization) {
      if ($hospitalization->id === $hospitalizationId) {
        return $hospitalization;
      }
    }

    return null;
  }

  public function jsonSerialize(): array {
    return parent::jsonSerialize() + [
      'consultations' => $this->consultations,
      'hospitalizations' => $this->hospitalizations,
      'registeredBy' => $this->registeredBy,
      'canBeDeleted' => $this->canBeDeleted(),
    ];
  }
}
