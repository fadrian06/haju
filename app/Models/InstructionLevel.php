<?php

namespace App\Models;

enum InstructionLevel: string {
  case Doctor = 'Dr';
  case Engineer = 'Ing';
  case TSU = 'TSU';
  case Graduate = 'Licdo';

  function getLongValue(): string {
    return match ($this) {
      self::Doctor => 'Doctor/a',
      self::Engineer => 'Ingeniero/a',
      self::TSU => 'Técnico Superior Universitario',
      self::Graduate => 'Licenciado/a'
    };
  }

  function getId(): int {
    return match ($this) {
      self::Doctor => 1,
      self::Engineer => 2,
      self::TSU => 3,
      self::Graduate => 4
    };
  }

  static function values(): array {
    return array_map(function (self $instruction): string {
      return $instruction->value;
    }, self::cases());
  }
}
