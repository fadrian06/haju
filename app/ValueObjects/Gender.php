<?php

declare(strict_types=1);

namespace App\ValueObjects;

enum Gender: string {
  use BackedEnum;

  case Male = 'Masculino';
  case Female = 'Femenino';
}
