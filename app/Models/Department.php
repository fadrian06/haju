<?php

namespace App\Models;

use PharIo\Manifest\Url;

class Department extends Model {
  function __construct(
    public string $name,
    public readonly Url $iconFilePath,
    public readonly bool $belongsToExternalConsultation = false,
    public bool $isActive = true
  ) {
  }
}
