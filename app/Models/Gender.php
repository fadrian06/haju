<?php

namespace App\Models;

enum Gender: string {
  case Male = 'Masculino';
  case Female = 'Femenino';

  static function values(): array {
    return array_map(function (self $gender): string {
      return $gender->value;
    }, self::cases());
  }
}
