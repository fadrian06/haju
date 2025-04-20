<?php

declare(strict_types=1);

namespace HAJU\Models\Contracts;

interface Activable {
  public function isActive(): bool;
  public function isInactive(): bool;
  public function toggleStatus(): static;
}
