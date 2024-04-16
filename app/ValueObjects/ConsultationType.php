<?php

namespace App\ValueObjects;

enum ConsultationType: string {
  case FirstTime = 'P';
  case Succesive = 'S';
  case Associated = 'X';

  function getDescription(): string {
    return match ($this) {
      self::Associated => 'Asociada',
      self::FirstTime => 'Primera vez',
      self::Succesive => 'Sucesiva'
    };
  }

  /** @return array<int, static> */
  static function getCases(bool $excludeFirstTime = true): array {
    return [self::Succesive, self::Associated];
  }
}
