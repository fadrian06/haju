<?php

declare(strict_types=1);

namespace App\ValueObjects;

trait BackedEnum {
  public static function values(): array {
    return array_map(fn(self $gender): string => $gender->value, self::cases());
  }
}
