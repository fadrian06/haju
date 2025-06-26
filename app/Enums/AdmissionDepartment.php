<?php



namespace HAJU\Enums;

enum AdmissionDepartment: string
{
  case Emergency = 'Emergencia';
  case Pediatrics = 'Pediatría';
  case Surgery = 'Cirugía';
  case Gynecology = 'Ginecología';
  case Obstetrics = 'Obstetricia';
  case Traumatology = 'Traumatología';
  case Other = 'Otro';

  /** @return self[] */
  public static function sortedCases(): array
  {
    $cases = self::cases();
    usort($cases, fn(self $a, self $b) => strnatcmp($a->value, $b->value));

    return $cases;
  }
}
