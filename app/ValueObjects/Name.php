<?php

namespace HAJU\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidNameException;
use Stringable;

readonly class Name implements Stringable
{
  protected string $value;

  /** @throws InvalidNameException */
  public function __construct(string $value, string $field)
  {
    $this->validate($value, $field);
    $this->value = mb_convert_case($value, MB_CASE_TITLE);
  }

  /** @throws InvalidNameException */
  protected function validate(string $value, string $field): static
  {
    $value = mb_convert_case(str_replace('  ', ' ', $value), MB_CASE_LOWER);
    $pattern = '/^(del|de)?\s?[a-záéíóúñ]{3,}$/';

    if (!preg_match($pattern, $value)) {
      throw new InvalidNameException("{$field} debe contener mínimo 3 letras con inicial en mayúscula");
    }

    return $this;
  }

  public function __toString(): string
  {
    return $this->value;
  }
}
