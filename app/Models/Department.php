<?php

namespace App\Models;

use App\Models\Contracts\Model;
use App\Models\Helpers\HasActiveStatus;
use App\ValueObjects\Exceptions\InvalidNameException;
use App\ValueObjects\LongName;
use PharIo\Manifest\Url;

class Department extends Model {
  use HasActiveStatus;

  private LongName $name;

  /** @throws InvalidNameException */
  function __construct(
    string $name,
    public readonly string|Url $iconFilePath,
    public readonly bool $belongsToExternalConsultation = false,
    bool $isActive = true
  ) {
    $this->isActive = $isActive;
    $this->setName($name);
  }

  /** @throws InvalidNameException */
  function setName(string $name): static {
    $this->name = new LongName($name, 'Nombre del departamento');

    return $this;
  }

  function getName(): string {
    return $this->name;
  }

  function isEqualTo(self $department): bool {
    return $this->id === $department->id;
  }
}
