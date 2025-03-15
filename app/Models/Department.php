<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\Model;
use App\Models\Helpers\HasActiveStatus;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\LongName;
use PharIo\Manifest\Url;

/**
 * @property-read string $name
 */
final class Department extends Model {
  use HasActiveStatus;

  private LongName $name;

  /** @throws InvalidNameException */
  public function __construct(
    string $name,
    public readonly string|Url $iconFilePath,
    public readonly bool $belongsToExternalConsultation = false,
    bool $isActive = true
  ) {
    $this->isActive = $isActive;
    $this->setName($name);
  }

  public function isStatistics(): bool {
    assert($this->name instanceof LongName);

    return $this->name->__toString() === 'Estadística';
  }

  /** @throws InvalidNameException */
  public function setName(string $name): static {
    $this->name = new LongName($name, 'Nombre del departamento');

    return $this;
  }

  public function hasIcon(): bool {
    return (bool) $this->iconFilePath->asString();
  }

  public function __get(string $property): null|int|string {
    return match ($property) {
      'name' => $this->name,
      default => parent::__get($property)
    };
  }
}
