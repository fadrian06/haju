<?php

namespace HAJU\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidNameException;
use Stringable;

final readonly class IdCard implements Stringable
{
  private const MIN = 2_000_000;
  private const MAX = PHP_INT_MAX;
  public int $value;

  /** @throws InvalidNameException */
  public function __construct(int $value)
  {
    $this->validate($value);
    $this->value = $value;
  }

  /** @throws InvalidNameException */
  private function validate(int $value): static
  {
    if ($value < self::MIN || $value > self::MAX) {
      throw new InvalidNameException('Cédula debe estar entre ' . self::MIN . ' y ' . self::MAX);
    }

    return $this;
  }

  public function __toString(): string
  {
    return (string) $this->value;
  }
}
