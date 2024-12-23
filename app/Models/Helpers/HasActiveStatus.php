<?php

namespace App\Models\Helpers;

trait HasActiveStatus {
  private bool $isActive = true;

  final function toggleStatus(): static {
    $this->isActive = !$this->isActive;

    return $this;
  }

  final function isActive(): bool {
    return $this->isActive;
  }

  final function isInactive(): bool {
    return $this->isActive === false;
  }

  /** @return 'habilitado'|'inhabilitado' */
  final function getActiveStatusText(): string {
    return $this->isActive ? 'habilitado' : 'inhabilitado';
  }
}
