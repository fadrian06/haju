<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidNameException;

final readonly class LongName extends Name {
  protected function validate(string $value, string $field): static {
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]{4,}$/', $value)) {
      throw new InvalidNameException("$field debe contener mínimo 1 palabra con iniciales en mayúscula");
    }

    return $this;
  }
}
