<?php

namespace HAJU\Models;

use HAJU\Models\Contracts\Model;
use HAJU\Enums\ConsultationType;

final class Consultation extends Model
{
  public function __construct(
    public readonly ConsultationType $type,
    public readonly ConsultationCause $cause,
    public readonly Department $department,
    public readonly Doctor $doctor,
    public readonly Patient $patient,
  ) {
  }

  public function isFirstTime(): bool
  {
    return $this->type === ConsultationType::FirstTime;
  }

  public function jsonSerialize(): array
  {
    return parent::jsonSerialize() + [
      'type' => [
        'letter' => $this->type->value,
        'description' => $this->type->getDescription(),
      ],
      'cause' => $this->cause,
      'doctor' => $this->doctor,
      'patient' => $this->patient,
    ];
  }
}
