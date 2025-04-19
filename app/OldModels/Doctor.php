<?php

declare(strict_types=1);

namespace App\OldModels;

use App\OldModels\Contracts\Person;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use Generator;

/**
 * @deprecated
 */
final class Doctor extends Person
{
  /**
   * @var Consultation[]
   */
  private array $consultations = [];

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

  public function canBeEditedBy(User $user): bool
  {
    if ($user->appointment->isDirector()) {
      return true;
    }

    return $this->registeredBy->isEqualTo($user);
  }

  /**
   * @return Generator<int, Consultation>
   */
  public function getConsultation(): Generator
  {
    foreach ($this->consultations as $consultation) {
      yield $consultation;
    }
  }

  public function setConsultations(Consultation ...$consultations): self
  {
    $this->consultations = $consultations;

    return $this;
  }
}
