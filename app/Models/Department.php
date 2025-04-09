<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\Model;
use App\Models\Helpers\HasActiveStatus;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\LongName;
use PharIo\Manifest\Url;
use Stringable;

final class Department extends Model implements Stringable {
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

  public function isHospitalization(): bool {
    return $this->name->__toString() === 'Hospitalización';
  }

  public function isStatistics(): bool {
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
      'name' => (string) $this->name,
      default => parent::__get($property)
    };
  }

  public function __toString(): string {
    return strval($this->name);
  }
}
