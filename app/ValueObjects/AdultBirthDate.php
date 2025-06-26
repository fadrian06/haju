<?php

namespace HAJU\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidDateException;
use DateInterval;
use DateTimeImmutable;

final readonly class AdultBirthDate extends Date
{
  protected function validate(int $day, int $month, int $year): void
  {
    parent::validate($day, $month, $year);

    $currentDate = new DateTimeImmutable();
    $validBirthDate = $currentDate->sub(new DateInterval('P18Y'));

    if ("{$year}-{$month}-{$day}" > $validBirthDate->format('Y-n-j')) {
      throw new InvalidDateException("You don't have at least 18 years old");
    }
  }
}
