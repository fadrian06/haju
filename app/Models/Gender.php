<?php

namespace App\Models;

enum Gender: string {
  use BackedEnum;

  case Male = 'Masculino';
  case Female = 'Femenino';
}
