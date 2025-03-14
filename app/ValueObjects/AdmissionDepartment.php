<?php

declare(strict_types=1);

namespace App\ValueObjects;

enum AdmissionDepartment: string {
  case Emergency = 'Emergencia';
  case Pediatrics = 'Pediatría';
  case Surgery = 'Cirujía';
  case Other = 'Otro';
}
