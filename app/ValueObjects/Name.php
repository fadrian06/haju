<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidNameException;
use Stringable;

readonly class Name implements Stringable {
  protected string $value;

  function __construct(string $value, string $field) {
    $this->validate($value, $field);
    $this->value = mb_convert_case($value, MB_CASE_TITLE);
  }

  protected function validate(string $value, string $field): static {
    if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñ]{3,}$/', trim($value))) {
      throw new InvalidNameException("$field debe contener mínimo 3 letras con inicial en mayúscula");
    }

    return $this;
  }

  function __toString(): string {
    return $this->value;
  }
}
