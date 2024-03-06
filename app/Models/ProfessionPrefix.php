<?php

namespace App\Models;

enum ProfessionPrefix: string {
  case Dr = 'Dr.';
  case Ing = 'Ing.';

  function getLongValue(): string {
    return match ($this) {
      self::Dr => 'Doctor/a',
      self::Ing => 'Ingeniero/a'
    };
  }
}
