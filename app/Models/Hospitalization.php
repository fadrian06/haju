<?php

declare(strict_types=1);

namespace HAJU\Models;

use HAJU\Models\Contracts\Model;
use HAJU\Enums\DepartureStatus;
use DateTimeInterface;

final class Hospitalization extends Model {
  public function __construct(
    public readonly Patient $patient,
    public Doctor $doctor,
    public string $admissionDepartment,
    public DateTimeInterface $admissionDate,
    public ?DateTimeInterface $departureDate = null,
    public ?DepartureStatus $departureStatus = null,
    public ?string $diagnoses = null
  ) {
  }

  public function setAdmissionDate(DateTimeInterface $admissionDate): self {
    $this->admissionDate = $admissionDate;

    return $this;
  }

  public function setDepartureDate(DateTimeInterface $departureDate): self {
    $this->departureDate = $departureDate;

    return $this;
  }

  public function setDepartureStatus(DepartureStatus $departureStatus): self {
    $this->departureStatus = $departureStatus;

    return $this;
  }

  public function isFinished(): bool {
    return (bool) $this->departureDate;
  }

  public function jsonSerialize(): array {
    return parent::jsonSerialize() + [
      'isFinished' => $this->isFinished(),
      'admissionDateImperialFormat' => $this->admissionDate->format('Y-m-d'),
      'admissionDate' => $this->admissionDate->format('d/m/Y'),
      'admissionDepartment' => $this->admissionDepartment,
      'patient' => $this->patient,
      'doctor' => $this->doctor,
    ];
  }
}
