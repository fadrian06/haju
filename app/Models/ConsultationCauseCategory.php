<?php

namespace App\Models;

use App\Models\Contracts\Model;
use App\ValueObjects\LongName;

/**
 * @property-read string $shortName
 * @property-read ?string $extendedName
 */
final class ConsultationCauseCategory extends Model {
  private LongName $shortName;
  private ?LongName $extendedName;
  public readonly ?self $parentCategory;

  function __construct(
    string $shortName,
    ?string $extendedName = null,
    ?self $parentCategory = null
  ) {
    $this->setName($shortName, $extendedName);
    $this->parentCategory = $parentCategory;
  }

  function setName(string $short, ?string $extended = null): self {
    $this->shortName = new LongName($short, 'Nombre corto');

    $this->extendedName = $extended !== null
      ? new LongName($extended, 'Nombre extendido')
      : null;

    return $this;
  }

  function __get(string $property): null|int|string {
    return match ($property) {
      'shortName' => $this->shortName,
      'extendedName' => $this->extendedName,
      default => parent::__get($property)
    };
  }
}
