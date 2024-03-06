<?php

namespace App\Models;

class Department extends Model {
  function __construct(
    public string $name,
    public bool $isActive = true
  ) {
  }
}
