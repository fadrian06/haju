<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidNameException;
use Stringable;

final readonly class IdCard implements Stringable {
  private const MIN = 2_000_000;
  private const MAX = PHP_INT_MAX;
  public int $value;

  /** @throws InvalidNameException */
  function __construct(int $value) {
    $this->validate($value);
    $this->value = $value;
  }

  /** @throws InvalidNameException */
  protected function validate(int $value): static {
    if ($value < self::MIN || $value > self::MAX) {
      throw new InvalidNameException('Cédula debe estar entre ' . self::MIN . ' y ' . self::MAX);
    }

    return $this;
  }

  function __toString(): string {
    return (string) $this->value;
  }
}
