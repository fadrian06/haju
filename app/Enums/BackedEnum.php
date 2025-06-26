<?php



namespace HAJU\Enums;

trait BackedEnum
{
  /** @return string[] */
  public static function values(): array
  {
    return array_map(static fn(self $gender): string => $gender->value, self::cases());
  }
}
