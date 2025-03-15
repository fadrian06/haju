<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\ConsultationType;

final class Consultation extends Model {
  public function __construct(
    public readonly ConsultationType $type,
    public readonly ConsultationCause $cause,
    public readonly Department $department,
    public readonly Doctor $doctor
  ) {
  }

  public function isFirstTime(): bool {
    return $this->type === ConsultationType::FirstTime;
  }
}
