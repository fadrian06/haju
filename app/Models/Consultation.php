<?php

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\ConsultationType;

final class Consultation extends Model {
  function __construct(
    public readonly ConsultationType $type,
    public readonly ConsultationCause $cause,
    public readonly Department $department
  ) {
  }

  function isFirstTime(): bool {
    return $this->type === ConsultationType::FirstTime;
  }
}
