<?php

declare(strict_types=1);

namespace App\OldModels;

use App\OldModels\Contracts\Model;
use App\ValueObjects\ConsultationType;

/**
 * @deprecated
 */
final class Consultation extends Model {
  public function __construct(
    public readonly ConsultationType $type,
    public readonly ConsultationCause $cause,
    public readonly Department $department,
    public readonly Doctor $doctor,
    public readonly Patient $patient,
  ) {
  }

  public function isFirstTime(): bool {
    return $this->type === ConsultationType::FirstTime;
  }

  public function jsonSerialize(): array {
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
