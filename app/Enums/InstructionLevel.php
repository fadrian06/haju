<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum InstructionLevel: string
{
  use BackedEnum;

  case Doctor = 'Dr';
  case Engineer = 'Ing';
  case TSU = 'TSU';
  case Graduate = 'Licdo';

  public function getLongValue(): string
  {
    return match ($this) {
      self::Doctor => 'Doctor/a',
      self::Engineer => 'Ingeniero/a',
      self::TSU => 'Técnico Superior Universitario',
      self::Graduate => 'Licenciado/a'
    };
  }

  public function getId(): int
  {
    return match ($this) {
      self::Doctor => 1,
      self::Engineer => 2,
      self::TSU => 3,
      self::Graduate => 4
    };
  }
}
