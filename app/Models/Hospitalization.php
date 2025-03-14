<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\DepartureStatus;
use DateTimeInterface;

final class Hospitalization extends Model {
  function __construct(
    public readonly Patient $patient,
    public readonly Doctor $doctor,
    public readonly string $admissionDepartment,
    public readonly DateTimeInterface $admissionDate,
    public ?DateTimeInterface $departureDate = null,
    public ?DepartureStatus $departureStatus = null,
    public ?string $diagnoses = null
  ) {
  }

  function isFinished(): bool {
    return !!$this->departureDate;
  }
}
