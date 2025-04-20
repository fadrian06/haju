<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum ConsultationType: string
{
  case FirstTime = 'P';
  case Succesive = 'S';
  case Associated = 'X';

  public function getDescription(): string
  {
    return match ($this) {
      self::Associated => 'Asociada',
      self::FirstTime => 'Primera vez',
      self::Succesive => 'Sucesiva'
    };
  }

  /** @return array<int, static> */
  public static function getCases(): array
  {
    return [self::Succesive, self::Associated];
  }
}
