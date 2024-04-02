<?php

namespace App\Models\Helpers;

trait HasActiveStatus {
  private bool $isActive = true;

  /** @deprecated */
  final function getActiveStatus(): bool {
    return $this->isActive;
  }

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
}
