<?php

namespace App\Models\Contracts;

interface Activable {
  function isActive(): bool;
  function isInactive(): bool;
  function toggleStatus(): static;
}
