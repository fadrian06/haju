<?php

declare(strict_types=1);

namespace HAJU\ValueObjects;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

enum DateRange: string {
  case Anual = 'Anual';
  case Monthly = 'Mensual';
  case Weekly = 'Semanal';

  public function getDate(): DateTimeInterface {
    return (new DateTimeImmutable)->sub(new DateInterval(match ($this) {
      self::Anual => 'P1Y',
      self::Monthly => 'P1M',
      self::Weekly => 'P1W',
    }));
  }
}
