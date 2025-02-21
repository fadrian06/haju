<?php

namespace App\ValueObjects;

trait BackedEnum {
  static function values(): array {
    return array_map(fn(self $gender): string => $gender->value, self::cases());
  }
}
