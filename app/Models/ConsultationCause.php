<?php

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\LongName;

/**
 * @property-read string $shortName
 * @property-read ?string $extendedName
 * @property-read ?string $variant
 * @property-read ?string $code
 */
final class ConsultationCause extends Model {
  private LongName $shortName;
  private ?LongName $extendedName;
  private ?string $variant;
  private ?string $code;

  function __construct(
    public readonly ConsultationCauseCategory $category,
    string $shortName,
    ?string $extendedName,
    ?string $variant = null,
    ?string $code = null,
  ) {
    $this->setName($shortName, $extendedName)->setVariant($variant)->setCode($code);
  }

  function getFullName(bool $abbreviated = true): string {
    $fullName = $this->shortName;

    if (!$abbreviated && $this->extendedName) {
      $fullName = $this->extendedName;
    }

    if ($this->variant) {
      $fullName .= " {$this->variant}";
    }

    return $fullName;
  }

  function setName(string $shortName, ?string $extendedName = null): self {
    $this->shortName = new LongName($shortName, 'Nombre corto');
    $this->extendedName = $extendedName
      ? new LongName($extendedName, 'Nombre extendido')
      : null;

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
      'shortName' => $this->shortName,
      'extendedName' => $this->extendedName,
      'code' => $this->code,
      default => parent::__get($property)
    };
  }
}
