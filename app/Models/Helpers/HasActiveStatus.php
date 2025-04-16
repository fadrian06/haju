<?php

declare(strict_types=1);

namespace App\Models\Helpers;

trait HasActiveStatus {
  private bool $isActive = true;

  final public function toggleStatus(): static {
    $this->isActive = !$this->isActive;

    return $this;
  }

  final public function isActive(): bool {
    return $this->isActive;
  }

  final public function isInactive(): bool {
    return $this->isActive === false;
  }

  /**
   * @return 'habilitado'|'inhabilitado'
   */
  final public function getActiveStatusText(): string {
    return $this->isActive ? 'habilitado' : 'inhabilitado';
  }
}
