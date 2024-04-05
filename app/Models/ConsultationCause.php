<?php

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\LongName;

/**
 * @property-read string $name
 * @property-read ?string $variant
 * @property-read ?string $code
 */
final class ConsultationCause extends Model {
  private LongName $name;
  private ?string $variant;
  private ?string $code;

  function __construct(
    public readonly ConsultationCauseCategory $category,
    string $name,
    ?string $variant = null,
    ?string $code = null,
  ) {
    $this->setName($name)->setVariant($variant)->setCode($code);
  }

  function getFullName(): string {
    $fullName = $this->name;

    if ($this->variant) {
      $fullName .= " {$this->variant}";
    }

    return $fullName;
  }

  function setName(string $name): self {
    $this->name = new LongName($name, 'Nombre');

    return $this;
  }

  function setCode(?string $code): self {
    $this->code = $code;

    return $this;
  }

  function setVariant(?string $variant): self {
    $this->variant = $variant;

    return $this;
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'name' => $this->name,
      'variant' => $this->variant,
      'code' => $this->code,
      default => parent::__get($property)
    };
  }
}
