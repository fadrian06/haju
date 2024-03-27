<?php

namespace App\Models;

use App\ValueObjects\LongName;
use PharIo\Manifest\Url;

class Department extends Model {
  private LongName $name;

  function __construct(
    string $name,
    public readonly string|Url $iconFilePath,
    public readonly bool $belongsToExternalConsultation = false,
    private bool $isActive = true
  ) {
    $this->setName($name);
  }

  function setName(string $name): static {
    $this->name = new LongName($name, 'Nombre del departamento');

    return $this;
  }

  function toggleStatus(): static {
    $this->isActive = !$this->isActive;

    return $this;
  }

  function isInactive(): bool {
    return $this->isActive === false;
  }

  function getActiveStatus(): bool {
    return $this->isActive;
  }

  function getName(): string {
    return $this->name;
  }
}
