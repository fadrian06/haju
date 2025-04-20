<?php

declare(strict_types=1);

namespace HAJU\Models;

use HAJU\Models\Contracts\Model;
use HAJU\ValueObjects\LongName;

final class ConsultationCauseCategory extends Model {
  private LongName $shortName;
  private ?LongName $extendedName;

  public function __construct(
    string $shortName,
    ?string $extendedName = null,
    public readonly ?self $parentCategory = null
  ) {
    $this->setName($shortName, $extendedName);
  }

  public function setName(string $short, ?string $extended = null): self {
    $this->shortName = new LongName($short, 'Nombre corto');

    $this->extendedName = $extended !== null
      ? new LongName($extended, 'Nombre extendido')
      : null;

    return $this;
  }

  public function __get(string $property): null|int|string {
    return match ($property) {
      'shortName' => $this->shortName->__toString(),
      'extendedName' => $this->extendedName?->__toString(),
      default => parent::__get($property)
    };
  }

  public function jsonSerialize(): array {
    return parent::jsonSerialize() + [
      'shortName' => $this->shortName->__toString(),
      'extendedName' => $this->extendedName?->__toString(),
      'parentCategory' => $this->parentCategory,
    ];
  }
}
