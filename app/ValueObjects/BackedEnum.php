<?php

namespace App\ValueObjects;

trait BackedEnum {
  static function values(): array {
    return array_map(function (self $gender): string {
      return $gender->value;
    }, self::cases());
  }
}
