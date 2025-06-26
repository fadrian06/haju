<?php



namespace HAJU\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidNameException;

final readonly class LongName extends Name
{
  protected function validate(string $value, string $field): static
  {
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\.\,\-°\/\(\)]{4,}$/', trim($value))) {
      throw new InvalidNameException("{$field} = '{$value}' debe contener mínimo 1 palabra con iniciales en mayúscula y símbolos . , - ° / ( )");
    }

    return $this;
  }
}
