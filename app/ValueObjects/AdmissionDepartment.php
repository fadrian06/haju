<?php

declare(strict_types=1);

namespace HAJU\ValueObjects;

enum AdmissionDepartment: string {
  case Emergency = 'Emergencia';
  case Pediatrics = 'Pediatría';
  case Surgery = 'Cirujía';
  case Other = 'Otro';
}
